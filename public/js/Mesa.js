class Mesa{
    constructor(id,alto,ancho,sillas,x,y){
        this.id=id;
        this.alto=alto;
        this.ancho=ancho;
        this.sillas=sillas;
        this.x=x;
        this.y=y;
        this.representacion=$("<div>").attr("id",this.id).css({width:this.ancho,height:this.alto,"background-color":"black"}).addClass("mesa");
    }
    
    

    drag(){
        $(this.representacion).draggable({
            start: function (ev, ui) {
            //   $(this).attr("dataY",ui.offset.top);
            //   $(this).attr("dataX",ui.offset.left);
              //$(this).css({width:this.ancho,height:this.ancho})
            },
            revert:true,
            revertDuration:0,
            helper:'clone',
            accept: '#trastero, #sala',
          });
    }

    pinta(sala,trastero,difX,difY){
        var mesa=$(this.representacion);
        var x=this.x;
        var y=this.y;

        if(x===null || y===null){
            mesa.css({width:"50px",height:"50px"});
            trastero.append(mesa);
        }else{
            x=x+difX;
            y=y+difY;
            mesa.css({position: 'absolute', top: y + "px", left: x + "px"});
            sala.append(mesa);
        }
    } 
}