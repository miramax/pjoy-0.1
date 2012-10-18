/*
 * @date 15.06.2012
 * @author Drew
 */

$(document).ready(function(){

  $.ajaxSetup({ cache: false });

  var ajax_paragraph = $('<p>');
      ajax_paragraph.text('Загрузка');

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
    'top'  : 100,
    'left' : ($(window).width()/2) - (loader.width()/2)
  });

  var pointer = $('.pointer'),
      menu_links = $('#menu li a'),
      distance_list = [-3, 90, 195, 300, 400],
      ajax_list = ['services','diagnostic','clients','publications','base'],
      distance,
      slide_speed = 'normal',
      submenu = $('#submenu ul'),
      question = $('.question'),
      question_block = $('#question_block'),
      q_close = $('#question_block .close');

  question.live('click', function() {
    question_block.show();
  });

  q_close.live('click',function() {
    question_block.hide();
  });

  menu_links.each( function(index) {

  $(this).data('link_index', index);
    if($(this).hasClass('active')) {
      $(pointer).css({'left':distance_list[index]+'px'});
    }
  });

  menu_links.live('hover', function() {

    var index = $(this).data('link_index');
    distance = distance_list[index];
    $(pointer).css({'left':distance+'px'});

    var clas = $(this).attr('class');
    if( (clas.indexOf('active') != 0) && (clas.indexOf('hovered') == -1) ) {
      $(this).addClass('hovered');

      $('#menu li .active').addClass('hide');

      submenu.slideUp('fast',function(){
        submenu.load('/ajax/' + ajax_list[index] + '.html', function() {
          $(this).slideDown('fast');
        });
      });

    } else {
      if( (clas.indexOf('active') == 0) && clas.indexOf('hide') != -1 ) {

      submenu.slideUp('fast',function(){
        $(this).load('/ajax/' + ajax_list[index] + '.html', function() {
          $(this).slideDown('fast');
        });
      });
        $(this).removeClass('hide');
      }
    }

    menu_links.each(function(n){
      if(n != index) {
        $(this).removeClass('hovered');
      }
    });

  });


  function SubmitNativeForm(container, form_class) {

      var formdata = false,
          reader,
          file,
          input;

      $(form_class).live('submit',function(event) {

        event.preventDefault();
        event.stopPropagation();

        var $form = $( this ),
            request = $form.serialize(),
            url = $form.attr('action'),
            file = $form.find('#attach').val();

        var attach = ( typeof file != undefined ) ? file : false;

        if(attach) {
          if ( window.FormData && window.FileReader ) {
            formdata = new FormData();
            reader = new FileReader();
          }

          input = document.getElementById("attach");
          file = input.files[0];
          reader.readAsDataURL(file);
        }

          $.ajax({
            url: url,
            type: "POST",
            beforeSend : function() {
              loader.fadeIn();
              $('.submit')
              .attr('disabled', 'disabled')
              .addClass('disabled');
            },
            complete : function() {
              loader.fadeOut();
            },
            data: request,
            dataType: 'html',
            success : function( response ) {
                container.html( response );
               if( $('.form_error').length == 0) {
                  if (formdata && attach) {
                    formdata.append("images", file);
                    $.ajax({
                      url: url,
                      type: "POST",
                      data: formdata,
                      processData: false,
                      contentType: false,
                      success: function (data) {
                        //container.html( data );
                      }
                    });

                  }
                  $('#form_response').slideDown(slide_speed);
              } else {
                $('.empted').effect("highlight", {}, 700);
                $('.submit').effect("bounce",
                                    {times:4,
                                      direction: 'right',
                                      distance: 30},
                                    120,
                                    function(){
                                      $('#form_response')
                                      .slideDown(slide_speed);
                                    });
              }
            }
          });


      });
  }


  var form_bg = $('.forms_bg'),
      form_bot_bg = $('.forms_bot_bg'),
      form_container = $('#form_container'),
      form_bot_container = $('#form_bot_container'),
      order = $('#formhead .order'),
      order_bot = $('.feedback'),
      form_trianlge = $('.form_triangle'),
      close_form = $('#close_form'),
      close_bot_form = $('#close_bot_form'),
      form_name,
      ft_pos,
      f_class,
      clk = [0, 0, 0];

  form_bg.data('visible', 0)
  form_bot_bg.data('visible', '0');

  order.live('click',function(event) {

    event.preventDefault();
    event.stopPropagation();

    if(form_bot_bg.data('visible') == 1) {
      form_bot_bg.slideUp(slide_speed, function(){
        $(this).data('visible',0);
        form_bot_container.html('');
        order_bot.removeClass('activated');
      });
    }

    if($(this).hasClass('finanse')) {
      form_name = 'finanse';ft_pos = -200;
      f_class = '.formfinanse';
      clk[0]++;
    } else {
      form_name = 'callback';ft_pos = 50;
      f_class = '.formcallback';
      clk[1]++;
    }

    if($(this).hasClass('activated')) {
      $(window).scrollTo('#wrapper', slide_speed)
      form_bg.slideUp(slide_speed).data('visible', '0');
      $(this).removeClass('activated');
      return false;
    }


    if(form_bg.data('visible') == 1) {
      loader.fadeIn();
      order.each(function() {
        $(this).removeClass('activated');
      });
      form_bg.slideUp(slide_speed, function(){
        $.ajax({
          type : 'GET',
          url  : '/ajax/form'+form_name+'/',
          beforeSend : function() {
            loader.fadeIn();
          },
          complete : function() {
            loader.fadeOut();
          },
          success : function(html) {
            form_container.html(html);
            form_bg.slideDown(slide_speed);
            form_trianlge.css('left', ft_pos+'px');
            //SubmitNativeForm(form_container, f_class);
            $(window).scrollTo(f_class, 400, {over: -0.5});
          }
        });
      });
    }

    if(form_bg.data('visible') == 0) {
        $.ajax({
          type : 'GET',
          url  : '/ajax/form'+form_name+'/',
          beforeSend : function() {
            loader.fadeIn();
          },
          complete : function() {
            loader.fadeOut();
          },
          success : function(html) {
            form_container.html(html);
            form_bg.slideDown(slide_speed).data('visible', 1);
            form_trianlge.css('left', ft_pos+'px');
            //SubmitNativeForm(form_container, f_class);
            $(window).scrollTo(f_class, 400, {over: -0.5});
          }
        });
    }

    if(clk[0] == 1 && f_class == '.formfinanse') {
      SubmitNativeForm(form_container, '.formfinanse');
    }

    if(clk[1] == 1 && f_class == '.formcallback') {
      SubmitNativeForm(form_container, '.formcallback');
    }

    $(this).addClass('activated');
  });


  order_bot.live('click', function() {
    clk[2]++;
    f_class = '.formadmin';

    if(form_bg.data('visible') == 1) {
      form_bg.slideUp(slide_speed, function(){
        $(this).data('visible',0);
        form_container.html('');
        order.each(function() {
          $(this).removeClass('activated');
        });
      });
    }

    if($(this).hasClass('activated')) {
      form_bot_bg.slideUp(slide_speed).data('visible', '0');
      $(this).removeClass('activated');
      return false;
    }


    if(form_bot_bg.data('visible') == 1) {
      form_bot_bg.slideUp(slide_speed, function(){
        $.ajax({
          type : 'GET',
          url : '/ajax/formadmin/',
          beforeSend : function() {
            loader.fadeIn();
          },
          complete : function() {
            loader.fadeOut();
          },
          success : function(html) {
            form_bot_container.html(html);
            form_bot_bg.slideDown(slide_speed);
            //SubmitNativeForm(form_bot_container, f_class);
            $(window).scrollTo(f_class, slide_speed, {over: -0.1});
          }
        });
      });
    }

    if(form_bot_bg.data('visible') == 0) {
      $.ajax({
        type : 'GET',
        url : '/ajax/formadmin/',
        beforeSend : function() {
          loader.fadeIn();
        },
        complete : function() {
          loader.fadeOut();
        },
        success : function(html) {
          form_bot_container.html(html);
          form_bot_bg.slideDown(slide_speed).data('visible', 1);
          //SubmitNativeForm(form_bot_container, f_class);
          $(window).scrollTo(f_class, slide_speed, {over: -0.1});
        }
      });
    }

    if(clk[2] == 1 && f_class == '.formadmin') {
      SubmitNativeForm(form_bot_container, '.formadmin');
    }

    $(this).addClass('activated');
    return false;

  });


  close_form.live('click',function(){
    form_bg.slideUp(slide_speed, function(){
      $(this).data('visible',0);
      form_container.html('');
      order.each(function() {
        $(this).removeClass('activated');
      });
    });
  });


  close_bot_form.live('click',function(){
    form_bot_bg.slideUp(slide_speed, function(){
      $(this).data('visible',0);
      form_bot_container.html('');
      order_bot.removeClass('activated');
    });
  });


function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function setcookie(name, value, expires, path, domain, secure) {

	expires instanceof Date ? expires = expires.toGMTString() :
                            typeof(expires) == 'number' && (expires = (new Date(+(new Date) + expires * 1e3)).toGMTString());
	var r = [name + "=" + escape(value)], s, i;
	for(i in s = {expires: expires, path: path, domain: domain}){
		s[i] && r.push(i + "=" + s[i]);
	}
	return secure && r.push("secure"), document.cookie = r.join(";"), true;
}

  function refreshCode() {
    var refresh = $('.code-refresh,.code-image');
    refresh.live('click', function(){
        $.ajax({
          type : "GET",
          url : "/refresh-code.html",
          beforeSend: function(){
            loader.fadeIn();
          },
          complete: function(){
            loader.fadeOut();
          },
          success: function(data) {

            var img = new Image(),
                date = new Date(),
                version = date.getSeconds().toString() +
                          getRandomInt(11,99).toString();

                img.src = '/image.png';
                img.newSrc = img.src + '?v=' +
                             version;

                setcookie('iVersion', version);

                img.onload = function() {
                  $('.code-image').attr('src', img.newSrc);
                }
          }

        });
    });

  }

  refreshCode();

});