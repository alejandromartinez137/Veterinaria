<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/Mascota.php";
require_once __DIR__ . "/Limpiador.php";
require_once __DIR__ . "/GuardadorMascota.php";


class SubMenuItem
{
    private string $texto;
    private string $enlace;
    private string $icono;

    public function __construct(string $texto, string $enlace = "#", string $icono = "•")
    {
        $this->texto  = $texto;
        $this->enlace = $enlace;
        $this->icono  = $icono;
    }

    public function render(): string
    {
        return '
            <a href="' . htmlspecialchars($this->enlace) . '" 
               class="w3-bar-item w3-button submenu-item">
                <span class="w3-margin-right">' . $this->icono . '</span>' . htmlspecialchars($this->texto) . '
            </a>';
    }
}

class MenuPrincipal
{
    private string $titulo;
    private string $icono;
    private array $submenus = [];
    private string $idPanel;

    public function __construct(string $titulo, string $icono, string $idPanel)
    {
        $this->titulo  = $titulo;
        $this->icono   = $icono;
        $this->idPanel = $idPanel;
    }

    public function agregarSubMenu(SubMenuItem $item): void
    {
        $this->submenus[] = $item;
    }

    public function render(): string
    {
        $html = '
        <button onclick="toggleSubmenu(\'' . $this->idPanel . '\')" 
                class="w3-bar-item w3-button w3-left-align menu-principal">
            <span class="w3-margin-right">' . $this->icono . '</span>' . htmlspecialchars($this->titulo) . '
            <span class="w3-right flecha">▾</span>
        </button>
        <div id="' . $this->idPanel . '" class="w3-hide submenu-container">';

        foreach ($this->submenus as $sub) {
            $html .= $sub->render();
        }

        $html .= '</div>';

        return $html;
    }
}

class BarraLateral
{
    private string $nombreSantuario;
    private string $nombreAdmin;
    private string $logoTexto;
    private array $menus = [];

    public function __construct(string $nombreSantuario, string $nombreAdmin, string $logoTexto)
    {
        $this->nombreSantuario = $nombreSantuario;
        $this->nombreAdmin     = $nombreAdmin;
        $this->logoTexto       = $logoTexto;
    }

    public function agregarMenu(MenuPrincipal $menu): void
    {
        $this->menus[] = $menu;
    }

    private function renderIdentidad(): string
    {
        return '
        <div class="identidad-panel">
            <div class="w3-center w3-padding-24">
                <div class="logo-circulo w3-center">
                    <span>' . htmlspecialchars($this->logoTexto) . '</span>
                </div>
                <h3 class="w3-margin-top w3-margin-bottom-0">' . htmlspecialchars($this->nombreSantuario) . '</h3>
                <p class="w3-small" style="color: #e0e0e0;">Sistema de Gestión</p>
                <hr style="border-color: rgba(255,255,255,0.2);">
                <div class="w3-row">
                    <div class="w3-col s3">
                        <i class="w3-xlarge"></i>
                    </div>
                    <div class="w3-col s9 w3-left-align">
                        <p class="w3-margin-bottom-0"><b>' . htmlspecialchars($this->nombreAdmin) . '</b></p>
                        <p class="w3-small" style="color: #e0e0e0;">Administrador</p>
                    </div>
                </div>
            </div>
        </div>';
    }

    public function render(): string
    {
        $html = '<div class="w3-sidebar w3-bar-block w3-collapse w3-top w3-large barra-lateral" 
                      style="z-index:3;width:260px;" id="mySidebar">';

        $html .= $this->renderIdentidad();

        $html .= '<div class="menus-panel">';

        foreach ($this->menus as $menu) {
            $html .= '<div class="w3-bar-block menu-bloque">' . $menu->render() . '</div>';
        }

        $html .= '</div>';
        $html .= '<div class="w3-hide-large w3-hide-medium w3-padding-16"></div>';
        $html .= '</div>';

        return $html;
    }
}

class CampoFormulario
{
    private string $etiqueta;
    private string $nombre;
    private string $tipo;
    private string $columnas;
    private string $placeholder;
    private string $extra;
    private string $valor;

