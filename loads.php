<!doctype html><html><head><?php include'imports.php'?>
</head><body>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>4. Solve Loads: <span class=subtitle>Contaminants</span></div>
<div id=root></div>
<table id=loads style=margin:0.5em></table>
</body>
</html>

<script src=js/loads.js></script>
<script>
	document.body.onload=function(){
		var table=document.querySelector('#loads');

		//add headers
		for(var node in Loads)
		{
			var newRow=table.insertRow(-1)
			newRow.insertCell(-1).innerHTML="<b>Service</b>";
			for(var contaminant in Loads[node])
			{
				newRow.insertCell(-1).innerHTML="<b>"+contaminant+"</b>";
			}
			break;
		}
		//add contaminants
		for(var node in Loads)
		{
			var newRow=table.insertRow(-1)
			newRow.insertCell(-1).innerHTML="<b>"+node+"</b>";
			for(var contaminant in Loads[node])
			{
				newRow.insertCell(-1).innerHTML=format(Loads[node][contaminant]);
			}
		}
	}
</script>
