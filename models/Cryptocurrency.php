<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $symbol
 * @property string $name
 * @property string $price_usd
 * @property string $created_at
 * @property string $updated_at
 */
class Cryptocurrency extends ActiveRecord
{
    public static function tableName(): string {
        return '{{%cryptocurrency}}';
    }

    public function rules(): array {
        return [
            [['symbol', 'name', 'price_usd'], 'required'],
            [['symbol', 'name'], 'string'],
            [['price_usd'], 'number'],
        ];
    }

    public function fields(): array {
        return ['symbol', 'name', 'price_usd'];
    }

    public function extraFields(): array {
        return [];
    }
}