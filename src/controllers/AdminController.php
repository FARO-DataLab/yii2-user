<?php

namespace faro\core\user\controllers;

use faro\core\user\models\Role;
use Yii;
use faro\core\user\models\User;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * AdminController implements the CRUD actions for User model.
 */
class AdminController extends Controller
{
    /**
     * @var \faro\core\user\Module
     * @inheritdoc
     */
    public $module;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->view->params["navbar_menu_selected"] = "administracion";
        $this->view->params["ocultar_selector_fechas"] = true;
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'login-email'],
                        'allow' => true,
                        'roles' => ['admin']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * List all User models
     * @return mixed
     */
    public function actionIndex($baneados = false)
    {
        /** @var \faro\core\user\models\search\UserSearch $searchModel */
        $searchModel = $this->module->model("UserSearch");
        $searchModel->usuariosBaneados = $baneados;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', compact('searchModel', 'dataProvider', 'baneados'));
    }

    /**
     * Login/register via email
     */
    public function actionLoginEmail()
    {
        /** @var \faro\core\user\models\forms\LoginEmailForm $loginEmailForm */
        $loginEmailForm = $this->module->model("LoginEmailForm");

        // load post data and validate
        $post = Yii::$app->request->post();
        if ($loginEmailForm->load($post) && $loginEmailForm->sendEmail()) {
            $user = $loginEmailForm->getUser();
            $message = $user ? "Login link sent" : "Registration link sent";
            $message .= " - Please check your email";
            Yii::$app->session->setFlash("Login-success", Yii::t("user", $message));
        }

        return $this->render("loginEmail", compact("loginEmailForm"));
    }

    /**
     * Display a single User model
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'user' => $this->findModel($id),
        ]);
    }

    /**
     * Create a new User model. If creation is successful, the browser will
     * be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var \faro\core\user\models\User $user */
        /** @var \faro\core\user\models\Profile $profile */

        $user = $this->module->model("User");
        $user->setScenario("admin");
        $profile = $this->module->model("Profile");

        $post = Yii::$app->request->post();
        $userLoaded = $user->load($post);
        $profile->load($post);

        // validate for ajax request
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user, $profile);
        }

        if ($userLoaded && $user->validate() && $profile->validate()) {
            $user->save(false);
            $profile->setUser($user->id)->save(false);
            return $this->redirect(['view', 'id' => $user->id]);
        }

        // render
        return $this->render('create', compact('user', 'profile'));
    }

    /**
     * Update an existing User model. If update is successful, the browser
     * will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // set up user and profile
        $user = $this->findModel($id);
        $user->setScenario("admin");
        $profile = $user->profile;

        $post = Yii::$app->request->post();
        $userLoaded = $user->load($post);
        $profile->load($post);

        // validate for ajax request
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user, $profile);
        }

        //$categorias = $post['User']['categoriasHelper'];
        //$categorias = Json::decode($categorias);
        //if (!empty($post)) {
        //    VarDumper::dump($post, 3, true); die();
        //}

        // load post data and validate
        if ($userLoaded && $user->validate() && $profile->validate()) {
            $user->save(false);
            $profile->setUser($user->id)->save(false);
            Yii::$app->session->setFlash('success', 'Se actualizó el usuario correctamente');
            return $this->redirect(['view', 'id' => $user->username]);
        }

        // render
        return $this->render('update', compact('user', 'profile'));
    }

    /**
     * Delete an existing User model. If deletion is successful, the browser
     * will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // delete profile and userTokens first to handle foreign key constraint
        $user = $this->findModel($id);
        $profile = $user->profile;
        $userToken = $this->module->model("UserToken");
        $userAuth = $this->module->model("UserAuth");
        $userToken::deleteAll(['user_id' => $user->id]);
        $userAuth::deleteAll(['user_id' => $user->id]);
        $profile->delete();
        $user->delete();

        return $this->redirect(['index']);
    }

    /**
     * Find the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var \faro\core\user\models\User $user */
        $user = $this->module->model("User");

        $conditions = is_numeric($id) ? ['id' => $id] : ['username' => $id];
        $user = $user::findOne($conditions);
        if ($user) {
            return $user;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
