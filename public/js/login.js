$(document).ready(function(){

  $response = $('#form_response');
  $errors = $('.system_error');
  $success = $('.system_success');

  $('.close').click(function()
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

});