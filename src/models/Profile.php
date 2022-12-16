<?php

namespace faro\core\user\models;

use faro\core\enums\IdiomasDisponibles;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $full_name
 * @property string $timezone
 * @property string $idioma
 *
 * @property User $user
 */
class Profile extends ActiveRecord
{
    /**
     * @var \faro\core\user\Module
     */
    public $module;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%core_acl_perfil}}';
    }


    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->module) {
            $this->module = Yii::$app->getModule("user");
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name'], 'string', 'max' => 255],
            [['timezone'], 'string', 'max' => 255],
            [
                'idioma',
                function ($attribute, $params, $validator) {
                    // si no mando nada es valido
                    if (empty($this->$attribute)) {
                        return;
                    }
                    
                    if (!IdiomasDisponibles::isValidValue($this->$attribute)) {
                        $this->addError($attribute, 'Idioma no válido');
                    }
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'user_id' => Yii::t('user', 'User ID'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
            'full_name' => Yii::t('user', 'Full Name'),
            'timezone' => Yii::t('user', 'Time zone'),
            'idioma' => Yii::t('user', 'Idioma'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => function ($event) {
                    return gmdate("Y-m-d H:i:s");
                },
            ],
        ];
    }


    /**
     * Devuelve las iniciales del usuario en funcion de su nombre y apellido
     * @return string
     */
    public function getIniciales()
    {
        $nombres = explode(' ', $this->full_name);
        $iniciales = array_map(fn($e) => substr($e, 0, 1), $nombres);
        return strtoupper(implode('', $iniciales));
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        $user = $this->module->model("User");
        return $this->hasOne($user::className(), ['id' => 'user_id']);
    }

    /**
     * Set user id
     * @param int $userId
     * @return static
     */
    public function setUser($userId)
    {
        $this->user_id = $userId;
        return $this;
    }
}