<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Job as o_JobModel;
use Illuminate\Support\Facades\Input;
use App\models\Projects;
use App\models\Channel;
use App\models\Supplier;
use App\models\Branch;
use App\User;
use Illuminate\Support\Facades\Session;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;
use App\models\Role as o_RoleModel;

class JobController extends Controller
{
    /**
     * @author Dienct
     * @since 2/3/2017
     * @Des function construct
     */
    private $o_Job;
    private $o_Project;
    private $o_Channel;
    private $o_User;
    private $o_Supplier;
    private $o_Branch;

    public function __construct() {
        $this->o_Job = new o_JobModel();
        $this->o_Project = new Projects();
        $this->o_Channel = new Channel();
        $this->o_user = new User();
        $this->o_Supplier = new Supplier();
        $this->o_Branch = new Branch();
        $o_Role = new o_RoleModel();
    }
    /**
     * @Auth: Dienct
     * @Des: Update Project
     * @Since: 1/3/2017
     */
    public function addEditJob() {
        $a_DataView = array();
        $job_id = (int) Input::get('id', 0);
        $productname = Input::get('submit');
        if (isset($productname) && $productname != "") {
            $this->o_Job->AddEditJob($job_id);
                return redirect('list_job')->with('status', 'Cập nhật thành công!');
        }
        
        //get all project view
        $a_DataProjects = array();
        $a_DataProjects = $this->o_Project->getAll();
        
        //nha cung cap
        $a_DataSupplier = array();
        $a_DataSupplier = $this->o_Supplier->getAll();
        
        //chi nhanh
        $a_DataBranch = array();
        $a_DataBranch = $this->o_Branch->getAll();

        //get channel view
        $aryAllChannel = array();
        $this->o_Channel->getAllChannelByParentID(0, $aryAllChannel);

        $a_DataView = $this->o_Job->getJobById($job_id);

        return view('jobs.edit_jobs', ['a_Jobs' => $a_DataView, 'i_id' => $job_id, 'a_DataProjects'=>$a_DataProjects, 'aryAllChannel'=>$aryAllChannel, 'a_DataSupplier'=>$a_DataSupplier, 'a_DataBranch'=>$a_DataBranch]);
        
    }
    
    /**
     * @Auth: Dienct
     * @Des: list Job.
     * @Since: 6/3/2017
     */
    public function getAllJob() {
        $a_Data = $this->o_Job->getAllSearch();        

        $Data_view['a_Jobs'] = $a_Data['a_data'];
        $Data_view['a_search'] = $a_Data['a_search'];
        $Data_view['money_total'] = $a_Data['money_total'];
        
        $Data_view['a_users'] = $this->o_user->getAll();
        $Data_view['a_projects'] = $this->o_Project->getAll();
        $Data_view['a_supplier'] = $this->o_Supplier->getAll();
        $Data_view['a_branch'] = $this->o_Branch->getAll();
        
        $aryAllChannel = array();
        $this->o_Channel->getAllChannelByParentID(0, $aryAllChannel);
        $Data_view['aryAllChannel'] = $aryAllChannel;        

        return view('jobs.index',$Data_view);
        
    }
    
    //Export excel
    public function exportJob(){
        
        $sz_Sql = Session::get('sqlGetJob');        
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
    
    /**

     * @auth: Dienct
     * @since: 13/03/2017
     * @des: job statistics by channel, project or branch
     *
     */
    public function jobStatistics(){
        $Data_view = array();
        $Data_view['a_projects'] = $this->o_Project->getAll();
        $Data_view['a_branch'] = $this->o_Branch->getAll();
        $productname = Input::get('submit');
        if (isset($productname) && $productname != "") {
            $a_Data = $this->o_Job->getJobStatistics();
            $Data_view['a_Jobs'] = $a_Data['a_data'];
            $Data_view['a_search'] = $a_Data['a_search'];
        }

        return view('jobs.statistics',$Data_view);

    }
    /**

     * @auth: Dienct
     * @since: 14/03/2017
     * @des: job statistics by channel, project or branch
     *
     */
    public function exportJobStatistics() {

        $sz_Sql = Session::get('sqlJobStatistics');
        $szfrom_date = Session::get('ss_from_date');
        $szto_date = Session::get('ss_to_date');

        if (strpos($sz_Sql, 'limit') !== false) {
            $arr = explode('limit', $sz_Sql);
            $sz_Sql = $arr[0];
        }
        if (isset($sz_Sql) && $sz_Sql != '') {
            $a_Data = DB::select(DB::raw($sz_Sql));

            try {
                Excel::create('Job_Statistics', function($excel) use($a_Data, $szfrom_date, $szto_date) {
                    // Set the title
                    $excel->setTitle('no title');
                    $excel->setCreator('Dienct')->setCompany('no company');
                    $excel->setDescription('report file');
                    $excel->sheet('sheet1', function($sheet) use($a_Data, $szfrom_date, $szto_date) {
                        $money_total = 0;
                        if (count($a_Data)) {
                            foreach ($a_Data as $key => $o_person) {
                                $o_jobs = array();
                                $o_jobs['stt'] = $key + 1;

                                if (isset($o_person->channel_id)) {
                                    $o_jobs['name'] = isset($this->o_Channel->getChanneltById($o_person->channel_id)->name) ? $this->o_Channel->getChanneltById($o_person->channel_id)->name : 'khong xac dinh';
                                } else if (isset($o_person->project_id)) {
                                    $o_jobs['name'] = isset($o_person->project_id) && isset($this->o_Project->getProjectById($o_person->project_id)->name) ? $this->o_Project->getProjectById($o_person->project_id)->name : 'khong xac dinh';
                                } else if (isset($o_person->branch_id)) {
                                    $o_jobs['name'] = isset($o_person->branch_id) && isset($this->o_Branch->getBranchById($o_person->branch_id)->name) ? $this->o_Branch->getBranchById($o_person->branch_id)->name : 'khong xac dinh';
                                }else if (isset($o_person->parent_channel)) {
                                    $o_jobs['name'] = isset($this->o_Channel->getChanneltById($o_person->parent_channel)->name) ? $this->o_Channel->getChanneltById($o_person->parent_channel)->name : 'khong xac dinh';
                                }
                                $o_jobs['total_money'] = number_format($o_person->total_money);
                                $o_jobs['Time'] = $szfrom_date . '-' . $szto_date;

                                $ary[] = $o_jobs;
                            }
                        }


                        if (isset($ary)) {
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
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }else{
            echo'Ko ton tai dieu kien loc';
        }
    }
    
    //update parent-channel
    public function update_parent_channel(){
        $data = DB::table('jobs')->select('*')->get();

        foreach($data as $key => $val){
            $dataChannel = $this->o_Channel->getChanneltById($val->channel_id);
            if(isset($dataChannel->parent_id)){
                $parent_channel = $dataChannel->parent_id != 0 ?  $dataChannel->parent_id : $dataChannel->id;
            }else{
                $parent_channel = 0;
            }
            $a_DataUpdate['parent_channel'] = $parent_channel;
            DB::table('jobs')->where('id', $val->id)->update($a_DataUpdate);
        }
        
    }
}
