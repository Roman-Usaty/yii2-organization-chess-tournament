<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%listOfPlayer}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tournament}}`
 * - `{{%user}}`
 */
class m230419_071107_create_listOfPlayer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%listOfPlayer}}', [
            'id' => $this->primaryKey(),
            'tournament' => $this->integer()->notNull(),
            'player' => $this->integer()->notNull(),
        ]);

        // creates index for column `tournament`
        $this->createIndex(
            '{{%idx-listOfPlayer-tournament}}',
            '{{%listOfPlayer}}',
            'tournament'
        );

        // add foreign key for table `{{%tournament}}`
        $this->addForeignKey(
            '{{%fk-listOfPlayer-tournament}}',
            '{{%listOfPlayer}}',
            'tournament',
            '{{%tournament}}',
            'id',
            'CASCADE'
        );

        // creates index for column `player`
        $this->createIndex(
            '{{%idx-listOfPlayer-player}}',
            '{{%listOfPlayer}}',
            'player'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-listOfPlayer-player}}',
            '{{%listOfPlayer}}',
            'player',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tournament}}`
        $this->dropForeignKey(
            '{{%fk-listOfPlayer-tournament}}',
            '{{%listOfPlayer}}'
        );

        // drops index for column `tournament`
        $this->dropIndex(
            '{{%idx-listOfPlayer-tournament}}',
            '{{%listOfPlayer}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-listOfPlayer-player}}',
            '{{%listOfPlayer}}'
        );

        // drops index for column `player`
        $this->dropIndex(
            '{{%idx-listOfPlayer-player}}',
            '{{%listOfPlayer}}'
        );

        $this->dropTable('{{%listOfPlayer}}');
    }
}
