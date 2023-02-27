$(function(){
    var sala=$("#sala");
    var trastero=$("#trastero");
    var selectDis=$("#selectDis");
    var difX=sala.offset().left;
    var difY=sala.offset().top;
    var base=$("#base");
    var opcionesSelect=[];
    var distribucionNueva=$("#distribucionNueva");
    var btnDist=$("#crearDist");

    btnDist.prop("disabled",true);
    //Pinta todas las distribuciones en el SELECT
    $.getJSON("http://localhost:8000/api/getDistribuciones",function(data){
        const opciones=data['distribuciones'];
        $.each(opciones,function(i,v){
            $("<option>").text(v["fecha"]["date"].substr(0,10)).val(v["id"]).appendTo(selectDis);
            opcionesSelect.push(v["fecha"]["date"].substr(0,10))
            
        })
    })

    selectDis.change(function(){
        sala.empty();
        trastero.empty();

        $.getJSON("http://localhost:8000/api/getMesas",function(mesas){
            lista=[];

            mesas.mesas.forEach(element => {
                var mesa=new Mesa(element.id,element.alto,element.ancho,element.sillas,element.x,element.y);
                lista.push(mesa);
            });
            lista.forEach(mesa =>{
                $.getJSON("http://localhost:8000/api/getMesaDistribucion/"+mesa.id+"/"+selectDis.val(),function(distIds){
                    if(distIds["distribuciones"][0]===undefined){
                        var distribuciones={
                            "disposiciones":{
                              "mesa":mesa.id,
                              "distribucion":selectDis.val(),
                              "x":0,
                              "y":0
                            }
                          }

                          mesa.x=null;
                          mesa.y=null;

                          $.ajax({
                            type:"POST",
                            url:"http://localhost:8000/api/postDistribucionMesa",
                            data:JSON.stringify(distribuciones),
                            dataType:"JSON"
                          }); 
                    }else{
                        if(distIds["distribuciones"][0].x!=0){
                            var coordenadas=distIds["distribuciones"][0];
                            mesa.x=coordenadas.x;
                            mesa.y=coordenadas.y;
                        }else{
                            mesa.x=null;
                            mesa.y=null;
                        }
                    }
                    mesa.drag();
                    mesa.pinta(sala,trastero,difX,difY);
                })
            })
        })
    })

    base.click(function(){
        colocaBase(sala,trastero,difX,difY);
        document.getElementById("default").selected="true";
    })

    

    var festivos=["27/02/2023","28/02/2023","01/03/2023"];
    distribucionNueva.datepicker({
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
            var cadenaFecha=((dia<10)?"0"+dia:dia)+"-"+
                ((mes<10)?"0"+mes:mes)+"-"+anno
            var cadenaFecha2=anno+"-"+
            ((mes<10)?"0"+mes:mes)+"-"+((dia<10)?"0"+dia:dia)

            opcionesSelect.forEach(element=>{
                if(fecha.getDay()%6==0 || festivos.indexOf(cadenaFecha)>-1 || element==cadenaFecha2){
                    respuesta=[false,"","cerrado/distribucion ya existente"];
                }
            })

            return respuesta;
        },
        onSelect:function(selectedDate){
            sala.empty();
            trastero.empty();

            $.getJSON("http://localhost:8000/api/getDistribucionReserva/"+selectedDate,function(id){
                if(id["id"]==null){
                    colocaBase(sala,trastero,difX,difY);
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