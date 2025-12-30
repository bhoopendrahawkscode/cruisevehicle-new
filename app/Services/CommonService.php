<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Permission;
use Illuminate\Support\Arr;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Config, File;
use App\Models\Dropdown;
use DB, Redirect, Session;
use App\Constants\Constant;
use App\Constants\CsvConstant;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Log;

class CommonService
{
    public static function getLangIdFromLocale()
    {
        $locale = app()->getLocale();
        $languageId =  Cache::get($locale);

        if (empty($languageId)) {
            $langDetails =  Language::where('locale',   $locale)->first();
            Cache::put($locale, $langDetails->id);
        }
        return Cache::get($locale);
    }


    public static function dropDownItems()
    {
        if (Cache::has('dropdownItems')) {
            $dropdownItems = Cache::get('dropdownItems');
        } else {
            $dropdownItems = \App\Models\Dropdown::where('status', '1')->get();

            // Cache the dropdownItems for future use
            Cache::put('dropdownItems', $dropdownItems);
        }
        return $dropdownItems;
    }


    public static function getPaginate($mainTable)
    {
        if ($mainTable == 'categories') {
            return 2000;
        }
        return GeneralService::getSettings('pageLimit');
    }

    public static function categoryMenu($data)
    {
        if (empty($data)) {
            return '';
        }
        $html = '<ul class="nested">';
        foreach ($data as $item) {
            $html .= '<li>';

            $active = ' text-success ';
            if ($item['status'] == 0) {
                $active = " text-danger ";
            }

            if (!empty($item['children'])) {
                $html .= '<span class="caret " style="padding-left:0px;">' . $item['name'] . '<a class="editCategory" href="' . URL('/') .
                    '/admin/category-list/edit/' . $item['id'] . '">

                <em class=" ' . $active . ' fa-regular fa-pen-to-square ms-2"></em></a></span>';
            } else {
                $html .= '<a style="padding-left:0px;" href="' . URL('/') .
                    '/admin/category-list/edit/' . $item['id'] . '" >' . $item['name'] .
                    '<em class=" ' . $active . ' fa-regular fa-pen-to-square ms-2"></em></a>';
            }
            if (!empty($item['children'])) {
                $html .= self::categoryMenu($item['children']);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    public static function categoryMenuPosts($data, $oldSelected)
    {
        if (empty($data)) {
            return '';
        }
        $html = '<ul class="nested">';
        foreach ($data as $item) {
            $html .= '<li>';
            $checked = '';
            if (in_array($item['id'], $oldSelected)) {
                $checked = 'checked';
            }
            if (!empty($item['children'])) {
                $html .= '<span class="caret " style="padding-left:0px;">'
                    . $item['name'] . '</span><input type="checkbox" name="categories[]"
                 ' . $checked . ' value="' . $item['id'] . '" />';
            } else {
                $html .=  $item['name'] . ' <input type="checkbox" name="categories[]"
                ' . $checked . ' value="' . $item['id'] . '" />';
            }
            if (!empty($item['children'])) {
                $html .= self::categoryMenuPosts($item['children'], $oldSelected);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }




    public static function createExceptionLog($exception)
    {
        $filename = 'exception_logger_' . date('Y_m_d') . '.log';
        $data = 'Time: ' . gmdate("F j, Y, g:i a") . "\n";
        $data .= 'Message: ' . $exception->getMessage() . "\n";
        $data .= 'Line: ' . $exception->getLine() . "\n";
        $data .= 'File: ' . $exception->getFile() . "\n";
        \File::append(
            storage_path('logs' . DIRECTORY_SEPARATOR . $filename),
            $data . "\n" . str_repeat("=", 20) . "\n\n"
        );
    }
    public static function exists($name)
    {
        return file_exists(public_path() . $name);
    }
    public static function getImageUrl($name)
    {
        return url('/public/') . $name;
    }


    public static function getNameExists($type = null)
    {


        if (Cache::has('nameExists')) {
            $nameExists =  Cache::get('nameExists');
        } else {
            $nameExists = Dropdown::where('name', $type)->exists();
            Cache::put('nameExists', $nameExists);
        }
        return $nameExists;
    }
    public static function getImageExists($type = null)
    {


        if (Cache::has('image_exist')) {
            $image_exist =  Cache::get('image_exist');
        } else {
            $image_exist = Dropdown::where('name', $type)->first();
            Cache::put('image_exist', $image_exist);
        }
        return $image_exist;
    }


    public static function getDropDownTableExistOrNot($tableName)
    {
        $tableExist =  Cache::get('dropDownTableExist_' . $tableName);
        if (empty($tableExist)) {

            $dropDownTables =  Dropdown::where('name', $tableName)->first();
            if (!empty($dropDownTables)) {
                Cache::put('dropDownTableExist_' . $tableName, $dropDownTables->name);
            }
        }

        return Cache::get('dropDownTableExist_' . $tableName);
    }

    public static function createTableIfNotExists($tableName)
    {


        $dropdowntableExist = self::getDropDownTableExistOrNot($tableName);
        if (!$dropdowntableExist) {
            return abort(403);
        }

        if (Cache::has('createTableIfNotExists_' . $tableName)) {

            Cache::get('createTableIfNotExists_' . $tableName);
        } else {
            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) {
                    $table->id();
                    $table->string('image');
                    $table->string('status')->default(1);
                    $table->dateTime('created_at')->useCurrent();
                    $table->dateTime('updated_at')->useCurrent();
                    $table->dateTime('deleted_at')->nullable();
                });
                self::createDropDownTranslationTableIfNotExists($tableName, $tableName);
                self::insertLangTextIfNotExists($tableName);
                $permissionNames = [
                    'CHANGE_STATUS',
                    'LIST',
                    'ADD',
                    'EDIT',
                    'DELETE',
                ];
                foreach ($permissionNames as $permissionName) {
                    $existingPermission = Permission::where('action', strtoupper($tableName . '_' . $permissionName))->first();

                    if (!$existingPermission) {
                        // Permission doesn't exist, create it
                        Permission::create(['action' => strtoupper($tableName . '_' . $permissionName), 'name' => $permissionName, 'group_name' => ucfirst($tableName)]);
                    }
                }
            }
            Cache::forget('dropdownItems');
            Cache::put('createTableIfNotExists_' . $tableName, $tableName);
        }
    }

    public static function createDropDownTranslationTableIfNotExists($tableName)
    {
        if (Cache::has('TranslationTable_' . $tableName)) {
            $tableName = Cache::get('tableName');
        } else {
            if (!Schema::hasTable($tableName . Constant::TRANSLATION_TABLE)) {
                Schema::create($tableName . Constant::TRANSLATION_TABLE, function (Blueprint $table) use ($tableName) {
                    $table->id();
                    $table->string($tableName . '_id');
                    $table->string('language_id');
                    $table->string('name', 255);
                    $table->dateTime('created_at')->useCurrent();
                    $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                    $table->dateTime('deleted_at')->nullable();
                });
            } else {
                $tableName = '';
            }
            Cache::put('TranslationTable_' . $tableName, $tableName);
        }
        return $tableName;
    }

    public static function createDropDownTableIfNotExists()
    {
        if (Cache::has('dropdown')) {
            $dropdown = Cache::get('dropdown');
        } else {
            if (!Schema::hasTable('dropdown')) {
                Schema::create('dropdown', function (Blueprint $table) {
                    $table->id();
                    $table->string('name', 255);
                    $table->string('status')->default(1);
                    $table->dateTime('created_at')->default(now());
                    $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                });
                $dropdown = 'dropdown';
            } else {
                $dropdown = '';
            }
            Cache::put('dropdown', $dropdown);
        }
        return $dropdown;
    }

    public static function insertLangTextIfNotExists($text)
    {

        $messages = include(resource_path('lang/en/dropdowns.php'));

        //Back up old file


        $folderPath = public_path('dropdowns');
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        $fileName = 'dropdowns' . strtotime(date('Y-m-d H:i:s')) . '.php';
        $filePath = $folderPath . '/' . $fileName;




        file_put_contents($filePath, '<?php return ' . var_export($messages, true) . ';');


        //end BAck up old File

        Arr::set($messages, str_replace("_", " ", $text), ucfirst(str_replace("_", " ", $text)));
        Arr::set($messages, str_replace("_", " ", $text) . 'ImageHint', '200px*200px');


        file_put_contents(resource_path('lang/en/dropdowns.php'), '<?php return ' . var_export($messages, true) . ';');
    }

    public static function getLanguagesList()
    {
        return Language::where('status', '1')->orderBy('created_at', 'asc')->pluck('name', 'locale');
    }



    public static function getExceptionError($e)
    {

        if (env('APP_DEBUG')) {
            $error = $e->getMessage();
        } else {
            $error = __(Constant::ERROR_OCCURRED);
        }
        CommonService::createExceptionLog($e);
        return $error;
    }
    public static function redirectBackWithError($error)
    {
        Session::flash('error', $error);
        return Redirect::back()->withInput();
    }

    public static function parsAndReturn($url)
    {
        $urlComponents     =       parse_url($url);
        $queryString       =       '';
        $query             =       array();
        if (isset($urlComponents['query'])) {
            parse_str($urlComponents['query'], $query);
            if (isset($query['page']) && $query['page'] != '') {
                unset($query['page']);
            }
            $queryString = http_build_query($query);
        }
        if (isset($urlComponents['port'])) {
            $urlComponents['host']      = $urlComponents['host'] . ":" . $urlComponents['port'];
        }
        $url = $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'];
        if ($queryString != '') {
            $url .= '?' . $queryString;
        }
        return $url;
    }
    public static function redirectStatusChange($old)
    {
        $redirectUrl = $old->getTargetUrl();
        return redirect(self::parsAndReturn($redirectUrl));
    }

    public static function exportExcludeFields($modelName)
    {
        $excludeFields = array();
        $excludeFields = CsvConstant::COMMON_EXCLUDE_FIELDS;

        switch ($modelName) {
            case 'User':
                $userFields = CsvConstant::USER_EXCLUDE_FIELDS;
                $excludeFields = array_merge($excludeFields, $userFields);
                break;

            case 'Audio':
                $userFields = CsvConstant::AUDIO_EXCLUDE_FIELDS;
                $excludeFields = array_merge($excludeFields, $userFields);
                break;

            case 'Video':
                $userField = CsvConstant::VIDEO_EXCLUDE_FIELDS;
                $excludeFields = array_merge($excludeFields, $userField);
                break;

            case 'Setting':
                $userFields = CsvConstant::SETTING_EXCLUDE_FIELDS;
                $excludeFields = array_merge($excludeFields, $userFields);
                break;

            default:
                $excludeFields = CsvConstant::COMMON_EXCLUDE_FIELDS;
                break;
        }

        return $excludeFields;
    }


    public static function zipFilesCheckList($publicPath, $folderPath, $zipFile)
    {
        if ($zipFile == 'all') {
            $filesCheckList = File::allFiles($publicPath);
            foreach ($filesCheckList as $file) {
                $fileName = basename($file);
                $filePath = $publicPath . $fileName;
                $zipFolderPath = $folderPath . $fileName;
                $files[] = array('filePath' => $filePath, 'zipFolderFile' => $zipFolderPath);
            }
        } else {
            $fileName =  $zipFile;
            $zipFolderPath = $folderPath . $fileName;
            $filePath = $publicPath . $fileName;
            $files[] = array('filePath' => $filePath, 'zipFolderFile' => $zipFolderPath);
        }

        return $files;
    }
}
