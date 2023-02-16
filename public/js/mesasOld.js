$(function(){
    $(".mesa").draggable({
        start:function(ev,ui){
           $(this).attr("data-y",ui.offset.top);
            $(this).attr("data-x",ui.offset.left);
            
            //$(this).attr("style","width: 50px;height: 50px; background-color: black; border-color: black;position;absolute")
        }//,revert:true,helper:"clone",revertDuration:0
    });
    $("#trastero").droppable({
        drop:function(ev,ui){
            var mesa=ui.draggable;
            mesa.attr("style","");
            $(this).append(mesa);
        }
    })
    $("#sala").droppable({
        drop:function(ev,ui){
            //margenes de la página
            var difX=$(this).offset().left;
            var difY=$(this).offset().top;
            // console.log(ui);
            // console.log(ev);
            var mesa=ui.draggable;
            //coordenadas donde se está soltando
            var left=parseInt(ui.offset.left);
            var top=parseInt(ui.offset.top);
            //console.log([left,top]);
            var width=parseInt(mesa.width());
            var height=parseInt(mesa.height());
            var pos1=[left,left+width,top,top+height]; //left+width=coordenada de la derecha de la mesa, top+height=coordenada de abajo de la mesa
            //Hacer en una clase mesa en sus propiedades
            var mesaYa=$("#sala .mesa").eq(0);
            console.log(mesaYa);
            //debugger;
            if(mesaYa.length>0){
                var posX=parseInt(mesaYa.offset().left);//con parseInt se le quita el "px" que devuelve el offset
                var posY=parseInt(mesaYa.offset().top);
                var anchura=parseInt(mesaYa.width());
                var altura=parseInt(mesaYa.height());
                var pos2=[posX,posX+anchura,posY,posY+altura];

                debugger;
                if(((pos1[0]>pos2[0]&&pos1[0]<pos2[1]) || (pos1[1]>pos2[0]&&pos1[1]<pos2[1]) || (pos1[0]<=pos2[0]&&pos1[1]>=pos2[1]))&&
                ((pos1[2]>pos2[2]&&pos1[2]<pos2[3]) || (pos1[3]>pos2[2]&&pos1[3]<pos2[3]) || (pos1[2]<=pos2[2]&&pos1[3]>=pos2[2]))){
                    console.log("CHOQUE");
                }else{
                    $(this).append(mesa);
                    mesa.css({position:"absolute",top:top-difY+"px",left:left-difX+"px"});
                }
            }else{
                $(this).append(mesa);
                mesa.css({position:"absolute",top:top+"px",left:left+"px"});
            }
            
        }
    });


})