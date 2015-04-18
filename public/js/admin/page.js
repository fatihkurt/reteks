
$(function() { 
    
    CKEDITOR.on('instanceReady', function( ev ) {
        var blockTags = ['div','h1','h2','h3','h4','h5','h6','p','pre','li','blockquote','ul','ol',
        'table','thead','tbody','tfoot','td','th',];

        for (var i = 0; i < blockTags.length; i++)
        {
           ev.editor.dataProcessor.writer.setRules( blockTags[i], {
              indent : false,
              breakBeforeOpen : true,
              breakAfterOpen : false,
              breakBeforeClose : false,
              breakAfterClose : true
           });
        }
      });

    
    $('.editor').ckeditor();

    
    $('#page_form').ajaxForm({

        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(resp) { 
        
            if (resp.msg != null) {
    
                alert(resp.msg);
            }
            else
            if (resp.success && resp.data.id > 0) {
                
                location.href = '/admin/page/' + resp.data.id ;
            }
        }
    });
    
    
    $('#delete_btn').click(function(){
        
        $.ajax('/admin/page/delete', {
            method  : 'DELETE',
            dataType: 'json',
            data    : {
                id  : $('input[name=id]').val()
            },
            success : function(resp) {
                if (resp.success) {
                    
                    location.href = '/admin/page';
                }
                else {
                    alert('İşlem gerçekleştirilemedi');
                }
            }
        })
        
    });
});