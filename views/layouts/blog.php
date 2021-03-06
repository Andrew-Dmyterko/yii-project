<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Подключаем фансибокс -->
    <link  href="/js/fancybox356/jquery.fancybox.min.css" rel="stylesheet">


    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<!-- Применяем свои стили    -->
<body style="    /*height: 100%;*/
    background-size: cover;
    /*background: #8a6d3b;*/
    background-image: url('/images/6.jpg');
    background-attachment:fixed;">

<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->nameBlog,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
//            (!(Yii::$app->user->isGuest) && (Yii::$app->controller->action->id === 'edit' ||
//                                            Yii::$app->controller->action->id === 'add')) ? (
//            ['label' => 'Посмотреть картинки', 'url' => ['/blog/add_pics']]
//            ) : (""
//            ),
//            !(Yii::$app->user->isGuest) ? (
//            ['label' => 'Загрузить картинки', 'url' => ['/blog/add_pics']]
//            ) : (""
//            ),
            !(Yii::$app->user->isGuest) ? (
            ['label' => 'Новая статья', 'url' => ['/blog/add']]
            ) : (""
            ),
            ['label' => "Кинннориум", 'url' => ['/blog/articles']],
//            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Связаться с нами', 'url' => ['/blog/contact']],
            ['label' => 'О нас', 'url' => ['/blog/about']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>


    <div class="container"">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>

    </div>

</div>
<!---->
<!--<footer class="footer">-->
<!--    <div class="container">-->
<!--        <p class="pull-left">&copy; My Company --><?//= date('Y') ?><!--</p>-->
<!---->
<!--        <p class="pull-right">--><?//= Yii::powered() ?><!--</p>-->
<!--    </div>-->
<!--</footer>-->
<?php $this->endBody() ?>

<!--<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js"></script>-->
<script src="/js/fancybox356/jquery.fancybox.min.js"></script>

<!--<script type="text/javascript">-->
<!--    $(document).ready(function() {-->
<!--        $("a.fancyimage").fancybox();-->
<!--    });-->
<!--</script>-->
</body>
</html>
<?php $this->endPage() ?>
