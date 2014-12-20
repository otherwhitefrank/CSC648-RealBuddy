<?php

class QR {
 
function google_qr($url, $size) {

$url = urlencode($url);

echo '<img src="http://chart.apis.google.com/chart?chs='.$size.'x'.$size.'&cht=qr&&chl='.$url.'"/>';

}
}

?>