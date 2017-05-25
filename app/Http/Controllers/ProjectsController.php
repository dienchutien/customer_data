<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Projects as o_ProjectModel;
use Illuminate\Support\Facades\Input;
use App\models\Role as o_RoleModel;

class ProjectsController extends Controller {

    /**
     * @author Dienct
     * @since 2/3/2017
     * @Des function construct
     */
    private $o_Project;

    public function __construct() {
        $this->o_Project = new o_ProjectModel();
        $o_Role = new o_RoleModel();
    }

    /**
     * @author Dienct
     * @since 2/3/2017
     * @des get all projects
     */
    public function getAllProject() {
        $a_Data = $this->o_Project->getAll();
        return view('projects.index', ['a_Data' => $a_Data]);
    }

    /**
     * @Auth: Dienct
     * @Des: Update Project
     * @Since: 9/1/2015
     */
    public function ListProjects() {        

        $a_DataView = array();
        $projec_id = (int) Input::get('id', 0);
        $productname = Input::get('submit');
        if (isset($productname) && $productname != "") {
            $this->o_Project->AddEditProject($projec_id);            
                return redirect('list_projects')->with('status', 'Cập nhật thành công!');
        }

        $a_DataView = $this->o_Project->getProjectById($projec_id);

        return view('projects.edit_projects', ['a_Project' => $a_DataView, 'i_id' => $projec_id]);

        ///get data department one record///
    }

}
