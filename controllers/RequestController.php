<?php

namespace app\controllers;

use Yii;
use app\models\Request;
use app\models\User;
use app\controllers\Controller;
use app\models\form\RequestForm;

class RequestController extends Controller
{
    public function actionIndex()
    {
        $headers = Yii::$app->request->headers;
        $token = $headers->get('Authorization');

        if (empty($token)) {
            return $this->json(false, [], '', 'Not Access', 401);
        }
        $user = User::find()->where(['auth_key' => $token])->one();
        if (empty($user)) {
            return $this->json(false, [], '', 'Not Access', 401);
        }

        if ($user->getRole() == User::ROLE_HR) {
            $requests = Request::find()->all();
            return $this->json(true, ['requests' => $requests], '', 'All Request');
        }
        if ($user->getRole() == User::ROLE_STAFF) {
            $requests = Request::find()->where(['user_id' => $user->getId()])->all();
            return $this->json(true, ['requests' => $requests], '', 'All Request');
        }

        return $this->json(false, [], '', 'Not found role', 400);
    }

    public function actionCreate()
    {
        $headers = Yii::$app->request->headers;
        $token = $headers->get('Authorization');
        if (empty($token)) {
            return $this->json(false, [], '', 'Not Access', 401);
        }

        $user = User::find()->where(['auth_key' => $token])->one();
        if (empty($user)) {
            return $this->json(false, [], '', 'Not access', 401);
        }

        $requestForm = new RequestForm();
        $requestForm->load(Yii::$app->request->post());
        $requestForm->user_id = $user->id;
        $requestForm->status = Request::PENDING;

        if (!$requestForm->validate() || !$requestForm->save()) {
            return $this->json(false, [], $requestForm->getErrors(), "Can't create request", 400);
        }

        return $this->json(true, ['request' => $requestForm], "", 'Created Successfully');
    }

    public function actionUpdate($id)
    {
        $headers = Yii::$app->request->headers;
        $token = $headers->get('Authorization');
        if (empty($token)) {
            return $this->json(false, [], '', 'Not Access', 401);
        }

        $user = User::find()->where(['auth_key' => $token])->one();
        if (empty($user)) {
            return $this->json(false, [], '', 'Not access', 401);
        }
        if ($user->getRole() == User::ROLE_STAFF) {
            return $this->json(true, [], '', 'Not access', 403);
        }

        $request = Request::find()->where(['id' => $id])->one();
        if (empty($request)) {
            return $this->json(false, [], '', 'Request not found', 404);
        }
//        $request->status = Yii::$app->request->post('status');
        $request->load(Yii::$app->request->post());

        if (!$request->validate() || !$request->save()) {
            return $this->json(false, [], $request->getErrors(), "Can't update request", 400);
        }

        return $this->json(true, ['request' => $request], "", 'Updated Successfully');

    }

}