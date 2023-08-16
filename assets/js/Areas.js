// En listar Areas en la tabla
// joel.gonzalez@holdingvml.net
var tabladata;
var filaSeleccionada;

tabladata = $("#datatable-responsive").DataTable({
    responsive: true,
    ordering: false,
    "ajax": {
        url: './capaDatos/listArea.php',
        type: "GET",
        dataType: "json"
    },
    "columns": [{
            "data": "Id"
        },
        {
            "data": "NombreArea"
        },
        {
            "data": "NombreSede"
        },
        {
            "data": "NombreEmpresa"
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

// Validaciones del modal para que generen advertencias de los campos
// joel.gonzalez@holdingvml.net
// Validaciones del modal para que generen advertencias de los campos
$("#form-areas").validate({
    rules: {
        nomArea: {
            required: true
        },
        sede:{
            required: true
        }
    },
    messages: {
        nomArea: "El nombre del área es obligatorio.",
        sede: "Debe seleccionar una sede."
    },
    errorElement: "div",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).addClass("is-valid").removeClass("is-invalid");
    },
    submitHandler: function (form) {
        // Aquí agregas la validación manual del campo sede
        var sedeValue = $("#sede").val();
        if (!sedeValue) {
            $("#sede").addClass("is-invalid");
            return false;
        } else {
            $("#sede").removeClass("is-invalid");
        }

        // Si la validación es exitosa, puedes continuar con el guardado
        Guardar();
    }
});


// Obtener los datos en base de datos, y mostrarlo en un selector
// joel.gonzalez@holdingvml.net
function obtenerDatos() {
    fetch('capaDatos/obtenerCiudades.php?Estado=1')
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Error en la respuesta del servidor');
        })
        .then(function(data) {
            console.log('Datos recibidos:', data);

            var sede = data.Sede;

            var optionsSede = '<option value="">Seleccionar...</option>';
            sede.forEach(function(sede) {
                var sedeOption = sede.Nombre + ' - ' + sede.NombreEmpresa;
                optionsSede += '<option value="' + sede.IdSede + '">' + sedeOption + '</option>';
            });

            $("#sede").html(optionsSede);

        })
        .catch(function(error) {
            console.log('Error en la solicitud AJAX:', error);
        });
}

obtenerDatos();

// Evento para capturar la fila seleccionada
// joel.gonzalez@holdingvml.net
$('#datatable-responsive tbody').on('click', '.btn-editar', function() {
    if ($(this).closest("tr").hasClass('child')) { //vemos si el actual row es child row y nos devolvemos uno
        filaSeleccionada = $(this).closest("tr").prev();
    } else {
        filaSeleccionada = $(this).closest("tr");
    }
    var data = tabladata.row(filaSeleccionada).data();
    abrirModal(data, true)
});

// Abrir el modal, con datos o sin datos
// joel.gonzalez@holdingvml.net
function abrirModal(dataJson, isEditing) {
    $('#myModal').modal('show');
    if (isEditing) {
        // Mostrar el campo "Activa" solo en caso de edición
        $(".form-activa").show();
        $(".modal-title").text("Modificar Área");
    } else {
        $(".form-activa").hide();
        $(".modal-title").text("Crear Área");
    }
    if (dataJson != null && isEditing) {
        console.log(dataJson);
        $("#id").val(dataJson.Id);
        $("#nomArea").val(dataJson.NombreArea);
        $("#sede").val(dataJson.IdSede);
        $("#estado").val(dataJson.Estado);
    }else{
        resetForm();
    }

    $("#FormModal").modal("show");
}
// los scripts dentro del modal, sirva el devolverse o cerrar
// joel.gonzalez@holdingvml.net
function cerrarModal() {
    $("#id").val("");
    $("#sede").val("");
    $("#nomArea").val("");


    $('#myModal').modal('hide');
    // Redireccionar a la página anterior

}

// Guarda los datos nuevos a crear o actualizar, donde genera una alerta de satisfaccion o de error
// joel.gonzalez@holdingvml.net

function Guardar() {
    if ($("#form-areas").valid()) {
    var CreateArea = {
        id: $("#id").val(),
        nomArea: $("#nomArea").val(),
        estado: $("#estado").val(),
        sede: $("#sede").val(),
    };

    $.ajax({
            type: 'POST',
            url: './CapaDatos/CreateArea.php',
            data: CreateArea,
            dataType: 'json',
            async: true,
        })
        .done(function(res) {
            console.log(res);
            if (CreateArea.id == 0) {
                if (res && res.resultado !== undefined && res.resultado != 0) {
                    CreateArea.id = res.resultado;
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
    $("#sede").val("");
    $("#nomArea").val("");
    $("#estado").val("1");
    $(".form-activa").hide();
    $(".modal-title").text("Crear Área");
}