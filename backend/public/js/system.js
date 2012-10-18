$(document).ready(function(){

    $.ajaxSetup({ cache: false });

    /*** create loader div ***/
    var ajax_paragraph = $('<p>');
        ajax_paragraph.text('\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430');

    var ajax_loader = $('<span>', {
      'class' : 'ajax_loader'
    });

    var loader = $('<div>', {
      'class' : 'loader'
    });

    $('body').append(loader);
    loader.append(ajax_paragraph);
    loader.append(ajax_loader);
    loader.css({
      'top'  : ($(window).height()/3) - (loader.height()/3),
      'left' : ($(window).width()/2) - (loader.width()/2)
    });

   /*** Form Responses ***/
   function formResponse(){
      $response = $('#form_response');
      $errors = $('.system_error');
      $success = $('.system_success');

      $('.close').live('click',function()
        {
          $(this).closest($(this).parent()).fadeOut(200);
        });

      if ( $response.length )
       {
        var block;
        if ( $response.hasClass('form_error') ) {
          block = $errors;
        } else {
          block = $success;
        }
          block.append( $response.html() ).fadeIn(200);
       }
    }

    function directory() {
      $('.dir').live('click', function(){
        var file_list = $(this).next();
        if(file_list.is(':visible')) {
          file_list.hide();
        } else {
          file_list.show();
        }

      });
    }

    function initEditor(container) {
     var container = $(container);
     if(container.length) {
       container.redactor({
         lang: 'ru',
         imageUpload: '/auth/files/image/',
         imageGetJson: '/backend/public/js/images.json.php',
         fileUpload: '/auth/files/file/'
       });
     }
    }

    function afterLoadActions() {
      var page = $('#page');
      if(page.length) {
        var html = page.html();
        html = html.replace(/\.(jpg|jpeg|png|gif)"/, '.\1?v='+new Date().getTime()+'"');
        page.html(html);
      }

      $('.show_more').toggle(function(){
         $('.more_options').slideDown();
         $(this).addClass('active_span');
      }, function(){
         $('.more_options').slideUp();
         $(this).removeClass('active_span');
      });
    }


    function removeFile() {
      $('.remove_file').live('click', function(){
        var parent = $(this).parent().parent();
        var file = $(this).attr('rel');
        if(typeof file != 'undefined') {
          $.ajax({
            type: 'POST',
            data: {file:file},
            url: '/auth/filemanager/delete/',
            success: function() {
              parent.fadeOut(400, function(){
                $(this).remove();
              });
            },
            beforeSend : function() {
              loader.fadeIn(200);
            },
            complete : function() {
              loader.fadeOut(200);
            }
          });
        }
      });
    }


    function fileButton() {
      $('#file_button').live('click',function(event){
          event.preventDefault();
          event.stopPropagation();
          var id = $(this).attr('rel'),
              params = [],
              holder = $('#fileInfo');

          $('#'+id).click();
          $('#'+id).bind('change', function() {
              params[0] = this.files[0].name;
              params[1] = this.files[0].size;
              params[1] = Math.ceil(params[1] / 1000) + 'Kb';
              holder.find('#fName').text(params[0])
              .parent().find('#fSize').text(params[1]);
              holder.css({
                display: 'inline-block'
              });
          });
      });
    }


    function submitFileForm() {
      fileButton();
      $('#file_form').live('submit', function(event){

            event.preventDefault();
            event.stopPropagation();

          if ( window.FormData && window.FileReader ) {
            var formdata = new FormData();
            var reader = new FileReader();
          }

          var input = document.getElementById("file"),
              file = input.files[0],
              url = $(this).attr('action');
          reader.readAsDataURL(file);

          if (formdata && (typeof file != 'undefined')) {
            formdata.append("file", file);
            $.ajax({
              url: url,
              type: "POST",
              data: formdata,
              processData: false,
              contentType: false,
              success: function (response) {
                $('#insert').prepend(response);
                $('.lastFile').fadeIn(400);
                $('#fileInfo').fadeOut(400);
                $('#file').replaceWith('<input style="display:none" type="file" name="file" id="file" />');
              },
              beforeSend : function() {
                loader.fadeIn(200);
              },
              complete : function() {
                loader.fadeOut(200);
              }
            });

          }

      });
    }

    function submitNativeForm() {
      $('#native_form').live('submit', function(event){

            event.preventDefault();
            event.stopPropagation();

            var $form = $( this ),
            request = $form.serialize(),
            url = $form.attr('action');

            $.ajax({
              type: 'POST',
              url: url,
              data: request,
              dataType: 'html',
              success: function(response){
                $('#elementsUi').html(response);
                initEditor('#content');
                afterLoadActions();
                $(window).scrollTo('.pane', 300);
                formResponse();
              },
              beforeSend : function() {
                loader.fadeIn(200);
              },
              complete : function() {
                loader.fadeOut(200);
              }
            })
      });
    }

    var elements = {

      loading: function(hyperlink){
        var location = hyperlink.attr('href');
        if(location.indexOf('?') !== -1) {
          location = location + '&pj_v=' + new Date().getTime();
        } else {
          location = location + '/?pjv=' + new Date().getTime();
          location = location.replace(/\/\//, '/');
        }
        $.ajax({
          type: 'GET',
          url: location,
          success: function(response) {
            $('#elementsUi').html(response);
            initEditor('#content');
            afterLoadActions();
          },
          beforeSend : function() {
            loader.fadeIn(200);
          },
          complete : function() {
            loader.fadeOut(200);
          }
        });
      }

    };


    var navigation = {

      obj: $('.block-navigation').find('a:not(:animated)'),


      clear: function() {
        navigation.obj.each(navigation.eachClear);
      },


      eachClear: function() {
        if( $(this).hasClass('active') ) {
            $(this).removeClass('active');
        }
      },


      click: function() {
        navigation.obj.click( function(event) {
            event.preventDefault();
            event.stopPropagation();
            navigation.clear();
            $(this).addClass('active');
            elements.loading($(this));
            var ico = $('.activeBlock').find('.block-icon');
            $('#dynamic_icon').html(ico.clone());
        });
      },


      hover: function(){
        navigation.obj.hover(function(){
              $(this).addClass('hover', 150);
        }, function(){
              $(this).removeClass('hover', 150);
        });
      },


      behavior: function() {
        navigation.hover();
        navigation.click();
      }

    };




    var blockHead = {

      block: $('.block-head'),

      nav: '.block-navigation',

      behavior: function() {
        blockHead.hover();
        blockHead.click();
      },


      hover: function() {
        blockHead.block.find('.fadeBlock')
                       .fadeTo(30, 0.7)
                       .parent()
                       .hover(blockHead.over, blockHead.out);
        $('.activeBlock').find('.fadeBlock').fadeTo(30, 1);

      },


      over: function() {
        if( !$(this).parent().hasClass('activeBlock') ) {
          $(this).find('.fadeBlock').fadeTo(100, 1);
        }
      },


      out: function() {
        if( !$(this).parent().hasClass('activeBlock') ) {
          $(this).find('.fadeBlock').fadeTo(100, 0.7);
        }
      },


      click: function() {
        blockHead.block.click(blockHead.accordion);
      },


      accordion: function() {
        var parent = $(this).parent();

        if( !parent.hasClass('activeBlock') ) {
          var active = $('.activeBlock');
          active.find('.fadeBlock').fadeTo(100, 0.7);
          active.find('.navigate-icon')
                .hide(0, function(){
                  active.find(blockHead.nav)
                        .slideUp(300, function(){
                          active.removeClass('activeBlock');
                        });
                });
          parent.addClass('activeBlock');
          parent.find(blockHead.nav).slideDown(300, function(){
                  parent.find('.navigate-icon').show();
                  parent.find('.fadeBlock')
                        .fadeTo(100, 1);
              });
        }
      }

    };

    /*** init objects behavior ***/
    navigation.behavior();
    blockHead.behavior();



     /*** default ajax link ***/
     $('.data-load').live('click', function(event){
          event.preventDefault();
          event.stopPropagation();
          elements.loading($(this));
     });

     $('#click-doc').live('click', function(event){
          event.preventDefault();
          event.stopPropagation();
          $('#documentation-call').click();
     });


     /*** prepare page Ui ***/
    //$('#main_link').click();
    directory();
    formResponse();
    submitFileForm();
    initEditor('#content');
    removeFile();
    submitNativeForm();

});