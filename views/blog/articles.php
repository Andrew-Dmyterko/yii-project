<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\Comments;

// Формируем главную страницу
?>

<!--<div class="" style="margin-right: auto; margin-left: auto;"> #e9ecef-->
<!-- карусель -->

<?php if ($activePage  == 1 ) :?>
<!-- Bootstrap 3 -->
<div id="carousel" class="carousel slide" data-ride="carousel" data-interval="3000" align="center" style="box-sizing: content-box; height: 140px">
    <div class="carousel-inner">
        <div class="item active">
            <div class="alert alert-primary " style="background-color: rgba(168,171,174,0.99); margin-bottom: 0px; margin-top: 0px; margin-right: auto; margin-left: auto; padding-top: 15px;  padding-left: 60px; ">
                <img src="/images/blog.png" alt="" class="pull-right" width="70" height="70">
                <header>
                    <h2>
                <span><b><u>Кинннориум. <br>Новости кино. Все о новинках киноиндустрии.</u></b>
                </span>
                    </h2>
                </header>
            </div>
        </div>
        <div class="item ">
            <img src="/images/star_trek_discovery_season_2_title_card.1.png" alt="start-trek">
        </div>
        <div class="item">
            <img src="/images/netflix.jpg" alt="netflix">
        </div>
        <div class="item">
            <img src="/images/wallpapers-movies-018.jpg" alt="captain-america">
        </div>
        <div class="item">
            <img src="/images/filmz.ru_f_126532.jpg" alt="iron-man">
        </div>

    </div>
</div>
<script>
    $('.carousel').carousel();
</script>

<?php endif; ?>

<?php foreach($articles as $article) : ?>

    <div class="jumbotron " style="background-color: rgba(168,171,174,0.99); margin-right: auto;  margin-left: auto; padding-top: 15px; padding-bottom: 5px; margin-top:5px; margin-bottom: 12px; ">
        <img src="<?= $url = Url::to(['/images/'])."/".$article->image ?>" alt="" class="pull-right" width="152" height="220">
        <h3  class="display-6"><u><b><?= $article['title'] ?></b></u></h3>
        <p class="lead"><em><?= $article['small_text'] ?></em></p>
        <a class="btn btn-primary" href="<?= $url = Url::to(['blog/article', 'id' => $article->id]); ?>" target="_self" role="button" >Подробнее...</a>
        <?php if(!(Yii::$app->user->isGuest)) : ?>
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
        <p style="font-size: 12px;">
<!--            <br>Дата и время создания - --><?//= date("d-m-Y H:i:s", strtotime($article['date-time_create'])); ?>
            Автор статьи - <b><em><?= $article['author']; ?></em></b> Дата и время изменения - <?= date("d-m-Y H:i:s", strtotime($article['date-time_update'])); ?> <a href="<?= $url = Url::to(['blog/article', 'id' => $article->id])."#comment"; ?>"><b>Messages</b> <span class="badge"><?=Comments::find()->where(['articleid' => $article['id']])->count();?></span></a>

        </p>
    </div>

    <?php endforeach; ?>

<style>
    .text-center a {
        background-color: lightgrey !important;
    }
</style>
<!-- Pagination -->
    <div class="text-center" style="margin-top:0px; margin-bottom: 0px; ">
        <?= LinkPager::widget([
                'pagination' => $pagination,
                'firstPageLabel' => 'Start',
                'lastPageLabel' => 'End',
            ]); ?>
<!--            <div class="alert alert-info">-->
<!--                Page --><?//= $activePage ?><!-- from --><?//= $countPages ?>
<!--            </div>-->
    </div>

    <!--Типа мой футер-->
    <div class="alert alert-primary" style="background-color: rgba(168,171,174,0.99); margin-right: auto; margin-left: auto; ">
        <h6>
            <span><b>-- by sky_fox</b> <br>  e-mail: andrew.dmyterko@gmail.com; sky_fox123@ukr.net</span>
        </h6>
    </div>
<!--</div>-->
