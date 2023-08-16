<?php
// Función para verificar si la sesión está activa o no
function isSessionActive() {
    return isset($_SESSION['userId']) && isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] === true;
}
session_start();
if (!isSessionActive()) {
    // Redirige a la página de inicio si la sesión está cerrada
    header("Location: ../index.php");
    exit(); 
}
require_once('../layout/plantilla.php');
headerComponent();
headComponent();
lateralMenu();
?>

<main id="main" class="main">

    <!-- MODAL -->
    <div class="modal" id="myModal" hidden.bs.modal="cerrarModal">

        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Crear Área</h4>
                    <button type="button" class="close" data-dismiss="modal" onclick="cerrarModal()">&times;</button>
                </div>

                <!-- Modal body -->
                <section class="section">
                    <div class="card">
                        <div class="card-body"> <br>
                            <!-- Floating Labels Form -->
                            <input id="id" type="hidden" value="0" readonly />
                            <form class="row g-3" id="form-areas">

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="nomArea" name="nomArea"
                                            placeholder="Nombre de la sede"
                                            onKeyUp="this.value=this.value.toUpperCase()" required>
                                        <label for="floatingName">Nombre del Área</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="sede" name="sede" aria-label="State" required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                        <label for="floatingSelect">Sede</label>
                                    </div>
                                </div>

                                <div class="col-md-4 form-activa">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="estado" name="estado" aria-label="State">
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                        <label for="floatingSelect">Activa</label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="Guardar()">Guardar</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                        onclick="cerrarModal()">Cerrar</button>
                                </div>
                            </form><!-- End floating Labels Form -->
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <button type="button" class="btn btn-success" onclick="abrirModal(null, false)">
            <i class="bi bi-plus-circle" aria-hidden="true"></i> Crear Área
            </button>
        </div>
    </div>
    <br>

    <section class="section">
        <div class="row">
        </div>
        <hr />
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <p class="text-muted font-13 m-b-30"> Listado de Ciudades</p>
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th># </th>
                                    <th>ÁREA</th>
                                    <th>SEDE</th>
                                    <th>EMPRESA</th>
                                    <th>ESTADO</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
  endFooter();
?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script src="../assets/js/Areas.js"></script>
<style>
.is-invalid {
    border-color: red !important;
    box-shadow: 0 0 0.25rem red !important;
}

#datatable-responsive td:nth-child(5) {
    text-align: center;
    width: 1%;
}

#datatable-responsive td:nth-child(6) {
    text-align: center;
    width: 1%;
}
</style>