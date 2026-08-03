<?php

/**
 * GuardadorBase
 * Clase base abstracta para cualquier "guardador" que necesite una
 * conexión PDO. GuardadorMascota hereda de ella (requisito 6: herencia).
 */
abstract class GuardadorBase
{
    protected PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    abstract public function guardar(object $objeto): bool;
}