    public function __construct(
        string $etiqueta,
        string $nombre,
        string $tipo = "text",
        string $columnas = "s6",
        string $placeholder = "",
        string $extra = "",
        string $valor = ""
    ) {
        $this->etiqueta    = $etiqueta;
        $this->nombre      = $nombre;
        $this->tipo        = $tipo;
        $this->columnas    = $columnas;
        $this->placeholder = $placeholder;
        $this->extra       = $extra;
        $this->valor       = $valor;
    }

    public function render(): string
    {
        return '
        <div class="w3-' . $this->columnas . ' w3-container w3-margin-bottom">
            <label style="color: #000; font-weight: bold;">' . htmlspecialchars($this->etiqueta) . '</label>
            <input class="w3-input w3-border w3-round campo-input" type="' . $this->tipo . '" 
                   name="' . $this->nombre . '" 
                   value="' . htmlspecialchars($this->valor) . '"
                   placeholder="' . htmlspecialchars($this->placeholder) . '" ' . $this->extra . '>
        </div>';
    }
}

class FormularioRegistroMascotas
{
    private array $campos = [];

    public function agregarCampo(CampoFormulario $campo): void
    {
        $this->campos[] = $campo;
    }

    public function render(): string
    {
        $html = '
        <div class="w3-card w3-white w3-padding-24 w3-margin-bottom panel-formulario" id="registro-pacientes">
            <h2 class="w3-border-bottom w3-padding-16" style="color: #000;">
                 Registro de Mascotas
            </h2>
            <form method="POST" action="registrar_mascota.php">                <div class="w3-row-padding">';

        foreach ($this->campos as $campo) {
            $html .= $campo->render();
        }

        $html .= '
                </div>

                <div class="w3-padding-16 w3-right-align">
                    <button type="reset" class="w3-button w3-round w3-border w3-margin-right boton-limpiar" style="color: #000;">
                         Limpiar
                    </button>
                    <button type="submit" class="w3-button w3-round boton-guardar">
                         Guardar Mascota
                    </button>
                </div>
            </form>
        </div>';

        return $html;
    }
}

/**
 * TablaMascotas
 * Requisito CRUD (R + acceso a U/D): recibe un arreglo de objetos
 * Mascota y los dibuja en una tabla, con un botón "Editar" (que carga
 * el formulario de actualización) y un botón "Eliminar" (que borra el
 * registro tras confirmación) por cada fila.
 */
class TablaMascotas
{
    private int $totalMascotas;
    private int $totalPaginas;
    private int $paginaActual;

    /** @var Mascota[] solo las mascotas que corresponden a la página actual */
    private array $mascotasPagina;

    /**
     * @param Mascota[] $mascotas todas las mascotas (sin recortar)
     * @param int $paginaActual página solicitada por el usuario (1, 2, 3...)
     * @param int $porPagina cuántos registros se muestran por página
     */
    public function __construct(private array $mascotas, int $paginaActual = 1, private int $porPagina = 5)
    {
        $this->totalMascotas = count($mascotas);
        $this->totalPaginas  = max(1, (int) ceil($this->totalMascotas / $this->porPagina));

        // Nunca dejamos que la página pedida se salga del rango válido
        $this->paginaActual = min(max(1, $paginaActual), $this->totalPaginas);

        $inicio = ($this->paginaActual - 1) * $this->porPagina;
        $this->mascotasPagina = array_slice($this->mascotas, $inicio, $this->porPagina);
    }

    private function renderFila(Mascota $mascota): string
    {
        $id = $mascota->getId();

        return '
        <tr>
            <td>' . htmlspecialchars($mascota->getNombre()) . '</td>
            <td>' . htmlspecialchars($mascota->getEspecie()) . '</td>
            <td>' . htmlspecialchars($mascota->getRaza()) . '</td>
            <td>' . $mascota->getEdad() . '</td>
            <td>' . number_format($mascota->getPesoActual(), 2) . ' kg</td>
            <td>' . htmlspecialchars($mascota->getSenasFisicas()) . '</td>
            <td>' . htmlspecialchars($mascota->getNombreResponsable()) . '</td>
            <td>' . htmlspecialchars($mascota->getTelefonoEmergencia()) . '</td>
            <td class="w3-center">
                <a href="dashboard_vet.php?editar=' . $id . '#actualizar-mascota"
                   class="w3-button w3-round w3-small boton-editar">Editar</a>
                <form action="eliminar_mascota.php" method="POST" style="display:inline;"
                      onsubmit="return confirm(\'¿Eliminar el registro de ' . htmlspecialchars(addslashes($mascota->getNombre())) . '? Esta acción no se puede deshacer.\');">
                    <input type="hidden" name="id" value="' . $id . '">
                    <button type="submit" class="w3-button w3-round w3-small boton-eliminar">Eliminar</button>
                </form>
            </td>
        </tr>';
    }

