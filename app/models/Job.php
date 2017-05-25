<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Util;
use DB;
use Illuminate\Support\Facades\Input;
use Auth;
use App\models\Projects;
use App\User;
use App\models\Channel;
use App\models\Supplier;
use Illuminate\Support\Facades\Session;

class Job extends Model
{
    private $o_Project;
    private $o_Channel;
    private $o_User;
    private $o_Supplier;
    private $o_Branch;

    public function __construct() {
        $this->o_Project = new Projects();
        $this->o_Channel = new Channel();
        $this->o_user = new User();
        $this->o_Supplier = new Supplier();
        $this->o_Branch = new Branch();
    }
    
    /**
     * @Auth: Dienct
     * @Des: Add/edit project
     * @Since: 02/03/2017
     */
    public function AddEditJob($id) {
        $a_DataUpdate = array();
        $a_DataUpdate['title'] = Input::get('title');
        $a_DataUpdate['status'] = Input::get('status') == 'on' ? 1 : 0;
        $a_DataUpdate['description'] = Input::get('description');
        $a_DataUpdate['project_id'] = Input::get('projects');
        $a_DataUpdate['supplier_id'] = Input::get('supplier');
        $a_DataUpdate['branch_id'] = Input::get('branch');
        $a_DataUpdate['channel_id'] = Input::get('channel');
        
        $dataChannel = $this->o_Channel->getChanneltById(Input::get('channel'));
            if(isset($dataChannel->parent_id)){
                $parent_channel = $dataChannel->parent_id != 0 ?  $dataChannel->parent_id : $dataChannel->id;
            }else{
                $parent_channel = 0;
            }
        $a_DataUpdate['parent_channel'] = $parent_channel;
        $time_finish = Input::get('date_finish');
        $a_DataUpdate['date_finish'] = date('Y-m-d',strtotime($time_finish));
        $a_DataUpdate['job_type'] = Input::get('job_type');// 0 la tra truoc, 1 la tra sau
        
        $a_DataUpdate['admin_modify'] = Auth::user()->id;
        $a_DataUpdate['money'] = (int)str_replace(' ','',Input::get('money'));

        $a_DataUpdate['updated_at'] = date('Y-m-d H:i:s', time());
        
        if (is_numeric($id) == true && $id != 0) {
            if(Input::get('job_type') == 1){// => tra sau
                $a_DataUpdate['is_payment'] = 1;
            }else{
                $o_Job = $this->getJobById($id);
                if($o_Job->job_type == 1) $a_DataUpdate['is_payment'] = 0; // tra sau => tra truoc
            }
            DB::table('jobs')->where('id', $id)->update($a_DataUpdate);
        } else {
            $a_DataUpdate['created_at'] = date('Y-m-d H:i:s', time());
            $a_DataUpdate['is_payment']  = Input::get('job_type') == 0 ? 0 : 1;
            
            DB::table('jobs')->insert($a_DataUpdate);
        }
    }
    
    /**
     * @Auth: Dienct
     * @Des: 
     * @Since: 2/3/2017
     */
    public function getJobById($id) {

        $a_Data = array();
        $a_Data = DB::table('jobs')->where('id', $id)->first();
        if (count($a_Data) > 0){
            $a_Data->created_at = Util::sz_DateTimeFormat($a_Data->created_at);
            $a_Data->date_finish = Util::sz_DateFinishFormat($a_Data->date_finish);
            $a_Data->updated_at = Util::sz_DateTimeFormat($a_Data->updated_at);
        }
        return $a_Data;
    }
    
