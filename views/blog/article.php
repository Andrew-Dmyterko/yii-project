<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

//  Формируем view просмотра статьи

//foreach($article as $row)


<!--    col-md-offset-3 <div class="jumbotron col-lg-10" style="margin-right: auto; margin-left: auto;"> -->
<!--    <div class="jumbotron jumbotron-fluid">-->
    <div class="jumbotron " style="margin-right: auto; margin-left: auto; padding-right: 10px; white-space: normal;">
        <img src="/images/<?= $article->image ?>" alt="" class="pull-right" style="margin-right: 0px; padding-left: 10px; padding-right: 0px">
<!--        <div class="container">-->
        <label for="ArticleFullText">Название фильма:</label>
        <p class="lead" ><em><b><?= $article['title'] ?></b></em></p>
<!--        <h3 class="display-3" style="white-space: normal;"><u><pre>--><?//= $article['title'] ?><!--</pre></u></h3>-->

            <hr class="my-8">
        <label for="ArticleFullText">Сюжет:</label>
            <p class="lead" ><em><?= $article['small_text'] ?></em></p>
        <div class="form-group">
            <label for="ArticleFullText">Полная информация о фильме:</label>
            <textarea name="article_full_text" class="form-control"  id="ArticleFullText" rows="10" readonly><?= $article['full_text'] ?></textarea>
        </div>
            <a class="btn btn-primary" href="<?=$url = Url::to(['blog/articles']); ?>" target="_self" role="button" >Вернуться на главную</a>
            <a class="btn btn-primary" href="<?=$url = Yii::$app->request->referrer; ?>" target="_self" role="button" >Вернуться назад</a>

<!--        <script src='/js/autosize.js'></script>-->
<!---->
<!--        <script>-->
<!--            autosize(document.querySelectorAll('textarea'));-->
<!--        </script>-->


    <?php    if(!(Yii::$app->user->isGuest)) : ?>
         <a class="btn btn-primary" href="<?=$url = Url::to(['blog/edit', 'id' => $article['id']]);?>" target="_self" role="button" >Изменить статью</a>


        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
            Удалить статью
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLongTitle"><font color="red"><b><u>Удаление!!!</u></b></font></h5>
                    </div>
                    <div class="modal-body">
                        Подтвердите удаление статьи?<br>
                        <b>"<?= $article['title'] ?>"</b>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <a href="<?=$url = Url::to(['blog/delete', 'id' => $article['id']]);?>"> <button type="button"  class="btn btn-primary">Удаление!!!</button></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
            <hr class="my-8">
            <p style="font-size: 12px;">Дата и время обновления - <?= date("d-m-Y H:i:s", strtotime($article['date-time_update'])); ?>
                 <br>Дата и время создания - <?= date("d-m-Y H:i:s", strtotime($article['date-time_create'])); ?>
                 <br>Автор статьи - <b><em><?= $article['author']; ?></em></b>
            </p>
<!--        </div>-->
        <!-- Bootstrap 3 -->
        <?php
        if (!empty($pictures)) : ?>
            <label for="carousel">Кадры из фильма:</label>
            <div id="carousel" class="carousel slide" data-ride="carousel" data-interval="3000" align="center" style="box-sizing: content-box; height: 140px" >
                <div class="carousel-inner">
                    <?php foreach ($pictures as $id => $picture) : ?>
                        <div class="item <?= ($id == 0) ? "active" : "" ?> ">
                            <img class="img-responsive" src="/images/<?=$picture->imagename?>" width="200" height="98" alt="" >
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="jumbotron " style="margin-right: auto; margin-left: auto; padding-right: 10px; white-space: normal;">
        <div class="row justify-left-left">
            <form class="col-sm-12" id="main-form" name="formComment" method="post" enctype="multipart/form-data" action="<?=Url::base(true).Url::to(['blog/comment', 'id' => $article->id]); ?>;?>">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
                <div class="form-group">
                    <label for="commText">Ваш комментарий</label>
                    <textarea name="comment_small_text" class="form-control textarea" id="commText" rows="2">Текст коментария</textarea>
                </div>
                <div class="form-group">
                    <label for="author">Автор</label>
                    <input name="comment_author" class="form-control textarea" id="author" value="<?= !(Yii::$app->user->isGuest) ? Yii::$app->user->identity->username : "Гость" ?>">
                </div>
                <div class="row">
                    <div class="form-group">
                        <a class="btn btn-primary" href="<?=$url = Url::to(['blog/articles']); ?>" target="_self" role="button" >Вернуться на главную</a>
                        <button type="submit" class="btn btn-primary">Написать коментарий</button>
                    </div>
                </div>
            </form>
        <!-- тут комменты -->
            <p><a name="comment"></a></p>
            <?php foreach ($comments as $id => $comment) : ?>
                <div class="alert alert-primary   col-md-3-push" style="background-color: rgba(168,171,174,0.99); margin-right: auto; margin-left: auto; margin-top 0px; margin-bottom: 0px; padding-top: 0px; padding-bottom: 0px; ">
                    <h6>
                        <span><b><?=$comment->commentdaytime?></b><br><b><?=$comment->autor?>:</b> <br><?=$comment->text?></span>
                    </h6>
                </div>
            <?php endforeach; ?>
        </div>
    </div>



<script src='/js/autosize.js'></script>

<script>
    autosize(document.querySelectorAll('textarea'));
</script>

