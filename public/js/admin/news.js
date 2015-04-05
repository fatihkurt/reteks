
$(function() { 
    
    
    CKEDITOR.replace('content_tr', {
        "language": "tr"
    });
    
    
    var dateOptions = {
        locale: 'tr',
        showClose: true
    };
    
    $('#start_date').datetimepicker(dateOptions);
    
    
    dateOptions.defaultDate =  "01.01.2038 00:00";
    
    $('#end_date').datetimepicker(dateOptions);
    

    
    $('#news_form').ajaxForm(function(resp) { 
        
        if (resp.msg != null) {

            alert(resp.msg);
        }
        else
        if (resp.success && resp.data.id > 0) {
            
            location.href = '/admin/news/' + resp.data.id;
        }
    });
    
    
    $('#delete_btn').click(function(){
        
        $.ajax('/admin/news/delete', {
            method  : 'DELETE',
            dataType: 'json',
            data    : {
                id  : $('input[name=id]').val()
            },
            success : function(resp) {
                if (resp.success) {
                    
                    location.href = '/admin/news';
                }
                else {
                    alert('İşlem gerçekleştirilemedi');
                }
            }
        })
        
    });
});