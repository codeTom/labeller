<?php
error_reporting(E_ALL); ini_set('display_errors', '1');
//this may be better in pure js, but this was faster
$labels=["parasite"=>"Parasite", "abcell"=>"Abnormal WBC","zoom"=>"Zoom +"];
$img_asprat=1803.0/1197.0;

$pad=1/$img_asprat*100;

$user=""; //TODO login and checks
$image="";
if(isset($_GET['image_id']))
    $image=$_GET["image_id"];
if(isset($_GET['user']))
    $user=$_GET['user'];

?>

<!doctype html>
<html style="height:100%;">
<head>
    <title>Labeller</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="canvas.js"></script>
    <script>
        <?php if(!$user){?>
            window.onload=function(){
                $("#login").show();
            }
        <?php }else{ ?>
            window.onload=function(){ setup(<?php echo "'$image','$user'"; ?>); };
        <?php } ?>
    </script>
</head>
<body style="height:100%; overflow:hidden;">

<ul class="nav">
   <li><a href="#" id="idLogin" rel="popover" style="display:none"></a></li>
</ul>

<nav id="sidebar" style="
            max-width: 17%;
            height: 100%;
            float: left;
            z-index: 8000;
            margin-bottom: 0px;">
    <div class="panel panel-default" style="height: 100%;">
        <div class="panel-heading">
            <h3 class="panel-title"></h3>
        </div>
        <div class="panel-body">
            <div class="btn-group btn-group-toggle btn-group-vertical" style="width: 100%;" data-toggle="buttons">
               <?php $c=false; foreach($labels as $id=>$label){?>

                    <label class="btn btn-secondary <?php if(!$c) echo "active"; ?>"><input type="radio" name="lbl" value="<?php echo $id; if(!$c) echo '" checked="'; $c=true; ?>"><?php echo $label; ?></label>
                <?php } ?>
                    <label class="btn btn-secondary"><button class="btn" id="zoomout">Reset Zoom</label>
            </div>
        </div>
        <button class="btn btn-success" style="width: 100%;" id="save">Save & next</button></br />
        <button class="btn btn-danger" style="width: 100%;" id="bad">Useless image</button></br />
        <button class="btn btn-primary" style="width: 100%;" id="logout">Logout</button></br />
        <button class="btn btn-info" style="width: 100%;" id="help" data-html="true" data-toggle="popover" data-trigger="hover" title="Instructions" data-content="Select label type above, click and drag to draw on the image. Click inside a label to remove it. When all features have been labelled click Save&next. It may take several seconds for the next image to load. <br />
            Zoom by selecting Zoom + and clicking on the image or using the mouse wheel.<br/>
            If the image is not good enough, click Useless Image. <br />">Help</button></br />
        <div class="panel-body">
            <table class="table table-striped">
            <tr> <th scope="row">User:</th> <td><strong id="user"><?php echo $user; ?></strong></td> </tr>
            <!--<tr> <th scope="row">ID:</th> <td><span id="image_id"></span></td></tr>
            <tr> <th scope="row">Type:</th> <td><span id="image_type"></span></td></tr>-->
            </table>
        </div>
    </div>
</nav>
<div id="content" class="container" style="overflow: hidden; width: 80%; padding-bottom: <?php echo $pad; ?>%" >
        <canvas id="canvas" style="width:90%; height:90%; margin: 0; padding: 0;"></canvas>
</div>
</div>
<div id="login" style="
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding:5%;
    display:none;" class="card-body bg-light rounded">
<form method='GET' action='index.php' class="well form-inline">
    <input type="text" class="form-control" id="username" name="user" class="input-medium" placeholder="username">
    &nbsp;
    <button type="submit" class="btn btn-success">Log in</button>
</form>
</div>

<div style="
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding:5%;
    display:none; text-align: center;" id="alldone" class="alert alert-success">
<strong>Thank you!</strong><br />Please check back later as we might add more images.
</div>
</body>
</html>
