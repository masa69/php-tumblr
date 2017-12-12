<?php

require '../app.php';

try {
	$error = '';

	$id = (isset($_GET['id'])) ? $_GET['id'] : null;

	$isSinglePost = false;
	$option = [];

	if (!empty($id)) {
		$option['id'] = $id;
		$isSinglePost = true;
	}

	$tumblr = new Tumblr();
	$lists = $tumblr->get($option);
} catch (Exception $e) {
	$error = $e->getMessage();
	$isSinglePost = false;
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
		<section style="padding: 12px;">
			<header>
				<time>date <?php echo $list['createdAt'] ?></time>
				<?php if ($isSinglePost) { ?>
					<h1><?php echo $list['title'] ?></h1>
				<?php } ?>
			</header>
			<div>
				<?php if ($isSinglePost) { ?>
					<?php echo $list['body'] ?>
				<?php } else { ?>
					<a href="?id=<?php echo $list['id'] ?>"><?php echo $list['title'] ?></a>
				<?php } ?>
			</div>
		</section>
	<?php } ?>
</body>
</html>