<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

class Apple extends ActiveRecord
{
    const STATUS_ON_TREE = 'on_tree';
    const STATUS_FALLEN = 'fallen';
    const STATUS_ROTTEN = 'rotten';

    public $color; // цвет яблока
    public $created_at; // дата появления
    public $fallen_at; // дата падения
    public $status; // статус яблока
    public $size; // размер яблока, 1 = целое яблоко

    /**
     * Инициализация нового яблока с случайным цветом и временем появления.
     */
    public function __construct($color = null)
    {
        parent::__construct();
        
        // Случайный цвет, если не указан
        $this->color = $color ?? $this->getRandomColor();
        $this->created_at = time();
        $this->status = self::STATUS_ON_TREE;
        $this->size = 1.0;
    }

    /**
     * Случайный выбор цвета яблока.
     */
    private function getRandomColor()
    {
        $colors = ['green', 'red', 'yellow'];
        return $colors[array_rand($colors)];
    }

    /**
     * Яблоко падает с дерева.
     */
    public function fallToGround()
    {
        if ($this->status === self::STATUS_ON_TREE) {
            $this->status = self::STATUS_FALLEN;
            $this->fallen_at = time();
        }
    }

    /**
     * Откусить часть яблока.
     * 
     * @param float $percent процент откушенного яблока (от 0 до 100)
     * @throws \Exception если яблоко нельзя съесть
     */
    public function eat($percent)
    {
        if ($this->status === self::STATUS_ON_TREE) {
            throw new \Exception('Нельзя съесть яблоко, пока оно на дереве.');
        }

        if ($this->isRotten()) {
            throw new \Exception('Нельзя съесть испорченное яблоко.');
        }

        $biteSize = $percent / 100;
        $this->size -= $biteSize;

        if ($this->size <= 0) {
            $this->delete();
        }
    }

    /**
     * Проверка, испортилось ли яблоко.
     */
    public function isRotten()
    {
        if ($this->status === self::STATUS_FALLEN && (time() - $this->fallen_at) > (5 * 3600)) {
            $this->status = self::STATUS_ROTTEN;
        }

        return $this->status === self::STATUS_ROTTEN;
    }

    /**
     * Удаление яблока из базы данных.
     */
    public function delete()
    {
        // Если яблоко полностью съедено
        if ($this->size <= 0) {
            parent::delete();
        }
    }
}
