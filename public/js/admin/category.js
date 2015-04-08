
$(function() { 
    
    
    $('#category_form').ajaxForm(function(resp) { 
        
        if (resp.msg != null) {

            alert(resp.msg);
        }
        else
        if (resp.success && resp.data.id > 0) {
            
            location.href = '/admin/category/' + resp.data.id;
        }
    });
    
    
    $('#delete_btn').click(function(){
        
        $.ajax('/admin/category/delete', {
            method  : 'DELETE',
            dataType: 'json',
            data    : {
                id  : $('input[name=id]').val()
            },
            success : function(resp) {
                if (resp.success) {
                    
                    location.href = '/admin/category';
                }
                else {
                    alert('İşlem gerçekleştirilemedi');
                }
            }
        })
        
    });
});