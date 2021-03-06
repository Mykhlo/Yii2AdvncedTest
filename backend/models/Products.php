<?php

namespace app\models;

use Yii;
use app\models\Categories;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $full_desc
 * @property int|null $price
 * @property int|null $quantity
 */
class Products extends \yii\db\ActiveRecord
{
    const SCENARIO_UPDATE = 'update';    

    /**
     * Scenario for separated validation
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = [
                'name',
                'description',
                'full_desc',
                'price',
                'quantity',
            ];        
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'full_desc'], 'string'],
            [['price', 'quantity'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique',],           
            [['name'], 'unique', 'on' => self::SCENARIO_UPDATE,'when' => function($model){                
                return $model->isAttributeChanged('name');
            }],         
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
            'description' => 'Description',
            'full_desc' => 'Full Desc',
            'price' => 'Price',
            'quantity' => 'Quantity',
        ];
    }

    // get all categories of product
    public function getCategories() {
        return $this->hasMany(Categories::className(), ['id' => 'category_id'])
          ->viaTable('product_category', ['product_id' => 'id']);
    }

}
