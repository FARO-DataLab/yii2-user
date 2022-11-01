<?php

namespace faro\core\user\tests\unit\models;

use faro\core\tests\unit\FaroCoreUnitTest;
use faro\core\user\models\PermisoUsuarioCategoria;
use faro\core\user\models\User;

class PermisoUsuarioCategoriaTest extends FaroCoreUnitTest
{
    /**
     * @return void
     */
    public function testCrearError(): void
    {
        // crear sin pasar variables
        $data = ['PermisoUsuarioCategoria' => []];
        $permiso = new PermisoUsuarioCategoria();
        $permiso->load($data);
        $this->assertFalse($permiso->save());
        $this->assertCount(2, $permiso->getErrors());

        // crear pasando variables incorrectas o inexistentes
        $data['PermisoUsuarioCategoria'] = [
            'usuario_id' => 8, // error: no existe el usuario,
            'categoria_id' => 10, // error: no existe la categoria
        ];

        $permiso->load($data);
        $this->assertFalse($permiso->save());
        $this->assertCount(2, $permiso->getErrors());

        // pasar permisos categorias ya existentes
        $data['PermisoUsuarioCategoria'] = [
            'usuario_id' => 2,
            'categoria_id' => 1, // error: ya existe la combinacion
        ];

        $permiso->load($data);
        $this->assertFalse($permiso->save());
        $this->assertCount(1, $permiso->getErrors());

        $erroresEsperados = [
            'usuario_id' => ['La combinación de "1"-"2" de Categoria y Usuario ya ha sido utilizada.']
        ];
        $this->assertEquals($erroresEsperados, $permiso->getErrors());
    }

    /**
     * @return void
     */
    public function testCrearOk(): void
    {
        // crear el modelo correctamente
        $data = [
            'PermisoUsuarioCategoria' => [
                'usuario_id' => 2,
                'categoria_id' => 2
            ]
        ];

        $permiso = new PermisoUsuarioCategoria();
        $permiso->load($data);
        $this->assertTrue($permiso->save());

        // revisar que levanto las categorias
        $usuario = User::find()->where(['id' => 2])->one();
        $this->assertCount(2, $usuario->categorias);
    }

    /**
     * @return void
     */
    public function testEliminarOk(): void
    {
        // eliminar un permiso del modelo
        $permiso = PermisoUsuarioCategoria::find()->where(['usuario_id' => 2, 'categoria_id' => 1])->one();
        $this->assertEquals(1, $permiso->delete());
        
        // revisar que levante correctamente luego de borrar
        $usuario = User::find()->where(['id' => 2])->one();
        $this->assertCount(0, $usuario->categorias);
    }

    /**
     * Se encarga de testear el aftersave del Usuario para guardar bien los elementos
     * @return void
     */
    public function testActualizarPermisos(): void
    {
        // @todo: levantar un usuario

        // @todo: revisar los permisos que tiene

        // @todo: actualizar el usuario pasandole el categorias helper

        // @todo: revisar que se hayan actualizado y eliminado correctamente los permisos
    }
}