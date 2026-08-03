<?php


class Mascota
{
    protected ?int $id = null;
    protected string $nombre;
    protected string $especie;
    protected string $raza;
    protected int $edad;
    protected float $pesoActual;
    protected string $senasFisicas;
    protected string $nombreResponsable;
    protected string $telefonoEmergencia;

    public function __construct(
        string $nombre,
        string $especie,
        string $raza,
        int $edad,
        float $pesoActual,
        string $senasFisicas,
        string $nombreResponsable,
        string $telefonoEmergencia
    ) {
        $this->nombre             = $nombre;
        $this->especie            = $especie;
        $this->raza               = $raza;
        $this->edad               = $edad;
        $this->senasFisicas       = $senasFisicas;
        $this->nombreResponsable  = $nombreResponsable;
        $this->telefonoEmergencia = $telefonoEmergencia;

        // Pasa por el setter para que la validación del peso
        // se aplique también al construir el objeto.
        $this->setPesoActual($pesoActual);
    }

    // ---------- Getters ----------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getEspecie(): string
    {
        return $this->especie;
    }

    public function getRaza(): string
    {
        return $this->raza;
    }

    public function getEdad(): int
    {
        return $this->edad;
    }

    public function getPesoActual(): float
    {
        return $this->pesoActual;
    }

    public function getSenasFisicas(): string
    {
        return $this->senasFisicas;
    }

    public function getNombreResponsable(): string
    {
        return $this->nombreResponsable;
    }

    public function getTelefonoEmergencia(): string
    {
        return $this->telefonoEmergencia;
    }

    // ---------- Setters ----------

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setEspecie(string $especie): void
    {
        $this->especie = $especie;
    }

    public function setRaza(string $raza): void
    {
        $this->raza = $raza;
    }

    public function setEdad(int $edad): void
    {
        $this->edad = $edad;
    }

    /**
     * Requisito 4: el peso debe ser numérico (tipado float) y mayor que
     * cero. Si no cumple, se rechaza lanzando una excepción con un
     * mensaje de error claro.
     */
    public function setPesoActual(float $pesoActual): void
    {
        if ($pesoActual <= 0) {
            throw new InvalidArgumentException(
                "El peso debe ser un valor numérico mayor que cero."
            );
        }
        $this->pesoActual = $pesoActual;
    }

    public function setSenasFisicas(string $senasFisicas): void
    {
        $this->senasFisicas = $senasFisicas;
    }

    public function setNombreResponsable(string $nombreResponsable): void
    {
        $this->nombreResponsable = $nombreResponsable;
    }

    public function setTelefonoEmergencia(string $telefonoEmergencia): void
    {
        $this->telefonoEmergencia = $telefonoEmergencia;
    }
}
