<?php

/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */


/**
 * @var \yii\web\View $this
 * @var \app\models\forms\UpdateUserForm $model
 * @var \yii\data\ActiveDataProvider $provider
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>

<h1>Профиль</h1>

<div class="user-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'repeatPassword')->passwordInput() ?>

	<div class="form-group">
		<?= Html::submitButton('Применить', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>


<?= $this->render('/activity/index', compact('provider')) ?>