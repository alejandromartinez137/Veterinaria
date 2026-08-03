<?php

/**
 * ConexionBD
 * Clase encargada únicamente de establecer una conexión segura con la base
 * de datos usando PDO. Cualquier fallo se captura con manejo de excepciones
 * para que el sistema nunca se detenga sin explicación.
 */
class ConexionBD
{
    private string $servidor  = "localhost";
    private string $basedatos = "santuario_mascotas";
    private string $usuario   = "root";
    private string $password  = "";
    private ?PDO $conexion    = null;

    /**
     * Devuelve una instancia PDO ya conectada, o lanza una excepción
     * controlada con un mensaje claro si algo falla.
     */
    public function obtenerConexion(): PDO
    {
        try {
            $this->conexion = new PDO(
                "mysql:host={$this->servidor};dbname={$this->basedatos};charset=utf8mb4",
                $this->usuario,
                $this->password
            );

            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $this->conexion;
        } catch (PDOException $e) {
            // No se detiene sin explicación: se relanza como excepción
            // controlada con un mensaje entendible para la capa superior.
            throw new Exception("No se pudo conectar a la base de datos: " . $e->getMessage());
        }
    }
}
