$(function(){
    var lista=$("#reservasLista");
    var modal=$("#modalReserva");

    $.getJSON("http://localhost:8000/api/getReservas",function(data){
        var datos=data["reservas"];

        //rellena el datapicker con los datos de las reservas
        var datatable=lista.DataTable({
            data:datos,
            responsive:true,
            columns:[
                {data:'user'},
                {data:'fecha'},
                {data:'juego'},
                {data:'asiste'},
                {data:'f_cancelacion'},
                {data:'mesa'}
            ],
            //cambia el idioma a español
            language: {
                "emptyTable": "No hay información",
                "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoFiltered": "(Filtrado de un total de _MAX_ registros)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        })

        //al clicar en una reserva, aparece un dialog para decir si ha asistido o no a la reserva el usuario
        $("#reservasLista tbody").on('click', 'tr', function () {
            modal.empty();
            var data = datatable.row(this).data();
            var val;
            //texto que se enseña en el datatable según el estado de su asistencia
            if(data["asiste"]=="Esperando..."){
                val="Esperando...";
            }else if(data["asiste"]=="No"){
                val="No asistido";
            }else{
                val="Asistido";
            }
            html = `
                <p>Usuario: `+data["user"]+`</p>
                <p>Fecha: `+data["fecha"]+`</p>
                <p>Juego: `+data["juego"]+`</p>
                <p>Fecha cancelacion: `+data["f_cancelacion"]+`</p>
                <p>Asiste: `+val+`</p>
                <button id="btn-si" class="btn btn-success">Asiste</button>
                <button id="btn-no" class="btn btn-danger">No asiste</button>
                `
                
            modal.dialog().append(html);
            $(".ui-dialog-titlebar-close").eq(0).text("Cerrar");

            //guarda en la bd si asiste a la reserva
            $("#btn-si").click(function(){
                var asiste={
                    "reserva":{
                      "asiste":true
                    }
                }

                $.ajax({
                    type:"PUT",
                    url:"http://localhost:8000/api/putAsisteReserva/"+data["id"],
                    data:JSON.stringify(asiste),
                    dataType:"JSON"
                  });
                  modal.dialog("close");
            })

            //guarda en la bd si no asiste a la reserva
            $("#btn-no").click(function(){
                var asiste={
                    "reserva":{
                      "asiste":false
                    }
                }

                $.ajax({
                    type:"PUT",
                    url:"http://localhost:8000/api/putAsisteReserva/"+data["id"],
                    data:JSON.stringify(asiste),
                    dataType:"JSON"
                });
                modal.dialog("close");
            })
        }).css({"cursor":"pointer"});
    })
})