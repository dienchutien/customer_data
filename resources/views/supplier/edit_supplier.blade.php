@extends('layouts.app')
@section('content')

<h3 class="col-xs-12 no-padding text-uppercase"><?php echo $i_id == ''?'Thêm nhà cung cấp mới':'Sửa nhà cung cấp'?></h3>
<div class="alert alert-danger hide"></div>
<form class="form-horizontal" method="post" action="">
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
    <input type="hidden" id="id" value="<?php echo $i_id?>">
    <input type="hidden" id="tbl" value="supplier">
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <label for="name" class="col-xs-12 col-sm-3 control-label text-left">Tên nhà cung cấp</label>
            <div class="col-xs-12 col-sm-9 no-padding">
                <input id="name" name="name" field-name="Tên"  type="text" value="<?php echo isset($a_Supplier->name)?$a_Supplier->name:"" ?>" class="form-control check-duplicate" placeholder="Tên" required />
            </div>
        </div>
        
    </div>    
   
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <label for="status" class="col-xs-6 control-label text-left">Trạng thái hiển thị</label>
            <div class="col-xs-6 no-left-padding">
                <input id="status" name="status" type="checkbox" class="form-control" <?php if (isset($a_Supplier->status) && $a_Supplier->status): ?>checked<?php endif ?>>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-xs-6 col-sm-3 no-padding">
            <button type="reset" class="btn btn-default">Nhập lại</button>
            <input type="button" name="submit" VALUE="Cập nhật" class="btn btn-primary btn-sm " onclick="GLOBAL_JS.v_fSubmitProjectValidate()"/>
            <input type="submit" name="submit" class="btn btn-primary btn-sm hide submit">
        </div>
    </div>
</form>

@endsection