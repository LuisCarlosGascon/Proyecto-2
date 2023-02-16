$(function(){
    var sala=$("#sala");
    var trastero=$("#trastero");
    var selectDis=$("#selectDis");
    var difX=sala.offset().left;
    var difY=sala.offset().top;
    var base=$("#base");
    
    //Pinta todas las distribuciones en el SELECT
    $.getJSON("http://localhost:8000/api/getDistribuciones",function(data){
        const opciones=data['distribuciones'];
        $.each(opciones,function(i,v){
            $("<option>").text(v["fecha"]["date"].substr(0,10)).val(v["id"]).appendTo(selectDis);
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
                    if(distIds["distribuciones"].length!=0){
                        var coordenadas=distIds["distribuciones"][0];
                        mesa.x=coordenadas.x;
                        mesa.y=coordenadas.y;
                    }else{
                        mesa.x=null;
                        mesa.y=null;
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


})