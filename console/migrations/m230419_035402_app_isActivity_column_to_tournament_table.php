<?php

use yii\db\Migration;

/**
 * Class m230419_035402_app_isActivity_column_to_tournament_table
 */
class m230419_035402_app_isActivity_column_to_tournament_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tournament}}', 'isActive', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tournament}}', 'isActive');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230419_035402_app_isActivity_column_to_tournament_table cannot be reverted.\n";

        return false;
    }
    */
}
