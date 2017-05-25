<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Util;
use DB;
use Illuminate\Support\Facades\Input;
use App\User;
use Illuminate\Support\Facades\Auth;

class Channel extends Model
{
    private $o_User;
    
    public function __construct() {
        $this->o_User = new User();
    }
    /**
     * @Auth: Dienct
     * @Des: get all record table department
     * @Since: 2/3/2017
     */
    public function getAll() {
        $a_data = array();
        $a_data = DB::table('channel')->select('id', 'name', 'status', 'created_at', 'updated_at', 'admin_modify')->orderBy('name', 'asc')->get();
        if(count($a_data) > 0){
            foreach ($a_data as $key => &$val) {
                $val->stt = $key + 1;
                $val->created_at = Util::sz_DateTimeFormat($val->created_at);
                $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
                $val->admin = $this->o_User->GetUserById($val->admin_modify)->email ;
            }
        }

        return $a_data;
    }
    
    /**

     * @Auth: Dienct
     * @Des: 
     * @Since: 2/3/2017
     */
    public function getChanneltById($id) {
        $a_Data = array();
        $a_Data = DB::table('channel')->where('id', $id)->first();
        if (count($a_Data) > 0){
            $a_Data->created_at = Util::sz_DateTimeFormat($a_Data->created_at);
            $a_Data->updated_at = Util::sz_DateTimeFormat($a_Data->updated_at);
        }            

        return $a_Data;
    }
    
    /**

     * @Auth: Dienct
     * @Des: Add/edit project
     * @Since: 02/03/2017
     */
    public function AddEditChannel($id) {
        $a_DataUpdate = array();
        $a_DataUpdate['name'] = Input::get('name');
        $a_DataUpdate['status'] = Input::get('status') == 'on' ? 1 : 0;
        $a_DataUpdate['description'] = '';
        $a_DataUpdate['admin_modify'] = Auth::user()->id;
        $a_DataUpdate['parent_id'] = Input::get('parent_id');
        $a_DataUpdate['level'] = isset($this->getChanneltById(Input::get('parent_id'))->level) ? (int)$this->getChanneltById(Input::get('parent_id'))->level + 1 : 0;
        
        if (is_numeric($id) == true && $id != 0) {
            $a_DataUpdate['updated_at'] = date('Y-m-d H:i:s', time());
            DB::table('channel')->where('id', $id)->update($a_DataUpdate);
        } else {
            $a_DataUpdate['created_at'] = date('Y-m-d H:i:s', time());
            $a_DataUpdate['updated_at'] = date('Y-m-d H:i:s', time());
            DB::table('channel')->insert($a_DataUpdate);
        }
    }
    
    /**

     * @Auth: Dienct
     * @Des : get all channel by parent id- default = 0
     * @since: 8/3/2017
     * 
     */
    public function getAllChannelByParentID($parent_id = 0, &$aryChildID) {
        $aryResult = DB::table('channel')->select('*')->where('parent_id', $parent_id)->orderBy('name', 'asc')->get();

        foreach ($aryResult as $o_val) {
            $ary = array();
            $ary['name'] = $o_val->name;
            $ary['level'] = $o_val->level;
            $ary['status'] = $o_val->status;
            $ary['updated_at'] = Util::sz_DateTimeFormat($o_val->updated_at);
                $aryChildID[$o_val->id] = $ary;
            if (!empty($aryResult)) {
                $this->getAllChannelByParentID($o_val->id, $aryChildID);
            }
        }
    }
    
    /**
     * @Auth: Dienct
     * @Des : get all channel ChildID by parent id- default = 0
     * @since: 8/3/2017
     * 
     */
    public function getAllChannelIDByParentID($parent_id = 0, &$aryChildID) {
        $aryResult = DB::table('channel')->select('id', 'name', 'level')->where('parent_id', $parent_id)->get();
        foreach ($aryResult as $o_val) {
                $aryChildID[] = $o_val->id;
            if (!empty($aryResult)) {
                $this->getAllChannelIDByParentID($o_val->id, $aryChildID);
            }
        }
    }
}
