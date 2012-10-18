<?php

/* Switch off Cache, must have */
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");

/* Mime-Type, not necessarily */
header("Content-type: text/javascript");
?>
(function(){

  var statistics = {
      ua : window.navigator.userAgent,
      os : window.navigator.platform,
      ip : "<?php echo $_SERVER['REMOTE_ADDR'];?>",
      url : window.location.href,
      referer : "<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:''?>",
      language : window.navigator.language
  };

  $.ajax({
    type : "POST",
    data : statistics,
    url : "/statistic.html",
    success: function() {}
  });

})();