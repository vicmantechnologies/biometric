<?php
function headLogin()
{
    ?>

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Inicio de Sesion</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/huella.ico" rel="icon">
    <link href="assets/img/huella.ico" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>

<?php
}

function bodyLogin()
{
	?>
<link href="assets/css/login.css" rel="stylesheet">
<!DOCTYPE html>
<html lang="en">


<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="index.php" class="logo d-flex align-items-center w-auto">
                                    <img src="assets/img/huella.ico" alt="">
                                    <span class="d-none d-lg-block">BIOMETRIC</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Acceso al Sistema</h5>
                                        <p class="text-center small">Ingrese con su usuario y contraseña</p>
                                    </div>
                                    <div id="alertBox"></div>
                                    <?php
                                        if (isset($_GET['error']) && $_GET['error'] == 1) {
                                            echo '<script>
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Credenciales incorrectas",
                                                    text: "Por favor, inténtalo nuevamente.",
                                                    confirmButtonColor: "#3085d6",
                                                    confirmButtonText: "Aceptar",
                                                });
                                            </script>';
                                        } elseif (isset($_GET['estado']) && $_GET['estado'] == 0) {
                                            echo '<script>
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Usuario inactivo",
                                                    text: "Tu usuario está inactivo. Por favor, contacta al administrador.",
                                                    confirmButtonColor: "#3085d6",
                                                    confirmButtonText: "Aceptar",
                                                });
                                            </script>';
                                        }
                                    ?>
                                    <form class="row g-3 needs-validation" novalidate action="panel/login.php" method="POST"
                                        onsubmit="return validateForm()">

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Correo</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="text" name="username" class="form-control"
                                                    id="yourUsername" required
                                                    onKeyUp="this.value=this.value.toUpperCase()"
                                                    oninput="validateEmail()"
                                                    value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                                                <div class="invalid-feedback">Ingrese una direccion de correo valida.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Contraseña</label>
                                            <input type="password" name="password" class="form-control"
                                                id="yourPassword" required>
                                            <div class="invalid-feedback">Ingrese su contraseña.</div>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Iniciar Sesion </button>
                                        </div>

                                    </form>
                                </div>
                            </div>

                       

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/validation.js"></script>
    <script>
    function validateForm() {
        // Obtener los valores de los campos de usuario y contraseña
        var username = document.getElementById("yourUsername").value;
        var password = document.getElementById("yourPassword").value;

        // Verificar si alguno de los campos está vacío
        if (username === "" || password === "") {
            // Mostrar una alerta al usuario
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe ingresar datos en todos los campos.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                customClass: {
                    container: 'swal-container-white', // Fondo blanco
                    title: 'swal-title-black' // Texto negro
                }
            });
            return false; // Detener el envío del formulario
        }

        // Si todo está bien, enviar el formulario
        return true;
    }
    </script>
</body>

</html>

<?php
}

function headComponent(){
  session_start();

    ?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>BIOMETRIC</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/huella.ico" rel="icon">
    <link href="../assets/img/huella.ico" rel="apple-touch-icon">


    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


</head>
<?php
}

function headerComponent(){
    ?>
<!DOCTYPE html>

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="registerCity.php" class="logo d-flex align-items-center">
            <img src="../assets/img/huella.ico" alt="">
            <span class="d-none d-lg-block">BIOMETRIC</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->



    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->


            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-person-lines-fill"></i>
                    <span
                        class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['NombreUsuario']; ?></span>
                </a>
                <!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <i class="bi bi-envelope-fill">
                            <h8> <?php echo $_SESSION['username']; ?></h8>
                        </i>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="editProfile.php">
                            <i class="bi bi-gear"></i>
                            <span>Configuración</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="signout.php?logout=1" id="logoutBtn">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Cerrar Sesion</span>
                        </a>
                    </li>


                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
<script>
document.getElementById('logoutBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Evita que el enlace realice la acción predeterminada

    // Envía una solicitud al servidor para cerrar la sesión
    fetch('signout.php')
        .then(response => {
            if (response.ok) {
                window.location.href = '../index.php'; // Redirige a "index.php" después del cierre de sesión
            } else {
                // Maneja cualquier error que pueda ocurrir al cerrar la sesión
                console.error('Error al cerrar la sesión');
            }
        })
        .catch(error => {
            console.error('Error de red al cerrar la sesión:', error);
        });
});
</script>
<?php
}


function lateralMenu()
{
    ?>

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" id="ciudades">
                <i class="bi bi-pin-map"></i><span>Ciudades</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="registerCity.php">
                        <i class="ri-checkbox-blank-circle-fill"></i><span>Nueva Ciudad</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Forms Nav -->


        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#" id="ciudades">
                <i class="bi bi-building"></i><span>Empresas</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse" data-bs-parent="#tables-nav">
                <li>
                    <a href="registerCompanies.php">
                        <i class="ri-checkbox-blank-circle-fill"></i><span>Nueva Empresa</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Tables Nav -->


        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-pin-angle"></i><span>Sedes</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse" data-bs-parent="#components-nav">
                <li>
                    <a href="registerSede.php">
                        <i class="ri-checkbox-blank-circle-fill"></i><span>Nueva Sede</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->



        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-xd" data-bs-toggle="collapse" href="#">
                <i class="bi bi-map"></i><span>Áreas</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-xd" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="registerArea.php">
                        <i class="ri-checkbox-blank-circle-fill"></i><span>Nueva Area</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Forms Nav -->


        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-user" data-bs-toggle="collapse" href="#">
                <i class="bi-person-plus"></i><span>Usuarios</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-user" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="registerUser.php">
                        <i class="ri-checkbox-blank-circle-fill"></i><span>Nuevo Usuario</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Forms Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#form-empleados" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Empleados</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="form-empleados" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="registerEmployees.php">
                        <i class="ri-checkbox-blank-circle-fill"></i><span>Empleados</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Forms Nav -->

       <!--  <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#times-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clock-history"></i><span>Horarios</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="times-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="registerTimes.php">
                        <i class="ri-checkbox-blank-circle-fill"></i><span>Nuevos Horarios</span>
                    </a>
                </li>
            </ul>
        </li> End Icons Nav --> 

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-graph-up"></i><span>Reportes</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="icons-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="reportFinger.php">
                        <i class="ri-checkbox-blank-circle-fill"></i><span>Reporte Huellas</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Icons Nav -->



    </ul>
</aside><!-- End Sidebar-->
<script src="../assets/js/validation.js"></script>


<?php
}

function endFooter(){
    ?>
<footer id="footer" class="footer">
    <div class="copyright">
        &copy; <strong><span>Holding VML.</span></strong>Todos los derechos reservados.
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/chart.js/chart.umd.js"></script>
<script src="../assets/vendor/echarts/echarts.min.js"></script>
<script src="../assets/vendor/quill/quill.min.js"></script>
<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../assets/vendor/tinymce/tinymce.min.js"></script>
<script src="../assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="../assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<?php
}

?>