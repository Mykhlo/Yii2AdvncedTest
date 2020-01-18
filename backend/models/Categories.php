<?php

namespace app\models;

use Yii;

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
            [['name'], 'string', 'max' => 255],
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
    // get parent category
    public function parent()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent_id']);
    }
    // get all products of category
    public function products() {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
          ->viaTable('product_category', ['category_id' => 'id']);
    }
}
