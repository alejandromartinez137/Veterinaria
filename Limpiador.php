<?php

/**
 * Limpiador
 * Limpia cualquier dato ingresado antes de que llegue a la base de datos:
 * quita espacios innecesarios, barras invertidas y etiquetas HTML.
 */
class Limpiador
{
    public static function limpiarDato(string $dato): string
    {
        $dato = trim($dato);
        $dato = stripslashes($dato);
        $dato = strip_tags($dato);
        return $dato;
    }

    /**
     * Limpia todos los valores de un arreglo asociativo (por ejemplo,
     * $_POST completo) y devuelve el arreglo limpio.
     */
    public static function limpiarArreglo(array $datos): array
    {
        $limpios = [];
        foreach ($datos as $llave => $valor) {
            $limpios[$llave] = is_string($valor) ? self::limpiarDato($valor) : $valor;
        }
        return $limpios;
    }
}
