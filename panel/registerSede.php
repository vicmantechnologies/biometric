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
                    <h4 class="modal-title">Crear Sede</h4>
                    <button type="button" class="close" data-dismiss="modal" onclick="cerrarModal()">&times;</button>
                </div>

                <!-- Modal body -->
                <section class="section">
                    <div class="row">
                        <div class="card-body"> <br>
                            <!-- Floating Labels Form -->
                            <input id="id" type="hidden" value="0" readonly />
                            <form class="row g-3" id="form-sede">
                                <!-- Añadimos un ID al formulario -->

                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="sede" name="sede"
                                            placeholder="Nombre de la sede"
                                            onKeyUp="this.value=this.value.toUpperCase()" required>
                                        <label for="floatingName">Nombre de la sede</label>
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
                                        <select class="form-select" id="ciudad" name="ciudad" aria-label="State"
                                            required>
                                            <option value="">Seleccionar...</option>
                                        </select>
                                        <label for="floatingSelect">Ciudad</label>
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
                <i class="bi bi-plus-circle" aria-hidden="true"></i> Crear Nueva Sede
            </button>
        </div>
    </div>
    <br>

    <section class="section">
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <p class="text-muted font-13 m-b-30"> Listado de Sedes</p>
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th># </th>
                                    <th>SEDE</th>
                                    <th>CIUDAD</th>
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
        url: './capaDatos/listSede.php',
        type: "GET",
        dataType: "json"
    },
    "columns": [{
            "data": "IdSede"
        },
        {
            "data": "Nombre"
        },
        {
            "data": "IdCiudad"
        },
        {
            "data": "IdEmpresa"
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
            "defaultContent": '<button type="button" class="btn btn-primary btn-sm btn-editar" data-id="{{IdSede}}"><i class="bx bxs-edit-alt"></i></button>',
            "orderable": false,
            "searchable": false,
            "width": "90px"
        }

    ],
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
    }
});


$("#form-sede").validate({
    rules: {
      sede: { required: true },
      empresa: { required: true },
      ciudad: { required: true },
      estado: { required: true },
    },
    messages: {
      sede: "El nombre de la sede es obligatorio.",
      empresa: "Debe seleccionar una empresa.",
      ciudad: "Debe seleccionar una ciudad.",
      estado: "Debe seleccionar el estado.",
    },
    errorElement: "div",
    errorPlacement: function (error, element) {
      // En lugar de mostrar los errores en un contenedor específico,
      // los mostramos debajo de cada campo correspondiente.
      error.addClass("invalid-feedback");
      error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
      // Agregamos estilos de Bootstrap para campos inválidos
      $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element, errorClass, validClass) {
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
    abrirModal(data, true); // Pasamos true para indicar que se está editando una sede
});



function abrirModal(dataJson, isEditing) {
    $('#myModal').modal('show');

    if (isEditing) {
        // Mostrar el campo "Activa" solo en caso de edición
        $(".form-activa").show();
        $(".modal-title").text("Modificar Sede");
    } else {
        $(".form-activa").hide();
    }

    if (dataJson != null) {
        console.log(dataJson);
        $("#id").val(dataJson.IdSede);
        $("#sede").val(dataJson.Nombre);
        $("#empresa").val(dataJson.NombreEmpresa);
        $("#ciudad").val(dataJson.NombreCiudad);
        $("#estado").val(dataJson.Estado);
    } else{
        resetForm();
    }
}


function cerrarModal() {
    $("#id").val("0");
    $("#sede").val("");
    $("#empresa").val("");
    $("#ciudad").val("");
    $("#estado").val("");
    $('#myModal').modal('hide');
    // Redireccionar a la página anterior

}

// Traer los perfiles que vienen en un array para mostraralas en formulario
// joel.gonzalez@holdingvml.net
// ...
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

                var ciudades = data.ciudad;
                var empresas = data.Empresas;

                ciudades.sort(function(a, b) {
                    return a.Nombre.localeCompare(b.Nombre);
                });

                empresas.sort(function(a, b) {
                    return a.Nombre.localeCompare(b.Nombre);
                });
                
                var optionsCiudad = '<option value="">Seleccionar...</option>';
                ciudades.forEach(function(ciudad) {
                    optionsCiudad += '<option value="' + ciudad.IdCiudad + '">' + ciudad.Nombre +
                        '</option>';
                });

                $("#ciudad").html(optionsCiudad);

                var optionsEmpresa = '<option value="">Seleccionar...</option>';
                empresas.forEach(function(empresa) {
                    optionsEmpresa += '<option value="' + empresa.IdEmpresa + '">' + empresa.Nombre +
                        '</option>';
                });

                $("#empresa").html(optionsEmpresa);
            }
        })
        .catch(function(error) {
            console.log('Error en la solicitud AJAX:', error);
        });
}


obtenerDatos();

function Guardar() {
    // Primero, validamos el formulario antes de guardar los datos
    if ($("#form-sede").valid()) {
        var CreateSede = {
            id: $("#id").val(),
            sede: $("#sede").val(),
            empresa: $("#empresa").val(),
            ciudad: $("#ciudad").val(),
            estado: $("#estado").val(),
        };

        $.ajax({
            type: 'POST',
            url: './CapaDatos/CreateSede.php',
            data: CreateSede,
            dataType: 'json',
            async: true,
        }).done(function(res) {
            console.log(res);
            if (CreateSede.id == 0) {
                if (res && res.resultado !== undefined && res.resultado != 0) {
                    CreateSede.id = res.resultado;
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

function resetForm() {
    $("#id").val("0");
    $("#sede").val("");
    $("#empresa").val("");
    $("#ciudad").val("");
    $("#estado").val("1");


    $(".form-activa").hide();
    $(".modal-title").text("Crear Sede");
}
</script>
<style>
      .is-invalid {
    border-color: red !important;
    box-shadow: 0 0 0.25rem red !important;
  }

  #datatable-responsive td:nth-child(5) {
    text-align: center;

}

#datatable-responsive td:nth-child(6) {
    text-align: center;

}
</style>