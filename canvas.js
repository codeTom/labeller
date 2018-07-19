labels=[];
scale=1.0;
image="";
user="";
imgid=0;
//x y of the start of viewed area

setup = function (image_id, usr){
   $('[data-toggle="popover"]').popover();
   user=usr;
   data=$.getJSON("api.php?image="+image_id+'&user='+usr).done(loadimage)

   $("#zoomout").click(function(c){
        scale=1;
        $("#canvas").css({'transform':'scale(1,1) translate(0px,0px)'});
        //console.log("zoomed out");
    });

    $("#logout").click(function(c){
        location.href="index.php";
    });

    $("#save").click(function(c){
        if(labels.length==0){
            if(confirm("No labels for this image?"))
                labels=[{"x1":0,"x2":0,"y1":0,"y2":0,"type":"empty", "username": user, "time": new Date().getTime()}];
            else
                return;
        }
        ldata={
            labels:labels,
            'image_id':imgid,
        };
        $("#loading").show();
        $.ajax({
            type: "POST",
            url: "api.php?save=true&user="+user,
            async: false,
            data: {data:JSON.stringify(ldata)},
            success: loadimage
        });
    });

    $("#bad").click(function(c){
        if(!confirm("Are you sure you wish to mark this image as useless?")){
            return;
        }
        labels=[{"x1":0,"x2":0,"y1":0,"y2":0,"type":"bad", "username": user, "time": new Date().getTime()}];
        ldata={
            labels:labels,
            'image_id':imgid,
        };
        $("#loading").show();
        $.ajax({
            type: "POST",
            url: "api.php?save=true&user="+user,
            async: false,
            data: {data:JSON.stringify(ldata)},
            success: loadimage
        });
    });
}

loadimage = function (data){
    if(typeof data == "string")
        data=JSON.parse(data);
    labels=data.labels;
    image=data.image;
    image_url=image.url;
    imgid=image.image_id;
    if(image_url == null || image_url=="null"){
        $("#loading").hide();
        $("#alldone").show();
        return;
    }
    $("#image_id").html(image.image_id);
    $("#image_type").html(image.type);
    var c = document.getElementById("canvas");
    var ctx = c.getContext("2d");

    image = new Image();
    image.onload = function(e) {
        ctx.canvas.width = image.width;
        ctx.canvas.height = image.height;
        c.width = image.width;
        c.height = image.height;
        ctx.drawImage(image, 0, 0);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 10;
        scale=1;
        console.log(waitingDialog);
        $("#canvas").css({'transform':'scale(1,1) translate(0px,0px)'});
        redrawAll();
        $("#loading").hide();
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
        if(Math.abs(x-lstartx)<10||Math.abs(y-lstarty)<10){
            redrawAll();
            return;
        }
        labels.push({"x1":Math.min(lstartx,x),"x2":Math.max(x,lstartx),"y1":Math.min(lstarty,y),"y2":Math.max(y,lstarty),"type":labtype, "username": user, "time": new Date().getTime()});
    };

    function clearBox(x1,y1,x2,y2){
        var width = x2-x1;
        var height = y2-y1;
        //ctx.clearRect(x1,y1,width,height);
    }

    function zoomTo(x,y,s){
        //we need to shift x,y to make the point to centre the image on this point
        //currently we see width/scale of the image, we subtract half of this to get the centre
        if(scale*s<1)
            return;
        scale*=s;
        x-=image.width/scale/2.0;
        y-=image.height/scale/2.0;
        cw=$("#canvas").width();
        ch=$("#canvas").height()
        xv=Math.max(0,x*cw/image.width);
        yv=Math.max(0,y*ch/image.height);//image.height-c.height);
        console.log(cw+":"+image.width);
        $("#canvas").css({'transform-origin':'0px 0px','transform':'translate(-'+scale*xv+'px,-'+scale*yv+'px) scale('+scale+','+scale+')'});
    }
    lastscroll=0
    $("#canvas").bind('wheel mousewheel',function(e){
        if ( (new Date() - lastscroll) < 60)
            return;
        if (e.originalEvent.wheelDelta !== undefined)
            delta = e.originalEvent.wheelDelta;
        else
            delta = e.originalEvent.deltaY * -1;
        if(delta > 0 ){
            zoomTo(mx,my,1.3);
        }else if(delta<0){
            zoomTo(mx,my,1/1.3);
        }
        lastscroll=new Date();
    });

    function drawBox(x1,y1,x2,y2,colour){
        ctx.beginPath();
        var width = x2-x1;
        var height = y2-y1;
        ctx.strokeRect(x1,y1,width,height);
        ctx.strokeStyle = colour;
        ctx.lineWidth = 10;
        ctx.stroke();
    }

    function redrawAll(){
        ctx.drawImage(image,0,0);
        labels.forEach(function(label){
            drawBox(label.x1,label.y1,label.x2,label.y2,labels.colour?labels.colour:'black');
        });
    }

    mx=0;
    my=0;
    c.onmousemove=function(e){
        mx=(image.width / c.scrollWidth) * e.offsetX;
        my=(image.height / c.scrollHeight) * e.offsetY;
        if(!clicked)
            return;
        redrawAll();
        drawBox(lstartx,lstarty,mx,my);
    };
}
