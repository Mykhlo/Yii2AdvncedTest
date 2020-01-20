<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>      

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'parent',
                'value' => function ($data) use ($categories) {
                    
                    return $data->parent_id == NULL ? $data->parent_id : $categories[$data->parent_id];                     
                },
            ],
            [
                'attribute' => 'Products',                
                'value' => function ($data) {   
                    if($data->parent_id == NULL){
                        return Html::a('Products in '.$data->name. ' subcategories','/index.php?r=products%2Findex&id='.$data->id);                    
                    } else {
                        return Html::a('Products in '.$data->name,'/index.php?r=products%2Findex&id='.$data->id);                  
                    }                      
                },
                'format' => 'raw',
            ],              
        ],
    ]); ?>

</div>
