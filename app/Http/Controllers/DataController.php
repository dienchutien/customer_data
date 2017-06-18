<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\models\Data;
use App\User;
use Illuminate\Support\Facades\Session;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use DB;
use App\Util;

class DataController extends Controller {

    private $o_Data;
    private $o_User;

    public function __construct() {
        $this->o_Data = new Data();
        $this->o_user = new User();
    }

    /**
     * Auth: Dienct
     * Des: Import file excel table data
     * Since: 29/05/2017
     */
    public function importExcel() {
        $a_Res = array();
        if (Input::hasFile('excel')) {
            
            $filename = Input::file('excel')->getClientOriginalName();
            $extension = Input::file('excel')->getClientOriginalExtension();
            if ($extension == 'xlsx' || $extension == 'xls') {
                Input::file('excel')->move('uploads/', $filename);
                $sz_FileDir = 'uploads' . "/" . $filename;
                $a_Res = $this->o_Data->ImportExcel($sz_FileDir);
                $strRes = "";
                foreach ($a_Res as $key => $val) {
                    $strRes .= " " . $val;
                }
            } else {
                $strRes = "Cần nhập đúng định dạng file (xls, xlsx)!!!!";
            }

            return view('data.import', ['a_Res' => $strRes]);
        } else {
            return view('data.import');
        }
    }
    
    /**
     * @Auth: Dienct
     * @Des: list Data.
     * @Since: 6/6/2017
     */
    public function getAllData() {
        $a_Data = $this->o_Data->getAllSearch();

        $Data_view['a_Jobs'] = $a_Data['a_data'];
        $Data_view['a_search'] = $a_Data['a_search'];
        $Data_view['a_users'] = $this->o_user->getAll();
        return view('data.index',$Data_view);
    }
    
    public function autoGetData(){
        $newData = DB::connection('mongodb')->collection('regproject')->orderBy('createTime', 'desc')->take(25)->get();
        if(count($newData) > 0){
            $i_success = 0;
            foreach($newData as $key => $val){                
                if(isset($val['phone']) && $val['phone'] != ''){
                    $checkIsset = DB::table('data')->where('phone', $val['phone'])->get();
                    if(count($checkIsset) == 0){
                        $ary_project = explode('?', str_replace('https://datxanhmienbac.com.vn', '', $val['url']));
                        $dataInsert = array();
                        $dataInsert['email'] = $val['email'];
                        $dataInsert['phone'] = $val['phone'];
                        $dataInsert['name'] = isset($val['name']) && $val['name'] != '' ? $val['name']: $val['email'];
                        $dataInsert['project'] = $ary_project[0];
                        $dataInsert['created_at'] = Util::sz_fCurrentDateTime();
                        DB::table('data')->insert($dataInsert);                        
                        unset($dataInsert);
                        $i_success ++;
                    }
                }
            }
            echo 'Them duoc '.$i_success.' data';
        }

    }

}
