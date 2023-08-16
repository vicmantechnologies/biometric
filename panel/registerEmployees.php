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
    <div class="modal" id="myModal">

        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Datos del Empleado</h4>
                    <button type="button" class="close" data-dismiss="modal" onclick="cerrarModal()">&times;</button>
                </div>

                <!-- Modal body -->
                <section class="section">
                    <div class="row">
                        <div class="card-body"> <br>
                            <!-- Floating Labels Form -->
                            <input id="idEmployee" type="hidden" value="0" readonly />
                            <form id="registroFormEmpl" class="row g-3">

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nombre del Empleado"
                                            onKeyUp="this.value=this.value.toUpperCase()" required>
                                        <label for="floatingName">Nombre del Empleado</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="ape" name="ape"
                                            placeholder="Nombre del Empleado"
                                            onKeyUp="this.value=this.value.toUpperCase()" required>
                                        <label for="floatingName">Apellidos del Empleado</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="identidy" name="identidy"
                                            placeholder="Numero de documento"
                                            onKeyUp="this.value=this.value.toUpperCase()" required readonly>
                                        <label for="floatingName">Documento</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="empresa" name="empresa" aria-label="State"
                                            required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                        <label for="floatingSelect">Empresa</label>
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

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="area" name="area" aria-label="State" required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                        <label for="floatingSelect">Área</label>
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


    <br>

    <section class="section">
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <p class="text-muted font-13 m-b-30"> Listado de Empleados</p>
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th># </th>
                                    <th>DOCUMENTO</th>
                                    <th>NOMBRE</th>
                                    <th>EMPRESA</th>
                                    <th>SEDE</th>
                                    <th>AREA</th>
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
</main><!-- End #main -->

<?php
  endFooter();
?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script>
var tabladata;
var filaSeleccionada;

tabladata = $("#datatable-responsive").DataTable({
    responsive: true,
    ordering: false,
    "ajax": {
        url: './capaDatos/listEmployees.php',
        type: "GET",
        dataType: "json"
    },
    "columns": [{
            "data": "IdEmpleado"
        },
        {
            "data": "Documento"
        },
        {
            "data": "Nombres"

        },
        {
            "data": "NomEmpre"
        },
        {
            "data": "NomSede"
        },
        {
            "data": "NomArea"
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
            "defaultContent": '<div class="btn-group" role="group"><button type="button" class="btn btn-primary btn-sm btn-editar" data-id="{{IdEmpleado}}"><i class="bx bxs-edit-alt"></i></button></div>',
            "orderable": false,
            "searchable": false,
            "width": "90px"
        }

    ],
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
    }
});

$("#registroFormEmpl").validate({
    rules: {
        name: {
            required: true
        },
        ape: {
            required: true
        },
        identidy: {
            required: true
        },
        empresa: {
            required: true,
        },
        sede: {
            required: true,
        },
        area: {
            required: true,
        },
    },
    messages: {
        name: "El nombre del usuario es obligatorio.",
        ape: "El apellido del usuario es obligatorio.",
        identidy: "Ingrese su número de documento.",
        empresa: "Debe seleccionar una empresa.",
        sede: "Debe seleccionar una sede.",
        area: "Debe seleccionar un área.",
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
    if ($(this).closest("tr").hasClass('child')) {
        // Vemos si el actual row es child row y nos devolvemos uno
        filaSeleccionada = $(this).closest("tr").prev();
    } else {
        filaSeleccionada = $(this).closest("tr");
    }
    var data = tabladata.row(filaSeleccionada).data();
    abrirModal(data, true); // Pass true to indicate that it's editing an existing user
});

// Evento para capturar el botón "Restablecer Contraseña"
$('#datatable-responsive tbody').on('click', '.btn-reset', function() {
    if ($(this).closest("tr").hasClass('child')) {
        // Vemos si el actual row es child row y nos devolvemos uno
        filaSeleccionada = $(this).closest("tr").prev();
    } else {
        filaSeleccionada = $(this).closest("tr");
    }
    var data = tabladata.row(filaSeleccionada).data();
    abrirResetModal(data); // Open the reset password modal with the selected user data
});

function obtenerDatos() {
    fetch('capaDatos/obtenerCiudades.php?Estado=1')
        .then(function(response) {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error en la respuesta del servidor');
            }
        })
        .then(function(data) {
            if ('error' in data) {
                console.log('Error en la solicitud AJAX:', data.error);
            } else {
                console.log('Datos recibidos:', data);

                var sedes = data.Sede;
                var empresas = data.Empresas;
                var areas = data.Areas;

                var optionsSede = '<option value="">Seleccionar...</option>';
                sedes.forEach(function(sede) {
                    optionsSede += '<option value="' + sede.IdSede + '">' + sede.Nombre +
                        '</option>';
                });

                $("#sede").html(optionsSede);

                var optionsEmpresa = '<option value="">Seleccionar...</option>';
                empresas.forEach(function(empresa) {
                    optionsEmpresa += '<option value="' + empresa.IdEmpresa + '">' + empresa.Nombre +
                        '</option>';
                });

                $("#empresa").html(optionsEmpresa);

                var optionsArea = '<option value="">Seleccionar...</option>';
                areas.forEach(function(area) {
                    optionsArea += '<option value="' + area.IdArea + '">' + area.Nombre +
                        '</option>';
                });

                $("#area").html(optionsArea);
            }
        })
        .catch(function(error) {
            console.log('Error en la solicitud AJAX:', error);
        });
}


