<?php

use faro\core\enums\IdiomasDisponibles;
use faro\core\widgets\Panel;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use faro\core\user\helpers\Timezone;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var faro\core\user\models\Profile $profile
 */

$this->title = t('core', 'Editar');
$this->params['breadcrumbs'][] = ['label' => t('user', 'Perfil'), 'url' => ['profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-profile">

    <?php Panel::begin(['header' => t('user', 'Actualizar perfil')]) ?>

    <?php $form = \yii\bootstrap4\ActiveForm::begin([
        'id' => 'profile-form',
        'options' => [],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'control-label'],
        ],
        'enableAjaxValidation' => true,
    ]); ?>

    <div class="form-row">
        <?= $form->field($profile, 'full_name', ['options' => ['class' => 'form-group col-12']]) ?>
    </div>

    <?php
    // by default, this contains the entire php timezone list of 400+ entries
    // so you may want to set up a fancy jquery select plugin for this, eg, select2 or chosen
    // alternatively, you could use your own filtered list
    // a good example is twitter's timezone choices, which contains ~143  entries
    // @link https://twitter.com/settings/account
    ?>

    <div class="form-row">
        <?= $form->field($profile, 'timezone',
            ['options' => ['class' => 'form-group col-md-6']])->dropDownList(ArrayHelper::map(Timezone::getAll(),
            'identifier', 'name')); ?>
        <?= $form->field($profile, 'idioma',
            ['options' => ['class' => 'form-group col-md-6']])->dropDownList(IdiomasDisponibles::obtenerEtiquetas(),
            ['prompt' => t('core', 'Seleccionar idioma')]); ?>
    </div>

    <div class="form-row">
        <div class="form-group col">
            <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php \yii\bootstrap4\ActiveForm::end(); ?>

    <?php Panel::end() ?>

</div>