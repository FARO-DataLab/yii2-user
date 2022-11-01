<?php

namespace faro\core\user;

use yii\web\AssetBundle;

class UserAdminFormAsset extends AssetBundle
{
    public $js = [
        'js/admin_user_form.js',
    ];

    public $depends = [
        'faro\core\AppAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }
}