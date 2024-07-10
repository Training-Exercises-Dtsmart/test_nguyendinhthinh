<?php

namespace app\models\form;

use app\models\Request;

class RequestForm extends Request
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['start_date', 'end_date', 'reason'], 'required'],
            [['start_date', 'end_date'], 'date', 'format' => 'php:Y-m-d'],
            ['end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>', 'message' => 'End Date must be greater than Start Date'],
        ]);
    }
}