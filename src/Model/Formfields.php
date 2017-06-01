<?php

/**
 * UserFrosting (http://www.srinivasnukala.com)
 *
 * @link      https://github.com/ssnukala/ufsprinkle-autoforms
 * @copyright Copyright (c) 2013-2016 Srinivas Nukala

 * */

namespace UserFrosting\Sprinkle\AutoForms\Model;

use \Illuminate\Database\Capsule\Manager as Capsule;
use UserFrosting\Sprinkle\Core\Model\UFModel;
use UserFrosting\Sprinkle\SnUtilities\Controller\SnUtilities as SnUtil;

class Formfields extends UFModel {

    protected $table = "sevak_formfields";
    protected $fillable = ["form_prefix",
        "source",
        "db_field",
        "value_type",
        "edit_group",
        "visible",
        "orderable",
        "type",
        "lookup_category",
        "showin_editform",
        "primary_key",
        "label",
        "message",
        "validation_json",
        "size1",
        "size2",
        "default",
        "searchable",
        "search_function",
        "search_group",
        "showin_searchresult",
        "result_group",
        "status"];

    public static function getFieldDefinitions($table, $status = 'A', $par_orderdir = 'ASC') {
        $resultArr_obj = self::where('source', '=', $table)
                ->where('status', '=', $status)
                ->orderBy('seq', $par_orderdir)
                ->get();
        $resultArr = $resultArr_obj->toArray();
//logarr($resultArr,"Line 62 returning from formfields table $table");        
        $var_classfields = $var_fields = array();
        $var_primarykey = 'none';
        foreach ($resultArr as $var_rowid => $var_fldrec) {
            $var_dbfld = $var_fldrec['db_field'];
            $var_fields[$var_dbfld] = $var_fldrec;
            if ($var_fldrec['primary_key'] == 'Y' && $var_primarykey == 'none') {
//echobr("Setting primary key now to $var_dbfld");
                $var_primarykey = $var_dbfld;
            }
            if ($var_fldrec['searchable'] == 'Y') {
                $var_classfields['search_fields'][$var_dbfld] = $var_fldrec['edit_group'];
            }
            if ($var_fldrec['showin_editform'] == 'Y') {
                $var_classfields['edit_fields'][$var_dbfld] = $var_fldrec['edit_group'];
            }
        }
//echoarr($var_classfields[$par_table],"Line 618 classfields array");
        if (isset($var_classfields['search_fields']))
            asort($var_classfields['search_fields']); // sort the search fields by serarch group
        if (isset($var_classfields['edit_fields']))
            asort($var_classfields['edit_fields']); // sort the edit fields by edit group

        $var_classfields['primary_key'] = $var_primarykey;
        $var_classfields['fields'] = SnUtil::multisort($var_fields, array("edit_group"));
//logarr($var_classfields,"Line 93");        

        return $var_classfields;
    }

}
