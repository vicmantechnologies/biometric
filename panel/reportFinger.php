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
    <div class="x_content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <p class="text-muted font-13 m-b-30">Listado del huellero</p>
                    <form id="formBuscar" data-first-load="true">
                        <div class="row mb-3">
                            <label for="fechaInicio" class="col-sm-2 col-form-label">Fecha Inicio:</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                            </div>

                            <label for="fechaFin" class="col-sm-2 col-form-label">Fecha Fin:</label>
                            <div class="col-sm-4">
                                <!-- Agrega el atributo "value" para establecer la fecha actual -->
                                <input type="date" class="form-control" id="fechaFin" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="empresa" class="form-label">Empresa</label>
                                <select class="form-control" id="empresa" name="empresa">
                                    <option value="">Seleccionar...</option>

                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="sede" class="form-label">Sedes Relacionadas a la Empresa</label>
                                <select class="form-control" id="sede" name="sede" multiple>

                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="area" class="form-label">Areas Relacionadas a la Sede</label>
                                <select class="form-control" id="area" name="area" multiple>

                                </select>
                            </div>



                            <div class="row">
                                <div class="col-12 text-center mt-3">
                                    <button type="button" id="searchRevision" class="btn btn-primary">Buscar</button>
                                    <button type="button" id="exportarExcel" class="btn btn-secondary">Exportar a
                                        Excel</button>
                                    <button type="button" id="reporteGeneral" class="btn btn-success">Reporte
                                        General</button>
                                </div>
                            </div>
                    </form>

                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>CEDULA</th>
                                <th>NOMBRE COMPLETO</th>
                                <th>FECHA MARCACIÓN</th>
                                <th>HORA MARCACIÓN</th>
                                <th>LUGAR DE MARCACIÓN</th>
                                <th>EMPRESA</th>
                                <th>AREA</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</main>

<?php
endFooter();

echo '<script>$.extend(true, $.fn.dataTable.defaults, {
    pageLength: 50
});</script>';
?>


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>




<script>
var tabladata;
var filaSeleccionada;



$("#formBuscar").validate({
    rules: {
        fechaInicio: {
            required: true
        },
        empresa: {
            required: true
        },
        sede: {
            required: true
        },
        area: {
            required: true
        },
    },
    messages: {
        fechaInicio: "Debe seleccionar una fecha de inicio.",
        empresa: "Debe seleccionar una empresa.",
        sede: "Debe seleccionar una sede.",
        area: "Debe seleccionar una área.",
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


function cargarDatosPorDefecto() {
    // Obtiene la fecha actual en formato "YYYY-MM-DD"
    const fechaActual = new Date().toISOString().split('T')[0];
    $('#fechaInicio').val(fechaActual);
    $('#fechaFin').val(fechaActual);

    // Cambiar el valor del atributo data-first-load a "false" para indicar que ya se ha cargado previamente
    $('#formBuscar').attr('data-first-load', 'false');
}

// Verificar si es la primera carga al abrir el modal
const isFirstLoad = $('#formBuscar').attr('data-first-load');

if (isFirstLoad === 'true') {
    // Si es la primera carga, ejecutar la función cargarDatosPorDefecto()
    cargarDatosPorDefecto();
}




function obtenerDatos() {
    fetch('capaDatos/obtenerCiudades.php')
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Error en la respuesta del servidor');
        })
        .then(function(data) {
            console.log('Datos recibidos:', data);

            var empresas = data.Empre;

            var optionsDatos = '<option value="">Seleccionar...</option>';
            empresas.forEach(function(empresa) {
                optionsDatos += '<option value="' + empresa.IdEmpresa + '" id="empresa' + empresa
                    .IdEmpresa +
                    '">' + empresa.Nombre + '</option>';
            });

            $("#empresa").html(optionsDatos);
        })
        .catch(function(error) {
            console.log('Error en la solicitud AJAX:', error);
        });
}


function obtenerSedesPorEmpresa(empresa) {
    fetch('capaDatos/obtenerSedes.php?empresa=' + empresa)
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Error en la respuesta del servidor');
        })
        .then(function(data) {
            console.log('Sedes relacionadas:', data);

            var sedes = data;
            var optionsSedes = '';
            sedes.forEach(function(sede) {
                optionsSedes += '<option value="' + sede.IdSede + '" id="sede' + sede.IdSede + '">' + sede
                    .NombreSede + '</option>';
            });
            $("#sede").html(optionsSedes);
        })
        .catch(function(error) {
            console.log('Error en la solicitud AJAX:', error);
        });
}

