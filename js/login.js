const form = document.getElementById("loginForm");
const audio = document.getElementById("clickSound");

let enviado = false;

form.addEventListener("submit", function(e){
    e.preventDefault();

    function enviarFormulario(){
        if(!enviado){
            enviado = true;
            form.submit();
        }
    }

    audio.play()
        .then(() => {
            audio.onended = enviarFormulario;
        })
        .catch(() => {
            enviarFormulario();
        });

    setTimeout(enviarFormulario, 3000);
});