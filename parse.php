<?php
ini_set('display_errors', 'On');
date_default_timezone_set('America/Los_Angeles');
include_once("../pmparser/Parser.php");
include_once("../security/DBObject.php");
/**
* 
*/
class Parsing{
	
	public static function parse($filename){
		$data=array();
		if(!$file=fopen("../upload/".$filename,"r")){
			echo "{'success':false,'message':'Cannot locat the file $filename.'}";
			exit();
		}
		else{
			try{
				$data=Parser::parseFile($file);	
			}catch(Exception $e){
				fclose($file);
				echo '{"success":false,"message":"Wrong Format."}';
				exit();
			}
			fclose($file);
		}
		return $data;
		
	}

	public static function insertFile($db,$filename,$name,$exp_date,$bacteria_id,$notes){
		$stmt = $db->prepare("insert into pm_files (file_name, name, exp_date, bacteria_id, notes) VALUES (?,?, STR_TO_DATE(?, '%m/%d/%Y'), ?, ?)");
		$stmt->execute(array($filename,$name,$exp_date,$bacteria_id,$notes));
		$getid=$db->prepare("select file_id from pm_files where file_name=?");		
		$getid->execute(array($filename));
		$fid=$getid->fetch(PDO::FETCH_ASSOC);
		return $fid['file_id'];
		
	}

	public static function insertExp($db,$bacteriaid,$plateid,$fileid){
		$stmt=$db->prepare("insert into pm_exp(bacteria_id,plate_id,replicate_num,file_id) values(?,?,(select count(pe.replicate_num) from pm_exp pe where pe.bacteria_id=? and pe.plate_id=?)+1,?)");
		$stmt->execute(array($bacteriaid,$plateid,$bacteriaid,$plateid,$fileid));
		$getid=$db->prepare("select exp_id from pm_exp where file_id=?");
		$getid->execute(array($fileid));
		$eid=$getid->fetch(PDO::FETCH_ASSOC);
		return $eid["exp_id"];	
	}

	public static function insertGrowth($db,$expid,$dataset){
		$stmt=$db->prepare("insert into pm_growth(well_id,time,growth_measurement,exp_id) values(?,?,?,?)");
		foreach($dataset as $time=>$points){
			foreach($points as $well=>$measure){
				$stmt->execute(array($well,$time,$measure,$expid));
			}
		}

	}
	public static function checkDuplicate($filename){
		
	}	
}

class SingleFileUploading extends Parsing{
	public static function init(){
		$db=VDMDB::get();	
		if(!isset($_POST['filename']))
			exit();
		$data=self::parse($_POST['filename']);
		$data['success']=true;
		$fileid=self::insertFile($db,$_POST['filename'],$_POST['name'],$data['expdate'],$_POST['bacteriaid'],$_POST['note']);
		$expid=self::insertExp($db,$_POST['bacteriaid'],$_POST['plateid'],$fileid);
		$data['exp_id']=$expid;
		$data['file_id']=$fileid;
		$data['test']=$_POST;
		self::insertGrowth($db,$expid,$data['growth']);
		echo json_encode($data);

	}
}


SingleFileUploading::init();
?>
