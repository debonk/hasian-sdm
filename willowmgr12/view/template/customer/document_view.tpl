<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?= $title; ?>
	</title>
	<style>
		.img-responsive {
			display: block;
			max-width: 100%;
			height: auto;
		}
	</style>
</head>

<body>
	<div id="content">
		<div>
			<img src="<?= $image; ?>" class="img-responsive" />
		</div>
	</div>
</body>

</html>