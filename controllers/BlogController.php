<?php

namespace app\controllers;

use app\models\Article_pic;
use app\models\Comments;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Articles;
use yii\helpers\Url;
use yii\data\Pagination;



class BlogController extends Controller
{
    /**
     * Мои акшины для блога
     */


    public $layout = 'blog';
    public const VOTE_TIME = 1800;

    /**
     * Выводим все статьи
     *
     */
    public function actionArticles()
    {
        $query = Articles::find();
        $allArticlesCount = $query->count();

        $pagination = new Pagination([
            'totalCount' => $allArticlesCount,
            'pageSize' => 2,
            'pageSizeParam' => false,
            'forcePageParam' => false
        ]);
        $articles = $query->orderBy(['date-time_update' => SORT_DESC, 'date_create' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $this->layout = 'blog';
        $this->view->title = 'Кинннориум!!! Все о новинках киноиндустрии.';
        return $this->render(
            'articles',
            [
                'articles' => $articles,
                'allArticlesCount' => $allArticlesCount,
                'activePage' => Yii::$app->request->get('page', 1),
                'countPages' => $pagination->getPageCount(),
                'pagination' => $pagination
            ]);
    }

    /**
     * Выводим одну статью
     *
     */
    public function actionArticle()
    {
        // стартуем сессию
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

//        echo Url::base(true).Yii::$app->request->getUrl();
//        echo "br";
//        echo $_SERVER['HTTP_REFERER']; die;

//        if (!(Url::base(true).Yii::$app->request->getUrl()=='www.yii2.my/site/login')
//            || !(Url::base(true).Yii::$app->request->getUrl()=='www.yii2.my/site/login')) {

        if (!(Url::base(true).Yii::$app->request->getUrl()==$_SERVER['HTTP_REFERER'])) {
            $session->set('HTTP_ARTICLE_REFERER', $_SERVER['HTTP_REFERER']);
        }
//        }

        $articleId = Yii::$app->request->get('id');
        $article = Articles::find()->where(['id' => $articleId])->one();
        $pictures = Article_pic::find()->where(['articleid' => $articleId])->all();

        $comments = Comments::find()->where(['articleid' => $articleId])->orderBy(['commentdaytime' => SORT_DESC])->all();

        if (!(bool)$article) {
            $url = Url::to(['blog/articles']);
            return $this->redirect($url, 302);

        }

        // при заходе на страничку увеличиваем посещаемость
        // если не решрешим страничку (шоб не неакручивали рейтинг)
//        if (!(Url::base(true).Yii::$app->request->getUrl()==$_SERVER['HTTP_REFERER'])) {
        if (!(Url::base(true).Url::previous()==Url::base(true).Yii::$app->request->getUrl())) {

            Url::remember();

//            echo "555", Url::base(true).Url::previous(), "<br>";
//
//            echo Url::base(true).Yii::$app->request->getUrl();
//            echo "<br>";
//
//            echo $_SERVER['HTTP_REFERER'];
//
//            die;


            $article->visit++;
            $article->save();

        }

        $vote_show = false;

        // проверяем голосовали ли за статью
        if (isset($session["vote.$articleId"]) && (time()-(isset($session["vote.$articleId"]) ? $session["vote.$articleId"] : 0))>= self::VOTE_TIME) {

            $session->remove("vote.$articleId");
            $vote_show = true;

        } elseif (!(isset($session["vote.$articleId"]))){
            $vote_show = true;
        }


        $this->layout = 'blog';
        $this->view->title = $article->title;

        return $this->render(
            'article',
            [
                'article' => $article,
                'pictures' => $pictures,
                'comments' => $comments,
                'vote_show' => $vote_show
            ]
        );
    }

    /**
     * Добавляем коментарий
     *
     */
    public function actionComment()
    {
        // стартуем сессию
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        date_default_timezone_set("Europe/Kiev");
        // получаем post параметры
        $post = Yii::$app->request->post();

        $articleId = Yii::$app->request->get('id');

        $comment = new Comments;

        // Если все хорошо готовимся писать в базу
        $comment->articleid = $articleId;
        $comment->text = $post['comment_small_text'];
        $comment->autor = $post['comment_author'];
        $today = date("Y-m-d");
        $todayDayTime = date("Y-m-d H:i:s");
        $comment->commentdaytime = $todayDayTime;
//        $article['date-time_update'] = $todayDayTime;

        $comment->save();

//        echo $articleId;
//        die;


        $url = Url::to(['blog/article', 'id' => $articleId])."#comment";

        return $this->redirect($url, 302);

    }

    /**
     * Добавляем голcовалку и высчитываем рейтинг
     *
     */
    public function actionVote()
    {
        // стартуем сессию
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        date_default_timezone_set("Europe/Kiev");
        // получаем post параметры
        $post = Yii::$app->request->post();

        $articleId = Yii::$app->request->get('id');

        $article = Articles::find()->where(['id' => $articleId])->one();

        if (isset($post['vote_score'])) {

            $article->voted++;
            $article->score = $article->score + (int) $post['vote_score'];
            $article->rating = round(($article->score)/($article->voted),2);

            $article->save();
            $session->set("vote.$articleId", time());

        }

        $this->layout = 'blog';
//        $url = Url::to(['blog/article', 'id' => $articleId])."#comment";
        $url = $post['goback'];
        return $this->redirect($url, 302);

    }


    /**
     * Редактируем одну статью
     *
     */
    public function actionEdit()
    {
        date_default_timezone_set("Europe/Kiev");
        // проверяем шоб не гость
        if (!(Yii::$app->user->isGuest)) {

            // получаем post параметры
            $post = Yii::$app->request->post();


            $articleId = Yii::$app->request->get('id');

            $article = Articles::find()->where(['id' => $articleId])->one();


            if (!(bool)$article) {
                $url = Url::to(['blog/articles']);
                $this->redirect($url, 302);
                return;
            }

            // загрузку файла надо переделать в виде статического метода
            if (!empty($_FILES['userfile']['name'])){

                $uploaddir = 'images/'; //  /var/www/html/yii-project/web/
                $uploadfile = $uploaddir . $articleId."-main-".basename($_FILES['userfile']['name']);

                try {
                    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
                    Yii::$app->session->setFlash('success', "Файл ". $_FILES['userfile']['name']. "  корректен и был успешно загружен.");
//                        echo "Файл корректен и был успешно загружен.";
//                    Если все хорошо готовимся писать в базу
                    $article->image = $articleId."-main-".$_FILES['userfile']['name'];
                } catch (\Throwable $exception) {
                    Yii::$app->session->setFlash('error ', "Возможная атака с помощью файловой загрузки! ".$exception->getMessage());
//                        echo getAlert("Возможная атака с помощью файловой загрузки!");
                    $url = Url::to(['blog/articles']);
                    $this->redirect($url, 302);
                    return;
                }
            }

            // запись файлов в article_pic
            // загрузку файла надо переделать в виде статического метода
            if (!empty($_FILES['userfiles']['name'][0])){

//                $pic_dell = Article_pic::deleteAll('articleid = :id',[':id' => $articleId]);
                $pic_dell = Article_pic::deleteAll(['articleid' => $articleId]);
//                $pic_dell = Yii::$app->db->createCommand("delete from article_pic where id = $articleId")->execute();


                foreach ($_FILES['userfiles']['name'] as $id => $val) {

                    $uploaddir = 'images/'; //  /var/www/html/yii-project/web/
                    $uploadfile = $uploaddir . $articleId."-fbox-".basename($_FILES['userfiles']['name'][$id]);

                    try {
                        move_uploaded_file($_FILES['userfiles']['tmp_name'][$id], $uploadfile);
                        Yii::$app->session->setFlash('success', "Файл " . $_FILES['userfiles']['name'][$id] . "  корректен и был успешно загружен.");
//                        echo "Файл корректен и был успешно загружен.";

                        $pic = new Article_pic();

//                    Если все хорошо готовимся писать в базу
                        $pic->articleid = $article->id;
                        $pic->imagename = $articleId."-fbox-".$_FILES['userfiles']['name'][$id];
                        $pic->save();
                        unset ($pic);
                    } catch (\Throwable $exception) {
                        Yii::$app->session->setFlash('error ', "Возможная атака с помощью файловой загрузки! " . $exception->getMessage());
//                        echo getAlert("Возможная атака с помощью файловой загрузки!");
                        $url = Url::to(['blog/articles']);
                        $this->redirect($url, 302);
                        return;
                    }
                }
            }

            if (isset ($post['title'])) {
//            $article->id = $get['id'];
                $article->title = $post['title'];
                $article->small_text = $post['article_small_text'];
                $article->full_text = $post['article_full_text'];
                $today = date("Y-m-d");
                $todayDayTime = date("Y-m-d H:i:s");
                $article['date-time_update'] = $todayDayTime;

                $article->save();

                $url = Url::to(['blog/articles']);
                $this->redirect($url, 302);
            }

            $this->layout = 'blog';
            $this->view->title = $article->title;

            return $this->render(
                'edit',
                [
                    'article' => $article
                ]
            );
        } else {
            $url = Url::to(['blog/articles']);
            $this->redirect($url, 302);
        }
    }

    /**
     * Добавляем новую статью
     *
     */
    public function actionAdd()
    {
        date_default_timezone_set("Europe/Kiev");
        // проверяем шоб не гость
        if (!(Yii::$app->user->isGuest)) {

            $this->layout = 'blog';

            $username = (Yii::$app->user->identity->username);

            // получаем post параметры
            $post = Yii::$app->request->post();


            if (isset ($post['title']) || isset ($post['article_small_text']) || isset ($post['article_full_text'])) {

                $article = new Articles();

                $article->title = $post['title'];
                $article->small_text = $post['article_small_text'];
                $article->full_text = $post['article_full_text'];
                $article->author = !(Empty($post['article_author'])) ? $post['article_author'] : $username;
                $today = date("Y-m-d");
                $todayDayTime = date("Y-m-d H:i:s");
                $article['date-time_create'] = $todayDayTime;
                $article['date-time_update'] = $todayDayTime;
                $article->date_create = $today;


                $article->save();

                if (!empty($_FILES['userfile']['name'])){

                    $uploaddir = 'images/'; //  /var/www/html/yii-project/web/
                    $uploadfile = $uploaddir . $article->id."-main-".basename($_FILES['userfile']['name']);

                    try {
                        move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
                        Yii::$app->session->setFlash('success', "Файл ". $_FILES['userfile']['name']. "  корректен и был успешно загружен.");
//                        echo "Файл корректен и был успешно загружен.";
                    } catch (\Throwable $exception) {
                        Yii::$app->session->setFlash('error ', "Возможная атака с помощью файловой загрузки! ".$exception->getMessage());
//                        echo getAlert("Возможная атака с помощью файловой загрузки!");
                        $url = Url::to(['blog/articles']);
                        $this->redirect($url, 302);
                        return;
                    }
                }
                $article->image = $article->id."-main-".$_FILES['userfile']['name'];
                $article->save();

                // запись файлов в article_pic
                // загрузку файла надо переделать в виде статического метода
                if (!empty($_FILES['userfiles']['name'][0])){

                    foreach ($_FILES['userfiles']['name'] as $id => $val) {

                        $uploaddir = 'images/'; //  /var/www/html/yii-project/web/
                        $uploadfile = $uploaddir .$article->id."-fbox-". basename($_FILES['userfiles']['name'][$id]);

                        try {
                            move_uploaded_file($_FILES['userfiles']['tmp_name'][$id], $uploadfile);
                            Yii::$app->session->setFlash('success', "Файл " . $_FILES['userfiles']['name'][$id] . "  корректен и был успешно загружен.");
//                        echo "Файл корректен и был успешно загружен.";

                            $pic = new Article_pic();

//                    Если все хорошо готовимся писать в базу
                            $pic->articleid = $article->id;
                            $pic->imagename = $article->id."-fbox-". $_FILES['userfiles']['name'][$id];
                            $pic->save();
                            unset ($pic);
                        } catch (\Throwable $exception) {
                            Yii::$app->session->setFlash('error ', "Возможная атака с помощью файловой загрузки! " . $exception->getMessage());
//                        echo getAlert("Возможная атака с помощью файловой загрузки!");
                            $url = Url::to(['blog/articles']);
                            $this->redirect($url, 302);
                            return;
                        }
                    }
                }





                Yii::$app->session->setFlash('success', "Статья $article->title успешно создана!!");

                $url = Url::to(['blog/articles']);
                $this->redirect($url, 302);
            } else {

                $this->view->title = "Новая статья";

                return $this->render(
                    'add'
                );
            }
        } else {
            $url = Url::to(['blog/articles']);
            $this->redirect($url, 302);
        }
    }

    /**
     * удаляем статью
     *
     */
    public function actionDelete()
    {
        // проверяем шоб не гость
        if (!(Yii::$app->user->isGuest)) {
            $articleId = Yii::$app->request->get('id', 1);
            $article = Articles::find()->where(['id' => $articleId])->one();
            if ($article) {
                try {
                    $title = $article->title;
                    $article->delete();
                    Yii::$app->session->setFlash('success', "Article '" . $title . "' was removed!");
                } catch (\Throwable $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            }

            $url = Url::to(['blog/articles']);
            $this->redirect($url, 302);
        } else {
            $url = Url::to(['blog/articles']);
            $this->redirect($url, 302);
        }
    }


    public function actionAdd_pics()
    {
        // проверяем шоб не гость
        if (!(Yii::$app->user->isGuest)) {

            $dir    = '.';
            $files1 = scandir($dir);
            $files2 = scandir($dir, 1);

            print_r($files1);
            print_r($files2);

            return $this->render(
                'pictures',
                [
                    '$pictures' => $files1,
                    '$pictures1' => $files2,
                ]
            );
        } else {
            $url = Url::to(['blog/articles']);
            $this->redirect($url, 302);
        }
    }

    /**
     * Выводим страничку Связаться с нами
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }



    /**
     * Выводим страничку О нас
     *
     * @return string
     */
    public function actionAbout()
    {
        $this->view->title = "Киннориум. О нас.";
        return $this->render('about');
    }


}
