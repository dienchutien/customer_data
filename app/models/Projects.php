<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Util;
use DB;
use Illuminate\Support\Facades\Input;

class Projects extends Model
{
    /**
     * @Auth: Dienct
     * @Des: get all record table department
     * @Since: 2/3/2017
     */
    public function getAll() {
        $a_data = array();
        $a_data = DB::table('projects')->select('id', 'name', 'status', 'created_at', 'updated_at')->orderBy('name', 'asc')->get();
        if(count($a_data) > 0){
            foreach ($a_data as $key => &$val) {
                $val->stt = $key + 1;
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
                $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
            }
        }

        return $a_data;
    }
    
    /**
     * @Auth: Dienct
     * @Des: 
     * @Since: 2/3/2017
     */
    public function getProjectById($id) {

        $a_Data = array();
        $a_Data = DB::table('projects')->where('id', $id)->first();
        if (count($a_Data) > 0)
            $a_Data->created_at = Util::sz_DateTimeFormat($a_Data->created_at);
        if (count($a_Data) > 0)
            $a_Data->updated_at = Util::sz_DateTimeFormat($a_Data->updated_at);

        return $a_Data;
    }
    
    
    /**

     * @Auth: Dienct
     * @Des: Add/edit project
     * @Since: 02/03/2017
     */
    public function AddEditProject($id) {
        $a_DataUpdate = array();
        $a_DataUpdate['name'] = Input::get('name');
        $a_DataUpdate['status'] = Input::get('status') == 'on' ? 1 : 0;
        $a_DataUpdate['description'] = '';
        if (is_numeric($id) == true && $id != 0) {
            $a_DataUpdate['updated_at'] = date('Y-m-d H:i:s', time());
            DB::table('projects')->where('id', $id)->update($a_DataUpdate);
        } else {
            $a_DataUpdate['created_at'] = date('Y-m-d H:i:s', time());
            $a_DataUpdate['updated_at'] = date('Y-m-d H:i:s', time());
            DB::table('projects')->insert($a_DataUpdate);
        }
    }
}
