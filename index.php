<?php
$labels=["parasite"=>"parasite", "abcell"=>"Abnormal WBC","zoom"=>"Zoom +"];
?>

<!doctype html>
<html style="height:100%;">
<head>
    <title>Tagger</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="canvas.js"></script>
    <script> window.onload=function(){ setup("test.jpg"); }; </script>
</head>
<body style="height:100%;">
<nav id="sidebar" style="
            width: 10%;
            height: 100%;
            float: left;
            z-index: 8000;
            margin-bottom: 0px;">
    <div class="panel panel-default" style="height: 100%;">
        <div class="panel-heading">
            <h3 class="panel-title">Labels</h3>
        </div>
        <div class="panel-body">
            <div class="btn-group btn-group-toggle btn-group-vertical" data-toggle="buttons">
               <?php foreach($labels as $id=>$label){?>
                    <label class="btn btn-secondary"><input type="radio" name="lbl" value="<?php echo $id; ?>"><?php echo $label; ?></label>
                <?php } ?>
                    <label class="btn btn-secondary"><button class="btn" id="zoomout">Zoom -</label>
            </div>
        </div>
    </div>
</nav>
<div id="content" class="container">
<div class="row">
TODO: navigation
    <div style="overflow: scroll">
        <canvas id="canvas" style="width:100%; height:80%; margin: 0; padding: 0;"></canvas>
</div>
</div>
</body>
</html>
