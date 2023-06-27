<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;

use common\models\User;
use common\models\Tournament;
use common\models\ListOfPlayer;

class JoinTournamentForm extends Model
{
    public $username;
    public $rank;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 5, 'max' => 25],
            ['username', 'join', 'message' => "Создатели турниров не могут в нем участвовать"],

            ['rank', 'trim'],
            ['rank', 'required'],
            ['rank', 'integer', 'min' => 0, 'max' => 4000],
        ];
    }


    public function join($userId, $tournamentId)
    {
        if (!(isset($userId) && isset($tournamentId))) {
            return $this->addError($this->username, "Что пошло не так");
        }



        $listOfPlayer = new ListOfPlayer();
        $user = User::findOne(['id' => $userId]);
        $tournament = Tournament::findOne(['id' => $tournamentId]);

        if (!($tournament->minRank >= $user->rank && $user->rank <= $tournament->maxRank)) {
            return $this->addError($this->username, "Ваш ранк недостаточен");
        }

        if ($tournament->author == $user->id) {
            return $this->addError($this->username, "Создатель турнира не может в нем участвовать");
        }

        $listOfPlayer->tournament = $tournament->id;
        $listOfPlayer->player = $user->id;


        if (!$listOfPlayer->validate() && !$this->validate()) {
            return false;
        }
        return $listOfPlayer->save();


        return $listOfPlayer->errors;
    }
}
