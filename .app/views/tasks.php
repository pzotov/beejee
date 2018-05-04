<?php
/**
 * @var array $rows Список задач, выводимых на этой странице
 * @var int $page Номер страницы, начиная с 0
 * @var int $pagesCount Количество страниц
 * @var int $sortBy по какому полю идет сортировка
 * @var int $sortOrder направление сортировки
 */

use app\models\User;
use app\models\Task;
?>

<p><a href="/index.php?action=new" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Новая задача</a></p>
<table class="table table-bordered">
	<tr>
		<th<?= $sortBy==Task::SORT_NAME ? ' class="sorted-'.$sortOrder.'"' : '' ?>><a href="/?sortBy=<?= Task::SORT_NAME ?>&sortOrder=<?= $sortBy==Task::SORT_NAME && $sortOrder==SORT_ASC ? SORT_DESC : SORT_ASC ?>">Имя пользователя</a></th>
		<th<?= $sortBy==Task::SORT_EMAIL ? ' class="sorted-'.$sortOrder.'"' : '' ?>><a href="/?sortBy=<?= Task::SORT_EMAIL ?>&sortOrder=<?= $sortBy==Task::SORT_EMAIL && $sortOrder==SORT_ASC ? SORT_DESC : SORT_ASC ?>">E-mail</a></th>
		<th>Текст задачи</th>
		<th>Картинка</th>
		<th<?= $sortBy==Task::SORT_STATUS ? ' class="sorted-'.$sortOrder.'"' : '' ?>><a href="/?sortBy=<?= Task::SORT_STATUS ?>&sortOrder=<?= $sortBy==Task::SORT_STATUS && $sortOrder==SORT_ASC ? SORT_DESC : SORT_ASC ?>">Статус</a></th>
		<?php if(User::authed()){ ?>
		<th>Действия</th>
		<?php } ?>
	</tr>
	<tbody>
	<?php foreach($rows as $index => $row){ ?>
	<tr<?= $row->done ? ' class="bg-success"' : '' ?>>
		<td><?= $row->name ?></td>
		<td><?= $row->email ? '<a href="mailto:'.$row->email.'">'.$row->email.'</a>' : '' ?></td>
		<td><?= nl2br($row->text) ?></td>
		<td><?= $row->image ? '<img src="'.$row->image.'" class="img-responsive" alt="">' : '' ?></td>
		<td><?= $row->done ? 'выполнена' : 'в ожидании' ?></td>
		<?php if(User::authed()){ ?>
		<td>
			<a href="index.php?action=edit&id=<?= $row->id ?>" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i> Редактировать</a>
		</td>
		<?php } ?>
	</tr>
	<?php } ?>
	</tbody>
</table>
<?php if($pagesCount>1){ ?>
<nav aria-label="Page navigation">
	<ul class="pagination">
		<?php
		for($p=0; $p<$pagesCount; $p++){
			echo '<li'.($p==$page ? ' class="active"': '').'><a href="/?page='.$p.'">'.($p+1).'</a></li>';
		}
		?>
	</ul>
</nav>
<?php } ?>
<p><a href="/index.php?action=new" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Новая задача</a></p>

