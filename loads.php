<!doctype html><html><head><?php include'imports.php'?>
	<script src=js/loads.js></script>
	<script>
		function init()
		{
			drawLoadTable();
			drawConcTable();
		}
		function drawLoadTable()
		{
			var table=document.querySelector('#loads');
			var newRow=table.insertRow(-1);

			/*headers*/
			//service
			var newCell=document.createElement('th');
			newRow.appendChild(newCell);
			newCell.innerHTML="Service"
			newCell.rowSpan=2;
			//uses
			var newCell=document.createElement('th');
			newRow.appendChild(newCell);
			newCell.innerHTML="Uses/day"
			newCell.rowSpan=2;
			//header general contaminants
			var newCell=document.createElement('th');
			newRow.appendChild(newCell);
			newCell.innerHTML="Loads (mg/use) for each contaminant"
			newCell.colSpan=11;
			//add headers foreach contaminants
			for(var node in Loads)
			{
				var newRow=table.insertRow(-1)
				for(var contaminant in Loads[node].contaminants)
				{
					newRow.insertCell(-1).innerHTML="<b>"+contaminant+"</b>";
				}
				break;
			}

			//add info
			for(var node in Loads)
			{
				var newRow=table.insertRow(-1)
				newRow.insertCell(-1).innerHTML="<b>"+node+"</b>";

				//add uses
				newRow.insertCell(-1).innerHTML=Loads[node].uses

				for(var contaminant in Loads[node].contaminants)
				{
					//mg/use
					newRow.insertCell(-1).innerHTML=Loads[node].contaminants[contaminant];
				}
			}
			//add uses
		}
		function drawConcTable()
		{
			var table=document.querySelector('#concentrations');

			//add headers for contaminants
			for(var node in Loads) {
				var newRow=table.insertRow(-1)
				newRow.insertCell(-1).outerHTML="<th colspan=3>Connection</th>";
				for(var contaminant in Loads[node].contaminants) {
					newRow.insertCell(-1).outerHTML="<th><b>"+contaminant+"</b></th>";
				}
				break;
			}
			//add connections
			for(var i in Connections)
			{
				var from = Connections[i].from;
				var to = Connections[i].to;
				var newRow=table.insertRow(-1);
				newRow.insertCell(-1).innerHTML=from;
				newRow.insertCell(-1).innerHTML="&rarr;"
				newRow.insertCell(-1).innerHTML=to;
			}
		}
	</script>
	<style>
		body {background:#ddd}
	</style>
</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>

<!--title-->
<div class=title>4. Solve Loads: <span class=subtitle>Contaminants</span></div>

<!--table for loads (mg/use)-->
<div class="card"><?php cardMenu('Loads')?>
	<table id=loads></table>
		<style>
			#loads {
				margin:0.2em auto;
				font-size:10px
			}
		</style>
</div>

<!--table of loads per connection-->
<div class="card"><?php cardMenu('Concentration (mg/L) for each contaminant')?>
	<table id=concentrations></table>
		<style>
			#concentrations {
				margin:0.2em auto;
				font-size:10px
			}
		</style>
</div>
