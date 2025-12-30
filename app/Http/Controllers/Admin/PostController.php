<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest as FormRequest;
use App\Models\Post as MainModel;
use App\Models\Tag;
use App\Models\TagTranslation;
use App\Models\User;
use App\Models\PostTranslation as TranslationModel;
use App\Services\ImageService;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\FilterService;
use  DB, Config, Session, Redirect;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Http\Controllers\Admin\CategoryController;
class PostController extends BaseController
{
    protected $imageFolderPost;
    protected $listRoutePost;
    protected $addRoutePost;
    protected $editRoutePost;
    protected $thumbPost;
    protected $mainTablePost;
    protected $foreignKeyPost;
    protected $translationFieldsPost;
    protected $hasManyRelationPost;
    protected $hasManyRelationTag;
    protected $mainTableSingularPost;
    protected $titlePost;
    protected $treeMain;
    public function __construct()
    {
        $anotherController = new CategoryController();
        $this->treeMain = $anotherController->exportCategories();

        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolderPost = Config::get('constants.POST_FOLDER');
        $this->listRoutePost = 'admin.post.index';
        $this->addRoutePost = 'admin.post.add';
        $this->editRoutePost = 'admin.post.edit';
        $this->thumbPost  = '/thumb-';
        $this->mainTablePost  = 'posts';
        $this->mainTableSingularPost  = 'post';
        $this->foreignKeyPost  = 'post_id';
        $this->translationFieldsPost  = ['posts.*', 'post_translations.title'];
        $this->hasManyRelationPost = 'post_translations';
        $this->hasManyRelationTag = 'tag_translations';
        $this->titlePost  = __('messages.post');

        define('CATEGORIES_ID','categories.id');

        \View::share([
            'imageFolder' => $this->imageFolderPost,
            'title' => $this->titlePost,
            'listRoute' => $this->listRoutePost,
            'addRoute' => $this->addRoutePost,
            'saveRoute' => "admin.post.save",
            'updateRoute' => "admin.post.update",
            'listUrl' => "post-management/post-list",
            'formId' => "postForm",
            'mainTable' => $this->mainTablePost,
            'formPath' => 'admin.post.form',
            'addPermission' => "POST_MANAGEMENT_ADD",
            'editPermission' => "POST_MANAGEMENT_EDIT",
            'deletePermission' => "POST_MANAGEMENT_DELETE",
            'statusPermission' => "POST_MANAGEMENT_CHANGE_STATUS"
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {

        $DB                         =   MainModel::query();
        $fieldsToSearch             =   array('title' => 'like', 'status' => '=');
        $searchVariable             =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTablePost,
                "foreignKey" => $this->foreignKeyPost,
                "translationFields" => $this->translationFieldsPost,
                "mainTableSingular" => $this->mainTableSingularPost
            ]
        );
        extract($output);
        $statusChangeUrl        =    'admin/post-list/status/';
        return  View($this->listRoutePost, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        $data = User::where('status', 1)->get();
        $authors = $data->pluck('first_name', 'id')->map(function ($firstName, $userId) use ($data) {
            $user = $data->where('id', $userId)->first();
            $lastName = $user->last_name;
            return $firstName . ' ' . $lastName;
        });
        $DB                         =   Category::query();
        $blogCategories = $DB->leftJoin('category_translations', function ($join)
            {
                $join->on(CATEGORIES_ID, '=', "category_translations.category_id")
                    ->where("category_translations.language_id", '=',1);
            })
        ->select(CATEGORIES_ID,"category_translations.name")
        ->where("parent_id", "0")
        ->orderBy("name", "asc")
        ->paginate(1000);
        return  View($this->addRoutePost)->with(['authors' => $authors,'blogCategories'=>$blogCategories]);
    }



    /**
     * Save Added Record
     */
    public function save(FormRequest $request)
    {

        $error = '';
        DB::beginTransaction();
        try {

            $fileName = '';
            if (!empty($request->image)) {
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolderPost);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderPost,
                    400,
                    200,
                    $fileName
                );
            }
            $arr  =  [
                'image'        => $fileName,
                'author' =>$request->author,
            ];
            if (!empty($request->categories)) {
                $arr['categories'] = implode(",",$request->categories);
            }

            $row = MainModel::create($arr);

            $translations = [];
            foreach ($request->title as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'title' => $value,
                    'content' =>$request->content[$key],
                    'tags' => $request->tags[$key]?implode(',', array_map('trim',
                        explode(',', $request->tags[$key]))):'',
                    'slug' =>Str::slug($value)
                ];
                $this->tagInsert($request->tags[$key],$key);
            }


            $row->{$this->hasManyRelationPost}()->createMany($translations);

        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        return Redirect::route($this->listRoutePost)->with('success', $this->titlePost . __("messages.recordAddedSpecific"));
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {



        $DB                         =   Category::query();
        $blogCategories = $DB->leftJoin('category_translations', function ($join)
             {
                $join->on(CATEGORIES_ID, '=', "category_translations.category_id")
                    ->where("category_translations.language_id", '=',1);
            })
        ->select(CATEGORIES_ID,"category_translations.name")
        ->where("parent_id", "0")
        ->orderBy("name", "asc")
        ->paginate(1000);

        $DB             = new MainModel();

        $language_id = CommonService::getLangIdFromLocale();
        $data = $DB::with(['post_translation' => function ($query) use ($language_id) {
            $query->select('*')->where('language_id', $language_id);
        }])->where('id', $id)->first();

        if (!$data) {
            return abort(403);
        }

        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['thumbImage'] = $data->thumbImage;
        $dataReArrange['author'] = $data->author;
        $dataReArrange['categories'] = [];
        if (!empty($data->categories)) {
            $dataReArrange['categories'] = explode(",",$data->categories);
        }

        if (!empty($data[$this->hasManyRelationPost])) {
            foreach ($data[$this->hasManyRelationPost] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;

                }
            }
        }
        $user = User::where('status', 1)->get();
        $authors = $user->pluck('first_name', 'id')->map(function ($firstName, $userId) use ($user) {
            $user = $user->where('id', $userId)->first();
            $lastName = $user->last_name;
            return $firstName . ' ' . $lastName;
        });

        return  View($this->editRoutePost)->with(['data' => $dataReArrange,
        'authors' => $authors,'blogCategories'=>$blogCategories,'treeMain'=>$this->treeMain]);
    }

