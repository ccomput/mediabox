<?php

$name = "Your Name";

$f = fopen("position.cur", "r");
$p = fgets($f);

$p = explode(":", $p);

$time = $p[0];
$lat = $p[1];
$lon = $p[2];
$acc = $p[3];

$acc = (int)$acc;
$pos = "$lat,$lon";
$time = strftime("%Y-%m-%d %H:%M:%S", $time);
$utime = urlencode($time);
$uname = urlencode($name);

?>

<p>
  Latitude: <?=$lat?> <br />
  Longitude: <?=$lon?> <br />
  Accuracy: <?=$acc?> m<br />
  Updated: <?=$time?> <br />
</p>

<iframe width="640" height="480" frameborder="0" scrolling="no" 
        marginheight="0" marginwidth="0" 
        src="http://maps.google.com/?ie=UTF8&amp;q=Last+Update:+<?=$utime?>&lt;br&gt;Accuracy:+<?=$acc?>m(<?=$uname?>)@<?=$pos?>&amp;ll=<?=$pos?>&amp;z=13&amp;output=embed">
</iframe>