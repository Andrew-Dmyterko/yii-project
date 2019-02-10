<?php

namespace app\controllers;

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
        $articleId = Yii::$app->request->get('id');
        $article = Articles::find()->where(['id' => $articleId])->one();

        if (!(bool)$article) {
            $url = Url::to(['blog/articles']);
            return $this->redirect($url, 302);

        }

        $this->layout = 'blog';
        $this->view->title = $article->title;

        return $this->render(
            'article',
            [
                'article' => $article
            ]
        );
    }

    /**
     * Редактируем одну статью
     *
     */
    public function actionEdit()
    {
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
                $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

                try {
                    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
                    Yii::$app->session->setFlash('success', "Файл ". $_FILES['userfile']['name']. "  корректен и был успешно загружен.");
//                        echo "Файл корректен и был успешно загружен.";
//                    Если все хорошо готовимся писать в базу
                    $article->image = $_FILES['userfile']['name'];
                } catch (\Throwable $exception) {
                    Yii::$app->session->setFlash('error ', "Возможная атака с помощью файловой загрузки! ".$exception->getMessage());
//                        echo getAlert("Возможная атака с помощью файловой загрузки!");
                    $url = Url::to(['blog/articles']);
                    $this->redirect($url, 302);
                    return;
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
        // проверяем шоб не гость
        if (!(Yii::$app->user->isGuest)) {

            $this->layout = 'blog';

            $username = (Yii::$app->user->identity->username);

            // получаем post параметры
            $post = Yii::$app->request->post();

//            echo "<pre>";
//            var_dump($_FILES);
//            die;
//

            if (isset ($post['title']) || isset ($post['article_small_text']) || isset ($post['article_full_text'])) {

                if (!empty($_FILES['userfile']['name'])){

                    $uploaddir = 'images/'; //  /var/www/html/yii-project/web/
                    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

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
                $article->image = $_FILES['userfile']['name'];

                $article->save();

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
