<?php

namespace faro\core\user\enums;

use faro\core\helpers\BaseEnum;
use yii\base\InvalidArgumentException;

/**
 * Listado de todos los origenes de supermetrics
 */
abstract class EstadoUsuario extends BaseEnum
{
    public const INACTIVO = 0;
    public const ACTIVO = 1;
    public const MAIL_SIN_CONFIRMAR = 2;

    /**
     * @param int $estado
     * @return string
     */
    public static function obtenerEtiqueta(int $estado): string
    {
        if ($estado === '') {
            return '-';
        }

        if (!self::isValidValue($estado)) {
            throw new InvalidArgumentException('Estado de usuario invalido: ' . $estado);
        }

        $etiquetas = self::obtenerEtiquetas();
        return $etiquetas[$estado];
    }

    /**
     * @return string[]
     */
    public static function obtenerEtiquetas(): array
    {
        return [
            self::INACTIVO => 'Inactivo',
            self::ACTIVO => 'Activo',
            self::MAIL_SIN_CONFIRMAR => 'Mail sin confirmar',
        ];
    }
}