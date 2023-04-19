<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tournament}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m230419_031846_create_tournament_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tournament}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'author' => $this->integer()->notNull(),
            'description' => $this->text()->defaultValue(null),
            'minRank' => $this->integer()->defaultValue(1),
            'maxRank' => $this->integer()->defaultValue(1),
        ]);

        // creates index for column `author`
        $this->createIndex(
            '{{%idx-tournament-author}}',
            '{{%tournament}}',
            'author'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-tournament-author}}',
            '{{%tournament}}',
            'author',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-tournament-author}}',
            '{{%tournament}}'
        );

        // drops index for column `author`
        $this->dropIndex(
            '{{%idx-tournament-author}}',
            '{{%tournament}}'
        );

        $this->dropTable('{{%tournament}}');
    }
}