function obtenerAreasPorSede(sedes) {
    fetch('capaDatos/obtenerAreas.php?sede=' + sedes)
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Error en la respuesta del servidor');
        })
        .then(function(data) {
            console.log('Areas relacionadas:', data);

            var area = data;
            var optionsAreas = '';
            area.forEach(function(areas) {
                if (areas) {
                    optionsAreas += '<option value="' + areas.IdArea + '" id="areas' + areas.IdArea + '">' +
                        areas.NombreArea + '</option>';
                }
            });
            $("#area").html(optionsAreas);
        })
        .catch(function(error) {
            console.log('Error en la solicitud AJAX:', error);
        });
}

obtenerDatos();
$('#empresa').on('change', function() {
    const sedes = $(this).val();
    if (sedes) {
        obtenerSedesPorEmpresa(sedes);
    } else {
        // Si no se selecciona ninguna sede, vaciamos el selector de empresas
        $("#sede").html('');
        $("#area").html('');
    }
});

$('#sede').on('change', function() {
    const areas = $(this).val();
    if (areas) {
        obtenerAreasPorSede(areas);
    } else {
        $("#area").html('');
    }
});
var tabladata;
$('#searchRevision').on('click', function() {
    if ($("#formBuscar").valid()) {

        if (tabladata) {
            tabladata.destroy(); // Destruir la instancia previa
        }

        var Url = "./capaDatos/listReport.php" +
            "?fechainicio=" + $("#fechaInicio").val() +
            "&fechafin=" + $("#fechaFin").val() +
            "&idEmpresa=" + $("#empresa").val() +
            "&idSede=" + $("#sede").val() +
            "&idArea=" + $("#area").val();

        tabladata = $("#datatable-responsive").DataTable({
            responsive: true,
            ordering: false,
            "ajax": {
                url: Url,
                type: "GET",
                dataType: "json"
            },
            "columns": [{
                    "data": "cedula"
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        // Combinar los valores de campo1 y campo2 en una sola columna
                        return data.NomEmpleados + ' ' + data.ApeEmpleados;
                    }
                },
                {
                    "data": "fechaRegistro"
                },
                {
                    "data": "horaRegistro"
                },
                {
                    "data": "NombreSede"
                },
                {
                    "data": "NombreEmpresa"
                },
                {
                    "data": "NombreArea"
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
            }
        });
    }
});

$('#reporteGeneral').click(function() {

    // Realizar la solicitud AJAX para obtener los datos del servidor
    $.ajax({
        type: 'GET',
        url: './capaDatos/listReportGeneral.php' + '?fechainicio=' + $("#fechaInicio").val() +
            '&fechafin=' + $("#fechaFin").val(),
        dataType: 'json', // Esperamos datos en formato JSON
        success: function(data) {
            // Verificar si hay datos
            if (data.length === 0) {
                swal.fire('No hay datos para generar el archivo Excel.');
                return;
            }

            // Crear un nuevo libro de Excel
            var workbook = new ExcelJS.Workbook();
            var worksheet = workbook.addWorksheet('ReporteGeneral');

            // Agregar encabezados
            var headerRow = worksheet.addRow(['CEDULA', 'NOMBRE COMPLETO', 'FECHA MARCACIÓN',
                'HORA MARCACIÓN', 'LUGAR DE MARCACIÓN', 'EMPRESA', 'ÁREA'
            ]);

            headerRow.eachCell((cell) => {
                cell.font = {
                    color: {
                        argb: 'FFFFFF' // White text color
                    },
                    bold: true
                };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: '000000' // Black background color
                    }
                };
                cell.alignment = {
                    vertical: 'middle',
                    horizontal: 'center'
                };
            });

            var colors = ['9cc1ff', 'fefefe']; // Add more colors if needed

            // Add data rows to the sheet
            data.forEach(function(row, index) {
                var dataRow = worksheet.addRow([row.cedula, row.NomEmpleados + ' ' + row
                    .ApeEmpleados, row.fechaRegistro, row.horaRegistro, row
                    .NombreSede, row.NombreEmpresa, row.NombreArea
                ]);

                var colorIndex = (index + 1) % colors
                    .length; // Calculate the color index for each data row

                // Set alternating colors for each cell in the data row and center align them

                dataRow.eachCell((cell) => {

                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: colors[colorIndex]
                        },
                    };
                    cell.alignment = {
                        vertical: 'middle',
                        horizontal: 'center'
                    }; // Center align data cells
                });
            });

            // Adjust column widths to fit the content
            worksheet.columns.forEach((column) => {
                var maxLength = 0;
                column.eachCell({
                    includeEmpty: true
                }, (cell) => {
                    var columnLength = cell.value ? cell.value.toString().length :
                        10;
                    if (columnLength > maxLength) {
                        maxLength = columnLength;
                    }
                });
                column.width = maxLength < 10 ? 10 : maxLength + 2;
            });

            // Generate the Excel file and initiate the download
            workbook.xlsx.writeBuffer().then(function(buffer) {
                var blob = new Blob([buffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });

                var url = URL.createObjectURL(blob);

                var a = document.createElement('a');
                a.href = url;
                a.download = 'ReporteGeneral.xlsx';
                a.click();

                URL.revokeObjectURL(url);
            });
        }
    })
})







