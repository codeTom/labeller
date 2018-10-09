<?php
require_once("config.php");
$db=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
//TODO check $user or admin logged-in


/**
* Get next image for given user, ordered by image priority
*/
function nextImage($user){
    global $db;
    $user=$db->real_escape_string($user);
    $res=$db->query("SELECT id,url,type,process,priority,group_concat(username),count(username) as compl, sum(case when username = '$user' then 1 else 0 end) as userdone from (SELECT i.image_id as id,process,priority,username,url,i.type as type from images i left join labels l on l.image_id=i.image_id group by l.image_id,l.username) a group by id having compl<process and userdone=0 order by priority, RAND() desc")->fetch_assoc();
    $image=(object) [
        "image_id"=>$res['id'],
        "url"=>$res['url'],
        "type"=>$res['type'],
    ];
    return $image;
}

if(isset($_REQUEST['save'])){
    $data=json_decode($_POST['data']);
    $image_id=$data->image_id;
    $lblinsert=$db->prepare("INSERT INTO labels (image_id, username, type, x1, x2, y1, y2, updated) VALUES (?,?,?,?,?,?,?,FROM_UNIXTIME(?))");
    foreach($data->labels as $label){
        $lblinsert->bind_param('issiiiii',$image_id, $label->username, $label->type, $label->x1, $label->x2,
                                         $label->y1,$label->y2,$t=($label->time)/1000);
        $lblinsert->execute();
    }
    header("Location: api.php?user=".$_REQUEST['user'],true, 301);
    $next=nextImage($_REQUEST['user']);
}elseif(isset($_GET['user'])){
    $user=$db->real_escape_string($_GET['user']);
    if(isset($_GET['image'])&&$_GET['image']){
        $imid=(int) $_GET['image'];
        $image=(object) $db->query("SELECT * from images where image_id='$imid'")->fetch_assoc();
    }else{
        $image=nextImage($_GET['user']);
        $imid=$image->image_id;
    }
    $labels=[];
    foreach($db->query("SELECT * from labels where image_id='$imid' and username='$user'") as $row){
        $labels[]=(object) $row;
    }
    echo json_encode((object) ['labels'=>$labels,'image'=>$image]);
    exit(0);
}
