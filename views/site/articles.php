<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;


// Формируем главную страницу
?>
<!--Типа мой хедер-->
<div class="alert alert-primary col-xs-110 col-sm-10 col-md-10 col-lg-10" style="background-color: #e9ecef; margin-right: auto; margin-left: auto;">
    <header>
        <h2>
            <span><b><u>CMS блога на MVC паттерне и ООП</u></b></span>
        </h2>
    </header>
</div>

<?php foreach($articles as $article) : ?>

    <div class="jumbotron col-xs-110 col-sm-10 col-md-10 col-lg-10" style="margin-right: auto; margin-left: auto;">
        <h1 class="display-6"><u><?= $article['title'] ?></u></h1>
        <p class="lead"><em><?= $article['small_text'] ?></em></p>
        <a class="btn btn-primary" href="<?= $url = Url::to(['site/article', 'id' => $article->id]); ?>" target="_self" role="button" >Подробнее...</a>
        <a class="btn btn-primary" href="http://localhost/Article/edit/<?= $article['id'] ?>" target="_self" role="button" >Изменить статью</a>
        <hr class="my-8">
        <p>Дата и время создания - <?= date("d-m-Y H:i:s", strtotime($article['date-time_create'])); ?>
            <br>Автор статьи - <b><em><?= $article['author']; ?></em></b>
        </p>

    </div>

<?php endforeach; ?>


<!--Типа мой футер-->
<div class="alert alert-primary col-xs-110 col-sm-10 col-md-10 col-lg-10" style="background-color: #e9ecef; margin-right: auto; margin-left: auto;">
    <h6>
        <span><b>-- by sky_fox</b>  <br>  e-mail: andrew.dmyterko@gmail.com; sky_fox123@ukr.net</span>
    </h6>

    <!--               !!!!!!!!!!!потом перенести кнопку на навигатион бар-->
    <!--                <a class="btn btn-primary" href="http://--><?//=SITE_NAME?><!--/Article/insert/" target="_self" role="button" >Новая статья</a>-->
</div>

