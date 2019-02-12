<?php

namespace app\models;

use yii\db\ActiveRecord;

class Article_pic extends ActiveRecord
{
    public static function tableName()
    {
        return 'article_pic';
    }
}