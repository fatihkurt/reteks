
$(function() {
    
    $('.fancybox').fancybox({
        openEffect  : 'none',
        closeEffect : 'none',
        helpers : {
            media : {}
        }
    });
    
    $('.search-input').selectize({
        openOnFocus: false,
        maxItems: 1,
        valueField: 'url',
        labelField: 'title',
        searchField: ['title', 'url'],
        sortField: [
            {field: 'title', direction: 'asc'}
        ],
        render: {
            item: function(item, escape) {
                return '<div><a class="s-url">' + escape(item.title) + '</a></div>';
            },
            option: function(item, escape) {
                return '<div><a class="s-url">' + escape(item.title) + '</a></div>';
            }
        },
        load: function(query, callback) {
            
            if (!query.length) return callback();
            
            var s = this;
            
            $.ajax({
                url: '/search/' + encodeURIComponent(query),
                type: 'POST',
                error: function() {
                    callback();
                },
                success: function(resp) {

                    callback(resp.data);
                    
                    s.clearCache();
                }
            });
        },
        onChange: function(val) {
            
            if (val != '') {
                
                location.href = app.getUrl(val);
            }
        }
    });

    
    $('.main-banner').slidesjs({
        width: 1060,
        height: 450,
        navigation: {
            active: false,
            effect: "slide"
        },
        play: {
            active: false,
            effect: "slide",
            interval: 7000,
            auto: true,
            swap: true,
            pauseOnHover: false,
            restartDelay: 3500
        }
    });


    $('.users-slider').slidesjs({
        width: 285,
        height: 200,
        play: {            
            auto: true,
            interval: 6000,
            effect: "slide"
        },
        navigation: false,
        pagination: {
            active: true,
            effect: "fade"
        },
        effect: {
            fade: {
              speed: 200,
              crossfade: false
            }
        }
    });
    
    $('.news-slider').slidesjs({
        width: 1060,
        height: 450,
        start: 1,
        play: {            
            auto: true,
            interval: 10000,
            effect: "slide"
        },
        navigation: {
            active: false,
            effect: "fade"
        },
        pagination: {
            active: false
        },
        effect: {
            fade: {
                speed: 200,
                crossfade: true
            }
        },
        callback: {
            loaded: function(number) {
                if ($('.slider-item').length == 1)
                    $('.slidesjs-next').trigger('click'); // fix
            }
        }
    });
    




    $('.contact-form .form-btn').click(function() {
        
        var form = $(this).closest('form');
        
        $.ajax({
            url: '/contact/form',
            type: 'POST',
            dataType: 'json',
            data: form.serialize(),
            success: function(resp) {
                
                if (resp.success == false) {
                    
                    swal({
                        confirmButtonText : LANG == 'tr' ? 'Tamam' : 'OK',
                        cancelButtonText  : LANG == 'tr' ? 'İptal' : 'Cancel',
                        title : '',
                        text  : resp.message,
                        type  : 'error'
                    })
                }
                else {
                    $('.contact-form').html(resp.message);
                }
            }
        });
        
        return false;
    });
    
    
    $('.ebulten-btn').click(function() {
        
        var email = $('.ebulten-input').val();
        
        
        $.ajax({
            url: '/newsletter/save',
            type: 'POST',
            dataType: 'json',
            data: {
                email: email
            },
            success: function(resp) {
                
                if (resp.success == false) {

                    swal({
                        confirmButtonText : LANG == 'tr' ? 'Tamam' : 'OK',
                        cancelButtonText  : LANG == 'tr' ? 'İptal' : 'Cancel',
                        title : '',
                        text  : resp.message,
                        type  : 'error'
                    })
                    
                    $('.ebulten-input').select();
                }
                else {
                    
                    swal({
                        confirmButtonText : LANG == 'tr' ? 'Tamam' : 'OK',
                        cancelButtonText  : LANG == 'tr' ? 'İptal' : 'Cancel',
                        title : '',
                        text  : resp.message,
                        type  : 'success'
                    })
                }
            }
        });
        
        return false;
    });
});


