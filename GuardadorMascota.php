<?php

require_once __DIR__ . "/GuardadorBase.php";
require_once __DIR__ . "/Mascota.php";

/**
 * GuardadorMascota
 * Clase especializada (hereda de GuardadorBase) que recibe un objeto
 * Mascota, obtiene sus datos mediante getters y los guarda en la tabla
 * Mascotas usando una consulta preparada, sin concatenar SQL directamente.
 */
class GuardadorMascota extends GuardadorBase
{
    public function guardar(object $objeto): bool
    {
        if (!$objeto instanceof Mascota) {
            throw new InvalidArgumentException("Se esperaba un objeto de tipo Mascota.");
        }

        $sql = "INSERT INTO Mascotas
                    (nombre, especie, raza, edad, peso_actual, senas_fisicas,
                     nombre_responsable, telefono_emergencia)
                VALUES
                    (:nombre, :especie, :raza, :edad, :peso_actual, :senas_fisicas,
                     :nombre_responsable, :telefono_emergencia)";

        try {
            $consulta = $this->conexion->prepare($sql);

            $consulta->bindValue(":nombre", $objeto->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(":especie", $objeto->getEspecie(), PDO::PARAM_STR);
            $consulta->bindValue(":raza", $objeto->getRaza(), PDO::PARAM_STR);
            $consulta->bindValue(":edad", $objeto->getEdad(), PDO::PARAM_INT);
            $consulta->bindValue(":peso_actual", $objeto->getPesoActual());
            $consulta->bindValue(":senas_fisicas", $objeto->getSenasFisicas(), PDO::PARAM_STR);
            $consulta->bindValue(":nombre_responsable", $objeto->getNombreResponsable(), PDO::PARAM_STR);
            $consulta->bindValue(":telefono_emergencia", $objeto->getTelefonoEmergencia(), PDO::PARAM_STR);

            $consulta->execute();

            $objeto->setId((int) $this->conexion->lastInsertId());

            return true;
        } catch (PDOException $e) {
            // Se captura el error y se informa de forma controlada,
            // sin detener el sistema abruptamente.
            error_log("Error al guardar mascota: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Requisito CRUD (R): devuelve todas las mascotas registradas en la
     * base de datos como un arreglo de objetos Mascota, de la más
     * reciente a la más antigua.
     *
     * @return Mascota[]
     */
    public function obtenerTodas(): array
    {
        $sql = "SELECT * FROM Mascotas ORDER BY id DESC";

        try {
            $consulta = $this->conexion->query($sql);
            $filas = $consulta->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn(array $fila) => $this->mapearFila($fila), $filas);
        } catch (PDOException $e) {
            error_log("Error al obtener mascotas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Requisito CRUD (R): busca una sola mascota por su id.
     * Devuelve null si no existe o si ocurre un error de conexión.
     */
    public function obtenerPorId(int $id): ?Mascota
    {
        $sql = "SELECT * FROM Mascotas WHERE id = :id LIMIT 1";

        try {
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();

            $fila = $consulta->fetch(PDO::FETCH_ASSOC);

            return $fila ? $this->mapearFila($fila) : null;
        } catch (PDOException $e) {
            error_log("Error al buscar mascota: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Requisito CRUD (U): actualiza los datos de una mascota existente.
     * El objeto debe traer un id válido (mayor que 0), de lo contrario
     * se rechaza antes de tocar la base de datos.
     */
    public function actualizar(object $objeto): bool
    {
        if (!$objeto instanceof Mascota) {
            throw new InvalidArgumentException("Se esperaba un objeto de tipo Mascota.");
        }

        if (!$objeto->getId()) {
            throw new InvalidArgumentException("No se puede actualizar una mascota sin id.");
        }

        $sql = "UPDATE Mascotas SET
                    nombre = :nombre,
                    especie = :especie,
                    raza = :raza,
                    edad = :edad,
                    peso_actual = :peso_actual,
                    senas_fisicas = :senas_fisicas,
                    nombre_responsable = :nombre_responsable,
                    telefono_emergencia = :telefono_emergencia
                WHERE id = :id";

        try {
            $consulta = $this->conexion->prepare($sql);

            $consulta->bindValue(":nombre", $objeto->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(":especie", $objeto->getEspecie(), PDO::PARAM_STR);
            $consulta->bindValue(":raza", $objeto->getRaza(), PDO::PARAM_STR);
            $consulta->bindValue(":edad", $objeto->getEdad(), PDO::PARAM_INT);
            $consulta->bindValue(":peso_actual", $objeto->getPesoActual());
            $consulta->bindValue(":senas_fisicas", $objeto->getSenasFisicas(), PDO::PARAM_STR);
            $consulta->bindValue(":nombre_responsable", $objeto->getNombreResponsable(), PDO::PARAM_STR);
            $consulta->bindValue(":telefono_emergencia", $objeto->getTelefonoEmergencia(), PDO::PARAM_STR);
            $consulta->bindValue(":id", $objeto->getId(), PDO::PARAM_INT);

            return $consulta->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar mascota: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Requisito CRUD (D): elimina una mascota por su id.
     */
    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM Mascotas WHERE id = :id";

        try {
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);

            return $consulta->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar mascota: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Convierte una fila de la base de datos (arreglo asociativo) en un
     * objeto Mascota completo, incluyendo su id.
     */
    private function mapearFila(array $fila): Mascota
    {
        $mascota = new Mascota(
            $fila["nombre"],
            $fila["especie"],
            $fila["raza"],
            (int) $fila["edad"],
            (float) $fila["peso_actual"],
            $fila["senas_fisicas"],
            $fila["nombre_responsable"],
            $fila["telefono_emergencia"]
        );
        $mascota->setId((int) $fila["id"]);

        return $mascota;
    }
}
