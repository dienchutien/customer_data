var GLOBAL_JS = {
    go_on_business: 'Phiếu công tác',
    tbl: $('#tbl').val(),
    /**
     * Auth: Dienct
     * Des: delete record
     * Since: 31/12/2015
     * */
    b_fValidateEmpty: function (e) {
        var t = /^ *$/;
        if (e == "" || t.test(e)) {
            return true;
        }
        return false;
    },
    b_fCheckEmail: function (e) {
        var t = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
        return t.test(e)
    },
    b_fCheckEmailDXMB: function (sz_Email) {
        var sz_check = sz_Email.substr(sz_Email.indexOf("@") + 1);
        if (sz_check != 'dxmb.vn')
            return false;
        else
            return true;
    },
    b_fCheckMinLength: function (e, the_i_Length) {
        if (e.length < the_i_Length) {
            return false;
        }
        return true;
    },
    b_fCheckMaxLength: function (e, the_i_Length) {
        if (e.length > the_i_Length) {
            return false;
        }
        return true;
    },
    b_fCheckConfirmPwd: function (e, t) {
        if (e == t) {
            return true;
        }
        return false;
    },
    v_fDelRow: function (id, type) {
        var sz_confirm = type == 1 ? "Bạn có muốn cho vào thùng rác?" : "Xóa vĩnh viễn tài khoản trên Izi. Bạn có muốn tiếp tục?";
        if (confirm(sz_confirm)) {
            var o_data = {
                id: id,
                type: type,
                func: 'delete-row',
                tbl: GLOBAL_JS.sz_Tbl,
            };
            $.ajax({
                url: GLOBAL_JS.sz_CurrentHost + '/ajax',
                type: 'POST',
                data: o_data,
                dataType: 'json',
                success: function (data) {
                    alert(data.success);
                    location.reload();
                }
            });
        }
    },
    /**
     * Auth: Dienct
     * Des: recover record
     * Since: 31/12/2015
     * */
    v_fRecoverRow: function (id) {

        var o_data = {
            id: id,
            func: 'recover-row',
            tbl: GLOBAL_JS.sz_Tbl,
        };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost + '/ajax',
            type: 'POST',
            data: o_data,
            dataType: 'json',
            success: function (data) {
                alert(data.success);
                location.reload();
            }
        });
    },
    /**
     * Auth: Dienct
     * Des: transfer Data
     * Since: 14/06/2017
     * */
    v_fTransferData: function () {
        var new_assigner = $('#new_assigner').val();
        var o_data = {
            new_assigner: new_assigner,
            func: 'transfer-data',
            tbl: GLOBAL_JS.sz_Tbl,
        };
        $.ajax({
            url: GLOBAL_JS.sz_CurrentHost + '/ajax',
            type: 'POST',
            data: o_data,
            dataType: 'json',
            success: function (data) {
                alert(data.success);
                location.reload();
            }
        });
    },
    v_fUpdateStatus: function (id, type) {
        var sz_confirm = type == 1 ? "Bạn có muốn cho vào thùng rác?" : "Bạn có muốn xóa đơn này?";
        if (confirm(sz_confirm)) {
            var o_data = {
                id: id,
                type: type,
                func: 'update-status',
                tbl: GLOBAL_JS.sz_Tbl,
            };
            $.ajax({
                url: GLOBAL_JS.sz_CurrentHost + '/ajax',
                type: 'POST',
                data: o_data,
                dataType: 'json',
                success: function (data) {
                    alert(data.success);
                    location.reload();
                }
            });
        }
    },
    /**
     * Auth: Dienct
     * Des: 
     * Since: 02/003/2017
     * */
    v_fSubmitProjectValidate: function ()
    {
        var sz_name = $('#name').val();
        if (GLOBAL_JS.b_fValidateEmpty(sz_name)) {
            $('.required_name').remove();
            $('.alert-danger').append('<p><strong class="required_name">Bạn cần nhập tên</strong></p>');
            $('.alert-danger').removeClass('hide');
            $('#name').focus();
            return false;
        }
        if ($('.alert-danger').text() != '')
            return false;
        $('.submit').click();
    },
    /**
     * Auth: Dienct
     * Des: 
     * Since: 16/003/2017
     * */
    v_fSubmitUser: function ()
    {
        //$chech 0- insert 1-edit
        var $chech = $('#id').val();        
        var sz_pass = $('#password').val();
        var sz_repass = $('#re_password').val();
        $('.alert-danger').empty();
        if($chech == 0){
            if (sz_pass == '' || sz_pass != sz_repass) {
                $('.alert-danger').append('<p><strong class="required_name">Bạn cần kiểm tra lại password</strong></p>');
                $('.alert-danger').removeClass('hide');
            }
        }else{
            if (sz_pass != '' && sz_pass != sz_repass) {
                $('.alert-danger').append('<p><strong class="required_name">Bạn cần kiểm tra lại password</strong></p>');
                $('.alert-danger').removeClass('hide');
            }
        }
        if ($('.alert-danger').text() != '')
            return false;
        $('.submit').click();
    },

    /**
     * Auth: Dienct
     * Des: Check validate then submit form if 0 error
     * Since: 06/003/2017
     * */
    v_fSubmitJobValidate: function ()
    {
        var sz_projects = $('#projects').val();
        var sz_supplier = $('#supplier').val();
        var sz_channel = $('#channel').val();
        var sz_job_type = $('#job_type').val();
        var sz_branch = $('#branch').val();
        $('.alert-danger').empty();

        if (sz_projects == '') {
            $('.alert-danger').append('<p><strong class="required_name">Bạn cần chọn dự án</strong></p>');
            $('.alert-danger').removeClass('hide');
        }
        if (sz_supplier == '') {
            $('.alert-danger').append('<p><strong class="required_name">Bạn cần chọn nhà cung cấp</strong></p>');
            $('.alert-danger').removeClass('hide');
        }

        if (sz_channel == '') {
            $('.alert-danger').append('<p><strong class="required_name">Bạn cần chọn kênh</strong></p>');
            $('.alert-danger').removeClass('hide');
        }

        if (sz_job_type == '') {
            $('.alert-danger').append('<p><strong class="required_name">Bạn cần chọn loại tác vụ</strong></p></br>');
            $('.alert-danger').removeClass('hide');
        }
        
        if (sz_branch == '') {
            $('.alert-danger').append('<p><strong class="required_name">Bạn cần chọn chi nhanh</strong></p></br>');
            $('.alert-danger').removeClass('hide');
        }

        if ($('.alert-danger').text() != '')
            return false;
        $('.submit').click();
    },
    /**
     * Auth: Dienct
     * Des: Submit search All module
     * Since: 06/03/2017
     * */
    v_fSearchSubmitAll: function () {
        $('.submit').click();
    },
    
    /**
     * Auth: Dienct
     * Des: Submit search job statistics
     * Since: 06/03/2017
     * */
    v_fSearchSubmitStatistics: function () {                
        
        var sz_filter_by = $('#filter_by').val();
        $('.alert-danger').empty();
        if(sz_filter_by == ''){
            $('.alert-danger').append('<p><strong class="required_name">Bạn cần chọn loại lọc</strong></p></br>');
            $('.alert-danger').removeClass('hide');
        }
        if ($('.alert-danger').text() != '') return false;
        
        $('.submit').click();
    },
    pad: function (num) {
        var str = num.toString().split('.');
        if (str[0].length >= 4) {
            str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1 ');
        }
        return (str.join('.'));
    },
    /**
     * Auth: Dienct
     * Des: chekc all role
     * Since: 18/03/2017
     * */
    v_fCheckAllRoleGroup: function (the_sz_Id) {
        if ($('#' + the_sz_Id).is(':checked'))
        {
            $('.' + the_sz_Id).prop('checked', true);
        } else
            $('.' + the_sz_Id).prop('checked', false);
    },
    

};

$(function () {
    $(".datepicker").datepicker();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var t = $(location).attr("href");
    GLOBAL_JS.sz_CurrentHost = t.split("/")[0] + "//" + t.split("/")[2];
    GLOBAL_JS.sz_Tbl = $('#tbl').val();

    /////Định dạng ô money trong phần tính lãi vay////
    $("#money").keyup(function () {
        var num = $("#money").val();
        var i = 0, strLength = num.length;
        for (i; i < strLength; i++) {
            num = num.replace(' ', '');
        }
        $("#money").val(GLOBAL_JS.pad(num));
    });
    

});
