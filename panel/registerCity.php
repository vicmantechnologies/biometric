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

    <!-- Agrega este código antes de la etiqueta </body> -->
    <div class="modal" id="myModal" hidden.bs.modal="cerrarModal">

        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Crear Ciudad</h4>
                    <button type="button" class="close" data-dismiss="modal" onclick="cerrarModal()">&times;</button>
                </div>

                <!-- Modal body -->
                <section class="section">
                    <div class="card">
                        <div class="card-body"> <br>
                            <!-- Floating Labels Form -->
                            <input id="id" type="hidden" value="0" readonly />
                            <form class="row g-3" id="form-ciudad">

                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="nomCiudad" name="nomCiudad"
                                            placeholder="Nombre de la sede"
                                            onKeyUp="this.value=this.value.toUpperCase()" required>
                                        <label for="floatingName">Nombre de la ciudad</label>
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
            <button type="button" class="btn btn-success" onclick="abrirModal()">
                <i class="bi bi-plus-circle" aria-hidden="true"></i> Crear Nueva Ciudad
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
                                    <th>CIUDAD</th>
                                    <th style="text-align: center;">ESTADO</th>
                                    <th>ACCION</th>
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
</main><!-- End #main -->

<?php
  endFooter();
?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js">
</script>
<script>
var tabladata;
var filaSeleccionada;

tabladata = $("#datatable-responsive").DataTable({
    responsive: true,
    ordering: false,
    "ajax": {
        url: './capaDatos/listCity.php',
        type: "GET",
        dataType: "json"
    },
    "columns": [{
            "data": "IdCiudad"
        },
        {
            "data": "Nombre"
        },
        {
            "data": "Estado",
            "render": function(valor) {
                if (valor == "1") {
                    return '<span class="badge bg-success">SI</span>'
                } else {
                    return '<span class="badge bg-danger">NO</span>'
                }
            }
        },
        {
            "defaultContent": '<button type="button" class="btn btn-primary btn-sm btn-editar" data-id="{{IdCiudad}}"><i class="bx bxs-edit-alt"></i></button>',
            "orderable": false,
            "searchable": false,
            "width": "90px"
        }
    ],
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
    }
});

$("#form-ciudad").validate({
    rules: {
        nomCiudad: {
            required: true
        },
    },
    messages: {
        nomCiudad: "El nombre de la ciudad es obligatorio.",

    },
    errorElement: "div",
    errorPlacement: function(error, element) {
        // En lugar de mostrar los errores en un contenedor específico,
        // los mostramos debajo de cada campo correspondiente.
        error.addClass("invalid-feedback");
        error.insertAfter(element);
    },
    highlight: function(element, errorClass, validClass) {
        // Agregamos estilos de Bootstrap para campos inválidos
        $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function(element, errorClass, validClass) {
        // Agregamos estilos de Bootstrap para campos válidos
        $(element).addClass("is-valid").removeClass("is-invalid");
    },
});

// Evento para capturar la fila seleccionada
$('#datatable-responsive tbody').on('click', '.btn-editar', function() {
    if ($(this).closest("tr").hasClass('child')) { //vemos si el actual row es child row y nos devolvemos uno
        filaSeleccionada = $(this).closest("tr").prev();
    } else {
        filaSeleccionada = $(this).closest("tr");
    }
    var data = tabladata.row(filaSeleccionada).data();

    
    abrirModal(data, true)
});


function abrirModal(dataJson, isEditing) {
    $('#myModal').modal('show');
    if (isEditing) {
        // Mostrar el campo "Activa" solo en caso de edición
        $(".form-activa").show();
        $(".modal-title").text("Modificar Ciudad");
    } else {
        $(".form-activa").hide();
    }
   

    if (dataJson != null) {
        console.log(dataJson);
        $("#id").val(dataJson.IdCiudad);
        $("#nomCiudad").val(dataJson.Nombre);
        $("#estado").val(dataJson.Estado);
    }else {
        resetForm(); // Restablecer el formulario antes de mostrar el modal de creación
    }

    $("#FormModal").modal("show");
}

function cerrarModal() {
    $("#id").val("");
    $("#nomCiudad").val("");
    $('#myModal').modal('hide');
}

// Traer los perfiles que vienen en un array para mostraralas en formulario
// joel.gonzalez@holdingvml.net
// ...

function Guardar() {
    if ($("#form-ciudad").valid()) {

        var CreateCity = {
            id: $("#id").val(),
            nomCiudad: $("#nomCiudad").val(),
            estado: $("#estado").val(),
        };

        $.ajax({
                type: 'POST',
                url: './CapaDatos/CreateCity.php',
                data: CreateCity,
                dataType: 'json',
                async: true,
            })
            .done(function(res) {
                console.log(res);
                if (CreateCity.id == 0) {
                    if (res && res.resultado !== undefined && res.resultado != 0) {
                        CreateCity.id = res.resultado;
                        Swal.fire("¡Buen Trabajo!", res.mensaje, "success");
                        $("#myModal").modal("hide");
                        tabladata.ajax.reload();
                    } else {
                        swal("Revise los datos ingresados", res.mensaje, "info");
                        $("#mensajeError").text("Revise los datos ingresados.").show();
                    }
                } else {
                    if (res && res.resultado !== undefined && res.resultado != 0) {
                        Swal.fire("¡Buen Trabajo!", res.mensaje, "success");
                        $("#myModal").modal("hide");
                        tabladata.ajax.reload();
                    } else {
                        Swal.fire("No es posible actualizar", res.mensaje, "info");
                        $("#mensajeError").text("No es posible actualizar.").show();
                    }
                }
            });
    }
}

function resetForm() {
    $("#id").val("0");
    $("#nomCiudad").val("");
    $("#estado").val("");
    $("#form-ciudad").validate().resetForm();
    $("#form-ciudad .form-control").removeClass("is-invalid is-valid");
    $(".form-activa").hide();
    $(".modal-title").text("Crear Ciudad");
}

</script>
<style>
.is-invalid {
    border-color: red !important;
    box-shadow: 0 0 0.25rem red !important;
}

#datatable-responsive td:nth-child(3) {
    text-align: center;
    width: 10%;
}

#datatable-responsive td:nth-child(4) {
    text-align: center;
    width: 10%;
}
</style>