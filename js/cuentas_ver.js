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



function togglePassword(id, icon) {
    const span = document.getElementById(id);

    if (span.textContent === "••••••••••••") {
        span.textContent = span.dataset.password;
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        span.textContent = "••••••••••••";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}


function copiarTexto(id){
    const texto = document.getElementById(id).innerText;

    navigator.clipboard.writeText(texto)
        .then(() => {
            alert("Copiado correctamente");
        })
        .catch(err => {
            console.error("Error al copiar:", err);
        });
}








function cambiarVista(){

    const vista = document.getElementById("vista").value;

    const todas = document.getElementById("vistaTodas");

    const servicios = document.getElementById("vistaServicios");

    if(vista==="todas"){

        todas.style.display="block";
        servicios.style.display="none";

    }else{

        todas.style.display="none";
        servicios.style.display="block";

    }

}