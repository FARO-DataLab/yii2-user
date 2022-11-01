<?php

namespace faro\core\user\models;

use faro\core\models\Categoria;
use faro\core\models\FaroBaseActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%core_acl_permiso_usuario_categoria}}".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $categoria_id
 * @property string|null $fecha_ingreso_sistema
 * @property string|null $fecha_actualizacion_sistema
 *
 * @property Categoria $categoria
 * @property User $usuario
 */
class PermisoUsuarioCategoria extends FaroBaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_acl_permiso_usuario_categoria}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'categoria_id'], 'required'],
            [['usuario_id', 'categoria_id'], 'integer'],
            [['fecha_ingreso_sistema', 'fecha_actualizacion_sistema'], 'safe'],
            [
                ['categoria_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Categoria::class,
                'targetAttribute' => ['categoria_id' => 'id']
            ],
            [
                ['usuario_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['usuario_id' => 'id']
            ],
            [['categoria_id', 'usuario_id'], 'unique', 'targetAttribute' => ['categoria_id', 'usuario_id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'fecha_ingreso_sistema',
                'updatedAtAttribute' => 'fecha_actualizacion_sistema',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario',
            'categoria_id' => 'Categoria',
            'fecha_ingreso_sistema' => 'Fecha Ingreso Sistema',
            'fecha_actualizacion_sistema' => 'Fecha Actualizacion Sistema',
        ];
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::class, ['id' => 'usuario_id']);
    }
}
