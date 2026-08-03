<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/Mascota.php";
require_once __DIR__ . "/GuardadorMascota.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = (int) ($_POST["id"] ?? 0);

    try {
        if ($id <= 0) {
            throw new InvalidArgumentException("Id de mascota inválido, no se puede eliminar.");
        }

        $conexionBD = new ConexionBD();
        $pdo = $conexionBD->obtenerConexion();

        $guardador = new GuardadorMascota($pdo);

        // Buscamos el nombre antes de borrar, solo para el mensaje de confirmación
        $mascota = $guardador->obtenerPorId($id);
        $nombre = $mascota ? $mascota->getNombre() : "La mascota";

        $eliminadoOk = $guardador->eliminar($id);

        if ($eliminadoOk) {
            $mensaje = "$nombre fue eliminada del expediente correctamente.";
            header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=exito#lista-mascotas");
            exit;
        } else {
            $mensaje = "No se pudo eliminar la mascota de la base de datos.";
            header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=error#lista-mascotas");
            exit;
        }

    } catch (Exception $e) {
        $mensaje = "Error al eliminar: " . $e->getMessage();
        header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=error#lista-mascotas");
        exit;
    }
} else {
    header("Location: dashboard_vet.php");
    exit;
}
