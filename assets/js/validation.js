// Validaciones para el login, "corre" y "password"
// joel.gonzalez@holdingvml.net
function validateEmail() {
    var emailInput = document.getElementById("yourUsername");
    var emailValue = emailInput.value.trim();
    var isValidEmail = emailValue.includes("@") && emailValue.includes(".", emailValue.indexOf("@"));
    var iconContainer = document.getElementById("iconContainer");

    if (!isValidEmail) {
        emailInput.classList.add("is-invalid"); // Agrega la clase is-invalid
        emailInput.classList.remove("is-valid"); // Elimina la clase is-valid si el correo no es válido
        iconContainer.querySelector(".fa-check").classList.add("d-none"); // Oculta el ícono de verificación
        iconContainer.querySelector(".fa-exclamation").classList.remove(
        "d-none"); // Muestra el ícono de exclamación
    } else {
        emailInput.classList.remove("is-invalid"); // Elimina la clase is-invalid si el correo es válido
        emailInput.classList.add("is-valid"); // Agrega la clase is-valid
        iconContainer.querySelector(".fa-check").classList.remove("d-none"); // Muestra el ícono de verificación
        iconContainer.querySelector(".fa-exclamation").classList.add("d-none"); // Oculta el ícono de exclamación
    }
}

// Script para la function lateralMenu para que el modulo desplegable funcione
// joel.gonzalez@holdingvml.net
document.addEventListener("DOMContentLoaded", function() {
    // Obtener el elemento del menú activo almacenado en la cookie (si existe)
    var activeMenu = getCookie("activeMenu");
    if (activeMenu) {
        var activeMenuElement = document.querySelector(activeMenu);
        if (activeMenuElement) {
            var parentCollapse = activeMenuElement.closest(".nav-content.collapse");
            if (parentCollapse) {
                parentCollapse.classList.add("show");
            }
        }
    }

    // Función para guardar el elemento del menú activo en una cookie
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    // Función para obtener el valor de una cookie
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Escuchar el evento click en los enlaces del menú
    var navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach(function(link) {
        link.addEventListener("click", function(event) {
            var target = event.target.dataset.bsTarget;
            setCookie("activeMenu", target,
            1); // Guardar el elemento del menú activo en una cookie por 1 día
        });
    });
});
