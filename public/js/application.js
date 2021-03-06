
$(function() {
    
    
    
    $('#application_form button[type=submit]').click(function() {
        
        var form = $('#application_form');
        
        form.find('.input-err').removeClass('input-err');

        form.ajaxSubmit({

            //contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function(resp) { 
            
                if (resp.message != null) {
        
                    alert(resp.message);
                }

                if (resp.success) {
                    
                    $('.career-form').html('<p>' + resp.message + '</p>');
                }
                else {
                    for (err in resp.data) {

                        $('[name^="' +err+ '"]').addClass('input-err');
                    }
                }
            }
        });
        
        return false;
    });
    
    
    $('.section-title').click(function(){

        $(this).parent().next().slideToggle();
    });
    
    $('[name=position_id]').change(function() {
        
        $(this).parents('.section').next().find('.section-inner').slideDown();
    })

});
