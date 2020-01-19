<?php

namespace frontend\controllers;

use yii\data\ActiveDataProvider;
use frontend\models\Categories;

class CategoriesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Categories::find(),
        ]);
        $categories = $this->findAll();

        return $this->render('index', [           
            'dataProvider' => $dataProvider,
            'categories' => $categories,
        ]);
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
