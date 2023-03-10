$(function(){
    var juegos=$("#juegosReserva");
    var fecha=$("#fecha");
    var tramo=$("#tramo");
    var jugadores=$("#jugadores");
    var base=$("#base");
    var btn=$("#btn");
    var btnAtras=$("#btn-atras");
    var alert1=$("#alert1");
    var alert4=$("#alert4");
    var cerrar=$(".c-closebtn");

    var sala=$("#sala");
    var trastero=$("#trastero");
    var difX=sala.offset().left;
    var difY=sala.offset().top;

    base.click(function(){
        colocaBaseUser(sala,trastero,difX,difY);
        fecha.val(a.getFullYear()+"-"+(a.getMonth()+1)+"-"+a.getDate());
    })

    btnAtras.click(function(){
        btn.prop("disabled",true);
        fecha.prop("disabled",false);
        base.prop("disabled",false);
        jugadores.prop("disabled",false);
        tramo.prop("disabled",true);
        $("#jugadores option:eq(0)").prop("selected",true);
        $("#tramo option:eq(0)").prop("selected",true);
        juegos.empty();
        $("#sala .mesa .img-drag").parent().empty();
    })
   
    //boton de cerrar el alert
    cerrar.click(function(){
       var div=$(this).parent();
       div.css({"display":"none"});
    })
    colocaBaseUser(sala,trastero,difX,difY);
    rellenaJugadores(2,10,$("#jugadores"))
    var a=new Date();
    //inicializa la fecha por la de hoy
    fecha.val(a.getFullYear()+"-"+(a.getMonth()+1)+"-"+a.getDate()).css("cursor","pointer");

    btn.prop("disabled",true);
    tramo.prop("disabled",true);

    tramo.change(function(){
        btn.prop("disabled",false);
    })

    //rellena el div con los juegos draggables
    jugadores.change(function(){
        juegos.empty();
        $.getJSON("http://localhost:8000/api/getJuegos",function(data){
            var nJugadores=jugadores.val();
            data["juegos"].forEach(juego=>{
                if(juego["min_jugadores"]<=nJugadores && juego["max_jugadores"]>=nJugadores){
                    //crea los juegos dependiendo de los jugadores seleccionados
                    var divJuego=$("<div>").attr({'id':juego["id"],'alto':juego["alto"],'ancho':juego["ancho"],"minJ":juego["min_jugadores"],"maxJ":juego["max_jugadores"]}).css({'width':'50px','height':'50px'}).addClass("img-drag").append($("<img>").attr({'src':'../img/'+juego["imagen"],'width':'100%','height':'100%'}));
                    juegos.append(divJuego);
                    divJuego.draggable({
                        revert:true,
                        revertDuration:0,
                        helper:function(){
                            return $(this).clone().css({"z-index":1,"width":juego["ancho"],"height":juego["alto"]})
                        },
                        accept: "#sala .mesa"
                    });
                } 
            })
        })
    })
    
    //rellena el select de tramos
    $.getJSON("http://localhost:8000/api/getTramos",function(data){
        const opciones=data['tramos'];
        $.each(opciones,function(i,v){
            $("<option>").text(v["hora_inicio"]["date"].substr(11,5)+" - "+v["hora_fin"]["date"].substr(11,5)).val(v["id"]).appendTo(tramo);
        })
    })

    //datapicker de fechas
    var festivos=["27/02/2023","28/02/2023","01/03/2023"];
    fecha.datepicker({
        dateFormat:"yy-mm-dd",
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septtembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi??rcoles', 'Jueves', 'Viernes', 'S??bado'],
        dayNamesShort: ['Dom','Lun','Mar','Mi??','Juv','Vie','S??b'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S??'],
        firstDay:1,
        minDate:"+1D",
        maxDate:"+3M",
        beforeShowDay: function(fecha){ //Se??ala los dias festivos y los fines de semana como cerrado
            var respuesta=[true,"",""];
            var dia=fecha.getDate();
            var mes=fecha.getMonth()+1;
            var anno=fecha.getFullYear();
            var cadenaFecha=((dia<10)?"0"+dia:dia)+"/"+
                ((mes<10)?"0"+mes:mes)+"/"+anno
                    
            if(fecha.getDay()%6==0 || festivos.indexOf(cadenaFecha)>-1){
                respuesta=[false,"","cerrado"];
            }
            return respuesta;
        },
        onSelect:function(selectedDate){
            sala.empty();
            trastero.empty();

            //Muestra el mapa de mesas seg??n la distribuci??n elegida

            $.getJSON("http://localhost:8000/api/getDistribucionReserva/"+selectedDate,function(id){
                if(id["id"]==null){
                    colocaBaseUser(sala,trastero,difX,difY);
                }else{
                    $.getJSON("http://localhost:8000/api/getMesas",function(mesas){
                        lista=[];

                        mesas.mesas.forEach(element => {
                            var mesa=new Mesa(element.id,element.alto,element.ancho,element.sillas,element.x,element.y);
                            lista.push(mesa);
                        });
                        lista.forEach(mesa =>{
                            //Por cada mesa lee su distribuci??n
                            $.getJSON("http://localhost:8000/api/getMesaDistribucion/"+mesa.id+"/"+id["id"].id,function(distIds){
                                //si la mesa no tiene una ubicaci??n para esa distribuci??n se coloca en el trastero con posiciones null
                                if(distIds["distribuciones"][0].x!=0){
                                    var coordenadas=distIds["distribuciones"][0];
                                    mesa.x=coordenadas.x;
                                    mesa.y=coordenadas.y;
                                }else{
                                    mesa.x=null;
                                    mesa.y=null;
                                }

                                //mesa.drag();
                                mesa.pinta(sala,trastero,difX,difY);
                                //hace las mesas droppables para los juegos
                                $("#sala .mesa").droppable({
            
                                    drop: function (ev, ui) {
                                    let juego = ui.draggable;

                                    //comprueba que el juego sea compatible con la mesa
                                    if(compruebaJuegoMesa(juego,$(this),$("#jugadores").val())){
                                        $("#tramo").prop("disabled",false);
                                        $("#jugadores").prop("disabled",true);
                    
                                        juego.css({width:$(this).attr("ancho"),height:$(this).attr("alto")}).appendTo($(this));
                                        $("#juegosReserva").empty();
                                        $("#fecha").prop("disabled",true);
                                        $("#base").prop("disabled",true);
                                  }
                                  
                                  },
                                
                              });

                            })
                        })   
                    })
                } 
            })
        }
    })

    //crea la reserva
    btn.click(function(){
        var reserva={
            "reserva":{
              "fecha":fecha.val(),
              "mesa":$("#sala .mesa .img-drag").parent().attr("id"),
              "juego":$("#sala .mesa .img-drag").attr("id"),
              "tramo":tramo.val()
            }
          }
        $.ajax({
            type:"POST",
            url:"http://localhost:8000/api/postReserva",
            data:JSON.stringify(reserva),
            dataType:"JSON",
            success:function(json){
                if(json["Success"]==false){
                    alert1.css({"display":"block"});
                }else{//Limpia el formulario en caso de que se realice la reserva correctamente
                    btn.prop("disabled",true);
                    fecha.prop("disabled",false);
                    base.prop("disabled",false);
                    jugadores.prop("disabled",false);
                    tramo.prop("disabled",true);
                    $("#jugadores option:eq(0)").prop("selected",true);
                    $("#tramo option:eq(0)").prop("selected",true);
                    juegos.empty();
                    $("#sala .mesa .img-drag").parent().empty();
                    $(".c-alert").css({"display":"none"});
                    alert4.css({"display":"block"});

                }
            }
        });
    })
})

//Coloca el mapa base de las mesas
function colocaBaseUser(sala,trastero,difX,difY){
    sala.empty();
    trastero.empty();
    $.getJSON("http://localhost:8000/api/getMesas",function(data){
      data.mesas.forEach(element => {
        var mesa=new Mesa(element.id,element.alto,element.ancho,element.sillas,element.x,element.y);
        

        mesa.pinta(sala,trastero,difX,difY);

        $("#sala .mesa").droppable({
            
            drop: function (ev, ui) {
              let juego = ui.draggable;
            //Comprueba si el juego es valido para la mesa droppeada
              if(compruebaJuegoMesa(juego,$(this),$("#jugadores").val())){
                $("#tramo").prop("disabled",false);
                $("#jugadores").prop("disabled",true);

                juego.css({width:$(this).attr("ancho"),height:$(this).attr("alto")}).appendTo($(this));
                $("#juegosReserva").empty();
                $("#fecha").prop("disabled",true);
                $("#base").prop("disabled",true);
              }
              
              },
            
          });
      });

      return 
    });
}

function rellenaJugadores(min,max,select){
    for (let i=min;i<=max;i++){
        $("<option>").text(i).val(i).appendTo(select);
    }
}

//en funcion de la restricci??n violada, muestra su propio alert de fallo
function compruebaJuegoMesa(juego,mesa,jugadores){
    var resultado;
    if(!(parseInt(juego.attr("alto"))<=parseInt(mesa.attr("alto"))) || !(parseInt(juego.attr("ancho"))<=parseInt(mesa.attr("ancho")))){
        $("#alert2").css({"display":"block"});
    }else if(!(parseInt(jugadores)<=parseInt(mesa.attr("sillas")))){
        $("#alert3").css({"display":"block"});
    }



    if(parseInt(juego.attr("alto"))<=parseInt(mesa.attr("alto")) && parseInt(juego.attr("ancho"))<=parseInt(mesa.attr("ancho")) && parseInt(jugadores)<=parseInt(mesa.attr("sillas"))){
        resultado=true;
    }else{
        resultado=false;
    }
    return resultado;
}