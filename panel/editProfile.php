<?php

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

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
                <li class="breadcrumb-item">Configuracion</li>
                <li class="breadcrumb-item active">Perfil</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center"
                        style="text-align: center;">
                        <spam><i class="bi bi-person-circle"><h2><?php echo $_SESSION['nombre'] ?></h2></i><spam>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview"><i class="bx bxs-group"> Perfil</i></button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#profile-change-password"> <i class="bx bxs-lock-alt"> Cambiar Contraseña</i></button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nombre
                                        Completo</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="fullName" type="text" class="form-control" id="fullName"
                                            value="<?php echo $_SESSION['nombre'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="nickname" class="col-md-4 col-lg-3 col-form-label">Nombre
                                        Usuario</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="nickname" type="text" class="form-control" id="nickname"
                                            value="<?php echo $_SESSION['NombreUsuario'] ?> "
                                            onKeyUp="this.value=this.value.toUpperCase()">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="correo" class="col-md-4 col-lg-3 col-form-label">Correo</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="correo" type="text" class="form-control" id="correo"
                                            value="<?php echo $_SESSION['username'] ?>"
                                            onKeyUp="this.value=this.value.toUpperCase()">
                                    </div>
                                </div>


                                <div class="row mb-3 hide-div1">
                                    <label for="profile-nickname" class="col-md-4 col-lg-3 col-form-label" readonly>Nombre
                                        Usuario</label>
                                    <div class="col-md-8 col-lg-9">
                                        <span id="profile-nickname" readonly><?php echo $_SESSION['NombreUsuario'] ?></span>
                                    </div>
                                </div>



                                <div class="row mb-3 hide-div2">
                                    <label for="profile-correo" class="col-md-4 col-lg-3 col-form-label">Correo</label>
                                    <div class="col-md-8 col-lg-9">
                                        <span id="profile-correo"><?php echo $_SESSION['username'] ?></span>
                                    </div>
                                </div>


                                <div class="text-center">
                                    <button type="button" class="btn btn-primary"
                                        onclick="updateProfile('<?php echo $_SESSION['userId']; ?>')">Guardar</button>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form>

                                </form><!-- End Profile Edit Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-settings">

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form id="changePasswordForm" onsubmit="return false">

                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Contraseña
                                            Actual</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password" class="form-control"
                                                id="currentPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nueva
                                            Contraseña</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword" type="password" class="form-control"
                                                id="newPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Repetir
                                            Contraseña Nueva</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="renewpassword" type="password" class="form-control"
                                                id="renewPassword">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
endFooter();
?>

<script>
function updateProfile() {
    // Get the updated data from the input fields
    var nickname = $('#nickname').val();
    var correo = $('#correo').val();

    // AJAX request
    $.ajax({
            url: 'capaDatos/updateProfile.php', // PHP script to handle the update
            method: 'POST', // Use POST method to send data securely
            data: {
                nickname: nickname,
                correo: correo
            },
            dataType: 'json',
            async: true,
        })
        .done(function(res) {
            console.log(res);
            if (res.resultado != 0) {

                $('#nickname').val(res.newNickname);
                $('#correo').val(res.newCorreo);


                $('#profile-nickname').text(res.newNickname);
                $('#profile-correo').text(res.newCorreo);

                Swal.fire({ // Use the Swal.fire() function instead of swal()
                    title: "Buen Trabajo!",
                    text: res.mensaje,
                    icon: "success"
                })
            }
        })
        .fail(function(err) {
            // Handle the error if the AJAX request fails
            console.error("Error: ", err);
            Swal.fire({
                title: "Error",
                text: "¡Algo salió mal!",
                icon: "error"
            });
        });
}


function changePassword() {
    // Get the password values from the input fields
    var currentPassword = $('#currentPassword').val();
    var newPassword = $('#newPassword').val();
    var renewPassword = $('#renewPassword').val();

    // Validate password fields (you can add more validation as per your requirements)
    if (currentPassword === "" || newPassword === "" || renewPassword === "") {
        Swal.fire({
            title: "Error",
            text: "Por favor, rellene todos los campos de la contraseña.",
            icon: "warning"
        });
        return;
    }
    if (newPassword.length < 6) {
            Swal.fire({
                title: "Error",
                text: "La nueva contraseña debe tener al menos 6 caracteres.",
                icon: "warning"
            });
            return;
        }
        
    if (newPassword !== renewPassword) {
        Swal.fire({
            title: "Error",
            text: "La nueva contraseña y la contraseña reingresada no coinciden.",
            icon: "warning"
        });
        return;
    }




    // AJAX request
    $.ajax({
            url: 'capaDatos/changePassword.php', // PHP script to handle the password change
            method: 'POST',
            data: {
                currentPassword: currentPassword,
                newPassword: newPassword,
                renewPassword: renewPassword
            },
            dataType: 'json',
            async: true,
        })
        .done(function(res) {
            console.log(res);
            if (res.resultado !== 0) {
                Swal.fire({
                    title: "Buen trabajo!",
                    text: res.mensaje,
                    icon: "success"
                }).then(function() {
                    // Clear the password fields
                    $('#currentPassword').val('');
                    $('#newPassword').val('');
                    $('#renewPassword').val('');
                });
            }else {
                // Mostrar Sweet Alert si la contraseña actual no coincide
                Swal.fire({
                    title: "La contraseña actual no coincide",
                    text: res.mensaje, // Mensaje de error proporcionado por el servidor
                    icon: "error"
                });
            }
        })
        .fail(function(err) {
            console.error("Error: ", err);
            Swal.fire({
                title: "Error",
                text: "¡Algo salió mal!",
                icon: "error"
            });
        });
}
$('.hide-div1').hide();
$('.hide-div2').hide();
$('#changePasswordForm').on('submit', changePassword);
</script>
<style>
    .hide-div1,
    .hide-div2 {
        display: none;
    }
</style>

