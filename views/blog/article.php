<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
// стартуем сессию
$session = Yii::$app->session;
if (!$session->isActive) {
    $session->open();
}
?>

//  Формируем view просмотра статьи
    <div class="jumbotron " style="margin-right: auto; margin-left: auto; padding-right: 10px; white-space: normal;">
        <img src="/images/<?= $article->image ?>" alt="" class="pull-right" style="margin-right: 0px; padding-left: 10px; padding-right: 0px">
        <label for="ArticleFullText">Название фильма:</label>
        <p class="lead" ><em><b><?= $article['title'] ?></b></em></p>

        <hr class="my-8">
        <label for="ArticleFullText">Сюжет:</label>
            <p class="lead" ><em><?= $article['small_text'] ?></em></p>
        <div class="form-group">
            <label for="ArticleFullText">Полная информация о фильме:</label>
            <textarea name="article_full_text" class="form-control"  id="ArticleFullText" rows="10" readonly><?= $article['full_text'] ?></textarea>
        </div>
            <a class="btn btn-primary" href="<?=$url = Url::to(['blog/articles']); ?>" target="_self" role="button" >Вернуться на главную</a>
<!--            <a class="btn btn-primary" href="=$url = Yii::$app->request->referrer; ?>" target="_self" role="button" >Вернуться назад</a>-->
            <a class="btn btn-primary" href="<?= $url = isset($_SESSION['HTTP_ARTICLE_REFERER']) ? $_SESSION['HTTP_ARTICLE_REFERER'] : Yii::$app->request->referrer; ?>" target="_self" role="button" >Вернуться назад</a>


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
                <b>Посетили</b> <span class="badge"><?=$article->visit?></span>
                <b>Рейтинг</b> <span class="badge"><?=$article->rating?></span>
            </p>

        <!--- fancybox -->
        <?php
        if (!empty($pictures)) : ?>
        <label for="fbox"> Кадры из фильма:</label>
        <div class="container" id="fbox">
            <div class="row">
                <?php foreach ($pictures as $id => $picture) : ?>
                    <div class="col-md-3 col-sm-4 col-xs-6 thumb">
                        <a data-fancybox="gallery" rel="group" href="/images/<?=$picture->imagename?>">
                          <img class="img-responsive" src="/images/<?=$picture->imagename?>" />
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>


    </div>
<!--    </div>-->
<!-- голосовалка -->
<?php if ($vote_show) : ?>
<form class="col-sm-5" name="form_vote" method="post" enctype="multipart/form-data" action="<?=Url::base(true).Url::to(['blog/vote', 'id' => $article->id]); ?>" style="margin-left: 60px; margin-top: 20px">
    <div class="row">
        <div class="form-group">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
            <label for="vote">Голосовалка</label>
            <input type="hidden" name="goback" value="<?=$url = isset($_SESSION['HTTP_ARTICLE_REFERER']) ? $_SESSION['HTTP_ARTICLE_REFERER'] : Yii::$app->request->referrer; ?>"
            <input id="vote" type="radio" name="vote_score" value="1" > <b> 1 </b>
            <input id="vote" type="radio" name="vote_score" value="2" > <b> 2 </b>
            <input id="vote" type="radio" name="vote_score" value="3" > <b> 3 </b>
            <input id="vote" type="radio" name="vote_score" value="4" > <b> 4 </b>
            <input id="vote" type="radio" name="vote_score" value="5" > <b> 5 </b>
            <button class="btn btn-primary" type="submit">Голосуй</button>
            <b>Рейтинг</b> <span class="badge"><?=$article->rating?></span>
        </div>
    </div>
</form>
<?php else: ?>
<div class="col-sm-5" style="margin-left: 45px; margin-top: 20px">
<b>Вы уже голосовали!!! Ждите!!! Рейтинг</b> <span class="badge"><?=$article->rating?></span>
</div>
<?php endif;?>


<!-- форма ввода коментов -->
<p><a name="comment"></a></p>
    <div class="jumbotron " style="margin-right: auto; margin-left: auto; padding-right: 10px; white-space: normal;">
        <div class="row justify-left-left">
            <form class="col-sm-12" id="main-form" name="formComment" method="post" enctype="multipart/form-data" action="<?=Url::base(true).Url::to(['blog/comment', 'id' => $article->id]); ?>">
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
                        <input type="hidden" name="goback" value="<?=$url = isset($_SESSION['HTTP_ARTICLE_REFERER']) ? $_SESSION['HTTP_ARTICLE_REFERER'] : Yii::$app->request->referrer; ?>">
                        <a class="btn btn-primary" href="<?=$url = Url::to(['blog/articles']); ?>" target="_self" role="button" >Вернуться на главную</a>
                        <a class="btn btn-primary" href="<?= $url = isset($_SESSION['HTTP_ARTICLE_REFERER']) ? $_SESSION['HTTP_ARTICLE_REFERER'] : Yii::$app->request->referrer; ?>" target="_self" role="button" >Вернуться назад</a>
                        <button type="submit" class="btn btn-primary">Написать коментарий</button>
                    </div>
                </div>
            </form>

        <!-- тут комменты -->

            <?php foreach ($comments as $id => $comment) : ?>
            <div class="container" style="margin-bottom: 5px" >
                <div class=" alert alert-primary  col-lg-3 col-sm-3 col-md-3" style="background-color: rgba(168,171,174,0.99); margin-right: auto; margin-left: auto; margin-top 0px; margin-bottom: 0px; padding-top: 0px; padding-bottom: 0px; ">
                    <h6>
                        <span><b><?=date("d-m-Y H:i:s", strtotime($comment->commentdaytime))?></b><br><b><?=$comment->autor?>:</b> <br><?=$comment->text?></span>
                    </h6>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

<script src='/js/autosize.js'></script>

<script>
    autosize(document.querySelectorAll('textarea'));
</script>

