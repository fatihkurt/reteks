
$(function() {
    
    
    $('.contact-form .form-btn').click(function() {
        
        var form = $(this).closest('form');
        
        $.ajax({
            url: '/contact/form',
            type: 'POST',
            dataType: 'json',
            data: form.serialize(),
            success: function(resp) {
                
                if (resp.success == false) {
                    
                    alert(resp.message);
                }
                else {
                    $('.contact-form').html(resp.message);
                }
            }
        });
        
        return false;
    })
})