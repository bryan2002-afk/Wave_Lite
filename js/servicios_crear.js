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
/* ____________  Visualizar imagen Seleccionada _________________________*/
/*=======================================================================*/

const inputImagen = document.getElementById("imagen");
const preview = document.getElementById("preview");

if (inputImagen && preview) {

    inputImagen.addEventListener("change", function () {

        const archivo = this.files[0];

        if (!archivo) {
            preview.src = "";
            preview.style.display = "none";
            return;
        }

        const lector = new FileReader();

        lector.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };

        lector.readAsDataURL(archivo);

    });

}