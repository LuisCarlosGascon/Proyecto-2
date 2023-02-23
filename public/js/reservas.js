$(function(){
    var juegos=$("#juegosReserva");
    var fecha=$("#fecha");
    var tramo=$("#tramo");
    var mesas=$("#mesas");
    var jugadores=$("#jugadores");
    var btn=$("#btn");

    var sala=$("#sala");
    var trastero=$("#trastero");
    var difX=sala.offset().left;
    var difY=sala.offset().top;
   
    colocaBaseUser(sala,trastero,difX,difY);



    juegos.prop("disabled",true);
    tramo.prop("disabled",true);
    jugadores.prop("disabled",true);
    btn.prop("disabled",true);


    $.getJSON("http://localhost:8000/api/getJuegos",function(data){
        
        data["juegos"].forEach(juego=>{
            var divJuego=$("<div>").attr({'alto':juego["alto"],'ancho':juego["ancho"]}).addClass("img-drag").append($("<img>").attr({'src':'../img/'+juego["imagen"],'width':'50px','height':'50px'}));
            juegos.append(divJuego);
            divJuego.draggable({
                revert:true,
                revertDuration:0,
                helper:function(){
                    return $(this).clone().css({"z-index":1})
                },
                accept: "#sala .mesa"
            });
        })
    })

    $.getJSON("http://localhost:8000/api/getTramos",function(data){
        const opciones=data['tramos'];
        $.each(opciones,function(i,v){
            $("<option>").text(v["hora_inicio"]["date"].substr(11,5)+" - "+v["hora_fin"]["date"].substr(11,5)).val(v["id"]).appendTo(tramo);
        })
    })

    var festivos=["27/02/2023","28/02/2023","01/03/2023"];
    fecha.datepicker({
        dateFormat:"dd-mm-yy",
        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septtembre", "Octubre", "Noviembre", "Diciembre" ],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        firstDay:1,
        minDate:"+1D",
        maxDate:"+3M +1D",
        beforeShowDay: function(fecha){
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
                            $.getJSON("http://localhost:8000/api/getMesaDistribucion/"+mesa.id+"/"+id["id"].id,function(distIds){

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
                            })
                        })   
                    })
                } 
            })
        }
    })
})

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
              //$(this).append(juego)
              $("#tramo").prop("disabled",false);
              $("#jugadores").prop("disabled",false);
        
              //Ver si tiene algún choque o no
              var esMovible=true;
              },
            
          });
        
      });
    });
  }