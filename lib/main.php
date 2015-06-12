<?php
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('America/Argentina/Buenos_Aires');

function getData($link)
{
	include ("simple_html_dom.php");
	$randomHash = bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
	$url = $link;
	$getUserName = explode('/', $url);
	$userName = $getUserName[3];
	$html = file_get_html($url);
	$i = 0;
	$x = 0;
	$y = 0;
	foreach($html->find('img[class="avatar"]') as $element) {
		if (++$i > 4) break;

		$linkProfileSmall = $element->src;
		$linkProfile = str_replace('_bigger', "", $linkProfileSmall);
	}

	if (!$linkProfile) {
		echo "No se pudo encontrar el tweet";
	}

	foreach($html->find('strong[class="fullname"]') as $element) {
		if (++$x > 1) break;

		$fullnameAll = explode('<span', $element);
		$fullname = strip_tags($fullnameAll[0], '<strong/>');
	}

	foreach($html->find('p[class="TweetTextSize"]') as $element) {
		if (++$y > 1) break;

		$tweet = $element->plaintext;
	}

	foreach($html->find('span[class="metadata"]') as $element) {
		$metadata = $element->plaintext;
	}

	CrearImagen($linkProfile, $fullname, $tweet, $userName, $metadata, $randomHash);
}

function CrearImagen($linkProfile, $fullname, $tweetProfile, $username, $metadata, $randomHash)
{

	$im = imagecreatetruecolor(680, 680);
	$blanco = imagecolorallocate($im, 255, 255, 255);
	$negro = imagecolorallocate($im, 35, 35, 35);
	$gris = imagecolorallocate($im, 157, 157, 157);
	$NegroGris = imagecolorallocate($im, 63, 63, 68);
	$Link = imagecolorallocate($im, 63, 63, 68);
	imagefilledrectangle($im, 0, 0, 680, 680, $blanco);

	$name = $fullname;
	$UserName = "@" . $username;
	$tweet = $tweetProfile;
	$wrapTweet = wordwrap($tweet, 37, "\n");
	$data = $metadata;
	$fuente = 'ClearSans-Medium.ttf';

	imagettftext($im, 32, 0, 276, 164, $negro, $fuente, $name);
	imagettftext($im, 15, 0, 276, 200, $gris, $fuente, $UserName);
	imagettftext($im, 22, 0, 110, 350, $NegroGris, $fuente, $wrapTweet);
	imagettftext($im, 10, 0, 408, 570, $gris, $fuente, $data);
	imagepng($im, './temp/'.$randomHash."-base.png");

	if (substr($linkProfile, -3) == 'png') {
		$ProfileAvatar = imagecreatefrompng($linkProfile);
	}
	else {
		$ProfileAvatar = imagecreatefromjpeg($linkProfile);
	}

	$im = imagecreatefrompng('./temp/'.$randomHash.'-base.png');
	$x = imagesx($ProfileAvatar);
	$y = imagesy($ProfileAvatar);

	imagecopyresized($im, $ProfileAvatar, 85, 118, 0, 0, 141, 141, $x, $y);
	imagepng($im, './temp/'.$randomHash .'-final.png');

	// dumpear memoria
	imagedestroy($im);
	MostrarImagen($randomHash);
}

function MostrarImagen($randomHash)
{
	?>
	<img src="<?php echo './temp/'.$randomHash.'-final.png'; ?>" />
	<?php
}

?>