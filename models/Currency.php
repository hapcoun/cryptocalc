<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property float $rate_to_usd
 */
class Currency extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'rate_to_usd'], 'required'],
            [['rate_to_usd'], 'number'],
            [['code'], 'string', 'max' => 3],
            [['name'], 'string'],
            [['code'], 'unique'],
        ];
    }
}

