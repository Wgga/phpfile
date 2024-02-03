<?php
header('Access-Control-Allow-Origin:*');
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
	header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Key');
	die;
}

require_once $_SERVER['DOCUMENT_ROOT'].'/app/models/emoji.php';

//app现在是application/x-www-form-urlencoded
//mobile web是application/json
if(strpos($_SERVER['CONTENT_TYPE'], 'application/json')!==FALSE){
	$json = file_get_contents('php://input', 'r');
	$data=json_decode($json,true);
	$_POST=$data;
}else{
	$json=json_encode($_POST);
}

$model = new Model();

if($_POST['method']=='uploademoji'){
	$model->upload_emoji($_POST['emoji_list']);
	echo json_encode($model->list);
}elseif($_GET['method']=='getemojilist'){
	$model->get_emoji_list();
	echo json_encode($model->list);
}
