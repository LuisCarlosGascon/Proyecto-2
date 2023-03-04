$(function(){
    var nombre=$("#nombreEvento");
    var juego=$("#juegoEvento")
    var fecha=$("#fechaEvento");
    var btn=$("#btnEvento");
    var tramo=$("#juegoHora");
    var asistentes=$("#asistentesEvento");
    var nAsistentes=$("#nAsistentesEvento");
    var alert=$("#alert");
    var cerrar=$(".c-closebtn");

    nAsistentes.prop("disabled",true);
    fecha.css({"cursor":"pointer"});

    $.getJSON("http://localhost:8000/api/getJuegos",function(data){
        const opciones=data['juegos'];
        $.each(opciones,function(i,v){
            $("<option>").text(v["nombre"]).val(v["id"]).appendTo(juego);
        })
    })

    $.getJSON("http://localhost:8000/api/getTramos",function(data){
        const opciones=data['tramos'];
        $.each(opciones,function(i,v){
            $("<option>").text(v["hora_inicio"]["date"].substr(11,5)+" - "+v["hora_fin"]["date"].substr(11,5)).val(v["id"]).appendTo(tramo);
        })
    })

    $.getJSON("http://localhost:8000/api/getUserPuntos",function(data){
        const opciones=data['users'];
        $.each(opciones,function(i,v){
            $("<option>").text(v["nombre"]+" "+v["ape1"]).val(v["id"]).appendTo(nAsistentes);
        })
    })

    nAsistentes.change(function () {
        if($("select option:selected").length > asistentes.val()) {
            
        }
    });

    var festivos=["27/02/2023","28/02/2023","01/03/2023"];
    fecha.datepicker({
        dateFormat:"yy-mm-dd",
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
        }
    })

    btn.click(function(){
        
        var evento={
            "evento":{
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
            type:"POST",
            url:"http://localhost:8000/api/postEvento",
            data:JSON.stringify(evento),
            dataType:"JSON",
            success:function(json){
                if(json["Success"]){
                    alert.css({"display":"block"});
                }
            }
          });
    })

    cerrar.click(function(){
        var div=$(this).parent();
        div.css({"display":"none"});
    })

    asistentes.change(function(){
        
        if($(this).val()>0){
            nAsistentes.prop("disabled",false);
        }else{
            nAsistentes.prop("disabled",true);
            nAsistentes.val([]);
        }
    })

})