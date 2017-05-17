<!doctype html><html><head>
	<?php include'imports.php'?>
	<script>
		var arrows = false;
		function init() {
			Views.update()
			createGraph(60,arrows) //inside graph.php
			updateCookies()
		}

		//recalculate the flows for current network
		function recalculateFlows() {
			//reset flows
			Connections.forEach(function(con){con.flow=null});
			Reuse.forEach(function(con){con.flow=null});
			Tanks.forEach(function(tank){tank.value=null});
			updateCookies();
			window.location.reload();
		}
	</script>
	<script>
		/** update visual elements */
		var Views= {
			update:function() {
				//wrapper
				this.updateNodList()
				this.updateConList()
			},
			//update node list
			updateNodList:function() {
				var div = document.querySelector('#nodes')

				if(Nodes.length==0){ div.innerHTML="<i style='color:#ccc'>~No nodes</i>";return}

				div.innerHTML="";
				var table=document.createElement('table');
				div.appendChild(table);
				var newRow=table.insertRow(-1);
				newRow.insertCell(-1).outerHTML="<th>Nodes</th>";
				newRow.insertCell(-1).outerHTML="<th>Output (L/day)";

				Nodes.forEach(function(node){
					var newRow=table.insertRow(-1);
					newRow.onmouseenter=function(){see(node.name)};
					newRow.onmouseout=function(){unsee()};
					newRow.insertCell(-1).innerHTML=node.name;
					newRow.insertCell(-1).innerHTML=node.value==null ? "<b style=color:red>not calc</b>" : format(node.value);
				});
				var newRow=table.insertRow(-1);
				newRow.insertCell(-1).outerHTML="<th>Tanks</th>";
				newRow.insertCell(-1).outerHTML="<th>Output (L/day)";
				Tanks.forEach(function(node){
					var newRow=table.insertRow(-1);
					newRow.onmouseenter=function(){see(node.name)};
					newRow.onmouseout=function(){unsee()};
					newRow.insertCell(-1).innerHTML=node.name;
					newRow.insertCell(-1).innerHTML=node.value==null ? "<b style=color:red>not calc</b>" : format(node.value);
				});
			},
			//update connections list
			updateConList:function() {
				var div = document.querySelector('#connections')
				div.innerHTML="";
				if(Connections.length==0) {
					div.innerHTML="<i style='color:#ccc'>~No connections</i>"
				}
				else{
					var table=document.createElement('table');
					div.appendChild(table);
					//header
					var newRow=table.insertRow(-1);
					newRow.insertCell(-1).outerHTML="<th colspan=3>Connections</th>";
					newRow.insertCell(-1).outerHTML="<th>Flow (L/day)";

					//body
					for(var i in Connections) {
						var from = Connections[i].from;
						var to = Connections[i].to;
						var flow = Connections[i].flow;
						var newRow=table.insertRow(-1);

						var flow_f = flow==null ? "<b style=color:red>not calc</b>" : format(flow);
						newRow.setAttribute('from',from);
						newRow.setAttribute('to',to);
						newRow.insertCell(-1).outerHTML="<td style=text-align:right>"+from+"</th>";
						newRow.insertCell(-1).innerHTML="&rarr;";
						newRow.insertCell(-1).innerHTML=to;
						newRow.insertCell(-1).outerHTML="<td title='"+flow+"'>"+flow_f+"</td>";
					}
				}
			},
		};

		function see(node) {
			var froms=document.querySelectorAll('div#connections tr[from="'+node+'"]');
			var tos=document.querySelectorAll('div#connections tr[to="'+node+'"]');
			for(var i=0;i<froms.length;i++)
			{
				froms[i].style.background="#bca";
			}
			for(var i=0;i<tos.length;i++)
			{
				tos[i].style.background="orange";
			}
		}
		function unsee() {
			var list=document.querySelectorAll('div#connections tr');
			for(var i=0;i<list.length;i++)
			{
				list[i].style.background="";
			}
		}
	</script>
	<style>
		div#navbar a[page=solveNetwork]{background:orange;color:black}
		#nodes button {font-size:10px}
		#botonera {
			justify-content:center;
		}
		#botonera button {
			display:block;
			padding:1em 4em;
			margin:2px;
		}
	</style>
</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>3. Solve network: <span class=subtitle>Find flows</span></div>

<!--network solving-->
<script src="solveConnections2.js"></script><!--solve connection flows ('flow' property)-->
<script src="solveNodes2.js"></script><!--solve nodes outputs ('value' property)-->
<script src="solve.js"></script>
<!--network solving-->

<div id=root class=flex style="justify-content:center">
	<!--left column-->
	<div>
		<div id=botonera class=flex>
			<button onclick="recalculateFlows()">Reset Connection Flows and Tanks</button>
			<button onclick="solveNetwork();init()">Solve Network</button>
		</div>

		<!--nodes i connexions-->
		<div class=flex>
			<div id=nodes       style="padding:0.5em;font-size:10px;"></div>
			<div id=connections style="padding:0.5em;font-size:10px;"></div>
		</div>
	</div>

	<!--right column graph-->
	<div style="border:1px solid #ccc;border-top:none">
		<?php include'graph.php'?>
	</div>
</div>
