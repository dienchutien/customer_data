@extends('layouts.app')
@section('content')

<h3 class="col-xs-12 no-padding text-uppercase">Danh sách kênh</h3>
<form method="get" action="" id="frmFilter" name="frmFilter"  class="form-inline">
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
</form>
    <div class="">
        <table class="table table-responsive table-hover table-striped table-bordered">
            <tr class="header-tr">
                <td><strong>Tên kênh</strong></td>                
                <td><strong>Ngày sửa</strong></td>
                <td><strong>Action</strong></td>
            </tr>
        @foreach ($a_Data as $key => $a_val)
            <tr>
                <td> @if($a_val['level'] == 1) --- @endif   {{ $a_val['name'] }}</td>
                <td>    {{ $a_val['updated_at'] }}</td>   
                <td>                    
                    <?php
                        if($a_val['status'] == 1 || $a_val['status'] == 0){
                    ?>
                    <a title="Edit" href="<?php echo Request::root().'/channel/addedit?id='.$key;?>" title="Edit" class="not-underline">
                        <i class="fa fa-edit fw"></i>
                    </a>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow({{ $key }},1)" title="Cho vào thùng rác" class="not-underline">
                    <i class="fa fa-trash fa-fw text-danger"></i>
                    </a>
                    <?php }else{ ?>
                    <a title="Khôi phục user" href="javascript:GLOBAL_JS.v_fRecoverRow({{ $key }})"  title="Edit" class="not-underline">
                        <i class="fa fa-upload fw"></i>
                    </a>
                    <a id="trash_switch_" href="javascript:GLOBAL_JS.v_fDelRow({{ $key }},2)" title="Xóa vĩnh viễn" class="not-underline">
                        <i class="fa fa-trash-o fa-fw text-danger"></i>
                    </a>
                    <?php }?>
                </td>
            </tr>
        @endforeach
        </table>
    </div>

<!--Hidden input-->
<input type="hidden" name="tbl" id="tbl" value="channel">

@endsection