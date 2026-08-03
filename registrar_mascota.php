<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/Mascota.php";
require_once __DIR__ . "/Limpiador.php";
require_once __DIR__ . "/GuardadorMascota.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $datos = Limpiador::limpiarArreglo($_POST);

    try {
        // 1. Instanciar objeto Mascota
        $mascota = new Mascota(
            $datos["nombre"] ?? "",
            $datos["especie"] ?? "",
            $datos["raza"] ?? "",
            (int)($datos["edad"] ?? 0),
            (float)($datos["peso_actual"] ?? 0),
            $datos["senas_fisicas"] ?? "",
            $datos["nombre_responsable"] ?? "",
            $datos["telefono_emergencia"] ?? ""
        );

        // 2. Conectar e Insertar
        $conexionBD = new ConexionBD();
        $pdo = $conexionBD->obtenerConexion();

        $guardador = new GuardadorMascota($pdo);
        $guardadoOk = $guardador->guardar($mascota);

        // 3. Volvemos al dashboard con un mensaje, para que la mascota
        //    nueva aparezca de inmediato en la Lista de Mascotas.
        if ($guardadoOk) {
            $mensaje = "La mascota " . $mascota->getNombre() . " fue registrada correctamente.";
            header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=exito#lista-mascotas");
            exit;
        } else {
            $mensaje = "No se pudo guardar el registro en la base de datos.";
            header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=error#registro-pacientes");
            exit;
        }

    } catch (Exception $e) {
        // Mostrar el error exacto si la base de datos o el código fallan
        $mensaje = "Error en la base de datos o el código: " . $e->getMessage();
        header("Location: dashboard_vet.php?msg=" . urlencode($mensaje) . "&tipo=error#registro-pacientes");
        exit;
    }
} else {
    // Si entran directo al archivo por URL
    header("Location: dashboard_vet.php");
    exit;
}
?>