    /**
     * Dibuja los números de página (1, 2, 3...) abajo de la tabla.
     * Cada número es un enlace a dashboard_vet.php?pagina=N#lista-mascotas,
     * y el número de la página actual se resalta.
     */
    private function renderPaginacion(): string
    {
        if ($this->totalPaginas <= 1) {
            return "";
        }

        $html = '<div class="w3-center paginacion-mascotas">';

        if ($this->paginaActual > 1) {
            $html .= '<a href="dashboard_vet.php?pagina=' . ($this->paginaActual - 1) . '#lista-mascotas" class="w3-button w3-round pagina-item">&laquo;</a>';
        }

        for ($numero = 1; $numero <= $this->totalPaginas; $numero++) {
            $clase = "w3-button w3-round pagina-item";
            if ($numero === $this->paginaActual) {
                $clase .= " pagina-activa";
            }
            $html .= '<a href="dashboard_vet.php?pagina=' . $numero . '#lista-mascotas" class="' . $clase . '">' . $numero . '</a>';
        }

        if ($this->paginaActual < $this->totalPaginas) {
            $html .= '<a href="dashboard_vet.php?pagina=' . ($this->paginaActual + 1) . '#lista-mascotas" class="w3-button w3-round pagina-item">&raquo;</a>';
        }

        $html .= '</div>';

        return $html;
    }

    public function render(): string
    {
        $html = '
        <div class="w3-card w3-white w3-padding-24 w3-margin-bottom panel-formulario" id="lista-mascotas">
            <h2 class="w3-border-bottom w3-padding-16" style="color: #000;">
                Lista de Mascotas
            </h2>';

        if (empty($this->mascotas)) {
            $html .= '<p style="color:#000;">Todavía no hay mascotas registradas. Usa el formulario de "Registro de Mascotas" para agregar la primera.</p>';
        } else {
            $html .= '
            <div style="overflow-x:auto;">
            <table class="w3-table w3-striped w3-bordered tabla-mascotas">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th>Edad</th>
                        <th>Peso</th>
                        <th>Señas Físicas</th>
                        <th>Responsable</th>
                        <th>Teléfono</th>
                        <th class="w3-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($this->mascotasPagina as $mascota) {
                $html .= $this->renderFila($mascota);
            }

            $html .= '
                </tbody>
            </table>
            </div>';

            $html .= $this->renderPaginacion();
        }

        $html .= '</div>';

        return $html;
    }
}

/**
 * FormularioActualizarMascota
 * Requisito CRUD (U): reutiliza CampoFormulario para mostrar los datos
 * actuales de una mascota (precargados) y enviarlos a actualizar_mascota.php.
 * Si no se ha seleccionado ninguna mascota para editar, muestra un
 * mensaje de invitación en lugar de un formulario vacío.
 */
class FormularioActualizarMascota
{
    public function __construct(private ?Mascota $mascota) {}