var app = (function () {

    return {
        lang: LANG,
        
        getUrl: function (url) {

            return  '/' + this.lang + '/' + (url != undefined ? url.trim() : '');
        },
        getRoot: function () {

            return location.protocol + "//" + location.hostname;
        },
        getRootUrl: function (url) {

            return this.getRoot() + this.getUrl(url);
        },
        setCookie: function (name, value, days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
            }
            else
                var expires = "";
            document.cookie = name + "=" + value + expires + "; path=/";
        },
        getCookie: function (name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ')
                    c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0)
                    return c.substring(nameEQ.length, c.length);
            }
            return null;
        },
        delCookie: function (name) {
            setCookie(name, "", -1);
        },
        redirectPage: function (redirectUrl, timeout) {
            window.setTimeout(function () {
                location.href = redirectUrl;
            }, parseInt(timeout));
            return true;
        },
        
        initValidations : function(containerSelector) {
            
            containerSelector = containerSelector || 'body';

            $("input[type!=submit][type!=button][type!=hidden],select,textarea", containerSelector).jqBootstrapValidation();
        },
        
        hasValidationErrors : function(containerSelector) {
            
            containerSelector = containerSelector || 'body';
            
            return $("input[type!=submit][type!=button][type!=hidden],select,textarea", containerSelector).jqBootstrapValidation("hasErrors");
        },
        
        getValidationErrors : function(containerSelector) {
            
            containerSelector = containerSelector || 'body';
            
            return $("input[type!=submit][type!=button][type!=hidden],select,textarea", containerSelector).jqBootstrapValidation("collectErrors");
        },
        
        initMasks: function(containerSelector) {
            
            containerSelector = containerSelector || 'body';
            
            $('input[data-mask]', containerSelector).each(function() {
                var input = $(this),
                    options = {};

                if (input.attr('data-mask-reverse') === 'true') {
                    options['reverse'] = true;
                }

                if (input.attr('data-mask-maxlength') === 'false') {
                    options['maxlength'] = false;
                }

                input.mask(input.attr('data-mask'), options);
            });
        },

        alertWin: null,
        alert: function (msg, duration) {

            // İki uyarı üst üste gösterme
            this.closeAlertWin();

            this.alertWin = bootbox.alert(msg);
            
            if (duration === true)
                duration = 3000;

            if (duration > 0) {

                var self = this;

                window.setTimeout(function() {
                    self.closeAlertWin();
                }, duration);
            }
        },
        closeAlertWin: function() {

            if (this.alertWin != null) {

                this.alertWin.modal('hide');
                this.alertWin = null;
            }
        },
        confirm: function (msg, callback) {

            bootbox.confirm(msg, callback);
        },
        showErrors: function (errors, container) {

            container = container || $('body');

            var messages = '';
            

            for (var key in errors) {
                
                var opt = {};

                var el = container.find('[name=' + key + ']:first');
                
                if (el.attr('type') == 'radio' || el.attr('type') == 'checkbox') {
                    
                    opt.container = el.parents('.input-group');
     
                    if (opt.container.length == 0) {
                        opt.container = el.parent();
                    }
                    else {
                        el = opt.container;
                    }
                }
                
                var errMsg = typeof errors[key] == 'object' ? errors[key][0] : errors[key];

                // eğer böyle bir eleman yoksa mesajı alert ile ver
                if (el.length == 0) {

                    messages += '<br>' + errMsg + ' [' + key + ']';
                    continue;
                }

                showErr(el, errMsg, opt)
            }

            if (messages != '') {

                swal(messages);
            }
        },
        addScrollTopBtn: function () {

            //$('<a href="#" style="display: inline;">Yukarı</a>').appendTo('.container').addClass('scroll-top');

            $(window).scroll(function () {

                if ($(this).scrollTop() > $(this).height()) {
                    //$('.scroll-top').show();
                }
                else {
                    //$('.scroll-top').hide();
                }
            });


            $('.scroll-top').click(function () {
                $("html, body").animate({
                    scrollTop: 0
                }, 600);
                return false;
            });
        },
        checkImage: function(type) {
            var validTypes = new Array('image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/tiff', '');
            
            return validTypes.indexOf(type) != -1;
        },
        firstFormElement : function(container) {

            var result = false;

            container.find('input[type!=hidden],select,textarea').each(function() {

                if ($(this).is(':visible') && $(this).css('display') !== 'hidden') {

                    result = $(this);

                    return false;
                }
            })

            return result;
        },
        escape: function(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        },
        
        scroll2Target : function(targetId) {
            
            var target = $(targetId);
            
            if (target.length == 0)
                return false;

            $('html,body').animate({
                scrollTop : target.offset().top
            },{
                duration : 600,
                easing : 'swing'
            });
            
            return true;
        }
    }

})();


if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, '');
    };
}


function log(a) {
    console.log(a)
}




function showErr(el, errMsg, opt) {

    var showHide = false;

    var defOpt = {
        placement: 'bottom',
        position: {my: "50% center", at: "left center"},
        content: errMsg
        //container: el.parent()
    };
    
    if (typeof opt.container != undefined) {
        defOpt.container = opt.container;
    }

    // radio btn gizli olduğu için göster gizle yapılıyor
    if (el.css('display') == 'none') {
        showHide = true;
        el.show();
    }

    var options = $.extend({}, defOpt, opt);

    el.attr("title", errMsg);
    el.attr("data-original-title", errMsg);

    el.tooltip(options);

    el.tooltip('show');

    el.attr("title", "");
    el.attr("data-original-title", "");

    el.removeClass('err_open').addClass('err_open');

    if (showHide) {
        el.hide();
    }
}


