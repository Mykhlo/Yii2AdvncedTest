<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_category}}`.
 */
class m200117_164723_create_product_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_category}}', [
            'id' => $this->primaryKey(),
            'product_id' => 'integer',
            'category_id' => 'integer',
        ]);

        // add foreign key for parent catgory
        $this->addForeignKey(
            'fk-id-parent_id',
            'categories',
            'parent_id',
            'categories',
            'id',
            'CASCADE'
        );
    }
    

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product_category}}');
    }
}
