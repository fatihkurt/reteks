
$(function() { 
    

    
    $('#page_form').ajaxForm(function(resp) { 
        
        if (resp.msg != null) {
            
            alert(resp.msg);
        }
        else
        if (resp.success && resp.data.id > 0) {
            
            location.href = '/admin/page/' + resp.data.id;
        }
    });
    
});