function hideErr() {

    $('.err_open').tooltip('destroy').removeClass('err_open');
}


var loadableBtn = function (btn, loading) {

    var self = this;

    self.btn = btn;
    self.origTitle = '';

    this.init = function (loading) {

        self.origTitle = this.btn.attr('title');

        if (loading === true) {
            self.load();
        }
        else
        if (loading === false) {
            self.unload();
        }
    }


    this.load = function () {

        self.btn.css('opacity', '0.5');
        btn.css('cursor', 'progress');
        btn.attr('title', 'Lütfen bekleyiniz');

        self.btn.attr("disabled", "disabled");
    }

    self.unload = function () {

        btn.css('opacity', '');
        btn.css('cursor', '');

        btn.attr('title', self.origTitle);

        btn.removeAttr('disabled');
    }

    self.init(loading)

    return self;
};


function empty(mixed_var) {
  //  discuss at: http://phpjs.org/functions/empty/
  // original by: Philippe Baumann
  //    input by: Onno Marsman
  //    input by: LH
  //    input by: Stoyan Kyosev (http://www.svest.org/)
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Onno Marsman
  // improved by: Francesco
  // improved by: Marc Jansen
  // improved by: Rafal Kukawski
  //   example 1: empty(null);
  //   returns 1: true
  //   example 2: empty(undefined);
  //   returns 2: true
  //   example 3: empty([]);
  //   returns 3: true
  //   example 4: empty({});
  //   returns 4: true
  //   example 5: empty({'aFunc' : function () { alert('humpty'); } });
  //   returns 5: false

  var undef, key, i, len;
  var emptyValues = [undef, null, false, 0, '', '0'];

  for (i = 0, len = emptyValues.length; i < len; i++) {
    if (mixed_var === emptyValues[i]) {
      return true;
    }
  }

  if (typeof mixed_var === 'object') {
    for (key in mixed_var) {
      // TODO: should we check for own properties only?
      //if (mixed_var.hasOwnProperty(key)) {
      return false;
      //}
    }
    return true;
  }

  return false;
}

function is_numeric(mixed_var) {
  //  discuss at: http://phpjs.org/functions/is_numeric/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: David
  // improved by: taith
  // bugfixed by: Tim de Koning
  // bugfixed by: WebDevHobo (http://webdevhobo.blogspot.com/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Denis Chenu (http://shnoulle.net)
  //   example 1: is_numeric(186.31);
  //   returns 1: true
  //   example 2: is_numeric('Kevin van Zonneveld');
  //   returns 2: false
  //   example 3: is_numeric(' +186.31e2');
  //   returns 3: true
  //   example 4: is_numeric('');
  //   returns 4: false
  //   example 5: is_numeric([]);
  //   returns 5: false
  //   example 6: is_numeric('1 ');
  //   returns 6: false

  var whitespace =
    " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
  return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
    1)) && mixed_var !== '' && !isNaN(mixed_var);
}

// popover gizlendiğinde ilişkiyi tam kopar
$('body').on('hidden.bs.popover', function() {
    $('.popover:not(.in)').hide().detach();
});


function isEquivalent(a, b) {
    // Create arrays of property names
    var aProps = Object.getOwnPropertyNames(a);
    var bProps = Object.getOwnPropertyNames(b);

    // If number of properties is different,
    // objects are not equivalent
    if (aProps.length != bProps.length) {
        return false;
    }

    for (var i = 0; i < aProps.length; i++) {
        var propName = aProps[i];

        // If values of same property are not equal,
        // objects are not equivalent
        if (a[propName] !== b[propName]) {
            return false;
        }
    }

    // If we made it this far, objects
    // are considered equivalent
    return true;
}


String.prototype.turkishToUpper = function(){
    var string = this;
    var letters = { "i": "İ", "ş": "Ş", "ğ": "Ğ", "ü": "Ü", "ö": "Ö", "ç": "Ç", "ı": "I" };
    string = string.replace(/(([iışğüçö]))+/g, function(letter){ return letters[letter]; })
    return string.toUpperCase();
};

String.prototype.turkishToLower = function(){
    var string = this;
    var letters = { "İ": "i", "I": "ı", "Ş": "ş", "Ğ": "ğ", "Ü": "ü", "Ö": "ö", "Ç": "ç" };
    string = string.replace(/(([İIŞĞÜÇÖ]))+/g, function(letter){ return letters[letter]; })
    return string.toLowerCase();
};