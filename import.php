<?php
/*
* This should be run on the command line, it will rename images in the given folder so that the names are safe
*/

$indir="./data/";
$outdir="images/";
$process=3;
$priority=50;
$type='thin1';

require_once("config.php");
$db=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

$ifiles=glob($indir."*.jpg");
foreach($ifiles as $f){
    $newname=$outdir.uniqid().uniqid().uniqid().".jpg";
    rename($f,$newname);
    echo "INSERT INTO images (url,type,process,added,priority) ('$newname','$type','$process',now(),'$priority')";
    if($db->query("INSERT INTO images (url,type,process,added,priority) VALUES ('$newname','$type','$process',now(),'$priority')"))
        echo "Added image: $newname";
    else
        echo "ERROR adding $newname";
}
