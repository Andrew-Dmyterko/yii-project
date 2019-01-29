<?php

namespace app\models;

use yii\db\ActiveRecord;

class Articles extends ActiveRecord
{
    public static function tableName()
    {
        return 'article';
    }
}