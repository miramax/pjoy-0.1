<?php

/*** captcha incapsulation ***/
AppRouter::set('/image\.png$/', 'CaptchaController', 'indexAction');
AppRouter::set('/refresh\-code\.html$/', 'CaptchaController', 'refreshAction');

/*** statistic incapsulation ***/
AppRouter::set('/statistic\.html$/', 'StatisticController', 'updateAction');


/* default pages */
AppRouter::set('/about\.html$/', 'IndexController', 'pageAction', array(9));