<?php

namespace faro\core\user\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * User component
 *
 * @property-read int[] $permisosCategorias
 */
class User extends \yii\web\User
{
    /**
     * @inheritdoc
     */
    public $identityClass = \faro\core\user\models\User::class;

    /**
     * @inheritdoc
     */
    public $enableAutoLogin = true;

    /**
     * @inheritdoc
     */
    public $loginUrl = ['/user/login'];

    /**
     * @inheritdoc
     */
    public function getIsGuest()
    {
        /** @var \faro\core\user\models\User $user */

        // check if user is banned. if so, log user out and redirect home
        // https://github.com/amnah/yii2-user/issues/99
        $user = $this->getIdentity();
        if ($user && $user->banned_at) {
            $this->logout();
            Yii::$app->getResponse()->redirect(['/'])->send();
        }

        return $user === null;
    }

    /**
     * Check if user is logged in
     * @return bool
     */
    public function getIsLoggedIn()
    {
        return !$this->getIsGuest();
    }

    /**
     * @inheritdoc
     */
    public function afterLogin($identity, $cookieBased, $duration)
    {
        /** @var \faro\core\user\models\User $identity */
        $identity->updateLoginMeta();
        parent::afterLogin($identity, $cookieBased, $duration);
    }

    /**
     * Get user's display name
     * @return string
     */
    public function getDisplayName()
    {
        /** @var \faro\core\user\models\User $user */
        $user = $this->getIdentity();
        return $user ? $user->getDisplayName() : '';
    }

    /**
     * Check if user can do $permissionName.
     * If "authManager" component is set, this will simply use the default functionality.
     * Otherwise, it will use our custom permission system
     * @param string $permissionName
     * @param array $params
     * @param bool $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        // check for auth manager to call parent
        $auth = Yii::$app->getAuthManager();
        if ($auth) {
            return parent::can($permissionName, $params, $allowCaching);
        }

        // otherwise use our own custom permission (via the role table)
        /** @var \faro\core\user\models\User $user */
        $user = $this->getIdentity();
        return $user ? $user->can($permissionName) : false;
    }

    /**
     * Se encarga de devolver null si el usuario tiene acceso a todas las categorias y sino un array con los ids
     * de las categorias a las que tiene acceso.
     *
     * @return ?int[]
     * @throws InvalidConfigException
     */
    public function getPermisosCategorias(): ?array
    {
        $user = $this->getIdentity();
        if (!$user || !$this->getIsLoggedIn()) {
            throw new InvalidConfigException('El usuario no está logueado');
        }

        if ($this->can('admin')) {
            return null;
        }

        $categorias = $user->categorias;
        // si no tiene categorias asociadas entonces tiene acceso a todo
        if (!$categorias) {
            return null;
        }

        return ArrayHelper::getColumn($categorias, 'id');
    }
}
