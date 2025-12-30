<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest as FormRequest;
use App\Models\Category as MainModel;
use App\Models\CategoryTranslation as TranslationModel;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\FilterService;
use  DB, Session, Redirect;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    protected $listRouteCategory;
    protected $addRouteCategory;
    protected $editRouteCategory;
    protected $mainTableCategory;
    protected $foreignKeyCategory;
    protected $translationFieldsCategory;
    protected $hasManyRelationCategory;
    protected $mainTableSingularCategory;
    protected $titleCategory;

    protected $tree;

    protected $treeExport;


    public function buildTree($data, $parentId = 0, $level = 0)
    {


        $treeData = [];
        foreach ($data as $item) {
            if ($item['parent_id'] == $parentId) {
                $item['level'] = $level;
                $item['children'] = $this->buildTree($data, $item['id'], $level + 1);
                $treeData[] = $item;
            }
        }
        return $treeData;
    }
    public function displayTree($tree)
    {

        foreach ($tree as $node) {

            if(($node['level']) <= (intval(env('CATEGORY_LEVEL'))-1)){
                $this->tree[$node['id']] =  str_repeat('&nbsp; &nbsp;', $node['level']) . $node['name'];
                if (!empty($node['children'])) {
                    $this->displayTree($node['children']);
                }
            }

        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRouteCategory = 'admin.category.index';
        $this->addRouteCategory = 'admin.category.add';
        $this->editRouteCategory = 'admin.category.edit';
        $this->mainTableCategory  = 'categories';
        $this->mainTableSingularCategory  = 'category';
        $this->foreignKeyCategory  = 'category_id';
        $this->translationFieldsCategory  = ['categories.*', 'category_translations.name'];
        $this->hasManyRelationCategory = 'category_translations';
        $this->titleCategory  = __('messages.category');


        $cats = MainModel::select('parent_id', 'id', 'status')->with(['category_translation' => function ($query) {
            $query->select('category_id', 'name');
        }])->get()->toArray();
        $catsReArrange  = [];
        foreach ($cats as $row) {
            $catsReArrange[]    = ['id' => $row['id'], 'status' => $row['status'], 'parent_id' => $row['parent_id'], 'name' => $row['category_translation']['name']];
        }
        usort($catsReArrange, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        $treeMain = $tree = $this->buildTree($catsReArrange);
        $this->displayTree($tree);
		if($this->tree){
			$this->tree = [0 => __('messages.none')] + $this->tree;
		}else{
			$this->tree = [0 => __('messages.none')];
		}


        $treeMainReArrange  = [];
        foreach ($treeMain as $row) {
            $treeMainReArrange[$row['id']] = $row;
        }
        \View::share([
            'title' =>  $this->titleCategory,
            'listRoute' => $this->listRouteCategory,
            'addRoute' => $this->addRouteCategory,
            'saveRoute' => "admin.category.save",
            'updateRoute' =>"admin.category.update",
            'listUrl' =>"category-list",
            'formId' => "categoryForm",
            'mainTable' => $this->mainTableCategory,
            'formPath' => "admin.category.form",
            'addPermission' => "CATEGORY_MANAGEMENT_ADD",
            'editPermission' => "CATEGORY_MANAGEMENT_EDIT",
            'deletePermission' => "CATEGORY_MANAGEMENT_DELETE",
            'statusPermission' =>"CATEGORY_MANAGEMENT_CHANGE_STATUS",
            'tree'=>$this->tree,
            'treeMain'=>$treeMainReArrange
        ]);

        $this->treeExport   =   $treeMainReArrange;
    }


    /**
     * List Records
     */
    public function exportCategories()
    {
        return $this->treeExport;

    }


    /**
     * List Records
     */
    public function index(Request $request)
    {



        $DB                         =   MainModel::query();
        $fieldsToSearch              =   array('name' => 'like', 'status' => '=');
        $searchVariable                =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTableCategory,
                "foreignKey" => $this->foreignKeyCategory,
                "translationFields" => $this->translationFieldsCategory,
                "mainTableSingular" => $this->mainTableSingularCategory,
                "defaultConditions" => [["parent_id", "=", 0]],
                "defaultSort" => 'name',
                "defaultOrder" => 'asc',
            ],
            2000
        );
        extract($output);

        return  View($this->listRouteCategory, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRouteCategory);
    }

    /**
     * Save Added Record
     */
    public function save(FormRequest $request)
    {


        $error = '';
        DB::beginTransaction();
        try {

            $row = MainModel::create(['parent_id' => $request->parent_id, 'status' => $request->status]);
            $translations = [];
            foreach ($request->name as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'name' => $value,
                    'slug'=> Str::slug($value.'-'.$row->id)
                ];
            }
            $row->{$this->hasManyRelationCategory}()->createMany($translations);

        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        Session::flash('success', $this->titleCategory . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteCategory);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $DB             = new MainModel();
        $data = $DB::with($this->hasManyRelationCategory)->where('id', $id)->first();
        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['parent_id'] = $data->parent_id;
        $dataReArrange['status'] = $data->status;
        if (!empty($data[$this->hasManyRelationCategory])) {
            foreach ($data[$this->hasManyRelationCategory] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return  View($this->editRouteCategory)->with(['data' => $dataReArrange]);
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
            $row->updated_at = date("Y-m-d H:i:s");
            $row->parent_id = $request->parent_id;
            $row->status = $request->status;
            if ($row->save()) {
                $translations = [];
                foreach ($request->name as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyCategory => $row->id,
                        'language_id' => $key,
                        'name' => $value,
                        'slug'=> Str::slug($value.'-'.$row->id)
                    ];
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
        Session::flash('success', $this->titleCategory . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteCategory);
    }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRouteCategory)
            ->with('success', $this->titleCategory . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        $DB = new MainModel();
        $DB->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);
        return redirect()->route($this->listRouteCategory)
            ->with('success', $this->titleCategory . __('messages.statusUpdatedSpecific'));
    }
}
