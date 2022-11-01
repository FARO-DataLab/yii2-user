<?php

use dosamigos\multiselect\MultiSelectListBox;
use faro\core\models\Categoria;
use faro\core\user\UserAdminFormAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var faro\core\user\Module $module
 * @var faro\core\user\models\User $user
 * @var faro\core\user\models\Profile $profile
 * @var faro\core\user\models\Role $role
 * @var yii\widgets\ActiveForm $form
 */

$module = $this->context->module;
$role = $module->model('Role');

UserAdminFormAsset::register($this);
$categorias = ArrayHelper::map(Categoria::find()->orderBy(['nombre' => SORT_ASC])->all(), 'id', 'nombre');
//$categorias = array_values($categorias);
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
    ]); ?>

    <h5 class="mt-0 mb-2"><i class="fas fa-user mr-2"></i> Datos personales</h5>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($profile, 'full_name'); ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>

    <h5 class="my-2"><i class="fas fa-lock mr-2"></i> Datos de acceso</h5>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($user, 'role_id')->dropDownList($role::dropdown())->label('Rol'); ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($user, 'status')->dropDownList($user::statusDropdown()); ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($user, 'newPassword')->passwordInput() ?>
        </div>
    </div>

    <div class="usuario-selector-categorias d-none">
        <div class="row">
            <div class="col-12">
                <h6 class="my-2"><i class="fas fa-tags mr-1"></i> Asignación de categorías</h6>

                <div class="alert alert-info my-2 small">
                    Si no seleccionás ninguna categoría el usuario tendrá acceso a todas.
                </div>

                <?= $form->field($user, "categorias")
                    ->label(false)
                    ->checkboxList($categorias, ["class" => "checkbox-four-columns"])
                ?>

            </div>
        </div>
    </div>


    <div class="usuario-configuracion-ban <?= empty($user->banned_at) ? 'd-none' : '' ?>">
        <h5 class="my-2"><i class="fas fa-times-circle mr-2"></i> Baneo de usuario</h5>

        <?php // use checkbox for banned_at ?>
        <?php // convert `banned_at` to int so that the checkbox gets set properly ?>
        <?php $user->banned_at = $user->banned_at ? 1 : 0 ?>
        <?= Html::activeLabel($user, 'banned_at', ['label' => Yii::t('user', 'Banned')]); ?>
        <?= Html::activeCheckbox($user, 'banned_at', ['label' => false]); ?>
        <?= Html::error($user, 'banned_at'); ?>

        <?= $form->field($user, 'banned_reason'); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(
            $user->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'),
            ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
