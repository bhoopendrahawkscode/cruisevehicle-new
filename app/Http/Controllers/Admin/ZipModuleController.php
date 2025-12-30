<?php

namespace App\Http\Controllers\Admin;


use App\Models\ZipModule;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use  Hash, DB,  Config, Validator, Session, Redirect,  Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use App\Services\CommonService;
use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Contracts\Filesystem\Filesystem;
use ZipArchive, File,Str;

class ZipModuleController extends BaseController
{
    protected $paginate;
    protected $zipControllerNameArr = [];
    protected $zipModelNameArr = [];
    protected $zipRequestNameArr = [];
    protected $zipResourceNameArr = [];
    protected $zipViewFolderNameArr = [];

    public function __construct()
    {
		parent::__construct();
        $this->middleware('auth:admin');
        $this->paginate =  GeneralService::getSettings('pageLimit');
    }


    public function zipModuleList(Request $request)
    {
        $zipModuleList =  Config::get('constants.Zip_Module_List');

        $DB                         =   ZipModule::query();
        $fieldsToSearch              =   array(
            'zipType' => '=', 'status' => '=',
            '*zipFolderFileName*' => '*like-like-like*'
        );

        $searchVariable             =    $request->all();
        $extraConditions               =    [];
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at',$extraConditions);
        extract($output);
        $statusChangeUrl        =    'admin/zipmodule/status/';
        \View::share([
            'section' =>   __('messages.ZipManagement'),
        ]);


        return  View('admin.zipmodule.list', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl','zipModuleList'));
    } // end listUsers



    public function createZipModule(Request $request)
    {
        if($request->moduleName=='' || count($request->moduleName)==0)
        {
            return redirect()->back()->with('error', 'Select Module in the list');

        } else {

                $zip = new ZipArchive();
                $modulesName = '';
                foreach($request->moduleName as $moduleName)
                {
                    $modulesName = $moduleName.'-'.$modulesName;
                    $this->zipControllerNameArr[] = $moduleName.'Controller.php';
                    $this->zipControllerNameArr[] = $moduleName.'sController.php';
                    $this->zipModelNameArr[] = $moduleName.'.php';
                    $this->zipRequestNameArr[] = $moduleName.'Request.php';
                    $this->zipResourceNameArr[] = $moduleName.'Resource.php';
                    $this->zipViewFolderNameArr[] = strtolower($moduleName);
                }

                $zipFileName = $modulesName.time().'.zip';

                if (count( $this->zipControllerNameArr) > 0 && true === ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)))
                {
                    $files = [];

                    //project base directory and folder
                    $projectFolder = [];
                    $projectFolder = $this->getProjectFolderList();
                    $files = array_merge($files, $projectFolder);

                    //public base directory and folder
                    $publicFolder = [];
                    $publicFolder = $this->getPublicFolderList();
                    $files = array_merge($files, $publicFolder);

                    //public base files
                    $publicFile = [];
                    $publicFile =    $this->getPublicFileList();
                    $files = array_merge($files, $publicFile);

                    //public project root files
                    $publicRootFile = [];
                    $publicRootFile =  $this->getProjectRootFilesList();
                    $files = array_merge($files, $publicRootFile);

                    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                        foreach ($files as $file) {
                            $zip->addFile($file['filePath'], $file['zipFolderFile']);
                        }
                        $zip->close();
                        }
                    }
                    return response()->download(public_path($zipFileName));
            }

    }


    public function getProjectFolderList()
    {
        $files = [];
         //project base directory and folder
         $projectFolderList  =   ZipModule::where('zipPath', 'Root')->where('zipType', 'Folder')->where('status', 1)->pluck('zipFolderFileName')->toArray();
         if(!empty($projectFolderList)){
         foreach ($projectFolderList as $folder) {
                 $folderPath = $folder;
                 $publicPath = public_path('../'.$folderPath);
                 $filesCheckList = File::allFiles($publicPath);
                 $moduleFolder = [];
                 $moduleFolder = $this->checkIsModuleFolder($folderPath, $publicPath, $filesCheckList);
                 $files = array_merge($files, $moduleFolder);
             }
         }

         return $files;
    }


   public function checkIsModuleFolder($folderPath, $publicPath, $filesCheckList= array())
   {
                $files=array();
                foreach ($filesCheckList as $file) {
                    $fileName = '';
                    $relativeFilePath = Str::after($file->getPathname(), $publicPath . DIRECTORY_SEPARATOR);
                    $zipFolderFile = $folderPath . DIRECTORY_SEPARATOR . $relativeFilePath;
                    $fileName = $file->getFilename();
                    $pathArr = array();
                    $pathArr = $this->getModuleFile($file, $fileName, $zipFolderFile);
                    if($pathArr!='' && count($pathArr)>0){
                        $files[] = $pathArr;
                    }
                }
             return $files;
   }

   public function getModuleFile($file, $fileName, $zipFolderFile)
   {
            $pathArr = array();
            if (str_contains($zipFolderFile, 'Controllers')) {
                $pathArr =  $this->getControllersFile($file, $fileName, $zipFolderFile);
            } elseif (str_contains($zipFolderFile, 'Models')) {
                $pathArr =  $this->getModelsFile($file, $fileName, $zipFolderFile);
            } elseif (str_contains($zipFolderFile, 'Requests')) {
                $pathArr =  $this->getRequestsFile($file, $fileName, $zipFolderFile);
            } elseif (str_contains($zipFolderFile, 'Resources')) {
                $pathArr = $this->getResourcesFile($file, $fileName, $zipFolderFile);
            } elseif (str_contains($zipFolderFile, 'views')) {
                $pathArr = $this->getViewsFile($file, $zipFolderFile);
            } else {
                $pathArr = ['filePath' => $file->getPathname(), 'zipFolderFile' => $zipFolderFile];
            }
          return $pathArr;
   }

   public function getControllersFile($file, $fileName, $zipFolderFile)
   {
        return  (in_array($fileName, $this->zipControllerNameArr))?['filePath' => $file->getPathname(), 'zipFolderFile' => $zipFolderFile]:'';
   }

   public function getModelsFile($file, $fileName, $zipFolderFile)
   {
        return  (in_array($fileName, $this->zipModelNameArr))?['filePath' => $file->getPathname(), 'zipFolderFile' => $zipFolderFile]:'';
   }

   public function getRequestsFile($file, $fileName, $zipFolderFile)
   {
        return  (in_array($fileName, $this->zipRequestNameArr))?['filePath' => $file->getPathname(), 'zipFolderFile' => $zipFolderFile]:'';
   }

   public function getResourcesFile($file, $fileName, $zipFolderFile)
   {
        return  (in_array($fileName, $this->zipResourceNameArr))?['filePath' => $file->getPathname(), 'zipFolderFile' => $zipFolderFile]:'';
   }

   public function getViewsFile($file, $zipFolderFile)
   {
        $pathArr = array();
         foreach($this->zipViewFolderNameArr as $zipViewFolderName){
             $pathArr = (str_contains($zipFolderFile, $zipViewFolderName))?['filePath' => $file->getPathname(), 'zipFolderFile' => $zipFolderFile]:'';
        }
        return $pathArr;
   }

    public function getPublicFolderList()
    {
        $files = [];
        $publicFolderList = ZipModule::where('zipPath', 'Public')->where('zipType', 'Folder')->where('status', 1)->pluck('zipFolderFileName')->toArray();
        if(!empty($publicFolderList)){
        foreach ($publicFolderList as $folder) {
                $folderPath = $folder;
                $publicPath = public_path($folderPath);
                $filesCheckList = File::allFiles($publicPath);
                foreach ($filesCheckList as $file) {
                    $relativeFilePath = Str::after($file->getPathname(), $publicPath . DIRECTORY_SEPARATOR);
                    $zipFolderFile = 'public/'.$folderPath . DIRECTORY_SEPARATOR . $relativeFilePath;
                    $files[] = ['filePath' => $file->getPathname(), 'zipFolderFile' => $zipFolderFile];
                }
            }
        }
        return $files;
    }

    public function getPublicFileList()
    {
            $files = [];
            $publicFileList = ZipModule::where('zipPath', 'Public')->where('zipType', 'File')->where('status', 1)->pluck('zipFolderFileName')->toArray();
            if(!empty($publicFileList)){
            foreach ($publicFileList as $file) {
                    $folderPath = '';
                    $publicPath = public_path("");
                    $fileName = $file;
                    $zipFolderPath = 'public/'.$folderPath.$fileName;
                    $filePath = $publicPath.'/'.$fileName;
                    $files[] = array('filePath'=>$filePath, 'zipFolderFile'=>$zipFolderPath);
                }
            }
        return $files;
    }

    public function getProjectRootFilesList()
    {
              $files = [];
              //public project root files
              $projectRootFilesList = ZipModule::where('zipPath', 'Root')->where('zipType', 'File')->where('status', 1)->pluck('zipFolderFileName')->toArray();
              if(!empty($projectRootFilesList)){
              foreach ($projectRootFilesList as $file) {
                  $folderPath = '';
                  $publicPath = public_path("../");
                  $fileName = $file;
                  $zipFolderPath = $folderPath.$fileName;
                  $filePath = $publicPath.$fileName;
                  $files[] = array('filePath'=>$filePath, 'zipFolderFile'=>$zipFolderPath);
              }
          }
        return $files;
    }

    public function status($id, $value)
    {
        DB::table('zip_modules')->where('id', $id)->update(['status' => $value]);
        Session::flash('success', trans("messages.statusUpdated"));
        return CommonService::redirectStatusChange(Redirect::back());
    }


}
