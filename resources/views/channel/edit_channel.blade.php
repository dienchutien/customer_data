@extends('layouts.app')
@section('content')

<h3 class="col-xs-12 no-padding text-uppercase"><?php echo $i_id == ''?'Thêm kênh mới':'Sửa kênh'?></h3>
<div class="alert alert-danger hide"></div>
<form class="form-horizontal" method="post" action="">
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
    <input type="hidden" id="id" value="<?php echo $i_id?>">
    <input type="hidden" id="tbl" value="channel">
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <label for="name" class="col-xs-12 col-sm-3 control-label text-left">Tên Kênh</label>
            <div class="col-xs-12 col-sm-9 no-padding">
                <input id="name" name="name" field-name="Tên" <?php echo $i_id == 0 ? '' : 'old_val="'.$a_Channel->name.'"'?> type="text" value="<?php echo isset($a_Channel->name)?$a_Channel->name:"" ?>" class="form-control check-duplicate" placeholder="Tên kênh" required />
            </div>
        </div>        
        
    </div>
    
    <div class="form-group">
        <div class="col-xs-12 col-sm-6 no-padding">
            <label for="parent_id" class="col-xs-12 col-sm-3 control-label text-left">Chọn kênh cha</label>
            <div class="col-xs-12 col-sm-6 no-padding">
                <select class="form-control input-sm " id="parent_id" name="parent_id">
                    <option value="0"><span class="text-center">Danh mục gốc</span></option>
                    @if(count($aryAllChannel) > 0)
                        @foreach($aryAllChannel as $key => $val )
                        <option value="{{$key}}" <?php echo isset($a_Channel->parent_id) && $a_Channel->parent_id == $key ? 'selected':''?> > @if($val['level'] == 1) --- @endif {{$val['name']}}</option>
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
                <input id="status" name="status" type="checkbox" class="form-control" <?php if (isset($a_Channel->status) && $a_Channel->status): ?>checked<?php endif ?>>
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