    public function render(): string
    {
        $html = '<div class="w3-card w3-white w3-padding-24 w3-margin-bottom panel-formulario" id="actualizar-mascota">
            <h2 class="w3-border-bottom w3-padding-16" style="color: #000;">
                Actualizar Datos de Mascota
            </h2>';

        if (!$this->mascota) {
            $html .= '<p style="color:#000;">Selecciona "Editar" en la Lista de Mascotas para actualizar sus datos aquí.</p></div>';
            return $html;
        }

        $m = $this->mascota;

        $campos = [
            new CampoFormulario("Nombre de la Mascota", "nombre", "text", "half", "Ej. Firulais", "", $m->getNombre()),
            new CampoFormulario("Especie", "especie", "text", "half", "Ej. Canino, Felino", "", $m->getEspecie()),
            new CampoFormulario("Raza", "raza", "text", "half", "Ej. Labrador", "", $m->getRaza()),
            new CampoFormulario("Edad (años)", "edad", "number", "half", "Ej. 3", 'min="0"', (string) $m->getEdad()),
            new CampoFormulario("Peso Actual (kg)", "peso_actual", "number", "half", "Ej. 12.5", 'step="0.01" min="0.01"', (string) $m->getPesoActual()),
            new CampoFormulario("Color / Señas Físicas", "senas_fisicas", "text", "half", "Ej. Café con mancha blanca en el pecho", "", $m->getSenasFisicas()),
            new CampoFormulario("Nombre del Responsable", "nombre_responsable", "text", "half", "Ej. Ana Martínez", "", $m->getNombreResponsable()),
            new CampoFormulario("Teléfono de Emergencia", "telefono_emergencia", "tel", "half", "Ej. 9999-9999", "", $m->getTelefonoEmergencia()),
        ];

        $html .= '<form method="POST" action="actualizar_mascota.php">
                <input type="hidden" name="id" value="' . $m->getId() . '">
                <div class="w3-row-padding">';

        foreach ($campos as $campo) {
            $html .= $campo->render();
        }

        $html .= '
                </div>
                <div class="w3-padding-16 w3-right-align">
                    <a href="dashboard_vet.php#lista-mascotas" class="w3-button w3-round w3-border w3-margin-right boton-limpiar" style="color:#000;">
                        Cancelar
                    </a>
                    <button type="submit" class="w3-button w3-round boton-guardar">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>';

        return $html;
    }
}

class TarjetaResumen
{
    public function __construct(
        private string $titulo,
        private string $valor,
        private string $icono,
        private string $color
    ) {}

    public function render(): string
    {
        return '
        <div class="w3-quarter w3-container w3-margin-bottom">
            <div class="w3-card w3-padding w3-white tarjeta-resumen">
                <div class="w3-row">
                    <div class="w3-col s4">
                        <div class="icono-circulo ' . $this->color . '">' . $this->icono . '</div>
                    </div>
                    <div class="w3-col s8 w3-right-align">
                        <p class="w3-large w3-margin-bottom-0" style="color: #000;"><b>' . htmlspecialchars($this->valor) . '</b></p>
                        <p class="w3-small" style="color: #000; margin-top: 4px;">' . htmlspecialchars($this->titulo) . '</p>
                    </div>
                </div>
            </div>
        </div>';
    }
}

class DashboardSantuario
{
    private BarraLateral $barraLateral;
    private FormularioRegistroMascotas $formulario;
    private TablaMascotas $tablaMascotas;
    private FormularioActualizarMascota $formularioActualizar;
    private array $tarjetas = [];
    private string $mensaje;
    private string $tipoMensaje;

    public function __construct(
        BarraLateral $barraLateral,
        FormularioRegistroMascotas $formulario,
        TablaMascotas $tablaMascotas,
        FormularioActualizarMascota $formularioActualizar,
        string $mensaje = "",
        string $tipoMensaje = ""
    ) {
        $this->barraLateral         = $barraLateral;
        $this->formulario           = $formulario;
        $this->tablaMascotas        = $tablaMascotas;
        $this->formularioActualizar = $formularioActualizar;
        $this->mensaje              = $mensaje;
        $this->tipoMensaje          = $tipoMensaje;
    }

    public function agregarTarjeta(TarjetaResumen $tarjeta): void
    {
        $this->tarjetas[] = $tarjeta;
    }

    private function renderTarjetas(): string
    {
        $html = '<div class="w3-row-padding">';
        foreach ($this->tarjetas as $tarjeta) {
            $html .= $tarjeta->render();
        }
        $html .= '</div>';
        return $html;
    }

    private function renderMensaje(): string
    {
        if ($this->mensaje === "") {
            return "";
        }
        $clase = $this->tipoMensaje === "exito" ? "banner-exito" : "banner-error";
        return '<div class="w3-card w3-padding w3-margin-bottom banner-mensaje ' . $clase . '">'
             . htmlspecialchars($this->mensaje) . '</div>';
    }