    /**
     * @Auth:Dienct
     * @Des: get all record jobs table
     * @Since: 06/03/2017
     */
    public function getAllSearch() {
        DB::connection()->enableQueryLog();
        $a_data = array();
        $o_Db = DB::table('jobs')->select('*');
        $a_search = array();

//        if(Auth::user()->rolegroup_id != 2){
//            $o_Db->where('admin_modify', Auth::user()->id);
//        }

        //search 
        $i_is_payment = Input::get('is_payment','');
        if($i_is_payment != '') {
            $a_search['i_is_payment'] = $i_is_payment;
            $a_data = $o_Db->where('is_payment', $i_is_payment);
        }
        
        $sz_title_name = Input::get('title_name','');
        if($sz_title_name != '') {
            $a_search['title_name'] = $sz_title_name;
            $a_data = $o_Db->where('title', 'like', '%'.$sz_title_name.'%');
        }
        
        $i_admin_modify = Input::get('admin_modify','');
        if($i_admin_modify != '') {
            $a_search['admin_modify'] = $i_admin_modify;
            $a_data = $o_Db->where('admin_modify', $i_admin_modify);
        }
        
        $i_project = Input::get('project','');
        if($i_project != '') {
            $a_search['project'] = $i_project;
            $a_data = $o_Db->where('project_id', $i_project);
        }
        
        $i_supplier = Input::get('supplier','');
        if($i_supplier != '') {
            $a_search['supplier'] = $i_supplier;
            $a_data = $o_Db->where('supplier_id', $i_supplier);
        }
        
        $i_branch = Input::get('branch','');
        if($i_branch != '') {
            $a_search['branch'] = $i_branch;
            $a_data = $o_Db->where('branch_id', $i_branch);
        }
        
        $i_channel = Input::get('channel','');
        if($i_channel != '') {
            $a_search['channel'] = $i_channel;
            $this->o_Channel->getAllChannelIDByParentID($i_channel,$aryChildID);
            $aryChildID[] = $i_channel;
            $a_data = $o_Db->whereIn('channel_id', $aryChildID);
        }
        
        $sz_from_date = Input::get('from_date','');
        if($sz_from_date != '') {
            $a_search['from_date'] = $sz_from_date;
            $a_data = $o_Db->where('date_finish','>=', date('Y-m-d',strtotime($sz_from_date)));
           
        }
        
        $sz_to_date = Input::get('to_date','');
        if($sz_to_date != '') {
            $a_search['to_date'] = $sz_to_date;
            $a_data = $o_Db->where('date_finish','<=', date('Y-m-d',strtotime($sz_to_date)));
           
        }
        
        $a_data = $o_Db->orderBy('updated_at', 'desc')->paginate(30);
        // sql
        $query = DB::getQueryLog();
        $query = end($query);
        foreach ($query['bindings'] as $i => $binding) {
            $query['bindings'][$i] = "'$binding'";
        }

        $sz_query_change = str_replace(array('%', '?'), array('%%', '%s'), $query['query']);
        $sz_SqlFull = vsprintf($sz_query_change, $query['bindings']);        

        // save session
        Session::put('sqlGetJob', $sz_SqlFull);
        
        $money_total = 0;
        foreach ($a_data as $key => &$val) {
            $val->stt = $key + 1;
            $val->project = isset($this->o_Project->getProjectById($val->project_id)->name)? $this->o_Project->getProjectById($val->project_id)->name : 'khong xac dinh';
            $val->supplier = isset($this->o_Supplier->getSupplierById($val->supplier_id)->name) ? $this->o_Supplier->getSupplierById($val->supplier_id)->name : 'ko xac dinh';
            $val->channel = isset($this->o_Channel->getChanneltById($val->channel_id)->name) ? $this->o_Channel->getChanneltById($val->channel_id)->name : 'khong xac dinh';
            $val->branch = isset($this->o_Branch->getBranchById($val->branch_id)->name) ? $this->o_Branch->getBranchById($val->branch_id)->name : 'khong xac dinh';
            $val->user = $this->o_user->GetUserById($val->admin_modify)->email;            
            $val->date_finish = Util::sz_DateFinishFormat($val->date_finish);
            $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
            $money_total += $val->money;
        }
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search, 'money_total'=>$money_total);
        return $a_return;
    }
    
    /**
     * @Auth:Dienct
     * @Des: get all record jobs statistics
     * @Since: 13/03/2017
     */
    public function getJobStatistics(){
        DB::connection()->enableQueryLog();
        $a_data = array();
        $sz_filter = Input::get('filter_by','');
        
        $o_Db = DB::table('jobs')->select(DB::raw('sum(money) as total_money,'.$sz_filter));
        $a_search = array();
        
        $sz_from_date = Input::get('from_date','');
        if($sz_from_date != '') {
            $a_search['from_date'] = $sz_from_date;
            $a_data = $o_Db->where('date_finish','>=', date('Y-m-d',strtotime($sz_from_date)));
        }
        
        $sz_to_date = Input::get('to_date','');
        if($sz_to_date != '') {
            $a_search['to_date'] = $sz_to_date;
            $a_data = $o_Db->where('date_finish','<=', date('Y-m-d',strtotime($sz_to_date)));
        }
        
        $i_project = Input::get('project','');
        if($i_project != '') {
            $a_search['project'] = $i_project;
            $a_data = $o_Db->where('project_id', $i_project);
        }
        
        $i_branch = Input::get('branch','');
        if($i_branch != '') {
            $a_search['branch'] = $i_branch;
            $a_data = $o_Db->where('branch_id', $i_branch);
        }
        
        //group by
        if($sz_filter != '') {
            $a_search['filter_by'] = $sz_filter;
            $a_data = $o_Db->groupBy($sz_filter);
        }
        
        $a_data = $o_Db->get();

        // sql
        $query = DB::getQueryLog();
        $query = end($query);
        foreach ($query['bindings'] as $i => $binding) {
            $query['bindings'][$i] = "'$binding'";
        }

        $sz_query_change = str_replace(array('%', '?'), array('%%', '%s'), $query['query']);
        $sz_SqlFull = vsprintf($sz_query_change, $query['bindings']);        

        // save session
        Session::put('sqlJobStatistics', $sz_SqlFull);
        Session::put('ss_from_date', $sz_from_date);
        Session::put('ss_to_date', $sz_to_date);

        if(count($a_data)> 0){
            foreach ($a_data as $key => &$val) {
                $val->stt = $key + 1;
                if($sz_filter == 'channel_id'){
                    $val->name = isset($this->o_Channel->getChanneltById($val->channel_id)->name) ? $this->o_Channel->getChanneltById($val->channel_id)->name : 'khong xac dinh';
                }else if($sz_filter == 'project_id'){
                    $val->name = isset($this->o_Project->getProjectById($val->project_id)->name)? $this->o_Project->getProjectById($val->project_id)->name : 'khong xac dinh';                    
                }else if($sz_filter == 'branch_id'){
                    $val->name = isset($this->o_Branch->getBranchById($val->branch_id)->name) ? $this->o_Branch->getBranchById($val->branch_id)->name : 'khong xac dinh';
                }else if($sz_filter == 'parent_channel'){
                    $val->name = isset($this->o_Channel->getChanneltById($val->parent_channel)->name) ? $this->o_Channel->getChanneltById($val->parent_channel)->name : 'khong xac dinh';
                }
                
                $val->time = $sz_from_date."-".$sz_to_date;
            }
        }
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }
}
