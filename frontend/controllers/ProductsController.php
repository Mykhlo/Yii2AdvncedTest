<?php

namespace frontend\controllers;

use yii\helpers\VarDumper;
use yii\data\ActiveDataProvider;
use frontend\models\Products;
use frontend\models\Categories;

class ProductsController extends \yii\web\Controller
{
    public function actionIndex($id = false)
    {
        $categories = false;
        // products for current category
        if($id){
            // check if category exist and if it has products                                     
            $category = Categories::findOne($id);
            if($category != NULL) {   
                $ids = [];             
                $products = $category->products;                
                if($products != NULL){                   
                    foreach($products as $product){
                        array_push($ids, $product->id);                   
                    }                    
                    $categories = true;
                }else{
                    // if there are no products in category will check child categories
                    $categories = Categories::find()
                            ->where(['parent_id' => $id])->all();                            
                    foreach($categories as $category){                        
                        $products = $category->products;
                        if($products != NULL){
                            foreach($products as $product){
                                array_push($ids, $product->id);                   
                            }                   
                        }
                    }
                    if(!empty($ids))
                        $categories = true;
                }
            }
        }

        if($categories){
            $dataProvider = new ActiveDataProvider([
                'query' => Products::find()->where(['in', 'id', $ids]),
            ]);
        }else{
            $category = false;
            $dataProvider = new ActiveDataProvider([
                'query' => Products::find(),
            ]);        
        }

        return $this->render('index', [
            'category' => $category ? $category->name : NULL,
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
