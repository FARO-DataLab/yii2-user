<?php

use faro\core\components\ControlUsuarios;
use faro\core\components\HtmlComponentsHelper;
use faro\core\user\enums\EstadoUsuario;
use faro\core\user\models\Role;
use faro\core\widgets\AccionesLayoutWidget;
use faro\core\widgets\FaroDetailView;
use faro\core\widgets\Panel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var faro\core\user\models\User $user
 */

$this->title = $user->profile->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administración'), 'url' => ['/faro/admin']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['/user/admin/']];
$this->params['breadcrumbs'][] = $this->title;

if (ControlUsuarios::esAdmin()) {
    AccionesLayoutWidget::agregarBoton(
        \yii\bootstrap4\Html::a(
            "<i class='fas fa-edit'></i> Editar usuario",
            ['update', 'id' => $user->username],
            ['class' => 'dropdown-item']
        )
    );
}
?>

<div class="user-view">
    
    <?php if (!empty($user->banned_at)): ?>
    <div class="alert alert-danger">
        El usuario fue expulsado del sistema. Razón:
        <blockquote class="mt-2 mb-1"><?= $user->banned_reason ?></blockquote>
    </div>
    <?php endif ?>

    <div class="row">
        <div class="col-lg-3">

            <?php Panel::begin(['header' => false]) ?>

            <div class="w-100 text-center mb-3">
                <div class="mx-auto d-inline-block"
                     style="padding: 30px 35px 30px 35px; color: white; background: grey; border-radius: 50%; border: 1px solid grey;">
                    <i class="fas fa-user mx-auto" aria-hidden="true" style="font-size: 80px;"></i>
                </div>
            </div>

            <?= FaroDetailView::widget([
                'model' => $user,
                'attributes' => [
                    'status' => [
                        'attribute' => 'status',
                        'value' => EstadoUsuario::obtenerEtiqueta((int)$user->status),
                    ],
                    'email:email',
                    'username',
                    'profile.full_name',
                    'id',
                    'logged_in_ip',
                    'logged_in_at:relativeTime',
                    'created_at',
                ],
            ]) ?>

            <?php Panel::end() ?>

        </div>
        <div class="col-lg-9">

            <?php if ($user->role_id === Role::ROLE_ADMIN): ?>
                <div class="alert alert-info">
                    <h4 class="m-0">Usuario administrador</h4>
                </div>

            <?php else: ?>
                <?php Panel::begin(['header' => 'Accesos']) ?>
                <?php
                $categorias = $user->categorias;
                $categoriasPrint = ArrayHelper::map($categorias, 'id', 'nombre');
                ?>
                <ul class="list-unstyled">
                    <li>Acceso estándar</li>
                    <li>Categorías:
                        <?php if (empty($categorias)): ?>
                            Todas.
                        <?php else: ?>
                            <br>
                            <?php foreach ($categorias as $categoria): ?>
                                <?= HtmlComponentsHelper::imprimirBadgeCategoria($categoria->nombre, $categoria->slug,
                                    $categoria->color, 'mx-2') ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </li>
                </ul>

                <?php Panel::end() ?>

            <?php endif ?>

            <?php Panel::begin(['header' => 'Actividad', 'margenTop' => true]) ?>

            <div class="alert alert-info mb-0">
                <h5 class="my-0">Próximamente</h5>
            </div>

            <?php Panel::end() ?>

        </div>
    </div>


</div>
