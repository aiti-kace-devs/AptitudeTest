sAlert = (message, options = {}, callbackFn = undefined) => {
    options['toast'] = typeof options['toast'] === 'undefined' ? true : options['toast'];
                    Swal.fire({
                        title: 'Success!',
                        text: message,
                        icon: 'success',
                        backdrop: options.toast ? false : `rgba(0,0,0,0.95)`,
                        confirmButtonText: 'Okay',
                        allowOutsideClick: false,
                        timer: options.toast?  5000 : undefined,
                        toast: true,
                        position: options.toast ? 'top-end' : 'center',
                        showConfirmButton: callbackFn ? true : false,
                        preConfirm: () => {
                            if (callbackFn){
                                callbackFn();
                            }
                        },
                        ...options
                    });
}

$(document).on('submit','.database_operation',function(){
    var url=$(this).attr('action');
    var data=$(this).serialize();
    $.post(url,data,function(fb){
        var resp=$.parseJSON(fb);
        if(resp.status=='true'){
            sAlert(resp.message);
            setTimeout(() => {
                window.location.href=resp.reload;
            }, 1000);
        }
        else{
            sAlert(resp.message);
        }
    });
    return false;
});

$(document).on('click','.apply_exam',function(){
    var id=$(this).attr('data-id');
    $.get(BASE_URL+'/student/apply_exam/'+id,function(fb){
        var resp=$.parseJSON(fb);
        if(resp.status=='true'){
            sAlert(resp.message);
            setTimeout(() => {
                window.location.href=resp.reload;
            }, 1000);
        }
        else{
            sAlert(resp.message);
        }
    })
})

$(document).on('click','.category_status',function(){
    var id=$(this).attr('data-id');
    $.get(BASE_URL+'/admin/category_status/'+id,function(fb){
        sAlert("status successsfully changed");
    })
})


$(document).on('click','.exam_status',function(){
    var id=$(this).attr('data-id');
    $.get(BASE_URL+'/admin/exam_status/'+id,function(fb){
        sAlert("status successsfully changed");
    })
})

$(document).on('click','.student_status',function(){
    var id=$(this).attr('data-id');
    $.get(BASE_URL+'/admin/student_status/'+id,function(fb){
        sAlert("status successsfully changed");
    })
})

$(document).on('click','.portal_status',function(){
    var id=$(this).attr('data-id');
    $.get(BASE_URL+'/admin/portal_status/'+id,function(fb){
        sAlert("status successsfully changed");
    })
})

$(document).on('click','.question_status',function(){
    var id=$(this).attr('data-id');
    $.get(BASE_URL+'/admin/question_status/'+id,function(fb){
        sAlert("status successsfully changed");
    })
})

