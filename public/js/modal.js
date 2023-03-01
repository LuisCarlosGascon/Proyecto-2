$(function(){
    var modal=$("#modalLogin");
    var modal2=document.getElementById("modalLogin");
    var btn=$("#login");
    var cerrar=$(".cerrar");

    var btnJuegos=$("#editar-juego");
    var modalJuegos=$("#modalJuegos");
    var modalJuegos2=document.getElementById("modalJuego");
    var cerrarJuegos=$(".cerrar");


    btn.click(function(){
        modal.css({"display":"block","z-index":"99999"});
    })

    cerrar.click(function(){
        modal.css({"display":"none"});
    })

    window.onclick = function(event) {
        if (event.target == modal2) {
          modal.css({"display":"none"});
        }
    }

    //Juegos

    btnJuegos.click(function(){
      modalJuegos.css({"display":"block","z-index":"99999"});
    })

    cerrarJuegos.click(function(){
      modalJuegos.css({"display":"none"});
    })

    window.onclick = function(event) {
      if (event.target == modalJuegos2) {
        modalJuegos.css({"display":"none"});
      }
    }

    var btnAudio=$("#audioBtn");
    var audio=document.getElementById("audio");
    btnAudio.click(function(){
      if(!audio.paused){
        audio.pause();
      }else{
        audio.play();
      }
    })
})