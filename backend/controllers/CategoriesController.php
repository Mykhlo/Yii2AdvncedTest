<?php

namespace backend\controllers;

use Yii;
use app\models\Categories;
use app\models\ProductCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller
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
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex($error = NULL)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Categories::find(),
        ]);
        $categories = $this->findAll();

        return $this->render('index', [
            'error' => $error,
            'dataProvider' => $dataProvider,
            'categories' => $categories,
        ]);
    }

    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $parent = Categories::findOne($model->parent_id);
        return $this->render('view', [
            'model' => $model,
            'parent' => $parent,
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categories(); 
        $categories = $this->findAll();       

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('_form', [                   
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $categories = $this->findAll();
        unset($categories[$id]);
                 

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('_form', [
            'model' => $model,
            'categories' => $categories,            
        ]);
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $products = ProductCategory::find()
            ->where(['category_id' => $id])
            ->exists();
        $childs = Categories::find()
            ->where(['parent_id' => $id])
            ->exists();
        if($products){
            $error = 'Category '.$this->findModel($id)->name.' has products!';
        }else if($childs){
            $error = 'Category '.$this->findModel($id)->name.' has subcategories!';
        }else{
            $error = NULL;
            $this->findModel($id)->delete();
        }        

        return $this->redirect(['index', 'error' => $error]);
    }    

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds all Categories 
     * If the model is not found, a 404 HTTP exception will be thrown.     * 
     * @return Categories 
     * @throws NotFoundHttpException if the model epmty
     */
    protected function findAll()
    {
        if (($model = Categories::find()->where(['parent_id' => NULL])->select('name')->indexBy('id')->column()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('There are no categories in database.');
    }

    
}
