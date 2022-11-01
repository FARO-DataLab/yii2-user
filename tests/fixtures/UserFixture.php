<?php

namespace faro\core\user\tests\fixtures;

use faro\core\user\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}