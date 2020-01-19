<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?>
    <?php if($category != NULL) echo ' in '.$category.' category'; ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',            
            'price',
            'quantity',  
            [
                'attribute' => 'Details',                
                'value' => function ($data) {                    
                    return Html::a($data->name,'/index.php?r=products%2Fview&id='.$data->id);                    
                },
                'format' => 'raw',
            ],             
        ],
    ]); ?>


</div>
