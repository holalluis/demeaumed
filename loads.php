<!doctype html><html><head>
	<?php include'imports.php'?>
	<script src=js/loads.js></script>

	<!--perform concentration calculations for each connection-->
	<script src=calcLoads.js></script>

	<script>
		function init() {
			drawLoadTable();
			drawConcTable();
		}

		var method="load"; //default. toggle view "loads" or "conc"
		function toggleConcLoad()
		{
			//modify global variable
			if(method=="load") method="conc"; else method="load";
			init()
		}

		function drawLoadTable() {
			var table=document.querySelector('#loads');
			table.innerHTML="";

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
					newRow.insertCell(-1).outerHTML="<td style=background:orange><b>"+contaminant+"</b></td>";
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
					newRow.insertCell(-1).innerHTML=format(
						Loads[node].contaminants[contaminant]
					);
				}
			}
			//add uses
		}

		function drawConcTable() {
			var table=document.querySelector('#connections');
			table.innerHTML="";

			//get array of contaminants (strings)
			var contaminants=[];
			for(var node in Loads) {
				for(var contaminant in Loads[node].contaminants) {
					contaminants.push(contaminant);
				}
				break;
			}

			//add flow header
			var newRow=table.insertRow(-1);
			newRow.insertCell(-1).outerHTML="<th rowspan=2 colspan=3>Connection</th>";
			newRow.insertCell(-1).outerHTML="<th rowspan=2>Flow (L/day)</th>"

			if(method=="load")
				newRow.insertCell(-1).outerHTML="<th colspan=11>Loads (mg/day)</th>"
			else if(method=="conc")
				newRow.insertCell(-1).outerHTML="<th colspan=11>Loads (mg/L)</th>"

			//add contaminant headers
			var newRow=table.insertRow(-1);
			contaminants.forEach(function(contaminant)
			{
				newRow.insertCell(-1).outerHTML="<td style=background:orange><b>"+contaminant+"</b></td>";
			})

			//add connections
			for(var i in Connections)
			{
				var from = Connections[i].from;
				var to = Connections[i].to;
				var flow = Connections[i].flow;

				//connection
				var newRow=table.insertRow(-1);
				newRow.insertCell(-1).innerHTML=from;
				newRow.insertCell(-1).innerHTML="&rarr;"
				newRow.insertCell(-1).outerHTML="<td style=text-align:left>"+to+"</td>";

				//flow
				newRow.insertCell(-1).innerHTML=format(flow);

				//add contaminants
				for(var j in contaminants){
					var load = format(Connections[i].contaminants[contaminants[j]]);
					var newCell=newRow.insertCell(-1);
					if(method=="load")
					{
						newCell.innerHTML=load;
					}else if(method=="conc")
					{
						var loa = Connections[i].contaminants[contaminants[j]];
						var vol = Connections[i].flow;
						var con = vol==0 ? 0 : format(loa/vol);
						newCell.innerHTML=con;
					}
					else
						alert("error in method")

				}
			}
		}
	</script>

	<style>
		body {background:#ddd}
		#navbar a[page=loads]{background:orange;color:black}
		#loads, #connections {
			margin:0.3em auto;
		}
		#loads {
			font-size:10px
		}
		#connections {
			font-size:11px;
			margin-bottom:2em;
		}
		#connections td {
			text-align:right;
		}
	</style>
</head><body onload=init()>

<!--navbar--><?php include'navbar.php'?>

<!--title-->
<div class=title>4. Solve Loads: <span class=subtitle>Contaminants</span></div>

<!--table for loads (mg/use)-->
<div class="card folded"><?php cardMenu('Loads per service (mg/use)')?>
	<table id=loads></table>
</div>

<!--table of loads per connection-->
<div class="card"><?php cardMenu('Loads per connection (mg/day)')?>
	<div style=text-align:center>
		<button onclick=toggleConcLoad() style="margin:0.5em">Concentration <-> Load</button>
	</div>
	<table id=connections></table>
</div>
