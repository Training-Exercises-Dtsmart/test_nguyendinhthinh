<?php

namespace app\models;

use \app\models\base\Request as BaseRequest;

/**
 * This is the model class for table "request".
 */
class Request extends BaseRequest
{
    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;
    public function formName()
    {
        return "";
    }
}
