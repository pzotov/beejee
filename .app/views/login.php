<?php
/**
 * @var string $login Логин
 */

use app\models\User;
?>
<div class="row">
	<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
		<form action="" class="form" method="post" enctype="multipart/form-data" id="login-form">
			<div class="form-group">
				<label for="" class="control-label">Имя пользователя:</label>
				<input type="text" class="form-control" id="login-login" name="login" value="<?= htmlspecialchars($login, ENT_QUOTES) ?>" required autofocus>
			</div>
			<div class="form-group">
				<label for="login-password" class="control-label">Пароль:</label>
				<input type="password" class="form-control" id="login-password" name="password" value="" required>
			</div>
			<div class="form-group clearfix">
				<button class="btn btn-primary btn-save" type="submit">Войти</button>
				<a href="/" class="btn btn-link pull-right">Вернуться на главную</a>
			</div>
		</form>
	</div>
</div>
