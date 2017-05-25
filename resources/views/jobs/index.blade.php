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
            @if(count($a_users) > 0)
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
            @if(count($a_projects) > 0)
                @foreach($a_projects as $a_project )
                <option value="{{$a_project->id}}" <?php echo isset($a_search['project']) && $a_search['project'] == $a_project->id ? 'selected':''?> >{{$a_project->name}}</option>
                @endforeach
            @endif   
                    
        </select>
    </div>
    
    <div class="form-group">
        <select id="supplier" name="supplier" class="form-control input-sm">
            <option value="">Nhà cung cấp</option>
            @if(count($a_supplier) > 0)
                @foreach($a_supplier as $o_supplier )
                <option value="{{$o_supplier->id}}" <?php echo isset($a_search['supplier']) && $a_search['supplier'] == $o_supplier->id ? 'selected':''?> >{{$o_supplier->name}}</option>
                @endforeach
            @endif             
        </select>
    </div>
    
    <div class="form-group">
        <select id="branch" name="branch" class="form-control input-sm">
            <option value="">Chi nhánh</option>
            @if(count($a_branch) > 0)
                @foreach($a_branch as $o_branch )
                <option value="{{$o_branch->id}}" <?php echo isset($a_search['branch']) && $a_search['branch'] == $o_branch->id ? 'selected':''?> >{{$o_branch->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group">
        <select id="channel" name="channel" class="form-control input-sm">
            <option value="">Kênh</option>
                    @if(count($aryAllChannel) > 0)
                        @foreach($aryAllChannel as $key => $val )
                        <option value="{{$key}}" <?php echo isset($a_search['channel']) && $a_search['channel'] == $key ? 'selected':''?> > @if($val['level'] == 1) --- @endif {{$val['name']}}</option>
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
                <td class="bg-success"><strong>Dự án</strong></td>
                <td class="bg-success"><strong>Nhà cung cấp</strong></td>
                <td class="bg-success"><strong>Kênh</strong></td>
                <td class="bg-success"><strong>Chi Nhánh</strong></td>
                <td class="bg-success"><strong>Tiêu Đề</strong></td>
                <td class="bg-success"><strong>Người cập nhật</strong></td>
                <td class="bg-success"><strong>Số tiền (VNĐ)</strong></td>
                <td class="bg-success"><strong>Trạng thái</strong></td>
                <td class="bg-success"><strong>ngày hoàn thành</strong></td>
                <td class="bg-success"><strong>Ngày sửa</strong></td>
                <td class="bg-success"><strong>Action</strong></td>
            </tr>
        @foreach ($a_Jobs as $a_val)
            <tr>
                <td>    {{ $a_val->stt }}</td>
                <td>    {{ $a_val->project }}</td>
                <td>    {{ $a_val->supplier }}</td>
                <td>    {{ $a_val->channel }}</td>
                <td>    {{ $a_val->branch }}</td>
                <td>    {{ $a_val->title }}</td>
                <td>    {{ $a_val->user }}</td>
                <td>    {{ number_format($a_val->money) }}</td>
                <td> @if($a_val->job_type == 0) Trả trước @else Trả sau @endif  @if($a_val->is_payment == 0) (chưa tổng hợp) @endif</td>
                <td>    {{ $a_val->date_finish }}</td>
                <td>    {{ $a_val->updated_at }}</td>
                <td>
                    @if($a_val->admin_modify == Auth::user()->id || Auth::user()->rolegroup_id == 2)
                    <?php
                        if($a_val->status == 1 || $a_val->status == 0){
                    ?>
                    <a title="Edit" href="<?php echo Request::root().'/job/addedit?id='.$a_val->id;?>" title="Edit" class="not-underline">
                        <i class="fa fa-edit fw"></i>
                    </a>
                    <?php if($a_val->job_type != 0 || $a_val->is_payment !=1){?>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow({{ $a_val->id }},1)" title="Cho vào thùng rác" class="not-underline">
                    <i class="fa fa-trash fa-fw text-danger"></i>
                    </a>
                    <?php }?>
                    <?php }else{ ?>
                    <a title="Khôi phục user" href="javascript:GLOBAL_JS.v_fRecoverRow({{ $a_val->id }})"  title="Edit" class="not-underline">
                        <i class="fa fa-upload fw"></i>
                    </a>
                    <?php if($a_val->job_type != 0 || $a_val->is_payment !=1){?>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow({{ $a_val->id }},2)" title="Xóa vĩnh viễn" class="not-underline">
                        <i class="fa fa-trash-o fa-fw text-danger"></i>
                    </a>
                    <?php }?>
                    <?php }?>
                    @endif
                </td>
            </tr>
        @endforeach
        </table>
        <div class="form-group">
            <div class="col-xs-12 col-sm-2 no-padding">
                <a class="btn btn-primary btn-sm" href="/export_excel_Jobs" target="_blank">Export Excel</a>
            </div>
            <strong class="text-left text-danger">TỔNG TIÊN:  {{number_format($money_total)}} (VNĐ)</strong>
        </div>
              
    </div>

<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="jobs">
<?php echo (empty($a_search))?$a_Jobs->render():$a_Jobs->appends($a_search)->render();?>

@endsection