    public function render(): string
    {
        ob_start();
        ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manos Caritativas · Sistema de Gestión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<style>
:root{
    --azul:#1f4e79;
    --azul2:#2c6da4;
    --blanco:#fff;
    --gris:#f4f6f9;
    --texto:#000000;
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:var(--gris);
    color: var(--texto);
}

/*========= BARRA LATERAL =========*/

.barra-lateral{
    width:220px !important;
    background:linear-gradient(180deg,var(--azul),var(--azul2)) !important;
    color:#fff !important;
    overflow-y:auto;
}

.identidad-panel{
    border-radius:0;
    box-shadow:none;
    background:transparent !important;
    color:#fff;
    border:none;
}

.logo-circulo{
    width:90px;
    height:90px;
    border-radius:50%;
    margin:auto;
    background:#fff;
    background-image:url("IMG/Manos_Caritativas.png");
    background-repeat:no-repeat;
    background-position:center;
    background-size:70%;
}

.logo-circulo span{
    display:none;
}

.identidad-panel h3{
    color:#fff;
    font-size:23px;
    margin-top:15px;
}

.menus-panel{
    background:transparent !important;
    box-shadow:none;
    border:none;
}

.menu-principal{
    color:#fff !important;
    background:transparent !important;
    margin:6px 0;
    border-radius:8px;
    font-size:16px;
    width: 100%;
}

.menu-principal:hover{
    background:rgba(255,255,255,.15) !important;
    color:#fff !important;
}

.submenu-item{
    color:#ffffff !important;
    background:transparent !important;
    padding-left:35px !important;
    font-size: 14px;
}

.submenu-item:hover{
    background:rgba(255,255,255,.2) !important;
    color:#fff !important;
}

/*========= CONTENIDO =========*/

.contenido-principal{
    margin-left:220px;
    padding:20px;
}

header.w3-bar{
    margin-left:220px;
    background:#fff;
    border-bottom:1px solid #ddd;
    height:60px;
    display:flex;
    align-items:center;
    color: #000 !important;
}

/*========= TARJETAS =========*/

.tarjeta-resumen{
    border:none;
    border-radius:12px;
    box-shadow:0 3px 12px rgba(0,0,0,.08);
    padding:10px;
}

.icono-circulo{
    width:45px;
    height:45px;
    border-radius:50%;
}

/*========= FORMULARIO =========*/

.panel-formulario{
    border-radius:12px;
    padding:20px !important;
    color: #000;
}

.panel-formulario h2{
    margin-top:0;
    font-size:34px;
}

.campo-input{
    height:42px;
    border-radius:8px !important;
    color: #000 !important;
}

label{
    font-size:15px;
    color: #000 !important;
}

/*========= BOTONES =========*/

.boton-guardar{
    background:var(--azul)!important;
    color:#fff!important;
}

.boton-limpiar{
    background:#e5e5e5!important;
    color:#000!important;
}

.boton-editar{
    background:var(--azul2)!important;
    color:#fff!important;
    margin-bottom:4px;
}

.boton-eliminar{
    background:#c0392b!important;
    color:#fff!important;
    margin-bottom:4px;
}

/*========= MENSAJES =========*/

.banner-mensaje{
    border-radius:8px;
    font-weight:bold;
}

.banner-exito{
    background:#e8f5e9!important;
    color:#2e7d32!important;
    border:1px solid #a5d6a7;
}

.banner-error{
    background:#ffebee!important;
    color:#c62828!important;
    border:1px solid #ef9a9a;
}

/*========= TABLA DE MASCOTAS =========*/

.tabla-mascotas{
    color:#000;
    min-width:900px;
}

.tabla-mascotas th{
    background:var(--azul);
    color:#fff;
    text-align:left;
    padding:10px;
}

.tabla-mascotas td{
    vertical-align:middle;
    padding:8px 10px;
}

/*========= PAGINACIÓN =========*/

.paginacion-mascotas{
    margin-top:16px;
}

.pagina-item{
    display:inline-block;
    min-width:36px;
    margin:2px;
    background:#e5e5e5!important;
    color:#000!important;
    font-weight:bold;
    text-decoration:none;
}

.pagina-item:hover{
    background:var(--azul2)!important;
    color:#fff!important;
}

.pagina-activa{
    background:var(--azul)!important;
    color:#fff!important;
}

@media(max-width:992px){
    .barra-lateral{
        display:none;
    }

    .contenido-principal,
    header.w3-bar{
        margin-left:0;
    }
}
</style>

</head>
<body>

<?= $this->barraLateral->render() ?>

<header class="w3-bar w3-white" style="z-index:2;">
    <span class="w3-bar-item w3-large" style="color: #000;"><b>Manos Caritativas</b></span>
    <span class="w3-bar-item w3-right" style="color: #000;">Usuario activo: Indino Castro</span>
</header>

<div class="contenido-principal w3-padding-24">

    <h1 class="w3-margin-bottom" style="color: #000;">Panel General</h1>
    <p class="w3-margin-bottom-24" style="color: #000;">
        Resumen general de los animales rescatados y su expediente digital.
    </p>

    <?= $this->renderMensaje() ?>

    <?= $this->renderTarjetas() ?>

    <?= $this->tablaMascotas->render() ?>

    <?= $this->formulario->render() ?>

    <?= $this->formularioActualizar->render() ?>

    <div class="w3-card w3-white w3-padding-16 w3-margin-bottom panel-formulario">
        <h4 style="color: #000;">Módulos del Sistema</h4>
        <p class="w3-small" style="color: #000;">
            Utilice el menú lateral para navegar entre Mascotas, Adopciones, Salud,
            Donaciones y Configuración.
        </p>
    </div>

</div>

<script>
    function toggleSubmenu(id) {
        var panel = document.getElementById(id);
        if (panel.className.indexOf("w3-show") === -1) {
            panel.className = panel.className.replace("w3-hide", "w3-show");
        } else {
            panel.className = panel.className.replace("w3-show", "w3-hide");
        }
    }
</script>

</body>
</html>
        <?php
        return ob_get_clean();
    }
}

