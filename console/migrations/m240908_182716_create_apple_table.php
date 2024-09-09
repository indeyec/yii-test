<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m240908_18_create_apple_table extends \yii\db\Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string(50)->notNull(), // цвет яблока
            'created_at' => $this->integer()->notNull(), // время появления яблока
            'fallen_at' => $this->integer()->null(), // время падения
            'status' => $this->string(50)->notNull()->defaultValue('on_tree'), // статус: "on_tree", "fallen", "eaten"
            'size' => $this->float()->notNull()->defaultValue(1.0), // размер яблока (1.0 = целое яблоко)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
