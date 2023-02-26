$(function(){
    var btn=$(".btn-juego");
    var modal=$("#modalJuegos");
    var plantilla=
    `<div class="modal-contenido-juegos">
    <div class="modal-juegos__imagen">
        <img src="{{asset('img/' ~ juego.imagen)}}" class="c-juegoDetalles__imagen ml-2">
    </div>
            <div class="">
                <table>
                    <tr>
                        <td>Nombre</td>
                        <td>{{juego.nombre}}</td>
                    </tr>
                    <tr>
                        <td>Tamaño</td>
                        <td>{{juego.alto}} x {{juego.ancho}}</td>
                    </tr>
                    <tr>
                        <td>Mínimo de jugadores</td>
                        <td>{{juego.MinJugadores}}</td>
                    </tr>
                    <tr>
                        <td>Máximo de jugadores</td>
                        <td>{{juego.MaxJugadores}}</td>
                    </tr>
                </table>
            </div>
        
</div>`
    modal.dialog({
        modal:true,
        autoOpen: false,

      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "fade",
        duration: 1000
      }
    }).append(plantilla);
    btn.click(function(){

        modal.dialog("open");
        var id=btn.attr("idjuego");
        console.log(id)
    })
})