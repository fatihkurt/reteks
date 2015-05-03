
$(function() { 
    
    $('#application_form').ajaxForm(function(resp) { 
        
        if (resp.msg != null) {

            alert(resp.msg);
        }
        else
        if (resp.success && resp.data.id > 0) {
            
            location.href = '/admin/application/position/edit/' + resp.data.id;
        }
    });
    
    $('#delete_btn').click(function(){
        
        $.ajax('/admin/application/position/delete', {
            method  : 'DELETE',
            dataType: 'json',
            data    : {
                id  : $('input[name=id]').val()
            },
            success : function(resp) {
                if (resp.success) {
                    
                    location.href = '/admin/application/position';
                }
                else {
                    alert('İşlem gerçekleştirilemedi');
                }
            }
        });
        
    });
    
    
    $('#application-action-btn').click(function() {
        
        var status = $('#application-action-select').val();
        
        if (isNaN(status)) {
            
            return alert('Lütfen bir aksiyon seçiniz..')
        }
       
        
        $.ajax('/admin/application/getStatus/' + status, {
            method  : 'GET',
            dataType: 'json',
            success : function(resp) {
                
                $('#application-action-modal').modal('show')
            }
        });
    })
});