<?php
/**
 * Основной макет приложения
 * @var string $title Заголовок страница
 * @var string $content Контент, отрисованный в представлении страницы
 */
use app\models\User;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $title ?></title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
		.sorted-<?= SORT_ASC ?> a:after {
			content: ' ↓'
		}
		.sorted-<?= SORT_DESC ?> a:after {
			content: ' ↑';
		}
	</style>
</head>
<body>

<div class="container">
	<nav class="navbar navbar-inverse">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">Beejee</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="/">Задачи</a></li>
					<li><a href="/index.php?action=new">Новая задача</a></li>
					<?php if(User::authed()){ ?>
						<li><a href="/index.php?controller=user&action=logout">Выйти</a></li>
					<?php } else { ?>
						<li><a href="/index.php?controller=user&action=login">Авторизоваться</a></li>
					<?php } ?>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>
	
	<h1><?= $h1 ?: $title ?></h1>
	<?php
	if(isset($_SESSION['message']) && trim($_SESSION['message'])){ ?>
		<div class="alert alert-<?= $_SESSION['messageType'] ?>">
			<?= $_SESSION['message'] ?>
		</div>
	<?php
		unset($_SESSION['message']);
	} ?>
	<?= $content ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="/js/custom.js"></script>
</body>
</html>