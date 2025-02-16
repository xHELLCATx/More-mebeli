<?php

namespace app\controllers;

use Yii;
use app\models\Article;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;

class ArticleController extends Controller
{
    public $article;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (in_array(Yii::$app->user->identity->role, ['admin', 'owner'])) {
                                return true;
                            }
                            
                            if ($action->id === 'create') {
                                return true; // Любой авторизованный пользователь может создавать статьи
                            }
                            
                            if (in_array($action->id, ['update', 'delete'])) {
                                $seo_url = Yii::$app->request->get('seo_url');
                                $article = Article::findOne(['seo_url' => $seo_url]);
                                return $article && $article->user_id === Yii::$app->user->id;
                            }
                            
                            return false;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = Article::find()->orderBy(['created_at' => SORT_DESC]);
        
        $searchQuery = Yii::$app->request->get('search');
        if ($searchQuery) {
            $query->andWhere(['like', 'title', $searchQuery]);
        }

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 9,
            'pageSizeParam' => false,
        ]);

        $articles = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'articles' => $articles,
            'pages' => $pages,
            'searchQuery' => $searchQuery,
        ]);
    }

    public function actionCreate()
    {
        $model = new Article();
        
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            $model->image = UploadedFile::getInstance($model, 'image');
            
            if ($model->save() && $model->upload()) {
                Yii::$app->session->setFlash('success', 'Статья успешно создана');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionView($seo_url)
    {
        $this->article = Article::findOne(['seo_url' => $seo_url]);
        if ($this->article === null) {
            throw new NotFoundHttpException('Статья не найдена.');
        }
        return $this->render('view', [
            'model' => $this->article,
        ]);
    }

    public function actionUpdate($seo_url)
    {
        $model = Article::findOne(['seo_url' => $seo_url]);
        if ($model === null) {
            throw new NotFoundHttpException('Статья не найдена.');
        }

        $oldImage = $model->image; // Сохраняем старое изображение

        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            
            // Если новое изображение не загружено, сохраняем старое
            if ($model->image === null) {
                $model->image = $oldImage;
            }
            
            if ($model->save() && ($model->image === $oldImage || $model->upload())) {
                Yii::$app->session->setFlash('success', 'Статья успешно обновлена');
                return $this->redirect(['view', 'seo_url' => $model->seo_url]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($seo_url)
    {
        $model = $this->findModel($seo_url);
        $oldImage = Yii::getAlias('@webroot/') . $model->image;
        
        if ($model->delete()) {
            if (file_exists($oldImage)) {
                unlink($oldImage);
            }
            Yii::$app->session->setFlash('success', 'Статья успешно удалена');
        }

        return $this->redirect(['index']);
    }

    protected function findModel($seo_url)
    {
        if (($model = Article::findOne(['seo_url' => $seo_url])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Статья не найдена.');
    }
}
