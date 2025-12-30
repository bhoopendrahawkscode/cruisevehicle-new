<?php

namespace App\Services;
use App\Services\CommonService;
use Config;
use App\Constants\Constant;
use App\Services\GeneralService;
class FilterService
{
    public static  function addFiltersOrLanguage($key, $val, $searchData, $db)
    {

        $searchParams            =    explode("-", substr($key, 1, -1));

        $searchParamsValues        =    explode("-", substr($val, 1, -1));
        $db->where(function ($query) use ($searchParams, $searchParamsValues, $searchData) {

            foreach ($searchParams as $keys => $vals) {
                switch ($searchParamsValues[$keys]) {
                    case 'like':
                        $query->orWhere("$vals", 'like', '%' . $searchData['name'] . '%');
                        break;
                    case '=':
                        $query->orWhere("$vals", $searchData['name']);
                        break;
                    default:
                }
            }
        });

        return $db;
    }

    public static  function addFiltersLanguage($searchData, $fieldsToSearch, $db)
    {


        foreach ($fieldsToSearch as $key => $val) {

            if (str_contains($key, '*') && isset($searchData['name']) && $searchData['name'] != '') {
                $db =  self::addFiltersOrLanguage($key, $val, $searchData, $db);
            } elseif (isset($searchData[$key]) && $searchData[$key] != '') {
                switch ($val) {
                    case 'like':
                        $db->where("$key", "$val", '%' . $searchData[$key] . '%');
                        break;
                    case '=':

                        $db->where("$key", $searchData[$key]);
                        break;
                    default:
                }
            }
        }
        return $db;
    }

    public static  function getFiltersDropdownsLanguage($input, $request, $searchData, $fieldsToSearch, $DB, $dateField, $extras)
    {

        $mainTable  = $extras['mainTable'];
        $foreignKey  = $extras['foreignKey'];
        $translationFields  = $extras['translationFields'];

        $translationTable  = $mainTable . Constant::TRANSLATION_TABLE;
        $languageId = CommonService::getLangIdFromLocale();
        $paginate = GeneralService::getSettings('pageLimit');
        if (
            isset($searchData['from']) && $searchData['from'] != ''
            && isset($searchData['to']) && $searchData['to'] != ''
        ) {
            $DB->where("$mainTable." . $dateField, '>=', $searchData['from'] . Constant::ZERO_ZERO);
            $DB->where("$mainTable." . $dateField, '<=', $searchData['to'] . Constant::FULL_FULL);
        } elseif (isset($searchData['from']) && $searchData['from'] != '') {
            $DB->where("$mainTable." . $dateField, '>=', $searchData['from'] . Constant::ZERO_ZERO);
        } elseif (isset($searchData['to']) && $searchData['to'] != '') {
            $DB->where("$mainTable." . $dateField, '<=', $searchData['to'] . Constant::FULL_FULL);
        }

        $DB = self::addFiltersLanguage($searchData, $fieldsToSearch, $DB);
        $sortBy = ($request->sortBy) ? $request->sortBy : $mainTable . ".updated_at";
        $order  = ($request->order) ? $request->order  : 'DESC';
        $result = $DB
            ->leftJoin($translationTable, "$mainTable.id", '=', "$translationTable.$foreignKey")
            ->select(...$translationFields)
            ->where("$translationTable.language_id", '=', $languageId)
            ->orderBy($sortBy, $order)
            ->paginate($paginate);
        $complete_string        =   $input::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string           =   http_build_query($complete_string);
        $result->appends($input::all())->render();

        return [
            'result' => $result, 'DB' => $DB, 'sortBy' => $sortBy,
            'order' => $order, 'query_string' => $query_string
        ];
    }

    public static function getFiltersLanguage($input, $request, $searchDataX, $fieldsToSearch, $DB, $dateField, $extrasX)
    {

        $mainTable  = $extrasX['mainTable'];
        $foreignKey  = $extrasX['foreignKey'];
        $translationFields  = $extrasX['translationFields'];
        if (isset($extrasX['mainTableSingular'])) {
            $mainTableSingular  =   $extrasX['mainTableSingular'];
        } else {
            $mainTableSingular  = substr($mainTable, 0, -1);
        }
        $translationTable  = $mainTableSingular . Constant::TRANSLATION_TABLE;
        $languageId = CommonService::getLangIdFromLocale();
        $paginate = CommonService::getPaginate($mainTable);
        if (
            isset($searchDataX['from']) && $searchDataX['from'] != ''
            && isset($searchDataX['to']) && $searchDataX['to'] != ''
        ) {
            $DB->where("$mainTable." . $dateField, '>=', $searchDataX['from'] . Constant::ZERO_ZERO);
            $DB->where("$mainTable." . $dateField, '<=', $searchDataX['to'] . Constant::FULL_FULL);
        } elseif (isset($searchDataX['from']) && $searchDataX['from'] != '') {
            $DB->where("$mainTable." . $dateField, '>=', $searchDataX['from'] . Constant::ZERO_ZERO);
        } elseif (isset($searchDataX['to']) && $searchDataX['to'] != '') {
            $DB->where("$mainTable." . $dateField, '<=', $searchDataX['to'] . Constant::FULL_FULL);
        }
        if (isset($extrasX['defaultConditions'])) {
            foreach ($extrasX['defaultConditions'] as $defaultCondition) {
                $DB->where(...$defaultCondition);
            }
        }
        if (isset($extrasX['defaultSort'])) {
            $sortBy = $extrasX['defaultSort'];
            $dir    = $extrasX['defaultOrder'];
        } else {
            $sortBy = $mainTable . '.updated_at';
            $dir    = 'DESC';
        }

        $DB = self::addFiltersLanguage($searchDataX, $fieldsToSearch, $DB);
        $sortBy = ($request->sortBy) ? $request->sortBy : $sortBy;
        $order  = ($request->order) ? $request->order  : $dir;
        $result = $DB->leftJoin($translationTable, function ($join)
        use ($mainTable, $translationTable, $foreignKey, $languageId) {
            $join->on("$mainTable.id", '=', "$translationTable.$foreignKey")
                ->where("$translationTable.language_id", '=', $languageId);
        })
            ->select(...$translationFields)
            //->where("$translationTable.language_id", '=', $languageId)
            ->orderBy($sortBy, $order)
            ->paginate($paginate);
        $complete_string        =   $input::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string           =   http_build_query($complete_string);
        $result->appends($input::all())->render();

        return [
            'result' => $result, 'DB' => $DB, 'sortBy' => $sortBy,
            'order' => $order, 'query_string' => $query_string
        ];
    }

}
