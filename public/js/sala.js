$("document").ready(function () {
  var sala=$("#sala");
  var trastero=$("#trastero");
  var selectDis=$("#selectDis");
  var difX=sala.offset().left;
  var difY=sala.offset().top;

  var alto=$("#alto");
  var ancho=$("#ancho")
  var sillas=$("#sillas")
  var btnCrear=$("#crear");


  btnCrear.click(function(){
    if(alto.val()!=null && ancho.val()!=null && sillas.val()!=null){
      var mesa={
        "mesa":{
          "alto":alto.val(),
          "ancho":ancho.val(),
          "sillas":sillas.val()
        }
      }

      $.ajax({
        type:"POST",
        url:"http://localhost:8000/api/postMesa",
        data:JSON.stringify(mesa),
        dataType:"JSON",
        success:function(json){
          debugger
          console.log(json["id"])
          var mesaCreada=new Mesa(json.id,json.alto,json.ancho,json.sillas,null,null);
      
          mesaCreada.drag();
          mesaCreada.pinta(sala,trastero,difX,difY);
        }
      }); 
    }
  })

  
  
  
  colocaBase(sala,trastero,difX,difY);
  

    $('#trastero').droppable({
      drop:function (ev, ui) {
          let mesa = ui.draggable;    
          
          mesa.attr('style','width=50px;height=50px;');
          $(this).append(mesa);

          if(selectDis.val()!=null){
            $.getJSON("http://localhost:8000/api/getMesaDistribucion/"+mesa.attr("id")+"/"+selectDis.val(),function(final){
              console.log(final.distribuciones[0]["id"])
            
        
            if(final.distribuciones.length>0){
              var distribuciones={
                "disposiciones":{
                  "id":final.distribuciones[0]["id"],
                  "x":null,
                  "y":null
                }
              }
  
              $.ajax({
                type:"PUT",
                url:"http://localhost:8000/api/putDistribucionMesa",
                data:JSON.stringify(distribuciones),
                dataType:"JSON"
              });
            }else{
              var distribuciones={
                "disposiciones":{
                  "mesa":mesa.attr("id"),
                  "distribucion":selectDis.val(),
                  "x":null,
                  "y":null
                }
              }
  
              $.ajax({
                type:"POST",
                url:"http://localhost:8000/api/postDistribucionMesa",
                data:JSON.stringify(distribuciones),
                dataType:"JSON"
              }); 
            }
            // console.log(final.distribuciones[0]["id"]);
            
          });
        }else{

          $.getJSON("http://localhost:8000/api/getMesa/"+mesa.attr("id"),function(final){
          //Hago un formato JSON con las nuevas coordenadas de las mesas
          var mesaFinal={
            "mesa":{
              "id":final.mesa["id"],
              "alto":final.mesa["alto"],
              "ancho":final.mesa["ancho"],
              "sillas":final.mesa["sillas"],
              "x":null,
              "y":null
            }
          }
          //Llamo a la API que edita la mesa
          cambiaPosicion(mesaFinal);
        });
      }}
  })

  $("#sala").droppable({
    drop: function (ev, ui) {
      //Captura la mesa que se droppea y las coordenadas donde se está soltando
      let mesa = ui.draggable;
      let left = parseInt(ui.offset.left);
      let top = parseInt(ui.offset.top);
      let width = mesa.width();
      let height = mesa.height();

      var pos1=[left,left+width,top,top+height];//left+width=coordenada de la derecha de la mesa, top+height=coordenada de abajo de la mesa

      //Ver si tiene algún choque o no
      var esMovible=true;

      var lista=$('#sala .mesa');

      for (let i=0;i<lista.length-1;i++){
      //captura una mesa de la sala
        let mesaYa = $('#sala .mesa').eq(i);

        if (mesaYa.length>0) {
          if(!(mesa[0].id==mesaYa.attr("id"))){//Evitar que se comparen las mismas mesas 
            let posX = parseInt(mesaYa.offset().left);//con parseInt se le quita el "px" que devuelve el offset
            let posY = parseInt(mesaYa.offset().top);
            
            let anchura = mesaYa.width();
            let longitud = mesaYa.height();
            let pos2=[posX,posX+anchura,posY,posY+longitud];
            
            if(!validaPosicion(pos1,pos2)){
              esMovible=false;
            }
          }
        }
      }

      //Mover la mesa si no ha chocado con ninguna otra
      if(esMovible){
        mesa.css({width:mesa.attr("ancho")+"px",height:mesa.attr("alto")+"px"});
        $(this).append(mesa);
        mesa.css({ position: 'absolute', top: top + "px", left: left + "px" });
        //obtengo los datos de la mesa movida desde la base de datos
       
        if(selectDis.val()!=null){
          $.getJSON("http://localhost:8000/api/getMesaDistribucion/"+mesa.attr("id")+"/"+selectDis.val(),function(final){
     
          console.log(left)
        
            if(final.distribuciones.length>0){
              var distribuciones={
                "disposiciones":{
                  "id":final.distribuciones[0]["id"],
                  "x":left-difX,
                  "y":top-difY
                }
              }
  
              $.ajax({
                type:"PUT",
                url:"http://localhost:8000/api/putDistribucionMesa",
                data:JSON.stringify(distribuciones),
                dataType:"JSON"
              });
            }else{
              var distribuciones={
                "disposiciones":{
                  "mesa":mesa.attr("id"),
                  "distribucion":selectDis.val(),
                  "x":left-difX,
                  "y":top-difY
                }
              }
  
              $.ajax({
                type:"POST",
                url:"http://localhost:8000/api/postDistribucionMesa",
                data:JSON.stringify(distribuciones),
                dataType:"JSON"
              }); 
            }
            // console.log(final.distribuciones[0]["id"]);
            
          });
        }else{
          $.getJSON("http://localhost:8000/api/getMesa/"+mesa.attr("id"),function(final){
          //Hago un formato JSON con las nuevas coordenadas de las mesas
          var mesaFinal={
            "mesa":{
              "id":final.mesa["id"],
              "alto":final.mesa["alto"],
              "ancho":final.mesa["ancho"],
              "sillas":final.mesa["sillas"],
              "x":left-difX,
              "y":top-difY
            }
          }
          //Llamo a la API que edita la mesa
          cambiaPosicion(mesaFinal);
        });
        }
        
      }
    },
  });
  // });

});
function validaPosicion(pos1,pos2){
  var valido=true;
  if ( (pos1[0] > pos2[0] && pos1[0] < pos2[1] ||
                pos1[1] > pos2[0] && pos1[1] < pos2[1] ||
                pos1[0] <= pos2[0] && pos1[1] >= pos2[1])
                
                &&
              
                (pos1[2] > pos2[2] && pos1[2] < pos2[3] ||
                pos1[3] > pos2[2] && pos1[3] < pos2[3] ||
                pos1[2] <= pos2[2] && pos1[3] >= pos2[3]))
          {
              valido=false;
          }
  return valido;
}

function cambiaPosicion(mesa){
  $.ajax({
    type:"PUT",
    url:"http://localhost:8000/api/putMesa",
    data:JSON.stringify(mesa),
    dataType:"JSON"
  });
}

function colocaBase(sala,trastero,difX,difY){
  sala.empty();
  trastero.empty();
  
  $.getJSON("http://localhost:8000/api/getMesas",function(data){
    data.mesas.forEach(element => {
      var mesa=new Mesa(element.id,element.alto,element.ancho,element.sillas,element.x,element.y);
      
      mesa.drag();
      mesa.pinta(sala,trastero,difX,difY);
      
    });
  });
}