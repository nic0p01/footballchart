<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Blog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\UploadImage;
use yii\web\UploadedFile;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->identity->role != 10){
            $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Blog::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blog model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->user->identity->role != 10){
            $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->identity->role != 10){
            $this->goHome();
        }
        $model = new Blog();
        $upload_image = new UploadImage();    
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->alias = translit($post['Blog']['title']);
            $upload_image->image = UploadedFile::getInstance($model, 'image');
            debug($upload_image->image);
            $image_name = $upload_image->upload();
            $model->image = $image_name;

            if($model->save()){
                Yii::$app->session->setFlash('success', 'Статья добавлена');
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                Yii::$app->session->setFlash('fail', 'Ошибка');
                return $this->render('update', ['model' => $model]);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->identity->role != 10){
            $this->goHome();
        }
        $model = $this->findModel($id);
        
        $upload_image = new UploadImage(); 
        $image_name = $model->image;
//        debug(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post())) {
            $upload_image->image = UploadedFile::getInstance($model, 'image');
//            debug($upload_image->image);
            if($upload_image->image != NULL){
                $image_name = $upload_image->upload();
            }
            
            $model->image = $image_name;

            if($model->save(false)){
                Yii::$app->session->setFlash('success', 'Статья обновлена');
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                Yii::$app->session->setFlash('fail', 'Ошибка');
                return $this->render('update', ['model' => $model]);
            }
}
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->identity->role != 10){
            $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
