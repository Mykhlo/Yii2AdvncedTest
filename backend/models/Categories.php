<?php

namespace app\models;

use Yii;
use app\models\Products;    

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $parent_id
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255, ],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent_id' => 'Parent Category',
        ];
    }
    
    // get all products of category
    public function getProducts() {
        return $this->hasMany(Products::className(), ['id' => 'product_id'])
          ->viaTable('product_category', ['category_id' => 'id']);
    }
}
