<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;


//  Формируем view просмотра статьи

//foreach($article as $row)
{ ?>
<!--    col-md-offset-3 <div class="jumbotron col-lg-10" style="margin-right: auto; margin-left: auto;"> -->
<!--    <div class="jumbotron jumbotron-fluid">-->
    <div class="jumbotron " style="margin-right: auto; margin-left: auto;">
        <img src="/images/<?= $article->image ?>" alt="" class="pull-right">
<!--        <div class="container">-->
            <h3 class="display-6"><u><?= $article['title'] ?></u></h3>

            <hr class="my-8">
            <p class="lead"><em><?= $article['small_text'] ?></em></p>
            <hr class="my-8">
            <p class="lead"><em><?= $article['full_text'] ?></em></p>
            <a class="btn btn-primary" href="<?=$url = Url::to(['blog/articles']); ?>" target="_self" role="button" >Вернуться на главную</a>
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
<!--                        <b>"--><?//= $article['title'] ?><!--"</b>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
<!--                        <a href="--><?//=$url = Url::to(['blog/delete', 'id' => $article['id']]);?><!--"> <button type="button"  class="btn btn-primary">Удаление!!!</button></a>-->
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
    </div>
    <?php
}

?>