<?php

class StatisticController extends BackendController {


  function indexAction(){

    $pj_stat = new PjoyStatistic();

    $stat = new Statistic();
    $stat->order('date', ':desc');
    $stat->find(':all');
    $rows = $stat->result();
    $stats = array();

    foreach($rows as $stat) {
      $e = @explode(' ', $pj_stat->browser($stat['ua']));
      $stat['ua'] = $e[0];
      $stat['url'] = AppHelper::getUrlPath($stat['url']);
      $stats[] = $stat;
    }

    $data['stats'] = $stats;

    $data['buttons'] = $this->buttons(array(
        'Список переходов::/statistic/::Yellow',
        'Браузеры::/statistic/browsers/::Green',
        'Страницы::/statistic/pages/::Green',
        'Источники::/statistic/referer/::Green'
    ));

    $this->display('index', $data);
  }


  function browsersAction(){
    $pj_stat = new PjoyStatistic();

    $stat = new Statistic();

    $query = "SELECT COUNT(*) as `count`, `ua`
                FROM #_statistic
              GROUP BY `ua` ORDER BY `count` DESC LIMIT 10";

    $stat->query($query);
    $rows = $stat->result();

    $values = array();
    $names = array();

    $summ = 0;
    $browsers = array();
    $result = array();

    foreach($rows as $row) {
      $ua = $pj_stat->browser($row['ua']);

      $values[] = $row['count'];
      $names[] = $ua;

      $browsers[] = array(
          'count' => $row['count'],
          'ua' => $ua);

      $summ += (int)$row['count'];
    }

    $k = 100 / $summ;

    foreach($browsers as $b) {
      $result[] = array(
          'count' => $b['count'],
          'ua' => $b['ua'],
          'percents' => round($b['count'] * $k, 1)
      );
    }

    self::Component('pchart',array(
          'names'=>$names,
          'values'=>$values,
          'fName'=> 'browsers.png'));

    $data['buttons'] = $this->buttons(array(
        'Список переходов::/statistic/::Green',
        'Браузеры::/statistic/browsers/::Yellow',
        'Страницы::/statistic/pages/::Green',
        'Источники::/statistic/referer/::Green'
    ));

    $data['browsers'] = $result;
    $this->display('browsers', $data);
  }


  function pagesAction() {
    $stat = new Statistic();

    $query = "SELECT COUNT(*) as `count`, `url`
                FROM #_statistic
              GROUP BY `url` ORDER BY `count` DESC LIMIT 8";

    $stat->query($query);
    $rows = $stat->result();

    $values = array();
    $names = array();

    $summ = 0;
    $pages = array();
    $result = array();

    foreach($rows as $row) {
      $values[] = $row['count'];
      $names[] = $row['url'];

      $summ += (int)$row['count'];
      $pages[] = array('count' => $row['count'],
                     'url' => $row['url']);
    }

    $k = 100 / $summ;

    foreach($pages as $p) {
      $result[] = array(
          'count' => $p['count'],
          'url' => AppHelper::getUrlPath($p['url']),
          'percents' => round($p['count'] * $k, 1)
      );
    }


    self::Component('pchart',array(
        'names'=>$names,
        'values'=>$values,
        'fName' => 'pages.png'));

    $data['buttons'] = $this->buttons(array(
        'Список переходов::/statistic/::Green',
        'Браузеры::/statistic/browsers/::Green',
        'Страницы::/statistic/pages/::Yellow',
        'Источники::/statistic/referer/::Green'
    ));

    $data['pages'] = $result;
    $this->display('pages', $data);
  }


  function refererAction() {
    $stat = new Statistic();

    $query = "SELECT COUNT(*) as `count`, `referer`
                FROM #_statistic
              GROUP BY `referer` ORDER BY `count` DESC LIMIT 8";

    $stat->query($query);
    $rows = $stat->result();

    $values = array();
    $names = array();

    $refs = array();
    $summ = 0;
    $result = array();

    foreach($rows as $row) {
      $values[] = $row['count'];
      $names[] = $row['referer'];

      $summ+=$row['count'];
      $refs[] = array(
          'count' => $row['count'],
          'ref' => $row['referer']
      );
    }

    $k = 100 / $summ;

    foreach($refs as $r) {
      $result[] = array(
          'count' => $r['count'],
          'referer' => AppHelper::getUrlPath($r['ref']),
          'percents' => round($r['count'] * $k, 1)
      );
    }

    self::Component('pchart',array(
        'names'=>$names,
        'values'=>$values,
        'fName' => 'referer.png'));

    $data['buttons'] = $this->buttons(array(
        'Список переходов::/statistic/::Green',
        'Браузеры::/statistic/browsers/::Green',
        'Страницы::/statistic/pages/::Green',
        'Источники::/statistic/referer/::Yellow'
    ));

    $data['refs'] = $result;
    $this->display('referer', $data);

  }


  function renderAction($fName) {
      $filePath = DOCUMENT_ROOT.'/www/tmp/stat/'.$fName;
      header("Content-Type: image/png");
      header('Content-Length: ' . filesize($filePath));
      header("Cache-Control: no-cache, must-revalidate");
      header("Cache-Control: post-check=0,pre-check=0");
      header("Cache-Control: max-age=0");
      header("Pragma: no-cache");

      return readfile($filePath);
  }

}