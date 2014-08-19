<?php
ini_set('display_errors', 'On');
include_once("../security/DBObject.php");
include_once("../security/AuthChecker.php");
include_once("../widget/VdmWidget.php");
AuthChecker::init();
AuthChecker::require_authentication();
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="//vdm.sdsu.edu/data/css/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="//vdm.sdsu.edu/data/css/bootstrap_addition.css" />
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//vdm.sdsu.edu/data/api/PmapiClient.js"></script>
<script src="//vdm.sdsu.edu/data/css/bootstrap/js/bootstrap.min.js" ></script>
<script src="//vdm.sdsu.edu/data/api/PmapiClient.js" ></script>
<script>


$(document).ready(function(){

	var params=JSON.parse($("#get-variable").html());
	params.message=JSON.parse(params.message);
	params.message.username=params.username;

	function afterParsing(data){
		if(!data.success)
			alert("Uploading Failed: Failed to parse the file "+params.message.filename);
		else{
			console.log(data);
		}
	}

	dataHandler.init(params.message.filename,params.message.bacteria,params.message.plate,params.username,params.message.note);
});

var dataHandler=(function($){
	var bacteriaid,plateid,beid,plateName,filename,name,note,replicateCount;

	function getbacteriaid(bact){
		beid=bact;
		PmapiClient.getBacteria(0,[beid],setBacteriaid);
	}	

	function setBacteriaid(data){
		bacteriaid=data.data[beid].bacteria_id;
		console.log(bacteriaid);
	}

	function getPlateid(plate){
		plateName=plate;
		PmapiClient.getPlateList(0,setPlateid);
		
	}

	function setPlateid(data){
		for(var x in data.data){
			var elem = data.data[x];
			if(elem.plate_name==plateName){
				plateid=elem.plate_id;	
				break;
			}
				
		}
		console.log(plateid);
	}

	function getReplicate(){
		setTimeout(function(){
			if(bacteriaid==undefined||plateName==undefined){
				getreplicate();
			}
			else{
				PmapiClient.getExperiment(0,[bacteriaid],setReplicate);
			}
		},500);
	}	

	function setReplicate(data){
		var temp=0;
		for( var x in data.data[bacteriaid].data){
			var elem =data.data[bacteriaid].data[x];
			if(elem.plate_name==plateName&&elem.replicate_num>temp)
				temp=elem.replicate_num;
		}
		replicateCount=temp;
		console.log(replicateCount);
	}

	function sendParsingRequest(){
		$.ajax({
			type: "POST",
			url: "parse.php",
			data: "filename="+filename+"&bacteriaid="+bacteriaid+"&plateid="+plateid+"&name="+username+"&note="+note,
			success:test,
			dataType:"JSON"
		});
	}


	function test(data){
		if(data.success==true)
			alert("Uploading success.");
		console.log(data);
	}

	function waitResponse(){
		setTimeout(function(){
			if(plateid==undefined&&bacteriaid==undefined){
				waitResponse();
			}
			else{
				sendParsingRequest();
			}
			
		},500);
	}

	return {
		init:function(file,bact,plate,name,notemsg){
			filename=file;
			username=name;
			note=notemsg;
			getbacteriaid(bact);
			getPlateid(plate);
//			getReplicate();
			waitResponse();
		},
		checkFile: function(fileName){
//			PmapiClient.get
		}
	}
	
})(jQuery);
</script>

</head>
<?php
/**
* Get Nav-menu;
*/
$dbw=VDMDB::getWeb();
VdmWidget::init($dbw);
echo VdmWidget::getBsMenu();
?>
<div class="contianer masterhead">
<p id="get-variable" style="visibility:hidden"><?php $_GET['username']=$_SESSION['name'];echo json_encode($_GET);?></p>
</div>
</html>
