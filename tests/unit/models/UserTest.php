<?php /** @noinspection PhpStaticAsDynamicMethodCallInspection */

namespace faro\core\user\tests\unit\models;

use faro\core\tests\unit\FaroCoreUnitTest;
use faro\core\user\models\PermisoUsuarioCategoria;
use faro\core\user\models\User;
use faro\core\user\Module;
use Yii;
use yii\helpers\VarDumper;

class UserTest extends FaroCoreUnitTest
{
    /** @var Module */
    protected $modulo;

    public function _before()
    {
        parent::_before();
        Yii::$app->i18n->translations['user'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => __DIR__ . '/../../../src/messages/'
        ];
    }

    /**√
     * @return void
     */
    public function testLevantar(): void
    {
        $usuario = User::find()->where(['id' => 1])->one();
        $usuario = $this->inicializarModeloConModulo($usuario);

        $this->assertEquals(1, $usuario->role_id);
        $this->assertEquals('admin', $usuario->username);
        $this->assertNotEmpty($usuario->profile);
        $this->assertEquals('Gonzalo Bourdieu Admin', $usuario->profile->full_name);
    }

    /**
     * @return void
     */
    public function testCrearError(): void
    {
        $user = $this->inicializarModeloConModulo();

        // crear un usuario sin parametros
        $data = ['User' => []];
        $user->load($data);
        $user->setScenario('admin');
        $this->assertFalse($user->save());

        $errores = $user->getErrors();
        $this->assertCount(4, $errores);

        $erroresEsperados = [
            'email' => ['Correo elecrónico no puede estar vacío.'],
            'role_id' => ['Código de Rol no puede estar vacío.'],
            'status' => ['Estado no puede estar vacío.'],
            'newPassword' => ['Contraseña no puede estar vacío.'],
        ];
        $this->assertEquals($erroresEsperados, $errores);

        // aunque le pase todos los argumentos sino le paso el password no pasa nada
        $data['User'] = [
            'email' => 'prueba@faro.works',
            'role_id' => 2,
            'status' => 1
        ];

        $user->load($data);
        $this->assertFalse($user->save());
        $this->assertCount(1, $user->getErrors());
    }

    /**
     * @return void
     */
    public function testCrearOk(): void
    {
        // crear un usuario correctamente
        $usuario = $this->inicializarModeloConModulo();
        $usuario->setScenario('admin');

        $data = [
            'User' => [
                'email' => 'prueba@faro.works',
                'username' => 'pruebatest',
                'role_id' => 2,
                'status' => 1,
                'newPassword' => '123456',
            ],
            'Profile' => [
                'full_name' => 'Usuario prueba tradicional'
            ]
        ];

        $usuario->load($data);
        $this->assertTrue($usuario->save());
        $this->assertNotSame('123456', $usuario->password);

    }

    /**
     * @return void
     */
    public function testCrearConPermisosCategoria(): void
    {
        $usuario = $this->inicializarModeloConModulo();
        $usuario->setScenario('admin');

        $data = [
            'User' => [
                'email' => 'pruebaconpermisos@faro.works',
                'username' => 'pruebatest',
                'role_id' => 2,
                'status' => 1,
                'newPassword' => '123456',
                'categorias' => ['1']
            ],
            'Profile' => [
                'full_name' => 'Usuario prueba tradicional'
            ]
        ];

        $usuario->load($data);
        $this->assertTrue($usuario->save());
        $this->assertNotSame('123456', $usuario->password);

        $permisosCategoria = PermisoUsuarioCategoria::find()->where(['usuario_id' => $usuario->getId()])->count();
        $this->assertEquals(1, $permisosCategoria);
    }

    /**
     * @return User
     * @throws \yii\base\InvalidConfigException
     */
    protected function inicializarModeloConModulo(?User $usuario = null): User
    {
        if ($usuario === null) {
            $usuario = new User();
        }

        if (empty($this->modulo)) {
            $this->modulo = Yii::createObject(Module::class);
        }

        $usuario->module = $this->modulo;
        return $usuario;
    }
}