// Variables por defecto si no existen
$mensaje = $mensaje ?? "";
$tipoMensaje = $tipoMensaje ?? "";

// --- Mensajes de éxito/error que llegan tras registrar, actualizar o eliminar ---
if (isset($_GET["msg"])) {
    $mensaje = $_GET["msg"];
    $tipoMensaje = $_GET["tipo"] ?? "";
}

// --- Conexión a la base de datos y carga de mascotas reales (CRUD) ---
$listaMascotas = [];
$mascotaAEditar = null;

try {
    $conexionBD = new ConexionBD();
    $pdo = $conexionBD->obtenerConexion();
    $guardadorMascota = new GuardadorMascota($pdo);

    $listaMascotas = $guardadorMascota->obtenerTodas();

    // Si se hizo clic en "Editar" desde la lista, precargamos esa mascota
    if (isset($_GET["editar"])) {
        $idEditar = (int) $_GET["editar"];
        $mascotaAEditar = $guardadorMascota->obtenerPorId($idEditar);
        if (!$mascotaAEditar) {
            $mensaje = "No se encontró la mascota solicitada para editar.";
            $tipoMensaje = "error";
        }
    }
} catch (Exception $e) {
    // Si la base de datos no responde, el panel sigue funcionando
    // (solo se avisa del problema) en lugar de romperse por completo.
    $mensaje = "No se pudo conectar con la base de datos: " . $e->getMessage();
    $tipoMensaje = "error";
}

// --- Barra lateral con identidad ---
$barra = new BarraLateral("Manos Caritativas", "Indino Castro", "HF");

// --- Menú: Mascotas ---
$menuMascotas = new MenuPrincipal("Mascotas", "", "panelMascotas");
$menuMascotas->agregarSubMenu(new SubMenuItem("Lista de Mascotas", "#lista-mascotas"));
$menuMascotas->agregarSubMenu(new SubMenuItem("Registro de Mascotas", "#registro-pacientes"));
$menuMascotas->agregarSubMenu(new SubMenuItem("Actualizar Datos de Mascota", "#actualizar-mascota"));
$barra->agregarMenu($menuMascotas);

