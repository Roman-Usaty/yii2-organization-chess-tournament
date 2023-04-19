<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class IsTournamentOwnerRule extends Rule
{
    public $name = 'isTournamentOwner';

    public function execute($user_id, $permission, $params)
    {
        // пропускаем базовые проверки
        return isset($params['post']) ? $params['post']->createdBy == $user_id : false;
    }
}
