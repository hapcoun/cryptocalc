<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cryptocurrency}}`.
 */
class m241015_080207_create_cryptocurrency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cryptocurrency}}', [
            'id' => $this->primaryKey(),
            'symbol' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'price_usd' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->execute("
            CREATE OR REPLACE FUNCTION update_updated_at_column()
            RETURNS TRIGGER AS \$\$
            BEGIN
                NEW.updated_at = CURRENT_TIMESTAMP;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        $this->execute("
            CREATE TRIGGER update_timestamp
            BEFORE UPDATE ON cryptocurrency
            FOR EACH ROW
            EXECUTE FUNCTION update_updated_at_column();
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TRIGGER IF EXISTS update_timestamp ON cryptocurrency;");
        $this->execute("DROP FUNCTION IF EXISTS update_updated_at_column();");
        $this->dropTable('{{%cryptocurrency}}');
    }
}
