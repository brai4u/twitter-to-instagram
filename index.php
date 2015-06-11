<html>
<head>
	<title>tweet 2 instagram</title>
</head>
<body>
<h1>tweet 2 instagram</h1>
<form action="" method="post">
	<input type="text" name="linktweet" placeholder="link tweet"/>
	<input type="submit" />
</form>

<?php
include ('lib/main.php');
$link = $_POST['linktweet'];
if(stripos($link,'twitter.com/') !== false ){
	getData($link);
}
else if($link != null) {
	echo "No parece un link de twitter valido";
}
?>

</body>
</html>