<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;

use common\models\Tournament;
use common\models\User;

class TournamentForm extends Model
{
    public $name;
    public $description;
    public $minRank;
    public $maxRank;
    public $isActive;
    public $author;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 30],

            ['description', 'trim'],
            ['description', 'required'],
            ['description', 'string'],

            ['maxRank', 'trim'],
            ['maxRank', 'required'],
            ['maxRank', 'integer', 'max' => 4000],

            ['minRank', 'trim'],
            ['minRank', 'required'],
            ['minRank', 'integer', 'min' => 0],

            ['isActive', 'required'],
            ['isActive', 'integer', 'min' => 0, 'max' => 1],

        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя турнира',
            'description' => 'Описание',
            'minRank' => 'Минимальный ранг',
            'maxRank' => 'Максимальный ранг',
            'isActive' => 'Турнир активен?'
        ];
    }

    public function createTournament()
    {
        $userActiveTournament = Tournament::findAll(['author' => Yii::$app->user->identity->id, 'isActive' => 1]);

        if (!$this->validate() || count($userActiveTournament) > 2) {
            $this->addError('isActive', 'Форма не прошла валидацию или у вас активно 2 и более турниров');
            return;
        }

        $tournament = new Tournament();
        $tournament->name = $this->name;
        $tournament->description = $this->description;
        $tournament->minRank = $this->minRank;
        $tournament->maxRank = $this->maxRank;
        $tournament->isActive = $this->isActive;
        $tournament->author = $this->author;

        return $tournament->save();
    }
}
