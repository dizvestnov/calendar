<?php

/* @var $this View */

/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);

$navigationItems = [
    ['label' => 'Главная', 'url' => ['/site/index']],
];

if (Yii::$app->user->can('manager')) {
    $navigationItems[] = ['label' => 'Пользователи', 'url' => ['/user/index']];
}

if (Yii::$app->user->isGuest) {
    $navigationItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
} else {
    $navigationItems[] = ['label' => 'Календарь', 'url' => ['/calendar/index']];
    $navigationItems[] = ['label' => 'События', 'url' => ['/activity/index']];
    $navigationItems[] = ['label' => 'Профиль', 'url' => ['/user/profile']];
    $navigationItems[] = ['label' => 'Выйти', 'url' => ['/site/logout']];
}

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
</head>

<body>

<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $navigationItems,
    ]);

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= Alert::widget() ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>

</body>
</html>

<?php $this->endPage() ?>
