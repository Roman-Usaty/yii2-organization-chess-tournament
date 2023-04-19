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

    private $tournament;

    public function __construct($tournament)
    {
        if (!($tournament instanceof Tournament)) {
            throw new ErrorException();
        }
        $this->tournament = $tournament;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'join', 'message' => "Создатели турниров не могут в нем участвовать"],

            ['rank', 'trim'],
            ['rank', 'required'],
            ['rank', 'integer', 'min' => $this->tournament->minRank, 'max' => $this->tournament->maxRank],
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
