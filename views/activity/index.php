<?php

/**
 * @var $this yii\web\View
 * @var $provider ActiveDataProvider
 */

use app\models\Activity;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$columns = [
	[
		'label' => 'Порядковый номер',
		'value' => function (Activity $model) {
			return "# {$model->id}";
		},
	],
	'title',
	'date_start:datetime',
	[
		'label' => 'Имя создателя',
		'attribute' => 'user_id',
		'value' => function (Activity $model) {
			return $model->user->username;
		}
	],
	'repeat:boolean',
	'blocked:boolean',
	'created_at:date',
];

if (Yii::$app->user->can('user')) {
	$columns[] = [
		'class' => ActionColumn::class,
		'header' => 'Операции',
	];
}

?>

<div class="row">
	<h1>Список событий</h1>

	<div class="form-group pull-right">
		<?= Html::a('Создать', ['activity/update'], ['class' => 'btn btn-success pull-right']) ?>
	</div>
</div>

<?= GridView::widget([
	'dataProvider' => $provider,
	'columns' => $columns,
]) ?>