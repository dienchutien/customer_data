<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Util;
use Illuminate\Support\Facades\Input;
use DateTime;
use Illuminate\Support\Facades\Session;

class Data extends Model {

    public function ImportExcel($dirFile) {
        set_time_limit(600);

        $a_Error = array();
        //Get all users from db
        $a_DbData = DB::table('data')->select('phone', 'email', 'name', 'project')->get();
        $a_DbDatasPhone = array();
        foreach ($a_DbData as $o_DbUser) {
            $a_DbDatasPhone[] = trim($o_DbUser->phone);
        }
        $o_Result = Excel::selectSheetsByIndex(0)->load($dirFile, function($reader) use ($a_DbDatasPhone, &$a_Respon) {
            $a_NewUsers = array();
            $a_NeedUpdateUsers = array();
            foreach ($reader->toArray() as $key => $row) {
                //check field
                $i_Line = $key + 2;
                if (isset($row['phone']) && $row['phone'] != "") {

                    if (!isset($row['name']))
                        $a_Respon['Err_name'] = "Kiểm tra lại trường Name trong file excel dòng $i_Line <br/>";

                    //Job_id, DepartmetnId, PositionID

                    if (in_array(trim($row['phone']), $a_DbDatasPhone)) {
                        // Array Update for user                    
                        $a_userUpdate = array();
                        $a_userUpdate['email'] = $row['email'];
                        $a_userUpdate['phone'] = $row['phone'];
                        $a_userUpdate['name'] = $row['name'];
                        $a_userUpdate['project'] = $row['project'];
                        $a_userUpdate['updated_at'] = Util::sz_fCurrentDateTime();

                        $a_NeedUpdateUsers[] = $a_userUpdate;
                        unset($a_userUpdate);
                    } else {
                        // Array Insert new user                            
                        $a_userInsert = array();
                        $a_userInsert['email'] = $row['email'];
                        $a_userInsert['phone'] = $row['phone'];
                        $a_userInsert['name'] = $row['name'];
                        $a_userInsert['project'] = $row['project'];
                        $a_userInsert['created_at'] = Util::sz_fCurrentDateTime();
                        $a_NewUsers[] = $a_userInsert;
                        unset($a_userInsert);
                    }
                    if ($row['phone'] != "") {
                        $a_All[] = [
                            'phone' => $row['phone'],
                            'email' => $row['email'],
                        ];
                    }
                }
            }


            // check duplicate in excel file
            $a_ExcelPhone = array();
            if (isset($a_All) && count($a_All) > 0) {
                foreach ($a_All as $val) {
                    $a_ExcelPhone[] = $val['phone'];
                    $a_ExcelEmail[] = $val['email'];
                }

                $duplicates = array_unique(array_diff_assoc($a_ExcelPhone, array_unique($a_ExcelPhone)));
                $duplicatesEmail = array_unique(array_diff_assoc($a_ExcelEmail, array_unique($a_ExcelEmail)));
                if (count($duplicates) > 0) {
                    $str = "";
                    foreach ($duplicates as $val) {
                        $str .= $val . " ";
                    }
                    if ($str != "") {
                        $a_Respon['codeExcel'] = "Kiểm tra lại file excel $str đang bị trùng <br/>";
                    }
                }
                if (count($duplicatesEmail) > 0) {
                    $str = "";
                    foreach ($duplicatesEmail as $val) {
                        $str .= $val . " ";
                    }
                    if ($str != "") {
                        $a_Respon['emailExcel'] = "Kiểm tra lại file excel $str đang bị trùng <br/>";
                    }
                }
            }

            //end check duplicate in file
            //start process
            if (!isset($a_Respon)) {
                if (isset($a_NewUsers) && Util::b_fCheckArray($a_NewUsers)) {
                    //Get total of new users
                    $i_TotalNewUsers = count($a_NewUsers);
                    //Insert new user into db
                    if (DB::table('data')->insert($a_NewUsers)) {
                        $a_Respon['insert'] = "Inserted $i_TotalNewUsers successfully! <br/>";
                    } else {
                        $a_Respon['insert'] = "Insert new data failed! <br/>";
                    }
                } else {
                    $a_Respon['insert'] = "No any new data found! <br/>";
                }
                //Check to update
                if (isset($a_NeedUpdateUsers) && Util::b_fCheckArray($a_NeedUpdateUsers)) {
                    //Get total of new users
                    $i_UpdateSuccessfully = 0;
                    $i_UpdateFail = 0;
                    foreach ($a_NeedUpdateUsers as $a_UpdateUser) {
                        $sz_WherePhone = trim($a_UpdateUser['phone']);
                        unset($a_UpdateUser['phone']);
                        if (DB::table('data')->where('phone', $sz_WherePhone)->update($a_UpdateUser)) {
                            $i_UpdateSuccessfully++;
                        } else {
                            $i_UpdateFail++;
                        }
                    }
                    $a_Respon['update'] = "Updated $i_UpdateSuccessfully data(s) successfully! And $i_UpdateFail failed! <br/>";
                } else {
                    $a_Respon['update'] = "No any existed data to update found!<br/>";
                }
            }
            //end process
        });
        return $a_Respon;
    }
    /**
     * @Auth:Dienct
     * @Des: get all record jobs table
     * @Since: 06/03/2017
     */
    public function getAllSearch() {
        DB::connection()->enableQueryLog();
        $a_data = array();
        $o_Db = DB::table('data')->select('*');
        $a_search = array();

        //search 
        $i_status = Input::get('phone_status','');
        if($i_status != '') {
            $a_search['phone_status'] = $i_status;
            $a_data = $o_Db->where('status', $i_status);
        }
        
        $sz_phone_number = Input::get('phone_number','');
        if($sz_phone_number != '') {
            $a_search['phone_number'] = $sz_phone_number;
            $a_data = $o_Db->where('phone', 'like', '%'.$sz_phone_number.'%');
        }
        
        $i_assigner = Input::get('assigner','');
        if($i_assigner != '') {
            $a_search['assigner'] = $i_assigner;
            $a_data = $o_Db->where('partner', 'like', '%'.$i_assigner.'%');
        }
        
        $i_Noassigner = Input::get('not_assigner','');
        if($i_Noassigner != '') {
            $a_search['not_assigner'] = $i_Noassigner;
            $a_data = $o_Db->where('partner', 'not like', '%'.$i_Noassigner.'%');
        }
        
        $i_project = Input::get('project','');
        if($i_project != '') {
            $a_search['project'] = $i_project;
            $a_data = $o_Db->where('projects','like', '%'.$i_project.'%');
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
        
        
        foreach ($a_data as $key => &$val) {
            $val->stt = $key + 1;
            
            $val->created_at = Util::sz_DateFinishFormat($val->created_at);
            $val->updated_at = Util::sz_DateTimeFormat($val->updated_at);
        }
        $a_return = array('a_data' => $a_data, 'a_search' => $a_search);
        return $a_return;
    }

}
