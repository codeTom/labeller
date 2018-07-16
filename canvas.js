var labels=[];
var scale=1.0;
//x y of the start of viewed area
var zoomx=0;
var zoomy=0;
setup = function (image_url){
    var c = document.getElementById("canvas");
    var ctx = c.getContext("2d");


    var image = new Image();
    console.log(image);
    image.onload = function(e) {
        ctx.canvas.width = image.width;
        ctx.canvas.height = image.height;
        c.width = image.width;
        c.height = image.height;
        ctx.drawImage(image, 0, 0);
        console.log(labels);
            for (i = 0; i < labels.length; i++){
                drawLabels(labels[i].id, labels[i].xMin, labels[i].xMax, labels[i].yMin, labels[i].yMax);
            }
        };

    function getSelectedLabel(){
        return $("input[name='lbl']:checked").val();
    };

    image.style.display="block";
    image.src = image_url;
    var clicked = false;
    var fPoint = {};
    var lstartx;
    var lstarty;
    c.onmousedown=function(e,i){
        lstartx=(image.width / c.scrollWidth) * e.offsetX;
        lstarty=(image.height / c.scrollHeight) * e.offsetY;
        labtype=getSelectedLabel();
        if(labtype=="zoom")
            return zoomTo(lstartx,lstarty,2);
        ilen=labels.length;
        labels=labels.filter(function(l){
            return !(lstartx>l.x1&&lstartx<l.x2&&lstarty>l.y1&&lstarty<l.y2)
        });
        if(labels.length==ilen)
            clicked=true;
        else
            redrawAll();
    };

    c.onmouseup=function(e){
        if(!clicked)
            return;
        x=(image.width / c.scrollWidth) * e.offsetX;
        y=(image.height / c.scrollHeight) * e.offsetY;
        clicked=false;
        if(Math.abs(x-lstartx)<2||Math.abs(y-lstarty)<2)
            return;
        labels.push({"x1":Math.min(lstartx,x),"x2":Math.max(x,lstartx),"y1":Math.min(lstarty,y),"y2":Math.max(y,lstarty),"type":labtype});
    };

    function clearBox(x1,y1,x2,y2){
        var width = x2-x1;
        var height = y2-y1;
        //ctx.clearRect(x1,y1,width,height);
    }

    function zoomTo(x,y,s){
        //we need to shift x,y to make the point to centre the image on this point
        //currently we see width/scale of the image, we subtract half of this to get the centre
        scale*=s;
        x-=image.width/scale/2.0;
        y-=image.width/scale/2.0;
        xv=Math.max(0,x*$("#canvas").width()/image.width);
        yv=Math.max(0,y*$("#canvas").height()/image.height);
        console.log('translate(-'+scale*xv+'px,-'+scale*yv+'px) scale('+scale+','+scale+')');
        $("#canvas").css({'transform-origin':'0px 0px','transform':'translate(-'+scale*xv+'px,-'+scale*yv+'px) scale('+scale+','+scale+')'});
    }

    function drawBox(x1,y1,x2,y2){
        ctx.beginPath();
        var width = x2-x1;
        var height = y2-y1;
        ctx.strokeRect(x1,y1,width,height);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 10;
        ctx.stroke();
    }

    function redrawAll(){
        //TODO: scaling for zoom support
        ctx.drawImage(image,0,0);
        labels.forEach(function(label){
            drawBox(label.x1,label.y1,label.x2,label.y2);
        });
        console.log(labels);
    }

    c.onmousemove=function(e){
        if(!clicked)
            return;
        x=(image.width / c.scrollWidth) * e.offsetX;
        y=(image.height / c.scrollHeight) * e.offsetY;
        redrawAll();
        drawBox(lstartx,lstarty,x,y);
    };

    $("#zoomout").click(function(c){
        scale=1;
        $("#canvas").css({'transform':'scale(1,1) translate(0px,0px)'});
        console.log("zoomed out");
    });
}