// --- Menú: Adopciones ---
$menuAdopciones = new MenuPrincipal("Adopciones", "", "panelAdopciones");
$menuAdopciones->agregarSubMenu(new SubMenuItem("Solicitudes de Adopción", "#solicitudes-adopcion"));
$menuAdopciones->agregarSubMenu(new SubMenuItem("Adopciones Completadas", "#adopciones-completadas"));
$barra->agregarMenu($menuAdopciones);

// --- Menú: Salud ---
$menuSalud = new MenuPrincipal("Salud", "", "panelSalud");
$menuSalud->agregarSubMenu(new SubMenuItem("Historial Médico", "#historial-medico"));
$menuSalud->agregarSubMenu(new SubMenuItem("Vacunación", "#vacunacion"));
$barra->agregarMenu($menuSalud);

// --- Menú: Donaciones ---
$menuDonaciones = new MenuPrincipal("Donaciones", "", "panelDonaciones");
$menuDonaciones->agregarSubMenu(new SubMenuItem("Registrar Donación", "#registrar-donacion"));
$menuDonaciones->agregarSubMenu(new SubMenuItem("Historial de Donaciones", "#historial-donaciones"));
$barra->agregarMenu($menuDonaciones);

// --- Menú: Configuración ---
$menuConfiguracion = new MenuPrincipal("Configuración", "", "panelConfiguracion");
$menuConfiguracion->agregarSubMenu(new SubMenuItem("Datos del Santuario", "#datos-santuario"));
$menuConfiguracion->agregarSubMenu(new SubMenuItem("Usuarios del Sistema", "#usuarios-sistema"));
$menuConfiguracion->agregarSubMenu(new SubMenuItem("Cerrar Sesión", "#cerrar-sesion"));
$barra->agregarMenu($menuConfiguracion);

// --- Formulario de Registro de Mascotas ---
$formulario = new FormularioRegistroMascotas();
$formulario->agregarCampo(new CampoFormulario("Nombre de la Mascota", "nombre", "text", "half", "Ej. Firulais"));
$formulario->agregarCampo(new CampoFormulario("Especie", "especie", "text", "half", "Ej. Canino, Felino"));
$formulario->agregarCampo(new CampoFormulario("Raza", "raza", "text", "half", "Ej. Labrador"));
$formulario->agregarCampo(new CampoFormulario("Edad (años)", "edad", "number", "half", "Ej. 3", 'min="0"'));
$formulario->agregarCampo(new CampoFormulario("Peso Actual (kg)", "peso_actual", "number", "half", "Ej. 12.5", 'step="0.01" min="0.01"'));
$formulario->agregarCampo(new CampoFormulario("Color / Señas Físicas", "senas_fisicas", "text", "half", "Ej. Café con mancha blanca en el pecho"));
$formulario->agregarCampo(new CampoFormulario("Nombre del Responsable", "nombre_responsable", "text", "half", "Ej. Ana Martínez"));
$formulario->agregarCampo(new CampoFormulario("Teléfono de Emergencia", "telefono_emergencia", "tel", "half", "Ej. 9999-9999"));

// --- Tabla con la lista real de mascotas, paginada de 5 en 5 (CRUD: Read) ---
$paginaActual = max(1, (int) ($_GET["pagina"] ?? 1));
$tablaMascotas = new TablaMascotas($listaMascotas, $paginaActual, 5);

// --- Formulario de actualización, precargado si hay una mascota seleccionada (CRUD: Update) ---
$formularioActualizar = new FormularioActualizarMascota($mascotaAEditar);

// --- Dashboard principal ---
$dashboard = new DashboardSantuario($barra, $formulario, $tablaMascotas, $formularioActualizar, $mensaje, $tipoMensaje);
$dashboard->agregarTarjeta(new TarjetaResumen("Mascotas Registradas", (string) count($listaMascotas), "", "w3-green"));
$dashboard->agregarTarjeta(new TarjetaResumen("En Proceso de Adopción", "14", "", "w3-orange"));
$dashboard->agregarTarjeta(new TarjetaResumen("Consultas Médicas", "6", "", "w3-blue"));
$dashboard->agregarTarjeta(new TarjetaResumen("Donaciones del Mes", "23", "", "w3-red"));

echo $dashboard->render();