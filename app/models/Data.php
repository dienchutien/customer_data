<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Util;
use Illuminate\Support\Facades\Input;
use DateTime;

class Data extends Model
{
    public function ImportExcel($dirFile) {
        set_time_limit(600);

        $a_Error = array();
        //Get all users from db
        $a_DbData = DB::table('data')->select('phone', 'email', 'name')->get();
        $a_DbDatasPhone = array();
        if (Util::b_fCheckArray($a_DbData)) {
            foreach ($a_DbData as $o_DbUser) {
                $a_DbDatasPhone[] = trim($o_DbUser->phone);
                $a_DbDatasCode[] = trim($o_DbUser->code);
            }
        }

        $o_Result = Excel::selectSheetsByIndex(0)->load($dirFile, function($reader) use ($a_DbDatasPhone, $a_DbDatasCode, &$a_Respon) {
            $a_NewUsers = array();
            $a_NeedUpdateUsers = array();
            foreach ($reader->toArray() as $key => $row) {
                //check field
                $i_Line = $key + 2;
                if (isset($row['email']) && $row['email'] != "") {
                    if (!isset($row['code']))
                        $a_Respon['Err_code'] = "Kiểm tra lại trường Code trong file excel dòng $i_Line <br/>";
                    if (!isset($row['name']))
                        $a_Respon['Err_name'] = "Kiểm tra lại trường Name trong file excel dòng $i_Line <br/>";

                    if (isset($row['direct_manager_code']) && $row['direct_manager_code'] != "") {
                        $o_Manager = $this->o_ModelUser->getUserByCode($row['direct_manager_code']);
                        $i_ManagerID = count($o_Manager) > 0 ? $o_Manager->id : 0;
                    } else {
                        $i_ManagerID = 0;
                    }
                    //Job_id, DepartmetnId, PositionID
                    
                    if (in_array(trim($row['email']), $a_DbDatasPhone)) {
                        // Array Update for user                    
                        if ($row['code'] != "") {
                            $a_userUpdate = array();
                            $a_userUpdate['email'] = $row['email'];
                            $a_userUpdate['code'] = $row['code'];
                            $a_userUpdate['name'] = $row['name'];
                            $a_userUpdate['updated_at'] = Util::sz_fCurrentDateTime();

                            if(isset($row['job']) && $row['job'] != "") $a_userUpdate['job_id'] = $this->GetJobIdByName(trim($row['job']));
                            if(isset($row['department']) && $row['department'] != "") $a_userUpdate['department_id'] = $this->GetDepartmentIdByName(trim($row['department']));
                            if(isset($row['position']) && $row['position'] != "") $a_userUpdate['position_id'] = $this->GetPositionIdByName(trim($row['position']));
                            if(isset($row['direct_manager_code']) && $row['direct_manager_code'] != "") $a_userUpdate['direct_manager_id'] = $i_ManagerID;
                            if(isset($row['work_start']) && $row['work_start'] != ""){
                                $ary_date = explode("/",$row['work_start']);
                                $dateObject = new DateTime($ary_date[2].'-'.$ary_date[1].'-'.$ary_date[0]);
                                $date_work_start = $dateObject->format('Y-m-d');
                                $a_userUpdate['work_start'] = $date_work_start;
                            }
                            $a_NeedUpdateUsers[] = $a_userUpdate;
                            unset($a_userUpdate);

                        }
                    } else {
                        // Array Insert new user
                        if ($row['code'] != "") {
                            if (in_array(trim($row['code']), $a_DbDatasCode))
                                $a_Respon['codeDB'] = "đã tồn tại {$row['code']} trong dữ liệu <br/>";
                            else {
                                $a_userInsert = array();
                                $a_userInsert['email'] = $row['email'];
                                $a_userInsert['code'] = $row['code'];
                                $a_userInsert['name'] = $row['name'];
                                $a_userInsert['updated_at'] = Util::sz_fCurrentDateTime();
                                $a_userInsert['password'] = '$2y$10$JHhTMKnsGuHcKK7PcYy/NO2EZ70e7wvggWUnUuUn23Nl/amUSi8ju';//123456

                                if(isset($row['job']) && $row['job'] != "") $a_userInsert['job_id'] = $this->GetJobIdByName(trim($row['job']));
                                if(isset($row['department']) && $row['department'] != "") $a_userInsert['department_id'] = $this->GetDepartmentIdByName(trim($row['department']));
                                if(isset($row['position']) && $row['position'] != "") $a_userInsert['position_id'] = $this->GetPositionIdByName(trim($row['position']));
                                if(isset($row['direct_manager_code']) && $row['direct_manager_code'] != "") $a_userInsert['direct_manager_id'] = $i_ManagerID;
                                if(isset($row['work_start']) && $row['work_start'] != ""){
                                    $ary_date = explode("/",$row['work_start']);
                                    $dateObjectInsert = new DateTime($ary_date[2].'-'.$ary_date[1].'-'.$ary_date[0]);
                                    $date_work_start = $dateObjectInsert->format('Y-m-d');
                                    $a_userInsert['work_start'] = $date_work_start;
                                }

                                $a_NewUsers[] = $a_userInsert;
                                unset($a_userInsert);
                            }
                        }
                    }
                    if ($row['code'] != "") {
                        $a_All[] = [
                            'code' => $row['code'],
                            'email' => $row['email'],
                        ];
                    }
                }
            }


            // check duplicate in excel file
            $a_ExcelCode = array();
            if (isset($a_All) && count($a_All) > 0) {
                foreach ($a_All as $val) {
                    $a_ExcelCode[] = $val['code'];
                    $a_ExcelEmail[] = $val['email'];
                }

                $duplicates = array_unique(array_diff_assoc($a_ExcelCode, array_unique($a_ExcelCode)));
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
                    if (DB::table('users')->insert($a_NewUsers)) {
                        $a_Respon['insert'] = "Inserted $i_TotalNewUsers successfully! <br/>";
                    } else {
                        $a_Respon['insert'] = "Insert new users failed! <br/>";
                    }
                } else {
                    $a_Respon['insert'] = "No any new users found! <br/>";
                }
                //Check to update
                if (isset($a_NeedUpdateUsers) && Util::b_fCheckArray($a_NeedUpdateUsers)) {
                    //Get total of new users
                    $i_UpdateSuccessfully = 0;
                    $i_UpdateFail = 0;
                    foreach ($a_NeedUpdateUsers as $a_UpdateUser) {
                        $sz_WhereEmail = trim($a_UpdateUser['email']);
                        unset($a_UpdateUser['email']);
                        if (DB::table('users')->where('email', $sz_WhereEmail)->update($a_UpdateUser)) {
                            $i_UpdateSuccessfully++;
                        } else {
                            $i_UpdateFail++;
                        }
                    }
                    $a_Respon['update'] = "Updated $i_UpdateSuccessfully user(s) successfully! And $i_UpdateFail failed! <br/>";
                } else {
                    $a_Respon['update'] = "No any existed user to update found!<br/>";
                }
            }
            //end process
            
        });
        return $a_Respon;

    }
}
