<!doctype html><html><head>
	<?php include'imports.php'?>

	<!--perform concentration calculations for each connection-->
	<script src="js/loads.js"></script>
	<script src="calcLoads.js"></script>

	<script>
		var arrows = false;
		function init() {
			drawLoadTable();
			drawConcTable();
			createGraph(60,arrows);
		}

		var method="load"; //default. toggle view "loads" or "conc"
		function toggleConcLoad() {
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
			newRow.insertCell(-1).outerHTML="<th rowspan=2 colspan=3>Network connections</th>";
			newRow.insertCell(-1).outerHTML="<th rowspan=2>Water flow (L/day)</th>"

			if(method=="load"){
				newRow.insertCell(-1).outerHTML="<th colspan=11>Loads (mg/day)</th>";}
			else if(method=="conc"){
				newRow.insertCell(-1).outerHTML="<th colspan=11>Loads (mg/L)</th>";}

			//add contaminant headers
			var newRow=table.insertRow(-1);
			contaminants.forEach(function(contaminant) {
				newRow.insertCell(-1).outerHTML="<td style=background:orange><b>"+contaminant+"</b></td>";
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

				newRow.insertCell(-1).innerHTML=con.from;
				newRow.insertCell(-1).innerHTML="&rarr;"
				newRow.insertCell(-1).outerHTML="<td style='text-align:left;'>"+con.to+"</td>";

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
						newCell.innerHTML=load_f=="0"?"<span style=color:#ccc>0</span>":load_f;
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
	</script>

	<style>
		#root table {
				font-family:monospace;
			}
		td.valor:hover {
			cursor:context-menu;
			background:#abc;
		}
		body {background:#ddd}
		#navbar a[page=loads]{background:orange;color:black}

		#loads_cnt, #connections_cnt {
			margin:auto;
		}

		#loads, #connections {
			margin:0 auto;
		}
		#loads {
			font-size:10px
		}

		#connections {
			font-size:11px;
			margin-bottom:2em;
		}
		#root table td {
			text-align:right;
		}

		#loads th:first-child,
		#loads td:first-child,
		#connections th:first-child,
		#connections td:first-child{
			border-left:none;
		}
		#loads th:last-child,
		#loads td:last-child,
		#connections th:last-child,
		#connections td:last-child{
			border-right:none;
		}

		tr.reuse {
			background:#800080;
			color:white;
		}
	</style>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>6. Solve Loads: <span class=subtitle>calculate the contaminants at each water connection</span></div>

<!--root-->
<div id=root>

	<!--table of loads per connection-->
	<div id=connections_cnt class=card>
		<?php cardMenu('Outputs: Loads per connection (mg/day) (calculated)')?>
		<div style=text-align:center>
			<button onclick=toggleConcLoad() style="margin:0.5em">Concentration &harr; Load</button>
		</div>
		<table id=connections></table>
	</div>

	<!--table for loads (mg/use)-->
	<div id=loads_cnt class='card folded'>
		<?php cardMenu('Inputs: Loads per service (mg/use) (experimental data)')?>
		<table id=loads></table>
	</div>

	<!--graph-->
	<div class='card'>
		<?php cardMenu('Graph')?>

		<div id=selectContaminant>
			Select contaminant &emsp;
			<button name=SST onclick=contaminantDiagram(100,arrows,this.name)>SST</button>
			<button name=PO4 onclick=contaminantDiagram(100,arrows,this.name)>PO4</button>
			<button name=SO4 onclick=contaminantDiagram(100,arrows,this.name)>SO4</button>
			<button name=TOC onclick=contaminantDiagram(100,arrows,this.name)>TOC</button>
			<button name=COD onclick=contaminantDiagram(100,arrows,this.name)>COD</button>
			<button name=BOD onclick=contaminantDiagram(100,arrows,this.name)>BOD</button>
			<button name=TN  onclick=contaminantDiagram(100,arrows,this.name)>TN</button>
			<button name=Caffeine      onclick=contaminantDiagram(100,arrows,this.name)>Caffeine</button>
			<button name=Carbamazepine onclick=contaminantDiagram(100,arrows,this.name)>Carbamazepine</button>
			<button name=Diclophenac   onclick=contaminantDiagram(100,arrows,this.name)>Diclophenac</button>
		</div>

		<style>
			#selectContaminant {
				display:flex;
				flex-wrap:wrap;
				justify-content:center;
				padding:0.5em;
			}
			#selectContaminant button.selected {
				background:orange;
			}
			#selectContaminant button{
				border-radius:none;
				outline:none;
				margin:0;
				margin-left:-1px;
				display:block;
				background:#fafafa;
				border:1px solid #ccc;
			}
		</style>

		<div style='text-align:center;border:1px solid #ccc;border-top:none'>
			<?php include'graph.php'?>
		</div>
	</div>

</div root>

<script>
	function contaminantDiagram(gravetat,arrows,contaminant) {
		gravetat = Math.abs(gravetat)+0.01 || 60;
		arrows = arrows || false;

		contaminant = contaminant || document.querySelector('svg').getAttribute('lastContaminant');

		document.querySelector('svg').setAttribute('lastContaminant',contaminant);

		(function(){
			var btns=document.querySelectorAll('#selectContaminant button[name]');
			for(var i=0;i<btns.length;i++)
				btns[i].classList.remove('selected');
			document.querySelector('#selectContaminant button[name='+contaminant+']').classList.add('selected');
		})();
		
		zoomFunction=contaminantDiagram;

		//empty element
		document.querySelector('svg').innerHTML="";
		//load data
			var json = { 
				nodes: [
					// {"name": "Napoleon", "group": 1},
				],
				links: [
					// {"source": "Napoleon", "target": "Myriel", "value": 1},
				],
			};
			function existsTank(tank) { for(var i in Tanks) { if(Tanks[i].name==tank) return true } return false }
			Tanks.forEach(function(tank){
				json.nodes.push( {name:tank.name, group:1} )
			});
			//add only if not exists in tanks
			Nodes.forEach(function(node){
				if(!existsTank(node.name))	
				{
					json.nodes.push( {name:node.name, group:0} ) 
				}
			});
			//add links

			//find max mg of contaminant
			var max_cont = (function(){
				var conts=['SST', 'PO4', 'SO4', 'TOC', 'COD', 'BOD', 'TN', 'Caffeine', 'Carbamazepine', 'Diclophenac'];
				var ret=-1;
				Connections
					.map(function(con){return con.contaminants})
					.forEach(obj=>{
						conts.forEach(cont=>{
							if(obj[cont]>ret) ret=obj[cont];
						})
					});
				return ret;
			})();

			var divisor=max_cont/1000||1;

			for(var i in Connections){
				var flow=Connections[i].contaminants[contaminant];
				var value = flow/divisor;
				if(flow==null){value=-1}
				json.links.push( { source:Connections[i].from, target:Connections[i].to, value:value } )
			}
			//add reuse connections
			Reuse.forEach(function(con){
				var flow=con.contaminants[contaminant];
				var value=flow/divisor||1;
				if(flow==null){value=-1}
				json.links.push( { source:con.from, target:con.to, value:value, reuse:1} )
			})
		//load data end
		
		//draw
		dibuixa(json,gravetat,arrows);
	}
</script>
