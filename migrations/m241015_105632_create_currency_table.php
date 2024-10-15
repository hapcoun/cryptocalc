<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m241015_105632_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(3)->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'rate_to_usd' => $this->decimal(10, 4)->notNull(),
        ]);

        $this->batchInsert('{{%currency}}', ['code', 'name', 'rate_to_usd'], [
            ['USD', 'United States Dollar', 1.0000],
            ['EUR', 'Euro', 0.8500],
            ['GBP', 'British Pound', 0.7500],
            ['JPY', 'Japanese Yen', 110.00],
            ['AUD', 'Australian Dollar', 1.3500],
            ['CAD', 'Canadian Dollar', 1.2500],
            ['CHF', 'Swiss Franc', 0.9300],
            ['CNY', 'Chinese Yuan', 6.5000],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency}}');
    }
}
