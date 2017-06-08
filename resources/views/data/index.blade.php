@extends('layouts.app')
@section('content')

<h3 class="col-xs-12 no-padding text-uppercase">Danh sách tác vụ</h3>
<form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
    <div class="form-group">
        <select id="is_payment" name="is_payment" class="form-control input-sm">
            <option value="">Trạng thái</option>
            <option value="1" <?php echo isset($a_search['i_is_payment']) && $a_search['i_is_payment'] == 1 ? 'selected':''?>>Đã tổng hợp</option>
            <option value="0" <?php echo isset($a_search['i_is_payment']) && $a_search['i_is_payment'] == 0 ? 'selected':''?>>Chưa tổng hợp</option>            
        </select>
    </div>
    <div class="form-group">
        <input id="title_name" name="title_name" type="text" class="form-control input-sm" placeholder="Nhập tiêu đề tác vụ" value="<?php echo isset($a_search['title_name'])?$a_search['title_name']:''?>">
    </div>
    <div class="form-group">
        <select id="admin_modify" name="admin_modify" class="form-control input-sm">
            <option value="">Người cập nhật</option>
            @if(isset($a_users) && count($a_users) > 0)
                @foreach($a_users as $a_user )
                <option value="{{$a_user->id}}" <?php echo isset($a_search['admin_modify']) && $a_search['admin_modify'] == $a_user->id ? 'selected':''?> >{{$a_user->email}}</option>
                @endforeach
            @endif                      
        </select>
    </div>
    
    </br></br>
    <div class="form-group">
        <input type="text" class="form-control datepicker input-sm" id="from_date" name="from_date" placeholder="Từ ngày" value="<?php echo isset($a_search['from_date'])?$a_search['from_date']:''?>"> <span class="glyphicon glyphicon-minus"></span>
        <input type="text" class="form-control datepicker input-sm" id="to_date" name="to_date" placeholder="Tới ngày" value="<?php echo isset($a_search['to_date'])?$a_search['to_date']:''?>">
    </div>
    <div class="form-group">
        <select id="project" name="project" class="form-control input-sm">
            <option value="">Dự án</option>
            @if(isset($a_projects) && count($a_projects) > 0)
                @foreach($a_projects as $a_project )
                <option value="{{$a_project->id}}" <?php echo isset($a_search['project']) && $a_search['project'] == $a_project->id ? 'selected':''?> >{{$a_project->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
    
    <div class="form-group">
        <input type="button" class="btn btn-success btn-sm" value="Tìm kiếm" onclick="GLOBAL_JS.v_fSearchSubmitAll()">
        <input type="submit" class="btn btn-success btn-sm submit hide">
    </div>
</form>

    <div class="">
        <table class="table table-responsive table-hover table-striped table-bordered">
            <tr class="header-tr">
                <td class="bg-success"><strong>STT</strong></td>
                <td class="bg-success"><strong>Tên</strong></td>
                <td class="bg-success"><strong>SDT</strong></td>
                <td class="bg-success"><strong>Email</strong></td>
                <td class="bg-success"><strong>Dự án</strong></td>
                <td class="bg-success"><strong>Phân phối cho</strong></td>
                <td class="bg-success"><strong>ngày tạo</strong></td>                
                <td class="bg-success"><strong>Action</strong></td>
            </tr>
        @foreach ($a_Jobs as $a_val)
            <tr>
                <td>    {{ $a_val->stt }}</td>
                <td>    {{ $a_val->name }}</td>
                <td>    {{ $a_val->phone }}</td>
                <td>    {{ $a_val->email }}</td>
                <td>    {{ $a_val->project }}</td>
                <td>    {{ $a_val->partner }}</td>
                <td>    {{ $a_val->created_at }}</td>
                <td>                    
                    <?php
                        if($a_val->status == 1 || $a_val->status == 0){
                    ?>
                    <a title="Edit" href="<?php echo Request::root().'/data/addedit?id='.$a_val->id;?>" title="Edit" class="not-underline">
                        <i class="fa fa-edit fw"></i>
                    </a>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow({{ $a_val->id }},1)" title="Cho vào thùng rác" class="not-underline">
                    <i class="fa fa-trash fa-fw text-danger"></i>
                    </a>
                    <?php }else{ ?>
                    <a title="Khôi phục Data" href="javascript:GLOBAL_JS.v_fRecoverRow({{ $a_val->id }})"  title="Edit" class="not-underline">
                        <i class="fa fa-upload fw"></i>
                    </a>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow({{ $a_val->id }},2)" title="Xóa vĩnh viễn" class="not-underline">
                        <i class="fa fa-trash-o fa-fw text-danger"></i>
                    </a>
                    <?php }?>
                    
                </td>
            </tr>
        @endforeach
        </table>        
    </div>

<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="data">
<?php echo (empty($a_search))?$a_Jobs->render():$a_Jobs->appends($a_search)->render();?>

@endsection