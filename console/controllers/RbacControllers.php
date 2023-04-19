<?php

namespace console\controllers;

use Yii;

use yii\console\Controller;


class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $createTournament = $auth->createPermission('createTournament');
        $createTournament->description = "Create a tournament";
        $auth->add($createTournament);

        $updateTournament = $auth->createPermission('updateTournament');
        $updateTournament->description = 'Update tournament';
        $auth->add($updateTournament);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $createTournament);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateTournament);
        $auth->addChild($admin, $user);

        $rule = new \app\rbac\IsTournamentOwnerRule;
        $auth->add($rule);

        $updateOwnTournament = $auth->createPermission('updateOwnTournament');
        $updateOwnTournament->description = 'Update own Tournament';
        $updateOwnTournament->ruleName = $rule->name;
        $auth->add($updateOwnTournament);

        $auth->addChild($updateOwnTournament, $updateTournament);

        $auth->addChild($user, $updateOwnTournament);

        $auth->assign($admin, 1);
    }
}
