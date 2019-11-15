<?php

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model app\models\forms\LoginForm
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Вход на сайт';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-login">
    <div class="text-center" style="padding: 20px 0 70px 0;">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Для входа на сайт пройдите аутентификацию</p>

        <?= Html::a('Нет аккаунта?', ['site/signup'], ['class' => 'btn btn-lg btn-success', 'style' => 'margin-top: 30px']) ?>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        Логины: <strong>admin, manager, user</strong> <br>
        Пароль: <strong>123123123</strong>.
    </div>
</div>
