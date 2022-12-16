<?php

use faro\core\components\HtmlComponentsHelper;
use faro\core\enums\IdiomasDisponibles;
use faro\core\user\enums\EstadoUsuario;
use faro\core\user\models\Role;
use faro\core\widgets\AccionesLayoutWidget;
use faro\core\widgets\FaroDetailView;
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

$this->title = Yii::t('user', 'Profile');
$this->params['breadcrumbs'][] = $this->title;

AccionesLayoutWidget::agregarBoton(
    \yii\bootstrap4\Html::a("<i class='fas fa-edit'></i> " . t('user', 'Editar perfil'), ['edit-profile'],
        ["class" => "dropdown-item"])
);

$user = $profile->user;

?>

<div class="user-view">

    <?php if (!empty($user->banned_at)): ?>
        <div class="alert alert-danger">
            <?= t('user', 'El usuario fue expulsado del sistema. Razón:') ?>
            <blockquote class="mt-2 mb-1"><?= $user->banned_reason ?></blockquote>
        </div>
    <?php endif ?>

    <div class="row">
        <div class="col-lg-3">

            <?php Panel::begin(['header' => false]) ?>

            <div class="alert alert-info small text-center">
                <h6 class="m-0"><?= t('core', 'Usuario administrador') ?></h6>
            </div>
            
            <div class="w-100 mb-3">
                <div class="faro-profile-picture"><?= $user->profile->getIniciales() ?></div>
            </div>

            <?= FaroDetailView::widget([
                'model' => $user,
                'attributes' => [
                    'email:email',
                    'username',
                    'profile.full_name',
                    'profile.idioma',
                    'id',
                    'logged_in_ip',
                    'logged_in_at:relativeTime',
                    'created_at',
                ],
            ]) ?>

            <?php Panel::end() ?>

        </div>
        <div class="col-lg-9">

            <?php Panel::begin(['header' => t('core', 'Actividad'), 'margenTop' => false]) ?>

            <div class="alert alert-info mb-0">
                <h5 class="my-0"><?= t('core', 'Próximamente') ?></h5>
            </div>

            <?php Panel::end() ?>

        </div>
    </div>


</div>