obtenerDatos();



function abrirModal(dataJson, isEditing) {
    $('#myModal').modal('show');
    if (dataJson != null) {
        console.log(dataJson);
        $("#idEmployee").val(dataJson.IdEmpleado); // If dataJson is null, the id field will remain empty.
        $("#name").val(dataJson.Nombres);
        $("#ape").val(dataJson.Apellidos);
        $("#identidy").val(dataJson.Documento);
        $("#empresa").val(dataJson.IdEmpresa);
        $("#sede").val(dataJson.IdSede);
        $("#area").val(dataJson.idArea);
        $("#estado").val(dataJson.Estado);
    }
}

function cerrarModal() {
    $('#myModal').modal('hide');
    // Redireccionar a la página anterior
}

// Función Guardar
// Función Guardar con idUser como parámetro

function Guardar() {
    // Primero, validamos el formulario antes de guardar los datos
    if ($("#registroFormEmpl").valid()) {
        var CreateEmployee = {
            idEmployee: $("#idEmployee").val(),
            name: $("#name").val(),
            ape: $("#ape").val(),
            identidy: $("#identidy").val(),
            empresa: $("#empresa").val(),
            sede: $("#sede").val(),
            area: $("#area").val(),
            estado: $("#estado").val(),
        };

        $.ajax({
            type: 'POST',
            url: './CapaDatos/CreateEmployee.php',
            data: CreateEmployee,
            dataType: 'json',
            async: true,
        }).done(function(res) {
            console.log(res);
            if (CreateEmployee.idEmployee == 0) {
                if (res && res.resultado !== undefined && res.resultado != 0) {
                    CreateEmployee.idEmployee = res.resultado;
                    Swal.fire({
                        icon: 'success',
                        title: '¡Buen Trabajo!',
                        text: res.mensaje,
                    }).then(function() {
                        $("#myModal").modal("hide");
                        tabladata.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Revise los datos ingresados',
                        text: res.mensaje,
                    });
                    $("#mensajeError").text("Revise los datos ingresados.").show();
                }
            } else {
                if (res && res.resultado !== undefined && res.resultado != 0) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Buen Trabajo!',
                        text: res.mensaje,
                    }).then(function() {
                        $("#myModal").modal("hide");
                        tabladata.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'No es posible actualizar',
                        text: res.mensaje,
                    });
                    $("#mensajeError").text("No es posible actualizar.").show();
                }
            }
        });
    }
}
</script>
<style>
.is-invalid {
    border-color: red !important;
    box-shadow: 0 0 0.25rem red !important;
}


#datatable-responsive td:nth-child(7) {
    text-align: center;
    width: 10%;
}

#datatable-responsive td:nth-child(8) {
    text-align: center;
    width: 10%;
}
</style>