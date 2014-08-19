var InputModule=(function($){
	var bacteriaList,plateList,error_flag, bacteria_duplicate_flag=true;

	function displayRows($container,records,key){
		for(var x in records){
			var row=records[x];
			$container.append("<option>"+row[key]+"</option>");
		}
	}

	function callbackB(dataobj){
		bacteria_duplicate_flag=false;
		bacteriaList=dataobj.data;
		displayRows($("#bacteria-list"),bacteriaList,"bacteria_external_id");
	}

	function callbackP(dataobj){
		plateList=dataobj.data;
		displayRows($("#plate-list"),plateList,"plate_name");
	}
	
	function searchDuplicateBacteria(beid){
		var temp=[];
		for(var x in bacteriaList){
			var y=bacteriaList[x];
			if(y.bacteria_external_id.toLowerCase()==beid)
				temp.push(y.bacteria_external_id);
		}
		return temp;
	}

	//jquery callback	
	function getMatch(){
		var beid=$(this).val();
		var result=searchDuplicateBacteria(beid);
		console.log(result);
		if (result.length!=0)
			bacteria_duplicate_flag=true;
	}

	function appendAllField(){
			
	}

	function hideAllField(){
	
	}

	return {
		callbackB:callbackB,
		callbackP:callbackP,
		//jQuery callback
		getMatch:getMatch
	}
}(jQuery));
