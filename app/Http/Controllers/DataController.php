<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\models\Data;
use App\User;
use Illuminate\Support\Facades\Session;

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
    
    public function DataTransfer(){
        
        $sz_Sql = Session::get('sqlDataTransfer');
        echo"<pre>";
        print_r($sz_Sql);
        echo"</pre>";
        die;
        if(strpos($sz_Sql, 'limit') !== false){
            $arr =  explode('limit',$sz_Sql);
            $sz_Sql = $arr[0];
        }
        
        $a_Data = DB::select(DB::raw($sz_Sql));
        try{
            Excel::create('Danh_Sach_JOb', function($excel) use($a_Data) {
                // Set the title
                $excel->setTitle('no title');
                $excel->setCreator('Dienct')->setCompany('no company');
                $excel->setDescription('report file');
                $excel->sheet('sheet1', function($sheet) use($a_Data) {
                    $money_total = 0;
                    foreach ($a_Data as $key => $o_person) {
                        $o_jobs = array();
                        $o_jobs['stt'] = $key +1;
                        $o_jobs['project'] = isset($this->o_Project->getProjectById($o_person->project_id)->name) ? $this->o_Project->getProjectById($o_person->project_id)->name : 'Ko xac dinh';
                        $o_jobs['supplier'] = isset($this->o_Supplier->getSupplierById($o_person->supplier_id)->name) ? $this->o_Supplier->getSupplierById($o_person->supplier_id)->name : 'Ko xac dinh';
                        $o_jobs['channel'] = isset($this->o_Channel->getChanneltById($o_person->channel_id)->name) ? $this->o_Channel->getChanneltById($o_person->channel_id)->name :'Ko xac dinh' ;
                        $o_jobs['branch'] = isset($this->o_Branch->getBranchById($o_person->branch_id)->name) ? $this->o_Branch->getBranchById($o_person->branch_id)->name : 'khong xac dinh';
                        $o_jobs['title'] = $o_person->title;
                        $o_jobs['description'] = $o_person->description;
                        $o_jobs['money'] = number_format($o_person->money);
                        $o_jobs['date'] = $o_person->date_finish;
                        $o_jobs['admin'] = isset($this->o_user->GetUserById($o_person->admin_modify)->email) ? $this->o_user->GetUserById($o_person->admin_modify)->email :'ko xac dinh'; ;
                        $o_jobs['type'] = isset($o_person->job_type) && $o_person->job_type == 0 ? 'Trả trước' : 'Trả sau';
                        $o_jobs['update'] = $o_person->updated_at;
                        $money_total += (int)$o_person->money;
                        $ary[] = $o_jobs;
                        
                    }
                    $ary[0]['total'] = number_format($money_total).' (VNĐ)';

                    if(isset($ary)){
                        $sheet->fromArray($ary);
                    }
                    $sheet->cells('A1:BM1', function($cells) {
                        $cells->setFontWeight('bold');
                        $cells->setBackground('#AAAAFF');
                        $cells->setFont(array(
                            'bold' => true
                        ));
                    });
                });
            })->download('xlsx');
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }

}
