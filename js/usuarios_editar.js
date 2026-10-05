/*=======================================================================*/
/* ______________________ dropdown-menu __________________________________*/
/*=======================================================================*/

const profileBtn = document.querySelector('.profile-btn');
const dropdownMenu = document.querySelector('.dropdown-menu');

const arrowIcon = profileBtn.querySelector('.arrow');

profileBtn.addEventListener('click', () => {
    dropdownMenu.classList.toggle('active');
    arrowIcon.classList.toggle('rotate');
})

window.addEventListener('click', (e) => {
    const clickedOutside =
    !profileBtn.contains(e.target) &&
    !dropdownMenu.contains(e.target);

    if (clickedOutside) {
        dropdownMenu.classList.remove('active');
        arrowIcon.classList.remove('rotate');
    }
})




/*=======================================================================*/
/* ____________  Visualizar OJO para VER password _________________________*/
/*=======================================================================*/

const password = document.getElementById("password");
const toggle = document.getElementById("togglePassword");

// Detectar cuando se escribe algo en el campo de contraseña
password.addEventListener("input", function() {
    if (password.value.trim() !== "") {
        // Si hay texto en el campo, mostrar el icono del ojo
        toggle.style.display = "block";
    } else {
        // Si no hay texto, ocultar el icono
        toggle.style.display = "none";
    }
});

// Mostrar u ocultar la contraseña al hacer clic en el icono del ojo
toggle.addEventListener("click", function() {
    if (password.type === "password") {
        // Mostrar la contraseña (cambiar tipo de input)
        password.type = "text";
        toggle.classList.remove("fa-eye");
        toggle.classList.add("fa-eye-slash");
    } else {
        // Ocultar la contraseña (volver al tipo password)
        password.type = "password";
        toggle.classList.remove("fa-eye-slash");
        toggle.classList.add("fa-eye");
    }
});


/*=======================================================================*/
/* ____________  Visualizar imagen Seleccionada _________________________*/
/*=======================================================================*/
/*
const inputImagen = document.getElementById("imagen");
const preview = document.getElementById("preview");

inputImagen.addEventListener("change", function () {

    const archivo = this.files[0];

    if (archivo) {

        const lector = new FileReader();

        lector.onload = function(e){

            preview.src = e.target.result;
            preview.style.display = "block";

        };

        lector.readAsDataURL(archivo);

    } else {

        preview.src = "";
        preview.style.display = "none";

    }

});
*/

const inputImagen = document.getElementById("imagen");

const previewNueva = document.getElementById("previewNueva");

inputImagen.addEventListener("change", function(){

    const archivo = this.files[0];

    if(archivo){

        const lector = new FileReader();

        lector.onload = function(e){

            previewNueva.src = e.target.result;

            previewNueva.style.display = "block";

        }

        lector.readAsDataURL(archivo);

    }else{

        previewNueva.src = "";

        previewNueva.style.display = "none";

    }

});