<?php

require '../app.php';

try {
	$error = '';
	$tumblr = new Tumblr();
	$lists = $tumblr->gets();
} catch (Exception $e) {
	$error = $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Tumblr</title>
</head>
<body>
	<strong style="color: red"><?php echo $error ?></strong>
	<?php foreach ($lists as $list) { ?>
		<section>
			<header>
				<time>date <?php echo $list['createdAt'] ?></time>
			</header>
			<div>
				<?php echo $list['body'] ?>
			</div>
		</section>
	<?php } ?>
</body>
</html>