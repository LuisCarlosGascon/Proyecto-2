$(function(){
    var fecha=$("#evento_fecha");
    var btn=$("#btn-evento");
    var tramo=$("#tramo");
    var asistentes=$("#asistentes");
    var imagen=$("#imagen");

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
              "tramo":tramo.val()
            }
          }
        $.ajax({
            type:"POST",
            url:"http://localhost:8000/api/postEvento",
            data:JSON.stringify(evento),
            dataType:"JSON"
          });
    })


})