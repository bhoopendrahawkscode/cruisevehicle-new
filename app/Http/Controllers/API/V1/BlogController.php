<?php

namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\API\BlogRequest;
use App\Http\Requests\API\BlogReportRequest;
use App\Http\Requests\API\ArchiveBlogRequest;
use App\Http\Requests\API\BlogCommentRequest;
use App\Http\Requests\API\CommentReportRequest;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\GeneralService;
use App\Models\Blog;
use App\Models\User;
use App\Models\BlogAttachment;
use App\Models\Attachment;
use App\Models\BlogUser;
use App\Models\BlogReport;
use App\Models\CommentReport;
use App\Models\Comment;
use App\Http\Resources\BlogResource;
use App\Jobs\SendPushNotificationJob;
use  Auth, DB;

class BlogController extends BaseController
{
    protected $paginate;
    protected $paginateAll;
    protected $message;
    protected $data;
    protected $noRecord;

    protected $dataListed;
    public function __construct()
    {
        $this->paginate              =       GeneralService::getSettings('pageLimit');
        $this->paginateAll           =       1000;
        $this->data                  =       new \stdClass;
        $this->message               =       __(Constant::ERROR_OCCURRED);
        $this->noRecord              =      trans('api.record_not_found');
        $this->dataListed            =      trans('messages.GET_DATA');
        define('SUCCESS_MESSAGE', Constant::API_SUCCESS);

    }

