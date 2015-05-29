/**
 * HTML5 Placeholder Text jQuery Fallback with Modernizr
 *
 * @url     http://uniquemethod.com/
 * @author  Unique Method
 */
$(function()
{
    
    $('#login_btn').click(function() {
        
        $('#login_msg').html("");
        
        $('#forgot').hide();
        
        $.ajax({
            url : '/login',
            type: 'POST',
            dataType: 'json',
            data: $('form').serialize(),
            success: function(resp) {
                
                $('#login_msg').html(resp.message);
             
                if (resp.success == true) {
                    
                    location.reload();
                }
                else
                if (typeof resp.data.f != 'undefined') {
                    
                    $('#forgot').show();
                }
            }
        });
        
        return false;
        
    });
    
    
    $('#forgot_btn').click(function() {
        
        $('#login_msg').html("");
        
        $.ajax({
            url : '/login/forgot',
            type: 'POST',
            dataType: 'json',
            data: $('form').serialize(),
            success: function(resp) {

                if (resp.success == true) {

                    location.href = '/login';
                }
                else {
                    $('#login_msg').html(resp.message);
                }
            }
        });
        
        return false;
        
    });
    
    
    $('#forgot a').click(function() {
        
        var $btn = $(this);
        
        $.ajax({
            url : '/login/forgotLink',
            type: 'POST',
            dataType: 'json',
            data: $('form').serialize(),
            success: function(resp) {
                
                alert(resp.message);
                
                $btn.fadeOut();
            }
        });
    })
    
    
    
    // check placeholder browser support
    if (!Modernizr.input.placeholder)
    {
 
        // set placeholder values
        $(this).find('[placeholder]').each(function()
        {
            if ($(this).val() == '') // if field is empty
            {
                $(this).val( $(this).attr('placeholder') );
            }
        });
 
        // focus and blur of placeholders
        $('[placeholder]').focus(function()
        {
            if ($(this).val() == $(this).attr('placeholder'))
            {
                $(this).val('');
                $(this).removeClass('placeholder');
            }
        }).blur(function()
        {
            if ($(this).val() == '' || $(this).val() == $(this).attr('placeholder'))
            {
                $(this).val($(this).attr('placeholder'));
                $(this).addClass('placeholder');
            }
        });
 
        // remove placeholders on submit
        $('[placeholder]').closest('form').submit(function()
        {
            $(this).find('[placeholder]').each(function()
            {
                if ($(this).val() == $(this).attr('placeholder'))
                {
                    $(this).val('');
                }
            })
        });
 
    }
});