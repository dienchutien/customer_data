<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\models\Branch;

class BranchController extends Controller
{
    //
    private $o_Branch;
    public function __construct() {
        $this->o_Branch = new Branch();
    }
    
    /**
     * @author Dienct
     * @since 2/3/2017
     * @des get all projects
     */
    public function getAllBranch() {
        $a_Data = $this->o_Branch->getAll();
        return view('branch.index', ['a_Data' => $a_Data]);
    }
    
    /**
     * @Auth: Dienct
     * @Des: Update Project
     * @Since: 9/3/2017
     */
    public function addEditBranch() {

        $a_DataView = array();
        $branch_id = (int) Input::get('id', 0);
        $productname = Input::get('submit');
        if (isset($productname) && $productname != "") {
            $this->o_Branch->AddEditBranch($branch_id);            
                return redirect('list_branch')->with('status', 'Cập nhật thành công!');
        }

        $a_DataView = $this->o_Branch->getBranchById($branch_id);

        return view('branch.edit_branch', ['a_Branch' => $a_DataView, 'i_id' => $branch_id]);

        ///get data department one record///
    }
}
