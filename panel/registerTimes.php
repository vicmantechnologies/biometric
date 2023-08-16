<?php
// Función para verificar si la sesión está activa o no
function isSessionActive() {
    return isset($_SESSION['userId']) && isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'] === true;
}
session_start();
if (!isSessionActive()) {
    // Redirige a la página de inicio si la sesión está cerrada
    header("Location: index.php");
    exit(); // Asegura que el script termine aquí después de la redirección
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
                    <h4 class="modal-title">Horarios A Registrar</h4>
                    <button type="button" class="close" data-dismiss="modal" onclick="cerrarModal()">&times;</button>
                </div>

                <!-- Modal body -->
                <section class="section">
                    <div class="card">
                        <div class="card-body"> <br>
                            <!-- Floating Labels Form -->
                            <input id="id" type="hidden" value="0" readonly />
                            <form class="row g-3" id="formTimes">

                                <div class="row mb-3">
                                    <label for="inputTime" class="col-sm-8 col-form-label">Horario Entrada</label>
                                    <div class="col-sm-12">
                                        <input type="time" class="form-control" id="horarioApertura"
                                            name="horarioApertura">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputTime" class="col-sm-8 col-form-label">Horario Salida</label>
                                    <div class="col-sm-12">
                                        <input type="time" class="form-control" id="horarioCierre" name="horarioCierre">
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
             <i class="bi bi-plus-circle" aria-hidden="true"></i> Crear Horarios
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
                        <p class="text-muted font-13 m-b-30"> Listado de Horarios</p>
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive"
                            cellspacing="0" width="100%" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th># </th>
                                    <th>HORARIO ENTRADA</th>
                                    <th>HORARIO CIERRE</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js">
</script>
<script>
var tabladata;
var filaSeleccionada;

tabladata = $("#datatable-responsive").DataTable({
    responsive: true,
    ordering: false,
    "ajax": {
        url: './capaDatos/listTimes.php',
        type: "GET",
        dataType: "json"
    },
    "columns": [{
            "data": "IdHorario"
        },
        {
        "data": "HorarioApertura",
    
    },
    {
        "data": "HorarioCierre",
    
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

$("#formTimes").validate({
    rules: {
        horarioApertura: {
            required: true
        },
        horarioCierre: {
            required: true
        }
    },
    messages: {
        horarioApertura: "Debe ingresar un horario de entrada.",
        horarioCierre: "Debe ingresar un horario de salida."
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
    } else {
        $(".form-activa").hide();
    }

    if (dataJson != null) {
        console.log(dataJson);
        $("#id").val(dataJson.IdHorario);
        $("#horarioApertura").val(dataJson.HorarioApertura);
        $("#horarioCierre").val(dataJson.HorarioCierre);
    }

    $("#FormModal").modal("show");
}

function cerrarModal() {
    $('#myModal').modal('hide');
    // Redireccionar a la página anterior

}

// Traer los perfiles que vienen en un array para mostraralas en formulario
// joel.gonzalez@holdingvml.net
// ...

function Guardar() {
    if ($("#formTimes").valid()) {

        var CreateTime = {
            id: $("#id").val(),
            horarioApertura: $("#horarioApertura").val(),
            horarioCierre: $("#horarioCierre").val(),
            estado: $("#estado").val(),
        };

        $.ajax({
                type: 'POST',
                url: './CapaDatos/CreateTimes.php',
                data: CreateTime,
                dataType: 'json',
                async: true,
            })
            .done(function(res) {
                console.log(res);
                if (CreateTime.id == 0) {
                    if (res && res.resultado !== undefined && res.resultado != 0) {
                        CreateTime.id = res.resultado;
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


</script>
<style>
.is-invalid {
    border-color: red !important;
    box-shadow: 0 0 0.25rem red !important;
}

#datatable-responsive td:nth-child(1) {
    text-align: center;
}

#datatable-responsive td:nth-child(2) {
    text-align: center;
}

#datatable-responsive td:nth-child(3) {
    text-align: center;
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