$('#exportarExcel').on('click', function() {
    var tabla = $('#datatable-responsive').DataTable();
    var dataObjects = tabla.data().toArray();

    if (dataObjects.length === 0) {
        Swal.fire('No hay datos para exportar', 'Realiza una búsqueda primero.', 'warning');
        return;
    }

    var workbook = new ExcelJS.Workbook();
    var sheet = workbook.addWorksheet('ReporteBiometrico');

    var headerRow = sheet.addRow(['CEDULA', 'NOMBRE COMPLETO', 'FECHA MARCACIÓN', 'HORA MARCACIÓN',
        'LUGAR DE MARCACIÓN', 'EMPRESA', 'ÁREA'
    ]);

    // Define the colors for alternating rows
    var colors = ['9cc1ff', 'fefefe']; // Add more colors if needed

    // Format the header row with white text and black background
    headerRow.eachCell((cell) => {
        cell.font = {
            color: {
                argb: 'FFFFFF'
            },
            bold: true
        }; // White text
        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: {
                argb: '000000'
            }, // Black background
        };
        cell.alignment = {
            vertical: 'middle',
            horizontal: 'center'
        }; // Center align header cells
    });

    // Add data rows to the sheet
    dataObjects.forEach((obj, index) => {
        var dataRow = sheet.addRow([obj.cedula, obj.NomEmpleados + ' ' + obj.ApeEmpleados, obj
            .fechaRegistro, obj.horaRegistro, obj.NombreSede, obj.NombreEmpresa, obj
            .NombreArea
        ]);

        var colorIndex = (index + 1) % colors.length; // Calculate the color index for each data row

        // Set alternating colors for each cell in the data row and center align them
        dataRow.eachCell((cell) => {
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: {
                    argb: colors[colorIndex]
                },
            };
            cell.alignment = {
                vertical: 'middle',
                horizontal: 'center'
            }; // Center align data cells
        });
    });

    // Adjust column widths to fit the content
    sheet.columns.forEach((column) => {
        var maxLength = 0;
        column.eachCell({
            includeEmpty: true
        }, (cell) => {
            var columnLength = cell.value ? cell.value.toString().length : 10;
            if (columnLength > maxLength) {
                maxLength = columnLength;
            }
        });
        column.width = maxLength < 10 ? 10 : maxLength + 2;
    });

    // Generate the Excel file and initiate the download
    var fecha = new Date().toISOString().slice(0, 10);
    var nombreArchivo = 'ReporteBiometrico_' + fecha + '.xlsx';
    workbook.xlsx.writeBuffer().then((data) => {
        var blob = new Blob([data], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });
        saveAs(blob, nombreArchivo);
    });
});
</script>
<style>
.bold-cell {
    font-weight: bold;
}
</style>