<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

// Формируем главную страницу
?>

<!--<div class="" style="margin-right: auto; margin-left: auto;">-->

    <!--Типа мой хедер-->
    <div class="alert alert-primary " style="background-color: #e9ecef; margin-right: auto; margin-left: auto; padding-top: 9px; padding-left: 60px; ">
        <img src="/images/blog.png" alt="" class="pull-right" width="70" height="70" style="margin-top:0px; margin-bottom: 0px;  ">
        <header>
            <h2>
                <span><b><u>CMS блога на MVC паттерне и ООП <?= !(Yii::$app->user->isGuest) ? Yii::$app->user->identity->username :"" ?></u></b>
                </span>
            </h2>
        </header>
    </div>

<?php foreach($articles as $article) : ?>

    <div class="jumbotron " style="margin-right: auto;  margin-left: auto; padding-top: 15px; padding-bottom: 5px; margin-top:5px; margin-bottom: 12px; ">
        <img src="<?= $url = Url::to(['/images/'])."/".$article->image ?>" alt="" class="pull-right" width="152" height="220">
        <h3  class="display-6"><u><?= $article['title'] ?></u></h3>
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
        <p style="font-size: 12px;">Дата и время обновления - <?= date("d-m-Y H:i:s", strtotime($article['date-time_update'])); ?>
            <br>Дата и время создания - <?= date("d-m-Y H:i:s", strtotime($article['date-time_create'])); ?>
            <br>Автор статьи - <b><em><?= $article['author']; ?></em></b>
        </p>
    </div>

    <?php endforeach; ?>

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
    <div class="alert alert-primary" style="background-color: #e9ecef; margin-right: auto; margin-left: auto; ">
        <h6>
            <span><b>-- by sky_fox</b> <br>  e-mail: andrew.dmyterko@gmail.com; sky_fox123@ukr.net</span>
        </h6>
    </div>
<!--</div>-->
