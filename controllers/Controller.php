<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller as BaseController;

class Controller extends BaseController
{
    public function json($status = true, $data = [], $error = "", $message = "", $code = 200)
    {
        Yii::$app->response->statusCode = $code;

        return [
            "status" => $status,
            "data" => $data,
            "error" => $error,
            "message" => $message,
            "code" => $code
        ];
    }
}