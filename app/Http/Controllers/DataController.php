<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\models\Data;
use App\User;

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
//        $Data_view['a_projects'] = $this->o_Project->getAll();
//        $Data_view['a_supplier'] = $this->o_Supplier->getAll();
//        $Data_view['a_branch'] = $this->o_Branch->getAll();
//        
//        $aryAllChannel = array();
//        $this->o_Channel->getAllChannelByParentID(0, $aryAllChannel);
//        $Data_view['aryAllChannel'] = $aryAllChannel;

        return view('data.index',$Data_view);
        
    }

}
