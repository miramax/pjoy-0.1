<?php

class PjoyStatistic {

  private $ua;
  private $browsers = array(
      'MSIE','Opera','Firefox','Chrome',
      'Version','Opera Mini','Netscape',
      'Konqueror','SeaMonkey','Camino',
      'Minefield','Iceweasel','K-Meleon',
      'Maxthon'
  );

  public function browser($ua){
    return $this->detect($ua);
  }

  private function detect($agent) {
    # find browser from our list
    preg_match("/(".implode('|', $this->browsers).")(?:\/| )([0-9.]+)/", $agent, $browser_info);
    # from massive into string parametr
    list(,$browser,$version) = $browser_info;

    if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera))
      return 'Opera ' . $opera[1];

    if ($browser == 'MSIE') { // if IE

        preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie); // mb IE frameworks?

        if ($ie)
          return $ie[1].' based on IE '.$version; // if yes that return this

        return 'IE '.$version; // if not IE framework, return IE version
    }

    if ($browser == 'Firefox') { // if Firefox
                preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff); // Firefox Frameworks?
                if ($ff)
                  return $ff[1].' '.$ff[2]; // if yes return number and name of version
    }

    if ($browser == 'Opera' && $version == '9.80')
      return 'Opera '.substr($agent,-5); // if Opera, that gets version from the end of the string

    if ($browser == 'Version')
      return 'Safari '.$version; // Safari?

    if (!$browser && strpos($agent, 'Gecko'))
            return 'Gecko'; // for not supported browsers return this string

    return $browser.' '.$version; // for supported browsers return version and name
  }

}