    public function saveBlog(BlogRequest $request)
    {
        DB::beginTransaction();
        try {

            $userId                          =       Auth::user()->id;

            $blogData                   =       [];
            if($request->id != ''){
                $blogData               =       Blog::where('id', $request->id)
                ->where('user_id', $userId)->first();
                if(empty($blogData)){
                    $this->message = 'Invalid Blog';
                    return $this->sendError($this->message);
                }
            }else{
                $blogData                  =       new Blog();
            }
            $blogData['user_id']         =       $userId;
            $blogData['content']         =       $request->content;
            $blogData['asking_help']     =       $request->asking_help;
            $blogData['offer_help']      =       $request->offer_help;
            $blogData['community_id']    =       $request->community_id;
            if($blogData->save()){
                $this->saveBlogAttachments($request,$blogData);
            }
            DB::commit();
            return $this->sendResponse(new BlogResource($blogData,[]),"Post saved successfully.");
        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message = __(Constant::ERROR_OCCURRED);
            if(env('APP_DEBUG')){
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }
    public function saveBlogAttachments($request,$blogData){
        $attachments  = [];
        if(!empty($request->attachments)){
            foreach($request->attachments as $row){
                $attachments[]  = ['blog_id'=>$blogData->id,'url'=>$row['url'],'type'=>$row['type']];
            }
            if(!empty($attachments)){
                BlogAttachment::insert($attachments);
                foreach($request->attachments as $row){
                    Attachment::where('url', $row['url'])->delete();
                }
            }
        }
    }
    public function getBlogs(Request $request)
    {
        try {
            $query                  =       Blog::query();

            if ($request->has('owner_id')  && $request->owner_id != '') {
                $query->where('user_id', $request->owner_id);
            }
            if ($request->has('community_id')  && $request->community_id != '') {
                $query->where('community_id', $request->community_id);
            }
            if ($request->has('content')  && $request->content != '') {
                $query->where('content', 'like', '%' . $request->content . '%');
            }
            if ($request->has('user_id')  && $request->user_id != '' && $request->type == 'bookmarked') {
                $alreadyJoined = BlogUser::where(['user_id'=>$request->user_id,'bookmark'=>1])->pluck('blog_id','blog_id')
                ->toArray();
                $query->whereIn('blogs.id', $alreadyJoined);
            }
            if ($request->has('user_id')  && $request->user_id != '' && $request->type == 'archived') {
                $alreadyJoined = BlogUser::where(['user_id'=>$request->user_id,'archive'=>1])->pluck('blog_id','blog_id')
                ->toArray();
                $query->whereIn('blogs.id', $alreadyJoined);
            }
            $query->where('status',1);

            $dataList   =     $query->orderBy('created_at', "desc")
                            ->paginate($this->paginate);
            return $this->sendResponse(BlogResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function getBlogDetails(Request $request)
    {
        try {
            $blog           =   Blog::where('id', $request->id)->first();
            $query          =   Comment::query();
            $dataList       =   $query->with('user')->where('blog_id',$blog->id)->where('status',1)->orderBy('created_at', "desc")
            ->paginate($this->paginate);
            $comments       =   [];
            $allUsers       =   [];
            if(!empty($dataList)){
                foreach($dataList as $row){
                   $comments[] = ['users'=>$row->users,'attachment'=>$row->url,
                   'content'=>$row->content,
                   'full_name'=>$row->user->full_name,
                   'username'=>$row->user->username,
                   'created_at'=>$row->created_at->format(Constant::DB_DATE_FORMAT)];
                   $allUsers  = array_merge($allUsers,array_filter(explode(",",$row->users)));
                }
            }
            $query                  =       User::query();
            $query->select('id','username');
            $results                =       $query->whereIn('id', $allUsers)->get()->toArray();
            return $this->sendResponse(new BlogResource($blog,['comments'=>$comments,'users'=>$results]),Constant::API_SUCCESS);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function reportBlog(BlogReportRequest $request)
    {

        DB::beginTransaction();
        try {
            $message2                       =       '';
            $blogDetails               =       Blog::where('id', $request->blog_id)->first();
            $userId                         =       Auth::user()->id;
            if (empty($blogDetails) || $blogDetails->user_id == $userId) {
                $message2 = 'Invalid Action';
            }

            $alreadyBlogReport     =   BlogReport::where(['blog_id'=> $request->blog_id,'user_id'=>$userId])->first();

            if (!empty($alreadyBlogReport)) {
                $message2 = 'You already reported this post.';
            }
            if($message2 != ''){
                return $this->sendError($message2);
            }

            BlogReport::create(['blog_id'=> $request->blog_id,'user_id'=>$userId,'type'=>$request->type,'message'=>$request->message]);
            Blog::where(['id'=> $request->blog_id])->update(['last_reported_at' => date(Constant::DB_DATE_FORMAT)]);

            $adminDetails            =       User::getUserDetails(1);
            $temp['type']            =      'PostReported';
            $temp['user_id']         =      $adminDetails['id'];
            $temp['link_id']         =      $blogDetails->id;
            $temp['title']           =      "Post Reported.";
            $temp['description']     =      "One of user reported post (".excerpt($blogDetails['content'],100).") ";
            dispatch(new SendPushNotificationJob($adminDetails, $temp));
            DB::commit();
            $this->message  =   "Post Reported Successfully.";
            return $this->sendResponse($this->data,$this->message);

        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message  =  CommonService::getExceptionError($e);
        }
        return $this->sendError($this->message);
    }
    public function changeArchiveStatus(ArchiveBlogRequest $request)
    {
        DB::beginTransaction();
        try {

            // type = 1 means add bookmark
            // type = 2 means remove bookmark
            // type = 3 means make archive
            // type = 4 means make unarchive

            $userId                          =       Auth::user()->id;
            $alreadyExists                   =       BlogUser::where(['blog_id'=> $request->blog_id,'user_id'=>$userId])->first();
            if(empty($alreadyExists)){
                if($request->type == 1){
                    $this->message         =   'Post Bookmarked successfully.';
                    BlogUser::create(['blog_id'=> $request->blog_id,'user_id'=>$userId,'bookmark'=>1]);
                }elseif($request->type == 3){
                    BlogUser::create(['blog_id'=> $request->blog_id,'user_id'=>$userId,'archive'=>1]);
                    $this->message         =   'Post Archived successfully.';
                }
            }else{
                if($request->type == 1){
                    $this->message         =   'Post Bookmarked successfully.';
                    BlogUser::where(['blog_id'=> $request->blog_id,'user_id'=>$userId])->update(['bookmark'=>1]);
                }elseif($request->type == 3){
                    $this->message         =   'Post Archived successfully.';
                    BlogUser::where(['blog_id'=> $request->blog_id,'user_id'=>$userId])->update(['archive'=>1]);
                }
                if($request->type == 2){
                    $this->message         =   'Post Bookmark removed successfully.';
                    BlogUser::where(['blog_id'=> $request->blog_id,'user_id'=>$userId])->update(['bookmark'=>0]);
                }elseif($request->type == 4){
                    $this->message        =   'Post unarchive successfully.';
                    BlogUser::where(['blog_id'=> $request->blog_id,'user_id'=>$userId])->update(['archive'=>0]);
                }
            }
            BlogUser::where(['blog_id'=> $request->blog_id,'user_id'=>$userId,'archive'=>0,'bookmark'=>0])->delete();
            DB::commit();
            return $this->sendResponse($this->data,$this->message);
        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message  =  CommonService::getExceptionError($e);
        }
        return $this->sendError($this->message);
    }

    public function addBlogComment(BlogCommentRequest $request)
    {

        DB::beginTransaction();
        try {

            $blogDetails               =       Blog::where('id', $request->blog_id)->first();
            $userId                         =       Auth::user()->id;
            Comment::create(['blog_id'=> $request->blog_id,'user_id'=>$userId,
            'users'=>$request->users,'content'=>$request->content,'attachment'=>$request->attachment,
            'attachment_type'=>$request->attachment_type]);

            if(!empty($request->users)){
                $users  =  explode(",",$request->users);
                $query                  =       User::query();
                $query->whereIn('id', $users);
                $users  = $query->get();
                if(!empty($users)){
                    $users      =       $users->toArray();
                    foreach($users as $row){
                        $temp['type']            =      'CommentPosted';
                        $temp['user_id']         =      $row['id'];
                        $temp['link_id']         =      $request->blog_id;
                        $temp['title']           =      "You was tagged in a post comment.";
                        $temp['description']     =      "One of user tagged you in a post.";
                        dispatch(new SendPushNotificationJob($row, $temp));
                    }
                }
            }

            $blogOwnerDetails        =       User::getUserDetails($blogDetails->user_id);
            $temp['type']            =      'CommentPosted';
            $temp['user_id']         =      $blogOwnerDetails['id'];
            $temp['link_id']         =      $blogDetails->id;
            $temp['title']           =      "Your post have a new comment.";
            $temp['description']     =      "Your post have a new comment.";
            dispatch(new SendPushNotificationJob($blogOwnerDetails, $temp));
            DB::commit();
            $this->message  =   "Comment posted successfully.";
            return $this->sendResponse($this->data,$this->message);

        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message = __(Constant::ERROR_OCCURRED);
            if(env('APP_DEBUG')){
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function reportBlogComment(CommentReportRequest $request)
    {

        DB::beginTransaction();
        try {
            $message2                       =       '';
            $commentDetails                 =       Comment::where('id', $request->comment_id)->first();
            $userId                         =       Auth::user()->id;
            if (empty($commentDetails) || $commentDetails->user_id == $userId) {
                $message2 = 'Invalid Action';
            }

            $alreadyCommentReport     =   CommentReport::where(['comment_id'=> $request->comment_id,'user_id'=>$userId])->first();

            if (!empty($alreadyCommentReport)) {
                $message2 = 'You already reported this comment.';
            }
            if($message2 != ''){
                return $this->sendError($message2);
            }

            CommentReport::create(['comment_id'=> $request->comment_id,'user_id'=>$userId,'type'=>$request->type,'message'=>$request->message]);
            Comment::where(['id'=> $request->comment_id])->update(['last_reported_at' => date(Constant::DB_DATE_FORMAT)]);

            $adminDetails            =       User::getUserDetails(1);
            $temp['type']            =      'CommentReported';
            $temp['user_id']         =      $adminDetails['id'];
            $temp['link_id']         =      $commentDetails->id;
            $temp['title']           =      "Comment Reported.";
            $temp['description']     =      "One of user reported comment (".excerpt($commentDetails['content'],100).") ";
            dispatch(new SendPushNotificationJob($adminDetails, $temp));
            DB::commit();
            $this->message  =   "Comment Reported Successfully.";
            return $this->sendResponse($this->data,$this->message);

        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message  =  CommonService::getExceptionError($e);
        }
        return $this->sendError($this->message);
    }

    public function deleteBlog(Request $request)
    {
        try {
            $userId                    =       Auth::user()->id;
            $blogDetails               =       Blog::with('blog_attachments','comments')->where('id', $request->blog_id)->first();
            if (empty($blogDetails) || $blogDetails->user_id != $userId) {
                return $this->sendError("Invalid Action.");
            }

            if(isset($blogDetails->blog_attachments) && !empty($blogDetails->blog_attachments)){
                foreach($blogDetails->blog_attachments as $row){
                    $row->delete(); // delete attachments
                }
            }

            if(isset($blogDetails->comments) && !empty($blogDetails->comments)){
                foreach($blogDetails->comments as $row){
                    $row->delete(); // delete comments. comment reports will be deleted automatically
                }
            }
            $blogDetails->delete();
            return $this->sendResponse($this->data, "Blog deleted successfully.");
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

    public function updateStreak(Request $request){
        try {
            $userId                          =       Auth::user()->id;
            if(!empty(Auth::user()->last_streak_login) && Auth::user()->last_streak_login != null){
                $date1              =       new \DateTime(date(Constant::DB_ONLY_DATE_FORMAT));
                $date2              =       new \DateTime(Auth::user()->last_streak_login);
                $interval           =       $date1->diff($date2);
                if($interval->format('%a') > 1){
                    $date1 = new \DateTime(Auth::user()->streak_start_date);
                    $date2 = new \DateTime(Auth::user()->last_streak_login);
                    $interval = $date1->diff($date2);
                    if($interval->format('%a') > Auth::user()->longest_streak){
                        User::where(['id'=> $userId])->update(['longest_streak' => $interval->format('%a')]);
                    }
                    User::where(['id'=> $userId])->update(['streak_start_date' => date(Constant::DB_ONLY_DATE_FORMAT)]);
                }
            }
            if(empty(Auth::user()->streak_start_date) || Auth::user()->streak_start_date == null){
                User::where(['id'=> $userId])->update(['streak_start_date' => date(Constant::DB_ONLY_DATE_FORMAT)]);
            }
            User::where(['id'=> $userId])->update(['last_streak_login' => date(Constant::DB_ONLY_DATE_FORMAT)]);
            return $this->sendResponse($this->data,SUCCESS_MESSAGE);
        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message  =  CommonService::getExceptionError($e);
            return $this->sendError($this->message);
        }
    }
}
