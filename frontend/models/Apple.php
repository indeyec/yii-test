<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property string $color
 * @property int $created_at
 * @property int|null $fallen_at
 * @property string $status
 * @property float $size
 * @property float $eaten_percent
 */
class Apple extends ActiveRecord
{
    // Константы для статусов
    public const STATUS_ON_TREE = 'on_tree';
    public const STATUS_FALLEN = 'fallen';
    public const STATUS_ROTTEN = 'rotten';
    public const STATUS_EATEN = 'eaten';
    public const STATUS_PENDING = 'pending';

    // Время портиться после падения (5 часов в секундах)
    private const ROTTEN_TIME = 5 * 3600;

    public $eaten_percent = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['color', 'created_at'], 'required'],
            [['created_at', 'fallen_at'], 'integer'],
            [['size'], 'number'],
            [['color', 'status'], 'string', 'max' => 50],
            [['status'], 'in', 'range' => [self::STATUS_ON_TREE, self::STATUS_FALLEN, self::STATUS_ROTTEN, self::STATUS_EATEN, self::STATUS_PENDING]],
            [['eaten_percent'], 'number', 'min' => 0, 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'created_at' => 'Created At',
            'fallen_at' => 'Fallen At',
            'status' => 'Status',
            'size' => 'Size',
            'eaten_percent' => 'Eaten Percent',
        ];
    }

    /**
     * Инициализирует яблоко.
     */
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->color = $this->getRandomColor();
            $this->created_at = time();
            $this->size = 1.0; // Размер яблока по умолчанию
            $this->status = self::STATUS_ON_TREE;
        }
    }

    /**
     * Возвращает случайный цвет.
     *
     * @return string
     */
    private function getRandomColor(): string
    {
        $colors = ['red', 'green', 'yellow'];
        return $colors[array_rand($colors)];
    }

    /**
     * Определяет, можно ли съесть яблоко.
     *
     * @return bool
     */
    public function canEat(): bool
    {
        return $this->status === self::STATUS_FALLEN && !$this->isRotten();
    }

    /**
     * Упасть яблоко на землю.
     *
     * @throws Exception
     */
    public function fallToGround(): void
    {
        if ($this->status === self::STATUS_EATEN) {
            throw new Exception('Cannot fall, the apple is already eaten.');
        }
        $this->status = self::STATUS_FALLEN;
        $this->fallen_at = time();
        if (!$this->save()) {
            throw new Exception('Failed to update apple status to fallen.');
        }
    }

    /**
     * Съесть указанную часть яблока.
     *
     * @param float $percent Процент откушенной части
     * @throws Exception
     */
    public function eat(float $percent): void
    {
        if (!$this->canEat()) {
            throw new Exception('Cannot eat the apple in its current state.');
        }

        if ($percent < 0 || $percent > 100) {
            throw new Exception('Invalid percentage value.');
        }

        $this->eaten_percent += $percent;
        if ($this->eaten_percent >= 100) {
            $this->status = self::STATUS_EATEN;
            $this->size = 0;
        } else {
            $this->size = max(0, 1 - ($this->eaten_percent / 100));
        }

        if (!$this->save()) {
            throw new Exception('Failed to update apple after eating.');
        }
    }

    /**
     * Проверяет, портилось ли яблоко.
     *
     * @return bool
     */
    public function isRotten(): bool
    {
        if ($this->status === self::STATUS_ROTTEN) {
            return true;
        }
        if ($this->status === self::STATUS_FALLEN && time() - $this->fallen_at > self::ROTTEN_TIME) {
            return true;
        }
        return false;
    }

    /**
     * Обновляет статус яблока и удаляет его, если оно съедено.
     *
     * @throws Exception
     */
    public function updateStatusAndDeleteIfEaten(): void
    {
        if ($this->status === self::STATUS_FALLEN && $this->isRotten()) {
            $this->status = self::STATUS_ROTTEN;
            if (!$this->save()) {
                throw new Exception('Failed to update apple status to rotten.');
            }
        }
        if ($this->status === self::STATUS_EATEN) {
            if (!$this->delete()) {
                throw new Exception('Failed to delete the apple.');
            }
        }
    }
    public function testMethod(): string
{
    return 'Method is called';
}
}
