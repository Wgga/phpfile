<?php
// require_once $_SERVER['DOCUMENT_ROOT'].'/app/configi2.php';
$mysqli = new mysqli("127.0.0.1", "root", "root","nosetime");
function getFirstCharter($str){
	if(empty($str))
	{
		return '';
	}
	$fchar=ord($str[0]);

	if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str[0]);
	$s1=iconv('UTF-8','gb2312',$str);
	$s2=iconv('gb2312','UTF-8',$s1);

	$s=$s2==$str?$s1:$str;
	$asc=ord($s[0])*256+ord($s[1])-65536;
	if($asc>=-20319&&$asc<=-20284) return 'A';
	if($asc>=-20283&&$asc<=-19776) return 'B';
	if($asc>=-19775&&$asc<=-19219) return 'C';
	if($asc>=-19218&&$asc<=-18711) return 'D';
	if($asc>=-18710&&$asc<=-18527) return 'E';
	if($asc>=-18526&&$asc<=-18240) return 'F';
	if($asc>=-18239&&$asc<=-17923) return 'G';
	if($asc>=-17922&&$asc<=-17418) return 'H';
	if($asc>=-17417&&$asc<=-16475) return 'J';
	if($asc>=-16474&&$asc<=-16213) return 'K';
	if($asc>=-16212&&$asc<=-15641) return 'L';
	if($asc>=-15640&&$asc<=-15166) return 'M';
	if($asc>=-15165&&$asc<=-14923) return 'N';
	if($asc>=-14922&&$asc<=-14915) return 'O';
	if($asc>=-14914&&$asc<=-14631) return 'P';
	if($asc>=-14630&&$asc<=-14150) return 'Q';
	if($asc>=-14149&&$asc<=-14091) return 'R';
	if($asc>=-14090&&$asc<=-13319) return 'S';
	if($asc>=-13318&&$asc<=-12839) return 'T';
	if($asc>=-12838&&$asc<=-12557) return 'W';
	if($asc>=-12556&&$asc<=-11848) return 'X';
	if($asc>=-11847&&$asc<=-11056) return 'Y';
	if($asc>=-11055&&$asc<=-10247) return 'Z';
	return false;
}
class Model{
	public $list;
	public $msg;

	public function __construct(){
		$this->msg='';
	}

	public function get_prov_list(){
		$this->list = [];
		$sql = "SELECT city_name,city_code,parent_code,pinyin,first_letter,level FROM city_list WHERE level=0 ORDER BY first_letter ASC";
		$result = mysqli_query($GLOBALS['mysqli'], $sql);
		while($row=mysqli_fetch_assoc($result)) {
			extract($row);
			$this->list[$first_letter][] = $row;
		}
	}

	public function get_city_list(){
		$this->list = [];
		$code = intval($_POST['code']);
		$level = intval($_POST['level']);
		$sql = "SELECT city_name,city_code,parent_code,pinyin,first_letter,level FROM city_list WHERE level=$level AND parent_code=$code ORDER BY first_letter ASC";
		$result = mysqli_query($GLOBALS['mysqli'], $sql);
		while($row=mysqli_fetch_assoc($result)) {
			extract($row);
			$first_char = getFirstCharter($city_name);
			$sql2 = "UPDATE city_list SET first_letter='$first_char' WHERE city_code=$city_code AND first_letter=''";
			mysqli_query($GLOBALS['mysqli'], $sql2);
			$this->list[$first_letter][] = $row;
		}
	}
}