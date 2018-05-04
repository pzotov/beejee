<?php
/**
 * @var \app\models\Task $row Задача, которую редактируем или добавляем
 */

use app\models\User;
?>
<?php if($row->error){ ?>
<div class="alert alert-danger">
	<?= nl2br($row->error) ?>
</div>
<?php } ?>
<form action="" class="form" method="post" enctype="multipart/form-data" id="task-form">
	<?php if(User::authed()){ ?>
	<div class="form-group">
		<label for="task-done" class="control-label">
			<input type="checkbox" name="done" value="1"<?= $row->done ? ' checked' : '' ?> id="task-done">
			Задача выполнена
		</label>
	</div>
	<?php } ?>
	<div class="form-group">
		<label for="task-name" class="control-label">Имя пользователя:</label>
		<input type="text" class="form-control" id="task-name" name="name" value="<?= htmlspecialchars($row->name, ENT_QUOTES) ?>" required autofocus>
	</div>
	<div class="form-group">
		<label for="task-email" class="control-label">E-mail:</label>
		<input type="email" class="form-control" id="task-email" name="email" value="<?= htmlspecialchars($row->email, ENT_QUOTES) ?>">
	</div>
	<div class="form-group">
		<label for="task-text" class="control-label">Текст задач:</label>
		<textarea class="form-control" rows="6" required id="task-text" name="text"><?= htmlspecialchars($row->text, ENT_QUOTES) ?></textarea>
	</div>
	<div class="form-group">
		<label for="task-image" class="control-label">Картинка:</label>
		<input type="file" class="form-control" accept="image/gif, image/png, image/jpeg" id="task-image" name="image" data-current="<?= htmlspecialchars($row->image, ENT_QUOTES) ?>">
		<?php if($row->image){ ?>
			<div class="small">Уже загружена <a href="<?= $row->image ?>" target="_blank">картинка</a>.
				<label><input type="checkbox" name="imageDelete" value="1"> Удалить?</label></div>
		<?php } ?>
	</div>
	<div class="form-group clearfix">
		<button class="btn btn-primary btn-save" type="submit">Сохранить</button>
		<button class="btn btn-default btn-preview" type="submit">Предварительный просмотр</button>
		<a href="/" class="btn btn-default pull-right">Выйти без изменений</a>
	</div>
</form>

<div class="modal fade" id="preview-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Предварительный просмотр</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-info" role="alert">
					<strong>Внимание!</strong>
					Это только предварительный просмотр. <br>
					Чтобы изменения сохранились, нужно нажать кнопку "Сохранить".
				</div>
				<table class="table table-bordered">
					<tr>
						<th>Имя пользователя</th>
						<th>E-mail</th>
						<th>Текст задачи</th>
						<th>Картинка</th>
					</tr>
					<tbody id="preview"></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть предпросмотр</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->