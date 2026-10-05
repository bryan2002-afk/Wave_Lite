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