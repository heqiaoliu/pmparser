<?php
ini_set('display_errors', 'On');
include_once("../security/DBObject.php");
include_once("../security/AuthChecker.php");
include_once("../widget/VdmWidget.php");
//check indentification
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
<script>
$(document).ready(function(){
PmapiClient.getBacteriaList(0,callback);
PmapiClient.getPlateList(0,callbackP);
});
function callback(dataobj){
	var data=dataobj.data;
	for(var x in data){
		var bact=data[x];
		//$('#bacteria-list').append('<li ><a class="span4">'+bact.bacteria_external_id+"</a></li>");
		$('#bacteria-list').append('<option>'+bact.bacteria_external_id+"</option>");
	}
}
function callbackP(dataobj){
	var data=dataobj.data;
	for(var x in data){
		var plate=data[x];
		//$('#bacteria-list').append('<li ><a class="span4">'+bact.bacteria_external_id+"</a></li>");
		$('#plate-list').append('<option>'+plate.plate_name+"</option>");
	}
}
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
<div class="container masterhead">

<form action="file_uploader.php" class="span8" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Uploader</legend>
<label class="span4 ">Choose file from local:</label>
<input type="file" class="" name="file" id="file"><br><br>
<label class="span4 control-label">Bacteria:</label>
<select id="bacteria-list" name='bacteria' class="span4">
</select><br><br>
<label class="span4 control-label">Plate:</label>
<select id="plate-list" name="plate" class="span4">
</select><br><br>
<label class="span4">Note:</label>
<textarea rows="3" class="span4" name="note"></textarea><br><br>
<button type="submit" name="submit" class="btn pull-right">Upload</button>
</fieldset>
</form>


</div>
</html>

