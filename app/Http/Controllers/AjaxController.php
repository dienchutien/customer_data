<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Auth;
use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;

use App\Util;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class AjaxController extends Controller
{
    private $i_id;
    private $i_type;
    private $sz_func;
    private $sz_tbl;
    private $sz_field;
    private $sz_val;
    private $o_LeaveRequestModel;
    protected $_o_MailApi;


    /**
     * function __Contruct    
     */
    public function __construct() {

        $this->i_id = Input::get('id',0);
        $this->i_type = Input::get('type',0);
        $this->sz_func = Input::get('func');
        $this->sz_tbl = Input::get('tbl');
        $this->sz_field = Input::get('field');
        $this->sz_val = Input::get('val');
    }
    
    public function SetProcess(){
        if($this->sz_func == "") exit;
        switch ($this->sz_func) {
            case "delete-row":
                $this->DeleteRow();
                break;
            case "recover-row":
                $this->RecoverRow();
                break;
            case "save-session-job-statistics":
                $this->SaveSessionStatistics();
                break;
            case "transfer-data":
                $this->TransferData();
                break;
            default:
                break;
        }
    }
    
    /**

     * Auth: DienCt
     * Des: Delete record
     * Since: 31/12/2015
     */
    protected function DeleteRow(){

        if($this->i_id == 0 || $this->i_type == 0 || $this->sz_tbl == "") exit;
        if($this->i_type == 1){
            // update            
            $res = DB::table($this->sz_tbl)->where('id',(int)$this->i_id)->update(array('status' => 2));
            
        }else if($this->i_type == 2){
            $res = DB::table($this->sz_tbl)->where('id', '=', $this->i_id)->delete();
        }
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1 
                );
           
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);       
    }
    /**
     * @Auth: DienCt
     * @Des: Recover record
     * @Since: 31/12/2015
     */
    protected function RecoverRow(){

        if($this->i_id == 0 || $this->sz_tbl == "") exit;
        
            // update
            $res = DB::table($this->sz_tbl)->where('id',(int)$this->i_id)->update(array('status' => 1));
        
        if($res){
            $arrayRes = array('success' => "Cập nhật dữ liệu thành công!",
                              'result' => 1 
                );
        }else{
            $arrayRes = array('success' => "Không thể cập nhật dữ liệu!",
                               'result' => 0,
                );
        }
        echo json_encode($arrayRes);

    }
    /**
     * @Auth: DienCt
     * @Des: Transfer Data
     * @Since: 14/06/2017
     */
    protected function TransferData(){
        $partnerID = Input::get('new_assigner');
        $o_partner = DB::table('users')->where('id', $partnerID)->first();
        $partnerEmail = $o_partner->email;
        
        $sz_Sql = Session::get('sqlDataTransfer');
        if(strpos($sz_Sql, 'limit') !== false){
            $arr =  explode('limit',$sz_Sql);
            $sz_Sql = $arr[0];
        }
        $a_Data = DB::select(DB::raw($sz_Sql));
        $aryMSG = array();
        $flagError = 0;
        if(count($a_Data) > 0){
            foreach($a_Data as $key =>$o_val){
                if(strpos($o_val->partner, $partnerID ) !== false) $flagError = 1;
            }
        }
        if($flagError == 1){
            $aryMSG['msg'] = 'Đã được phân quyền cho người này, kiểm tra dữ liệu';
        }else{
            foreach($a_Data as $key =>$o_val){
                
                if($o_val->partner == null || $o_val->partner == ''){
                    $res = DB::table($this->sz_tbl)->where('id',$o_val->id)->update(array('partner' => $partnerID));
                }else{
                    $partnerSTR = $o_val->partner.','.$partnerID;
                    $res = DB::table($this->sz_tbl)->where('id',$o_val->id)->update(array('partner' => $partnerSTR));
                }
            }
                    Mail::send('data.mailH', array('a_EmailBody' => $a_Data), function($message) use ($partnerEmail){
                        $message->to($partnerEmail);
                        $message->subject(rand(10,1000));
                    });
            $aryMSG['msg'] = 'Cap nhat thanh cong';
        }
        echo json_encode($aryMSG);

    }
    
    /**

     * @auth: Dienct
     * @since: 14/03/2017
     * @des: save session
     * 
     *      */
    protected function SaveSessionStatistics(){
        $sz_filter_by = Input::get('sz_filter_by','');
        $szfrom_date = Input::get('szfrom_date','');
        $szto_date = Input::get('szto_date','');
        Session::forget('ss_from_date');
        Session::forget('ss_to_date');
        Session::put('ss_filter_by', $sz_filter_by);
        Session::put('ss_from_date', $szfrom_date);
        Session::put('ss_to_date', $szto_date);
        
    }
    
    
}
