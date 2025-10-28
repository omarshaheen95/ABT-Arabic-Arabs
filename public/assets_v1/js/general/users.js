$('#created_at').flatpickr();
$('#active_to_date').flatpickr();


initializeDateRangePicker('register_date')
initializeDateRangePicker('login_at')


$(document).on('click', '#unassigned_users_teachers', function () {
    let data = getFilterData();
    data['_token'] = CSRF
    showAlert(UNSIGNED_TEACHER, UNSIGNED_TEACHER_MESSAGE, 'warning', YES, NO,
        (callback) => {
            if (callback) {
                showLoadingModal()
                $.ajax({
                    url: UNSIGNED_TEACHER_URL,
                    type: 'post',
                    data: data,
                    success: function (response) {
                        hideLoadingModal()
                        table.DataTable().draw(true);
                        toastr.success(response.message);
                    },
                    error(error) {
                        hideLoadingModal()
                        toastr.error(error.responseJSON.message);
                    }
                });
            }
        })

});
$(document).on('click', '#btn_users_activation',function(){
    $('#users_activation_modal').modal('hide')
    showLoadingModal()
    let data = getFilterData();
    data['activation_data'] = getFormData('activation_form')
    data['_token'] = CSRF;
    $('#activation_form').find('input').val('')
    $('#activation_form').find('select').val('').trigger('change')
    $.ajax({
        url: USER_ACTIVATION_URL,
        type: 'post',
        data: data,
        success: function(response){
            hideLoadingModal()
            table.DataTable().draw(true);
            toastr.success(response.message);
        },
        error(error){
            hideLoadingModal()
            toastr.error(error.responseJSON.message);
        }
    });
});
$(document).on('click', '#btn_users_teacher',function(){
    $('#add_users_teachers').modal('hide')
    showLoadingModal()
    let data = getFilterData();
    data['users_data'] = getFormData('users_teacher_form')
    data['_token'] = CSRF;
    $('#users_teacher_form').find('input').val('')
    $('#users_teacher_form').find('select').val('').trigger('change')
    $.ajax({
        url: ASSIGNED_TO_TEACHER_URL,
        type: 'post',
        data: data,
        success: function(response){
            hideLoadingModal()
            table.DataTable().draw(true);
            toastr.success(response.message);
        },
        error(error){
            hideLoadingModal()
            toastr.error(error.responseJSON.message);
        }
    });
});
$(document).on('click', '#btn_users_update_grades',function(){
    $('#users_update_grades_modal').modal('hide')
    showLoadingModal()
    let data = getFilterData();
    data['users_grades'] = getFormData('users_grades_form')
    data['_token'] = CSRF;
    $('#users_grades_form').find('input').val('')
    $('#users_grades_form').find('select').val('').trigger('change')
    $.ajax({
        url: USER_UPDATE_GRADES_URL,
        type: 'post',
        data: data,
        success: function(response){
            hideLoadingModal()
            table.DataTable().draw(true);
            toastr.success(response.message);
        },
        error(error){
            hideLoadingModal()
            toastr.error(error.responseJSON.message);
        }
    });
});

$(document).on('click', '#update_users_grade', function () {
    showLoadingModal()
    let data = getFilterData();
    data['_token'] = CSRF
    data['grades_data'] = getFormData('update_users_grade_form')

    $.ajax({
        url: "",
        type: 'post',
        data: data,
        success: function (response) {
            hideLoadingModal()
            table.DataTable().draw(true);
            toastr.success(response.message);
        },
        error(error) {
            hideLoadingModal()
            toastr.error(error.responseJSON.message);
        }
    });
});


function restore(id = null) {
    let data = {
        '_token': $('meta[name="csrf-token"]').attr('content')
    };

    if (!id) {
        id = [];
        $("table input:checkbox:checked").each(function () {
            id.push($(this).val());
        });
        data['id'] = id;

        let school_id = $('select[name="school_id"]').val();
        if (id.length <= 0 && !school_id) {
            toastr.error('School is required');
            return;
        } else {
            data['school_id'] = school_id;
        }

        let year_id = $('select[name="year_id"]').val();
        if (id.length <= 0 && !year_id) {
            toastr.error('Year is required');
            return;
        } else {
            data['year_id'] = year_id;
        }
    }else {
        data['id']=id;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to restore the selected students?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, restore!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoadingModal()
            $.ajax({
                type: "POST",
                url: RESTORE_USERS_URL,
                data: data,
                success: function (result) {
                    hideLoadingModal()
                    Swal.fire({
                        icon: 'success',
                        title: 'Restored!',
                        text: result.message,
                        //timer: 2000,
                        showConfirmButton: true
                    });
                    table.DataTable().draw(false);
                },
                error: function (error) {
                    hideLoadingModal()
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.responseJSON?.message || 'Something went wrong'
                    });
                }
            });
        }
    });
}

$('#btn_reset_passwords').click(function () {
    let formData = getFormData('reset_passwords_form')
    let data = getFilterData()
    $.each(formData, function (key, val) {
        data[key] = val;
    });

    if (data['password']){
        $('#reset_passwords_modal').modal('hide')
        showLoadingModal()
        $.ajax({
            type: "POST", //we are using GET method to get data from server side
            url: RESET_PASSWORD_URL, // get the route value
            data: data,
            success:function (result) {
                hideLoadingModal()
                toastr.success(result.message)
                table.DataTable().draw(false);
            },
            error:function (error) {
                hideLoadingModal()
                toastr.error(error.responseJSON.message)
            }
        })
    }
})
