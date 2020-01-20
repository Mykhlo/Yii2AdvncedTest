<?php

namespace backend\controllers;

use Yii;
use app\models\Products;
use app\models\Categories;
use app\models\ProductCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                // 'only' => ['create', 'update'],
                'rules' => [                   
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Products::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();        
        
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post();
            $categories = $request['Products']['categories'];
           
            // save relation
            if($categories != NULL && $model->save()){                
                
                foreach ($categories as $categoryId) {

                    $productCategory = new ProductCategory();
       
                    $productCategory->product_id = $model->id;
       
                    $productCategory->category_id = $categoryId;
       
                    $productCategory->save();       
                 }
                return $this->redirect(['view', 'id' => $model->id]);
            }            
            $error = 'Product must have the category';
        } 

        return $this->render('_form', [
            'error' => $error ?? NULL,
            'model' => $model,
            'categories' => Categories::find()->where(['not', ['parent_id' => NULL]])->select(['id', 'name'])->all(),
        ]);        
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);  
        // scenario for validation
        $model->scenario = Products::SCENARIO_UPDATE;              
        
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post();
            $categories = $request['Products']['categories'];

            // save relation
            if($categories != NULL && $model->update()){ 

                /* clear old relations before saving */
                ProductCategory::deleteAll(['product_id' => $model->id]);

                foreach ($categories as $categoryId) {

                    $productCategory = new ProductCategory();
       
                    $productCategory->product_id = $model->id;
       
                    $productCategory->category_id = $categoryId;
       
                    $productCategory->save();       
                 }
                return $this->redirect(['view', 'id' => $model->id]);
            }            
            $error = 'Product must have the category';
        } 

        return $this->render('_form', [
            'error' => $error ?? NULL,
            'model' => $model,
            'categories' => Categories::find()->where(['not', ['parent_id' => NULL]])->select(['id', 'name'])->all(),
        ]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        /* clear relations */
        ProductCategory::deleteAll(['product_id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
