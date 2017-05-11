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
			Connections.forEach(function(con){con.flow=null})
			Tanks.forEach(function(tank){tank.value=null})
			updateCookies()
			window.location.reload()
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
				newRow.insertCell(-1).innerHTML="<b>Node</b>";
				newRow.insertCell(-1).innerHTML="<b>Output (L/day)</b>";
				newRow.insertCell(-1).innerHTML="<b>See</b>";
				var n=1;

				Nodes.concat(Tanks).forEach(function(node){
					var newRow=table.insertRow(-1);
					newRow.insertCell(-1).innerHTML=node.name;
					newRow.insertCell(-1).innerHTML=node.value==null ? "<b style=color:red>not calc</b>" : format(node.value);
					newRow.insertCell(-1).innerHTML="<button onmouseenter=\"see('"+node.name+"')\" onmouseout=unsee()>see</button>";;
					n++;
				});
			},
			//update connections list
			updateConList:function() {
				var div = document.querySelector('#connections')
				div.innerHTML="";
				if(Connections.length==0)
					div.innerHTML="<i style='color:#ccc'>~No connections</i>"
				else{
					var table=document.createElement('table');
					div.appendChild(table);
					//header
					var newRow=table.insertRow(-1);
					newRow.insertCell(-1).outerHTML="<td colspan=3><b>Connection</b></td>";
					newRow.insertCell(-1).innerHTML="<b>Flow (L/day)</b>";
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
				froms[i].style.background="#abc";
			}
			for(var i=0;i<tos.length;i++)
			{
				tos[i].style.background="#bca";
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
		#navbar a[page=solveNetwork]{background:orange;color:black}
		#nodes button {font-size:10px}
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
		<div class=flex>
			<div style=padding:0.5em;text-align:center>
				<button 
					onclick=recalculateFlows() 
					style="display:inline-block;margin:auto;padding:1em 4em"
					>Reset Flows
				</button>
			</div>
			<div style=padding:0.5em;text-align:center>
				<button 
					onclick="solveNetwork();init()";
					style="display:inline-block;margin:auto;padding:1em 4em"
					>Solve Network
				</button>
			</div>
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
