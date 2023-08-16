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
                    <h4 class="modal-title">Crear Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" onclick="cerrarModal()">&times;</button>
                </div>

                <!-- Modal body -->
                <section class="section">
                    <div class="row">
                        <div class="card-body"> <br>
                            <!-- Floating Labels Form -->
                            <input id="idUser" type="hidden" value="0" readonly />
                            <form id="registroForm" class="row g-3">

                                <div class="col-md-6">
                                    <div class="input-group has-validation">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nombre Completo" onKeyUp="this.value=this.value.toUpperCase()"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group has-validation">
                                        <input type="text" class="form-control" id="user" name="user"
                                            placeholder="Nombre de Usuario"
                                            onKeyUp="this.value=this.value.toUpperCase()" required>

                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                        <input type="text" class="form-control" id="correo" name="correo"
                                            placeholder="Correo Electrónico"
                                            onKeyUp="this.value=this.value.toUpperCase()" required>
                                    </div>
                                </div>


                                <div class="col-12" id="contra">
                                    <div class="input-group has-validation">
                                        <input type="password" class="form-control" id="pass" name="pass"
                                            placeholder="Password" onKeyUp="this.value=this.value.toUpperCase()"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-4 form-permiso">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="permiso" name="permiso" aria-label="State" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                        <label for="floatingSelect">Permiso App</label>
                                    </div>
                                </div>

                                <div class="col-md-4 form-activa">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="estado" name="estado" aria-label="State">
                                            <option value="0">NO</option>
                                            <option value="1">SI</option>
                                        </select>
                                        <label for="floatingSelect">Activa</label>
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary"
                                        onclick="enviarDatos()">Guardar</button>
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

    <!-- Restablecer Contraseña Modal -->
    <div class="modal" id="resetPasswordModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Restablecer Contraseña</h4>
                    <button type="button" class="close" data-dismiss="modal"
                        onclick="cerrarResetModal()">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form id="resetPasswordForm" class="row g-3">
                        <input type="hidden" id="userId" value="">
                        <div class="col-md-12">
                            <label for="newPassword" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword"
                                placeholder="Nueva Contraseña">
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="resetPassword()">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="cerrarResetModal()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-4">
            <button type="button" class="btn btn-success" onclick="abrirModal()">
                <i class="bi bi-plus-circle" aria-hidden="true"></i> Crear Nuevo Usuario
            </button>
        </div>
    </div>
    <br>

    <section class="section">
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <p class="text-muted font-13 m-b-30"> Listado de Usuarios</p>
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th># </th>
                                    <th>NOMBRE</th>
                                    <th>CORREO</th>
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
        url: './capaDatos/listUser.php',
        type: "GET",
        dataType: "json"
    },
    "columns": [{
            "data": "IdUsuario"
        },
        {
            "data": "Nombre"
        },
        {
            "data": "Correo"
        },
        {
            "data": "estado",
            "render": function(valor) {
                if (valor == "1") {
                    return '<span class="badge bg-success">SI</span>'
                } else {
                    return '<span class="badge bg-danger">NO</span>'
                }
            }
        },
        {
            "defaultContent": '<div class="btn-group" role="group"><button type="button" class="btn btn-primary btn-sm btn-editar" data-id="{{IdUsuario}}"><i class="bx bxs-edit-alt"></i></button><button type="button" class="btn btn-secondary btn-sm btn-reset" data-id="{{IdUsuario}}"><i class="bi bi-key-fill"></i></button></div>',
            "orderable": false,
            "searchable": false,
            "width": "90px"
        }

    ],
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
    }
});

