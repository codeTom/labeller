<?php
/**
* Import images from $indir into $outdir and add them to the database,
* renaming images to random identifiers. Run in the command line to get readable log.
*/

$indir="./data/";
$outdir="images/";
//how many people should label these images
$process=1000; #everyone labels every image
$priority=50;
$type='boyko1';

require_once("config.php");
$db=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

$ifiles=glob($indir."*.jpg");
foreach($ifiles as $f){
    $fname=basename($f);
    $newname=$outdir.uniqid().uniqid().uniqid().".jpg";
    rename($f,$newname);
    if($db->query("INSERT INTO images (url,filename,type,process,added,priority) VALUES ('$newname','$fname','$type','$process',now(),'$priority')"))
        echo "Added image: $newname\n";
    else
        echo "ERROR adding $newname\n";
}
