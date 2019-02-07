<?php

use yii\helpers\Html;
use yii\helpers\Url;

// Формируем view для редактирования статьи

//foreach($articles as $article) {
 ?>
<!--    col-md-offset-3 <div class="jumbotron col-lg-10" style="margin-right: auto; margin-left: auto;"> -->
<!--    <div class="jumbotron jumbotron-fluid">-->
    <!-- форма изменения статьи -->
    <div class="jumbotron" style="margin-right: auto; margin-left: auto;">
        <img src="<?= $url = Url::to(['/images/'])."/".$article->image ?>" alt="" width="10%" height="10%" class="pull-left">
        <a name="edit_article"></a>
        <h3 class="display-6"><em style="padding-left: 10px"> Пожалуйста внесите изменения в статью и нажмите <u>"Внести изменения"<?= Html::csrfMetaTags() ?></u></em></h3>
<!--        <br> <?//=Url::to();?> -->
<!--        <br> <?//=Yii::$app->request->pathInfo?> -->
<!--        <br> <?//=Yii::$app->controller->route; ?>-->
<!--        <br> <?//=Yii::$app->controller->module->id; ?>-->
<!--        <br> <?//=Yii::$app->controller->id; ?>-->
<!--        <br> <?//=Yii::$app->controller->action->id; ?>-->

        <div class="row justify-left-left">
            <form class="col-sm-12" id="main-form" name="form" method="post" enctype="multipart/form-data" action="<?=Url::base(true). Url::to(['blog/edit', 'id' => $article['id']]);?>">
                <div class="form-group">
<!--                    action формы    <?//= Url::base(true). Url::to(['blog/edit', 'id' => $article['id']]);?>-->
                    <label for="ArticleTitle">Заголовок статьи </label>
                    <textarea name="title" type="text" class="form-control" id="ArticleTitle" rows="2" ><?= $article['title'] ?></textarea>
                </div>
                <input type="hidden" name="_csrf"
                       value="<?=Yii::$app->request->getCsrfToken()?>" />
                <div class="form-group">
                    <label for="ArticleSmallText">Краткое содержание статьи</label>
                    <textarea name="article_small_text" class="form-control" id="ArticleSmallText" rows="2"><?= $article['small_text'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="ArticleFullText">Полный текст статьи</label>
                    <textarea name="article_full_text" class="form-control"  id="ArticleFullText" rows="10"><?= $article['full_text'] ?></textarea>
                    <!--                <input name="Article_Full_Text" type="password" class="form-control" id="FullText" placeholder="Confirm Your Password" required>-->
                </div>
                <div class="form-group">
                    <input name="userfile" class="form-control-file btn btn-primary" type="file" role="button"><br>
                <a class="btn btn-primary" href="<?=$url = Url::to(['blog/articles']); ?>" target="_self" role="button" >Вернуться на главную</a>
                <button type="submit" class="btn btn-primary">Внести изменения</button>
                </div>
<!--                    <label class="form-check-label" for="exampleCheck1">Статья активна</label> -->
<!--                    <input name="isActive" type="checkbox" class="form-check-input" id="exampleCheck1">-->
<!--                <input name="isActive" type="checkbox" class="form-control" --><?//=  ($row['is_active']===1) ?  "checked" : ""; ?><!-- > Статья активна-->
<!--                </div>-->
                <!--            <a href="https://www.facebook.com/v3.2/dialog/oauth?client_id={{ID}}&redirect_uri={{URL}}&response_type=code&scope=public_profile,email,user_location">Войти через FB</a>-->
            </form>
        </div>
    </div>

    
<!--    --><?php
//}
//
//?>