$("#registroForm").validate({
    rules: {
        name: {
            required: true
        },
        user: {
            required: true
        },
        correo: {
            required: true,
            emailWithDot: true
        },
        pass: {
            required: true,
            minlength: 6
        },
        permiso: {
            required: true,
        }
    },
    messages: {
        name: "El nombre del usuario obligatorio.",
        user: "El nickname del usuario obligatorio.",
        correo: "Ingrese un correo valido.",
        pass: "La contraseña debe contener minimo 6 digitos.",
        permiso: "Ingresar permiso para la app de escritorio"
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
$("#resetPasswordForm").validate({
    rules: {
        newPassword: {
            required: true,
            minlength: 6
        },
    },
    messages: {
        newPassword: "La contraseña debe contener minimo 6 digitos.",
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
// Evento para capturar el botón "Crear Nuevo Usuario"
$('#datatable-responsive_wrapper').on('click', '.btn-success', function() {
    abrirModal(null, false); // Open the modal for creating a new user
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
    abrirModal(data, true); // Open the modal for editing an existing user
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




function abrirModal(dataJson, isEditing) {
    $('#myModal').modal('show');
    $("#idUser").val(0);
    $("#name").val("");
    $("#user").val("");
    $("#correo").val("");
    $("#pass").val("");

    if (isEditing) {
        $(".form-activa").show();
        $(".form-permiso").show();
        $("#contra").hide();
        $(".modal-title").text("Modificar Usuario");
    } else {
        $("#contra").show();
        $(".form-activa").hide();
        $(".form-permiso").show();
        $(".modal-title").text("Crear Usuario");
    }

    if (dataJson != null) {
        console.log(dataJson);
        $("#idUser").val(dataJson.IdUsuario); 
        $("#name").val(dataJson.Nombre);
        $("#user").val(dataJson.NombreUsuario);
        $("#correo").val(dataJson.Correo);
        $("#estado").val(dataJson.estado);
        $("#permiso").val(dataJson.App);

        if (!isEditing && dataJson.Contrasena != null) {
            $("#pass").val(dataJson.contrasena);
        }
    }
}



function abrirResetModal(dataJson) {
    $('#resetPasswordModal').modal('show');
    $("#userId").val(dataJson.IdUsuario);
    // Mostrar el título "Restablecer Contraseña" en el modal
    $(".modal-title", $('#resetPasswordModal')).text("Restablecer Contraseña");
}



function cerrarModal() {
    $('#myModal').modal('hide');
}

function cerrarResetModal() {
    $('#resetPasswordModal').modal('hide');
}

$("#resetPasswordModal").on("hidden.bs.modal", function() {
    $("#newPassword").val(""); // Limpiar el campo de contraseña
});

// Función Guardar
// Función Guardar con idUser como parámetro
function Guardar(idUser) {
    if ($("#registroForm").valid()) {
        var data = $('#registroForm').serialize();
        // Agregar el valor de idUser a los datos enviados en la solicitud AJAX
        data += "&idUser=" + idUser;
        $.ajax({
            type: 'POST',
            url: 'capaDatos/CreateUser.php',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.resultado === '1') {
                    Swal.fire("¡Buen Trabajo!", response.mensaje, "success");
                    $("#myModal").modal("hide");
                    // Reload or update the user list table after successful user creation
                    tabladata.ajax.reload();
                } else {
                    Swal.fire("Revise los datos ingresados", response.mensaje, "info");
                    $("#mensajeError").text("Revise los datos ingresados.").show();
                }
            },
            error: function() {
                Swal.fire("Error", "Ha ocurrido un error en la solicitud.", "error");
            }
        });
    }
}


// Función para enviar los datos al hacer clic en el botón
function enviarDatos() {
    if ($('#registroForm').valid()) {
        // Obtener el valor de idUser
        var idUser = $("#idUser").val();
        // Si el formulario es válido, se activa la función Guardar() con el valor de idUser
        Guardar(idUser);
    }
}

function resetPassword() {
    // Validate the form using jQuery Validation Plugin
    if ($("#resetPasswordForm").valid()) {
        var userId = $("#userId").val();
        var newPassword = $("#newPassword").val();

        // Perform the AJAX request to reset the password
        $.ajax({
            type: "POST",
            url: "./CapaDatos/resetPassword.php", // Replace with the actual PHP file for resetting password
            data: {
                userId: userId,
                newPassword: newPassword
            },
            dataType: "json",
            success: function(response) {
                if (response.resultado === "1") {
                    Swal.fire("¡Contraseña restablecida!", response.mensaje, "success");
                    $("#resetPasswordModal").modal("hide");
                } else {
                    Swal.fire("Error al restablecer contraseña", response.mensaje, "info");
                }
            },
            error: function() {
                Swal.fire("Error", "Ha ocurrido un error en la solicitud.", "error");
            }
        });
    }
}
// Custom validation method for email format with a dot (.) after the "@" symbol
$.validator.addMethod("emailWithDot", function(value, element) {
    return this.optional(element) || /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(value);
}, "Ingrese un correo válido con un punto (.) después del @.");


</script>
<style>
.is-invalid {
    border-color: red !important;
    box-shadow: 0 0 0.25rem red !important;
}

#datatable-responsive td:nth-child(4) {
    text-align: center;
    width: 10%;
}

#datatable-responsive td:nth-child(5) {
    text-align: center;
    width: 10%;
}
</style>