<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\auth\HttpBasicAuth;
use app\controllers\Controller;
use app\models\form\UserForm;

class UserController extends Controller
{
    /**
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function actionCreate()
    {
        $headers = Yii::$app->request->headers;
        $token = $headers->get('Authorization');
        if (empty($token)) {
            return $this->json(false, [], '', 'Not access', 401);
        }

        $user = User::find()->where(['auth_key' => $token])->one();
        if (empty($user) || $user->getRole() == User::ROLE_STAFF) {
            return $this->json(false, [], '', 'Not access', 401);
        }

        if ($user->getRole() == User::ROLE_HR) {
            $userForm = new UserForm();
            $userForm->load(Yii::$app->request->post());
            $userForm->setPassword(Yii::$app->request->post('password'));
            $userForm->role = USER::ROLE_STAFF;
            if (!$userForm->validate() || !$userForm->save()) {
                return $this->json(false, '', $userForm->getErrors(), 'Cant create user.', 400);
            }
            return $this->json(true, ['user' => $userForm], '', 'Created Successfully');
        }

        return $this->json(false, [], '', 'Not access', 401);
    }

    /**
     * @throws Exception
     */
    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        $user = User::findOne(['username' => $username]);
        if (empty($user)) {
            return $this->json(false, [], '', 'User not found', 404);
        }

        if (!Yii::$app->security->validatePassword($password, $user->password_hash)) {
            return $this->json(false, [], $user->getErrors(), 'Wrong password', 400);
        }

        $user->generateAuthKey();
        if (!$user->save()) {
            return $this->json(false, [], $user->getErrors(), 'Cant login.', 400);
        }

        return $this->json(true, ['token' => $user->auth_key], '', 'Login Successfully');
    }
}