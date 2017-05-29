<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\models\Data;

class DataController extends Controller {

    private $o_Data;

    public function __construct() {
        $this->o_Data = new Data();
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

}
