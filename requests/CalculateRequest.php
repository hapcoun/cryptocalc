<?php
namespace app\requests;

use app\models\Currency;
use yii\base\Model;

class CalculateRequest extends Model
{
    public string $symbol;
    public float $amount;
    public string $currency;

    public function rules(): array {
        return [
            [['symbol', 'amount', 'currency'], 'required'],
            [['amount'], 'number', 'min' => 0.0001],
            [['symbol'], 'string', 'max' => 10],
            [['currency'], 'string', 'max' => 3],
            [['currency'], 'validateCurrency'],
        ];
    }

    public function validateCurrency($attribute, $params): void {
        if (!Currency::find()->where(['code' => strtoupper($this->currency)])->exists()) {
            $this->addError($attribute, 'Currency is not supported or does not exist in the database.');
        }
    }
}