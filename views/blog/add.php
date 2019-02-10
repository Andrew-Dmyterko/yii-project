<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\LinkPager;
//use yii\bootstrap\Nav;
//use yii\bootstrap\NavBar;
//use yii\widgets\Breadcrumbs;

// Формируем view для редактирования статьи

//foreach($articles as $article) {
 ?>
<style>
    .textarea {
        background-color: lightgrey
    }
</style>
<!--    col-md-offset-3 <div class="jumbotron col-lg-10" style="margin-right: auto; margin-left: auto;"> -->
<!--    <div class="jumbotron jumbotron-fluid">-->
    <!-- форма изменения статьи -->
    <div class="jumbotron" style="margin-right: auto; margin-left: auto; background-color: rgba(168,171,174,0.99)">
        <a name="edit_article"></a>
        <p class="lead"><em>Пожалуйста введите данные в статью и нажмите <u>"Создать статью"</u></em></p>
        <div class="row justify-left-left">
            <form class="col-sm-12" id="main-form" name="form" method="post" enctype="multipart/form-data" action="<?=Url::base(true). Url::to(['blog/add']);?>">
                <div class="form-group">
                    <label for="ArticleTitle">Заголовок статьи</label>
                    <textarea name="title" type="text" class="form-control textarea" id="ArticleTitle" rows="2" >Название по умолчанию.</textarea>
                </div>
                <input type="hidden" name="_csrf"
                       value="<?=Yii::$app->request->getCsrfToken()?>" />
                <div class="form-group">
                    <label for="ArticleSmallText">Краткое содержание статьи</label>
                    <textarea name="article_small_text" class="form-control textarea" id="ArticleSmallText" rows="2">Краткий сюжет по умолчанию.</textarea>
                </div>
                <div class="form-group">
                    <label for="ArticleFullText">Полный текст статьи</label>
                    <textarea name="article_full_text" class="form-control textarea"  id="ArticleFullText" rows="10">Полная информация по умолчанию.</textarea>
                </div>
                <div class="form-group">
                    <label for="ArticleSmallText">Автор</label>
                    <textarea name="article_author" class="form-control textarea" id="ArticleSmallText" rows="1"><?=Yii::$app->user->identity->username?></textarea>
                </div>
                <div class="row">
                   <div class="form-group">
                       <label for="file">Картинка для центральной страницы</label>
                      <input id="file" name="userfile" class="form-control-file btn btn-primary" type="file" role="button"><br>
                       <label for="files">Картинки к фильму</label>
                      <input id="files" name="userfile[]" class="form-control-file btn btn-primary" type="file" role="button" multiple><br>
                       <a class="btn btn-primary" href="<?=$url = Url::to(['blog/articles']); ?>" target="_self" role="button" >Вернуться на главную</a>
                       <button type="submit" class="btn btn-primary">Создать статью</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script src='/js/autosize.js'></script>

<script>
    autosize(document.querySelectorAll('textarea'));
</script>
<!--    --><?php
//}
//
//?>
