<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "listOfPlayer".
 *
 * @property int $id
 * @property int $tournament
 * @property int $player
 *
 * @property User $player0
 * @property Tournament $tournament0
 */
class ListOfPlayer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listOfPlayer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tournament', 'player'], 'required'],
            [['tournament', 'player'], 'integer'],
            [['player'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player' => 'id']],
            [['tournament'], 'exist', 'skipOnError' => true, 'targetClass' => Tournament::class, 'targetAttribute' => ['tournament' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tournament' => 'Tournament',
            'player' => 'Player',
        ];
    }

    /**
     * Gets query for [[Player0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer0()
    {
        return $this->hasOne(User::class, ['id' => 'player']);
    }

    /**
     * Gets query for [[Tournament0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournament0()
    {
        return $this->hasOne(Tournament::class, ['id' => 'tournament']);
    }
}
