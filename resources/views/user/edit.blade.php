@extends('layouts.app')
@section('content')

<h3 class="col-xs-12 no-padding text-uppercase"><?php echo $i_id == ''?'Thêm User':'Sửa User'?></h3>
<div class="alert alert-danger hide"></div>
<form class="form-horizontal" method="post" action="">
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
    <input type="hidden" id="id" value="<?php echo $i_id?>">
    <input type="hidden" id="tbl" value="users">
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <label for="name" class="col-xs-12 col-sm-3 control-label text-left">Tên</label>
            <div class="col-xs-12 col-sm-8 no-padding">
                <input id="name" name="name" field-name="Tên"  type="text" value="<?php echo isset($a_User->name)?$a_User->name:"" ?>" class="form-control" placeholder="Tên" required />
            </div>
        </div>
    </div>
    <div class="form-group">
            <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Email</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="text" name="email" class="form-control" id="email" value="<?php echo isset($a_User->email)?$a_User->email:"" ?>" required />
                </div>
            </div>
    </div>
    <div class="form-group">
            <div class="col-xs-6 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Mật khẩu</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" id="password">
                </div>
            </div>
               
        </div>
    <div class="form-group">
            <div class="col-xs-6 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Nhập lại mật khẩu</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" id="re_password">
                </div>
            </div> 
        </div>
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
                <label for="" class="col-xs-12 col-sm-3 control-label text-left">Quyền hạn</label>
                <div class="col-xs-12 col-sm-8 no-padding">
                    <select class="form-control input-sm" name="rolegroup_id">
                        @if(count($a_role) > 0)
                            @foreach($a_role as $o_role )
                            <option value="{{$o_role->id}}" <?php echo isset($a_User->rolegroup_id) && $a_User->rolegroup_id == $o_role->id ? 'selected':''?>>{{$o_role->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
        </div>
    </div>
   
    <div class="form-group">
        <div class="col-xs-12 col-sm-3 no-padding">
            <label for="status" class="col-xs-6 control-label text-left">Trạng thái hiển thị</label>
            <div class="col-xs-6 no-left-padding">
                <input id="status" name="status" type="checkbox" class="form-control" <?php if (isset($a_User->status) && $a_User->status): ?>checked<?php endif ?>>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-xs-6 col-sm-3 no-padding">
            <button type="reset" class="btn btn-default">Nhập lại</button>
            <input type="button" name="submit" VALUE="Cập nhật" class="btn btn-primary btn-sm " onclick="GLOBAL_JS.v_fSubmitUser()"/>
            <input type="submit" name="submit" class="btn btn-primary btn-sm hide submit">
        </div>
    </div>
</form>

@endsection