    /**
     * Save Edited Record
     */

    public function update($id, FormRequest $request)
    {

        $error = "";
        DB::beginTransaction();

        try {

            $row = MainModel::findOrFail($id);
            if (!empty($request->image)) {
                $fileName = ImageService::fileUploadImage($request->image, $row->image, $this->imageFolderPost);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderPost,
                    400,
                    200,
                    $fileName
                );
                $row->image = $fileName;
            }
            if (!empty($request->author)) {
                $row->author = $request->author;
            }
            if (!empty($request->categories)) {
                $row->categories = implode(",",$request->categories);
            }
            $row->updated_at = date("Y-m-d H:i:s");
            if ($row->save()) {
                $translations = [];
                foreach ($request->title as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyPost => $row->id,
                        'language_id' => $key,
                        'title' => $value,
                        'slug' =>Str::slug($value),
                        'content' =>$request->content[$key],
                        'tags' => $request->tags[$key]?implode(',', array_map('trim',
                        explode(',', $request->tags[$key]))):''
                    ];
                    $this->tagInsert($request->tags[$key],$key);
                }
                TranslationModel::upsert($translations, ['id']);
            }
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }

        if ($error !== "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }

        DB::commit();
        Session::flash('success', $this->titlePost . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRoutePost);
    }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRoutePost)
            ->with('success', $this->titlePost . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        $DB = new MainModel();
        $DB->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);
        Session::flash('success',$this->titlePost . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());
    }



    public function tagInsert($tagsArray,$languageId=1){
        if(!empty($tagsArray)){
            $tagNamesArray = explode(',', $tagsArray);
            foreach ($tagNamesArray as $tagName) {
                $tag = Tag::whereHas('tag_translations', function ($query) use ($tagName, $languageId) {
                    $query->where('name', $tagName)->where('language_id', $languageId);
                })->first();
                if (!$tag) {
                    $tag = new Tag();
                    $tag->status = 1;
                    $tag->save();
                    $tagTranslation = new TagTranslation(['name' => trim($tagName),'slug' => Str::slug($tagName),
                    'tag_id' => $tag->id, 'language_id' => $languageId]);
                    $tag->tag_translations()->save($tagTranslation);
                }
            }
        }
    }


}
