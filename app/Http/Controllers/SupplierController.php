<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Supplier;
use Illuminate\Support\Facades\Input;
use App\models\Role as o_RoleModel;

class SupplierController extends Controller
{
    /**
     * @author Dienct
     * @since 8/3/2017
     * @Des function construct
     */
    private $o_Supplier;

    public function __construct() {
        $this->o_Supplier = new Supplier();
        $o_Role = new o_RoleModel();
    }
    
    /**
     * @author Dienct
     * @since 2/3/2017
     * @des get all projects
     */
    public function getAllSupplier() {
        $a_Data = $this->o_Supplier->getAll();
        return view('supplier.index', ['a_Data' => $a_Data]);
    }
    
    /**
     * @Auth: Dienct
     * @Des: Update Project
     * @Since: 9/3/2017
     */
    public function addEditSupplier() {

        $a_DataView = array();
        $supplier_id = (int) Input::get('id', 0);
        $productname = Input::get('submit');
        if (isset($productname) && $productname != "") {
            $this->o_Supplier->AddEditSupplier($supplier_id);            
                return redirect('list_supplier')->with('status', 'Cập nhật thành công!');
        }

        $a_DataView = $this->o_Supplier->getSupplierById($supplier_id);

        return view('supplier.edit_supplier', ['a_Supplier' => $a_DataView, 'i_id' => $supplier_id]);

        ///get data department one record///
    }

}
