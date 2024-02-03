<?php
// require_once $_SERVER['DOCUMENT_ROOT'].'/app/configi2.php';
$mysqli = new mysqli("127.0.0.1", "root", "root","nosetime");

class Model{
	public $list;
	public $msg;

	public function __construct(){
		$this->msg='';
	}

	public function upload_emoji($data){
		foreach($data as $v){
			$index = array_search($v, $data);
			$origin_uri = trim(str_replace("'",'',$v['origin_uri']));
			$display_name = trim(str_replace("'",'',$v['display_name']));
			$hide=intval($v['hide']);
			$emoji_url="app/emoji/$index"."_$display_name"."_$origin_uri";
			$values[] = "('$origin_uri', '$display_name', '$hide', '$emoji_url')";
		}
		$this->msg="";
		$sql = "INSERT INTO emoji_list (origin_uri, display_name, hide, emoji_url) VALUES" . implode(",", $values);
		mysqli_query($GLOBALS['mysqli'],$sql);
		if(mysqli_affected_rows($GLOBALS['mysqli'])>0)
			$this->msg='OK';
	}

	public function get_emoji_list(){
		$this-> list = [];
		$sql = "SELECT origin_uri,display_name,emoji_url FROM emoji_list WHERE hide=0 LIMIT 1000";
		$result = mysqli_query($GLOBALS['mysqli'], $sql);
		while($row=mysqli_fetch_assoc($result)) {
			$this-> list[] = $row;
		}
	}
}