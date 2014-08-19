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
<script src="//vdm.sdsu.edu/data/input/input-script.js" ></script>
<script>
$(document).ready(function(){
PmapiClient.getBacteriaList(0,InputModule.callbackB);
PmapiClient.getPlateList(0,InputModule.callbackP);
$("#input-beid").keyup(InputModule.getMatch);
});

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
<legend>Data Uploader</legend>
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
<form class="span8" enctype="multipart/form-data">
	<fieldset>
		<legend>New Bacteria Record</legend>
		<label class="span4">Bacteria External ID*:</label>
		<input id="input-beid" name="beid"><br><br>	
		<label class="span4">Genotype*:</label>
		<input name="genotype"><br><br>
	<fieldset>
	<!-- #bact-extend -->
	<a id="bact-extend" class="pull-right">+Show more field</a><br>
	<p class="pull-right" style="color:red">( Field with * must be filled. )</p>
</form>

</div>
</html>

