<?php
ini_set("display_errors",1);
include_once("../security/DBObject.php");

class FileUploader{
	private static $FILENAME="file";
	private static $_DANGEROUS_TYPE=array("php","html","htm");
	private static function isThreat($filename){
		$buffer=strtolower(end(explode(".",$filename)));
		return in_array($buffer,self::$_DANGEROUS_TYPE);
	}
	private static function isExist($filename){
		$db=VDMDB::get();
		$stmt=$db->prepare("select count(*) as count from pm_files where file_name=?");
		$stmt->execute(array($filename));
		$buffer=$stmt->fetch(PDO::FETCH_ASSOC);
		return ($buffer['count']>0);
	}

	private static function redirect($success,$message){
		header("location://vdm.sdsu.edu/data/input/pending.php?success=$success&message=$message");
	}


	public static function init(){
		if($_FILES['file']['name']){
			if(self::isThreat($_FILES[self::$FILENAME]['type'])){
				$_POST["message"]="Dangerouse file type";
				self::redirect("False",json_encode($_POST));
			}
			if(!$_FILES[self::$FILENAME]['type']=="text/plain"){
				$_POST["message"]="Invalid file type";
				self::redirect("False",json_encode($_POST));
				exit();
			}
			if(self::isExist($_FILES[self::$FILENAME]['name'])){
				$_POST["message"]="File exists: ".$_FILES[self::$FILENAME]['name'];
				self::redirect("False",json_encode($_POST));
				exit();
			}
			move_uploaded_file($_FILES[self::$FILENAME]['tmp_name'],'/var/www/html/data/upload/'.$_FILES[self::$FILENAME]['name']);
			unset($_POST['']);
			$_POST["filename"]=$_FILES[self::$FILENAME]['name'];
			self::redirect("True",json_encode($_POST));
		}
		else
			echo "No file was found";
	}
	
}


try{
FileUploader::init();
}catch(Exception $e){
var_dump($e);
}
?>
