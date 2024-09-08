<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Apple extends ActiveRecord
{
    const STATUS_ON_TREE = 'on_tree';
    const STATUS_FALLEN = 'fallen';
    const STATUS_EATEN = 'eaten';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['color', 'status', 'created_at'], 'required'],
            [['color', 'status'], 'string', 'max' => 50],
            [['created_at', 'fallen_at'], 'integer'],
            [['size'], 'number', 'min' => 0, 'max' => 1],
            ['status', 'in', 'range' => [self::STATUS_ON_TREE, self::STATUS_FALLEN, self::STATUS_EATEN]],
        ];
    }

    /**
     * Метка атрибутов
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'created_at' => 'Created At',
            'fallen_at' => 'Fallen At',
            'status' => 'Status',
            'size' => 'Size',
        ];
    }

    /**
     * Проверка, на дереве ли яблоко.
     * @return bool
     */
    public function isOnTree()
    {
        return $this->status === self::STATUS_ON_TREE;
    }

    /**
     * Яблоко падает с дерева.
     */
    public function fall()
    {
        if ($this->isOnTree()) {
            $this->status = self::STATUS_FALLEN;
            $this->fallen_at = time();
            return $this->save();
        }

        return false;
    }

    /**
     * Съесть яблоко на определенный процент.
     * @param float $percent
     * @return bool
     */
    public function eat($percent)
    {
        if ($this->status === self::STATUS_FALLEN && $this->size > 0) {
            $this->size -= $percent;
            if ($this->size <= 0) {
                $this->status = self::STATUS_EATEN;
                $this->size = 0;
            }
            return $this->save();
        }

        return false;
    }
}
