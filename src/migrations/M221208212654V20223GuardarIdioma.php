<?php

namespace faro\core\user\migrations;

use faro\core\components\FaroBaseMigration;
use yii\db\Migration;

/**
 * Class M221208212654V20223GuardarIdioma
 */
class M221208212654V20223GuardarIdioma extends FaroBaseMigration
{
    protected $tabla = "{{%core_acl_perfil}}";
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tabla, 'idioma', $this->string(5)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tabla, 'idioma');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M221208212654V20223GuardarIdioma cannot be reverted.\n";

        return false;
    }
    */
}
