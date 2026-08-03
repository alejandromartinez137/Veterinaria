<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/Mascota.php";
require_once __DIR__ . "/Limpiador.php";
require_once __DIR__ . "/GuardadorMascota.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $datos = Limpiador::limpiarArreglo($_POST);
    $id = (int) ($datos["id"] ?? 0);

    try {
        if ($id <= 0) {
            throw new InvalidArgumentException("Id de mascota inválido, no se puede actualizar.");
        }

        // 1. Instanciar objeto Mascota con los datos editados
        $mascota = new Mascota(
            $datos["nombre"] ?? "",
            $datos["especie"] ?? "",
            $datos["raza"] ?? "",
            (int) ($datos["edad"] ?? 0),
            (float) ($datos["peso_actual"] ?? 0),
            $datos["senas_fisicas"] ?? "",
            $datos["nombre_responsable"] ?? "",
            $datos["telefono_emergencia"] ?? ""
        );
        $mascota->setId($id);

        // 2. Conectar y actualizar
        $conexionBD = new ConexionBD();
        $pdo = $conexionBD->obtenerConexion();

        $guardador = new GuardadorMascota($pdo);
        $actualizadoOk = $guardador->actualizar($mascota);

        if ($actualizadoOk) {
            $mensaje = "Los datos de " . $mascota->getNombre() . " fueron actualizados correctamente.";
            header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=exito#lista-mascotas");
            exit;
        } else {
            $mensaje = "No se pudo actualizar la mascota en la base de datos.";
            header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=error&editar=" . $id . "#actualizar-mascota");
            exit;
        }

    } catch (Exception $e) {
        $mensaje = "Error al actualizar: " . $e->getMessage();
        header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=error#actualizar-mascota");
        exit;
    }
} else {
    // Si entran directo al archivo por URL
    header("Location: dashboard_vet.php");
    exit;
}
