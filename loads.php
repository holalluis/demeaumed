<!doctype html><html><head><?php include'imports.php'?>
	<style>
		body {background:#ddd}
		#navbar a[page=loads]{background:orange;color:black}

		#root table {
			font-size:11px;
			margin:2px;
		}

		#root table td {
			text-align:right;
		}

		td.valor:hover {
			cursor:context-menu;
			background:#abc;
		}

		tr.reuse {
			background:#800080;
			color:white;
		}
	</style>

	<!--perform concentration calculations for each connection-->
	<script src="js/loads.js"></script>
	<script src="calcLoads.js"></script>

	<script>
		function init() {
			updateViews();
		}

		/*<views>*/
		function updateViews() {
			drawLoadTable();
			drawTechsTable();
			drawConcTable();
		}
		function drawLoadTable() {
			var table=document.querySelector('#loads');
			table.innerHTML="";

			var newRow=table.insertRow(-1);

			/*headers*/
			//service
			var newCell=document.createElement('th');
			newRow.appendChild(newCell);
			newCell.innerHTML="Hotel service"
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
			for(var node in Loads) {
				var newRow=table.insertRow(-1)
				for(var contaminant in Loads[node].contaminants) {
					newRow.insertCell(-1).outerHTML="<td style=background:#bca><b>"+contaminant+"</b></td>";
				}
				break;
			}

			//add info
			for(var node in Loads) {
				var newRow=table.insertRow(-1)
				newRow.insertCell(-1).outerHTML="<th style=text-align:left>"+node+"</th>";
				//add uses
				newRow.insertCell(-1).innerHTML=Loads[node].uses
				for(var contaminant in Loads[node].contaminants) {
					//mg/use
					newRow.insertCell(-1).innerHTML=format(
						Loads[node].contaminants[contaminant]
					);
				}
			}
		}
		function drawTechsTable() {
			var table=document.querySelector('#technologies');
			table.innerHTML="";

			var newRow=table.insertRow(-1);

			/*headers*/
			var newCell=document.createElement('th');
			newRow.appendChild(newCell);
			newCell.innerHTML="Treatment technologies"
			newCell.rowSpan=2;
			//header general contaminants
			var newCell=document.createElement('th');
			newRow.appendChild(newCell);
			newCell.innerHTML="Removal efficiency (%)";
			newCell.colSpan=11;

			//add headers foreach contaminants
			for(var node in Loads) {
				var newRow=table.insertRow(-1);
				for(var contaminant in Loads[node].contaminants) {
					newRow.insertCell(-1).outerHTML="<td style=background:#bca><b>"+contaminant+"</b></td>";
				}
				break;
			}

			//add info
			Technologies.forEach(function(tec) {
				var newRow=table.insertRow(-1);
				newRow.insertCell(-1).outerHTML="<th style=text-align:left>"+tec.name;
				for(var contaminant in tec.removal) {
					newRow.insertCell(-1).innerHTML=tec.removal[contaminant];
				}
			});
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
			newRow.insertCell(-1).outerHTML="<th rowspan=2 colspan=3>Network connections</th>";
			newRow.insertCell(-1).outerHTML="<th rowspan=2>Water flow (L/day)</th>"

			if(method=="load"){
				newRow.insertCell(-1).outerHTML="<th colspan=11>Loads (mg/day)</th>";}
			else if(method=="conc"){
				newRow.insertCell(-1).outerHTML="<th colspan=11>Loads (mg/L)</th>";}

			//add contaminant headers
			var newRow=table.insertRow(-1);
			contaminants.forEach(function(contaminant) {
				newRow.insertCell(-1).outerHTML="<td style=background:#bca><b>"+contaminant+"</b></td>";
			})

			//add connections
			Connections.concat(Reuse).forEach(function(con){

				//add row
				var newRow=table.insertRow(-1);

				//is a reuse connection? (have tec string defined)
				if(con.tec) {
					newRow.classList.add('reuse');
					newRow.title="Water reuse - Treatment technology: '"+con.tec+"'";
					newRow.style.cursor="help";
				}

				newRow.insertCell(-1).outerHTML="<th style='text-align:left;'>"+con.from+"</th>";
				if(con.tec) {
					newRow.insertCell(-1).innerHTML="<center>&rarr; "+con.tec+" &rarr;</center>"
				}
				else {
					newRow.insertCell(-1).innerHTML="<center>&rarr;</center>"
				}
				newRow.insertCell(-1).outerHTML="<th style='text-align:left;'>"+con.to+"</th>";

				//add flow
				newRow.insertCell(-1).innerHTML=format(con.flow);

				//add contaminants
				contaminants.forEach(function(cont) {
					var newCell=newRow.insertCell(-1);
					newCell.classList.add("valor");
					if(method=="load") {
						var load = con.contaminants[cont];
						var load_f = format(load);
						newCell.title=load;
						newCell.innerHTML=load_f;
					}else if(method=="conc") {
						var loa = con.contaminants[cont];
						var vol = con.flow;
						var conc = vol==0 ? 0 : format(loa/vol);
						newCell.title= vol==0 ? 0 : loa/vol;
						newCell.innerHTML=conc;
					}
					else alert("error in method")
				});

			});
		}
		/*</views>*/

		/*toggle load (mg/day) <-> concentration (mg/L) */
		var method='load'; //default. toggle view "loads" or "conc"
		function toggleConcLoad() {
			//modify global variable
			method = (method=='load') ? 'conc' : 'load';
			init();
		}
	</script>
</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>6. Solve Loads: <span class=subtitle>Contaminants without water reuse</span></div>

<!--root-->
<div id=root>

	<!--table for loads (mg/use)-->
	<div id=loads_cnt class=card>
		<?php cardMenu('Inputs: Loads per service (mg/use)')?>
		<div class=flex style=justify-content:center>
			<table id=loads></table>
			<table id=technologies></table>
		</div>
	</div>

	<!--table of loads per connection-->
	<div id=connections_cnt class=card>
		<?php cardMenu('Outputs: Loads per connection (mg/day)')?>
		<div style=text-align:center>
			<button onclick=toggleConcLoad() style="margin:0.5em">Concentration &harr; Load</button>
		</div>
		<div class=flex style=justify-content:center>
			<table id=connections></table>
		</div>
	</div>

</div root>

<div style=margin-bottom:5em></div>
