<?php
header('Content-Type: text/html; charset=UTF-8');
error_reporting(0);

function getData($link)
{
	include ("simple_html_dom.php");

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

	CrearImagen($linkProfile, $fullname, $tweet, $userName, $metadata);
}

function CrearImagen($linkProfile, $fullname, $tweetProfile, $username, $metadata)
{

	// Crear imagen base

	$im = imagecreatetruecolor(680, 680);
	$blanco = imagecolorallocate($im, 255, 255, 255);
	$negro = imagecolorallocate($im, 35, 35, 35);
	$gris = imagecolorallocate($im, 157, 157, 157);
	$NegroGris = imagecolorallocate($im, 63, 63, 68);
	$Link = imagecolorallocate($im, 63, 63, 68);
	imagefilledrectangle($im, 0, 0, 680, 680, $blanco);

	// Texto obtenido del tweet

	$name = $fullname;
	$UserName = "@" . $username;
	$tweet = $tweetProfile;
	$wrapTweet = wordwrap($tweet, 37, "\n");
	$data = $metadata;
	$fuente = 'ClearSans-Medium.ttf';

	// printear texto en imgen base

	imagettftext($im, 32, 0, 276, 164, $negro, $fuente, $name);
	imagettftext($im, 15, 0, 276, 200, $gris, $fuente, $UserName);
	imagettftext($im, 22, 0, 110, 350, $NegroGris, $fuente, $wrapTweet);
	imagettftext($im, 10, 0, 408, 570, $gris, $fuente, $data);
	imagepng($im, "yo.png");

	// Obtener foto de perfil actual, y cargar imagen base

	if (substr($linkProfile, -3) == 'png') {
		$estampa = imagecreatefrompng($linkProfile);
	}
	else {
		$estampa = imagecreatefromjpeg($linkProfile);
	}

	$im = imagecreatefrompng('yo.png');
	$x = imagesx($estampa);
	$y = imagesy($estampa);

	// Rezisar imagen de perfil y pegar en imagen base

	imagecopyresized($im, $estampa, 85, 118, 0, 0, 141, 141, $x, $y);

	// Guardar imagen en servidor
	// header('Content-type: image/png');

	imagepng($im, 'final.png');

	// dumpear memoria

	imagedestroy($im);
	MostrarImagen();
}

function MostrarImagen()
{
	echo '<img src="final.png" />';
}

?>