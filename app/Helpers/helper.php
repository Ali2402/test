<?php

function ryuReply($status,$payload = null, $code){
    $reply = json_encode(array(
        "STATUS" => $status,
        "PAYLOAD" => $payload
    ));

    return Response::make($reply, $code)->header('Content-Type', 'application/json');
}

 function getBrowser($agent) {
    $user_agent     =   ($agent == null ) ? $_SERVER['HTTP_USER_AGENT'] : $agent;
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );
    foreach ($browser_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }
    return $browser;
    }

     function getOS($agent = null) { 
    $user_agent     =   ($agent == null ) ? $_SERVER['HTTP_USER_AGENT'] : $agent;
    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );
    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }
    }   
    return $os_platform;
    }
   function is_mobile()
  {
    $platform = $this->getOS();
    $mobile = ['iPad','iPhone','iPod','Android','BlackBerry','Mobile'];
    if(in_array($platform,$mobile))
    {
      return true;
    }else{
      return false;
    }
  }

  function radio($config,$value)
  {
      $cfg = @$config[''.$value.''];
      if($cfg == 1)
      {
          return ' 
          <div class="flex flex-row gap-x-2">
              <div class="flex items-center mr-4 mb-4">
                  <input id="'.$value.'" type="radio" name="'.$value.'" class="hidden" value="1" checked />
                  <label for="'.$value.'" class="flex items-center cursor-pointer">
                     <span class="w-4 h-4 inline-block mr-1 rounded-full border border-grey"></span>
                      Yes
                  </label>
              </div>
              <div class="flex items-center mr-4 mb-4">
                  <input id="'.$value.'2" type="radio" name="'.$value.'" class="hidden" value="0" />
                  <label for="'.$value.'2" class="flex items-center cursor-pointer">
                      <span class="w-4 h-4 inline-block mr-1 rounded-full border border-grey"></span>
                      No
                  </label>
              </div>
          </div>';
      }else{
          return ' 
          <div class="flex flex-row gap-x-2">
              <div class="flex items-center mr-4 mb-4">
                  <input id="'.$value.'" type="radio" name="'.$value.'" class="hidden" value="1" />
                  <label for="'.$value.'" class="flex items-center cursor-pointer">
                     <span class="w-4 h-4 inline-block mr-1 rounded-full border border-grey"></span>
                      Yes
                  </label>
              </div>
              <div class="flex items-center mr-4 mb-4">
                  <input id="'.$value.'2" type="radio" name="'.$value.'" class="hidden" value="0" checked />
                  <label for="'.$value.'2" class="flex items-center cursor-pointer">
                      <span class="w-4 h-4 inline-block mr-1 rounded-full border border-grey"></span>
                      No
                  </label>
              </div>
          </div>';
      }
  }