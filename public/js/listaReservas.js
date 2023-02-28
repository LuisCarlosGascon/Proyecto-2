$(function(){
    var lista=$("#reservasLista");
    var modal=$("#modalReserva");

    $.getJSON("http://localhost:8000/api/getReservas",function(data){
        var datos=data["reservas"];

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

        $("#reservasLista tbody").on('click', 'tr', function () {
            modal.empty();
            var data = datatable.row(this).data();
            var val;
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