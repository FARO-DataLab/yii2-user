<?php

namespace faro\core\user\tests\fixtures;

use faro\core\user\models\Profile;
use faro\core\user\models\User;
use yii\test\ActiveFixture;

class PerfilFixture extends ActiveFixture
{
    public $modelClass = Profile::class;
}