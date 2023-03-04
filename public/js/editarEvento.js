$(function(){
    var nombre=$("#nombreEvento");
    var juego=$("#juegoEvento")
    var fecha=$("#fechaEvento");
    var btn=$("#btnEvento_editar");
    var tramo=$("#juegoHora");
    var asistentes=$("#asistentesEvento");
    var nAsistentes=$("#nAsistentesEvento");
    var alert=$("#alertPut");
    var cerrar=$(".c-closebtn");

    nAsistentes.prop("disabled",false);

    btn.click(function(){
        var evento={
            "evento":{
              "id":nombre.attr("eventoId"),
              "nombre":nombre.val(),
              "fecha":fecha.val(),
              "num_asistentes_max":asistentes.val(),
              "tramo":tramo.val(),
              "juego":juego.val(),
              "users":nAsistentes.val()
            }
          }
          console.log(evento)
        $.ajax({
            type:"PUT",
            url:"http://localhost:8000/api/putEvento",
            data:JSON.stringify(evento),
            dataType:"JSON",
            success:function(json){
                if(json["Success"]){
                    alert.css({"display":"block"});
                }
            }
          });
    })
})