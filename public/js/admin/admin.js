$(function() {
   
    if (typeof CKEDITOR != 'undefined') {
        
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

        
        $('.editor').ckeditor({
            "language": "tr"
        });
    }
    
    
    $('.slide-menu').click(function() {
        
        $(this).next().slideToggle();
    });
    
    
    var loading = function() {
        // add the overlay with loading image to the page
        var over = '<div id="overlay">' +
            '<img id="loading" src="http://bit.ly/pMtW1K">' +
            '</div>';
        $(over).appendTo('body');
    };
    
    var unloading = function() {
        $('#overlay').remove()
    }
    
    $(document)
    .ajaxStart(function(){
        loading();
    })
    .ajaxStop(function(){
        unloading();
    });
    
});