<?php

AppRouter::set('/login\.html$/', 'AuthController', 'indexAction');
AppRouter::set('/logout\.html$/', 'AuthController', 'logoutAction');
AppRouter::set('/forgot\.html$/', 'AuthController', 'recoveryAction');
AppRouter::set('/newpass\.html$/', 'AuthController', 'newPasswordAction');


AppRouter::set('/browsers\_statistic\.png$/', 'StatisticController', 'renderAction', array('browsers.png'));
AppRouter::set('/pages\_statistic\.png$/', 'StatisticController', 'renderAction', array('pages.png'));
AppRouter::set('/pages\_referer\.png$/', 'StatisticController', 'renderAction', array('referer.png'));