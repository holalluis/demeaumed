<!doctype html><html><head>
	<?php include'imports.php'?>
	<script>
		var arrows = false;
		function init() {
			Views.update();
			createGraph(60,arrows); //inside graph.php
			updateCookies();
		}

		//recalculate the flows for current network
		function recalculateFlows() {
			//reset flows
			Reuse.forEach(function(con){con.flow=null});
			updateCookies();
			window.location.reload();
		}
	</script>
	<script>
		/** update visual elements */
		var Views= {
			update:function() {
				//wrapper
				this.updateNodList();
				this.updateConList();
				this.updateReuList();
			},
			//update node list
			updateNodList:function() {
				var div = document.querySelector('#nodes');
				if(Nodes.length==0){ div.innerHTML="<i style='color:#ccc'>~No nodes</i>";return}
				div.innerHTML="";
				var table=document.createElement('table');
				div.appendChild(table);
				var newRow=table.insertRow(-1);
				newRow.insertCell(-1).innerHTML="<b>Nodes</b>";
				newRow.insertCell(-1).innerHTML="<b>Output (L/day)</b>";
				var n=1;

				Nodes.concat(Tanks).forEach(function(node){
					var newRow=table.insertRow(-1);
					newRow.onmouseenter=function(){see(node.name)};
					newRow.onmouseout=function(){unsee()};

					newRow.insertCell(-1).innerHTML=node.name;
					newRow.insertCell(-1).innerHTML=node.value==null ? "<b style=color:red>not calc</b>" : format(node.value);
					n++;
				});
			},
			//update connections list
			updateConList:function() {
				var div = document.querySelector('#connections')
				div.innerHTML="";
				if(Connections.length==0)
				{
					div.innerHTML="<i style='color:#ccc'>~No connections</i>"
				}
				else{
					var table=document.createElement('table');
					div.appendChild(table);
					//header
					var newRow=table.insertRow(-1);
					newRow.insertCell(-1).outerHTML="<td colspan=3><b>Connections</b></td>";
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
			updateReuList:function() {
				var div = document.querySelector('#reuse');
				div.innerHTML="";
				if(Reuse.length==0) {
					div.innerHTML="<i style='color:#ccc'>~No reuse connections</i>";
				}
				else{
					var table=document.createElement('table');
					div.appendChild(table);
					//header
					var newRow=table.insertRow(-1);
					newRow.insertCell(-1).outerHTML="<td colspan=3><b>Water reuse connections</b></td>";
					newRow.insertCell(-1).innerHTML="<b>Max flow (L/day)</b>";
					newRow.insertCell(-1).innerHTML="<b>Flow (L/day)</b>";
					//body
					for(var i in Reuse) {
						var from = Reuse[i].from;
						var to = Reuse[i].to;
						var flow = Reuse[i].flow;
						var newRow=table.insertRow(-1);
						var flow_f = flow==null ? "<b style=color:red>not calc</b>" : format(flow);
						newRow.setAttribute('from',from);
						newRow.setAttribute('to',to);
						newRow.insertCell(-1).outerHTML="<td style=text-align:right>"+from+"</th>";
						newRow.insertCell(-1).innerHTML="&rarr;";
						newRow.insertCell(-1).innerHTML=to;
						newRow.insertCell(-1).innerHTML=format(Reuse[i].maxFlow);
						newRow.insertCell(-1).outerHTML="<td title='"+flow+"'>"+flow_f+"</td>";
					}
				}
			},
		};

		function see(node) {
			var froms=document.querySelectorAll('#taules tr[from="'+node+'"]');
			var tos=document.querySelectorAll('#taules tr[to="'+node+'"]');
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
			var list=document.querySelectorAll('div#taules tr');
			for(var i=0;i<list.length;i++)
			{
				list[i].style.background="";
			}
		}
	</script>
	<style>
		div#navbar a[page=solveReuse]{background:orange;color:black}
		#root {
			justify-content:center;
		}
		#left {
			max-width:40%;
		}
		#botonera {
			justify-content:center;
		}
		#botonera button {
			display:block;
			padding:1em 4em;
			margin:1px;
		}
		#nodes, #connections, #reuse {
			padding:1px;
			font-size:10px;
			margin:0 auto;
			max-width:33%;
		}
	</style>
</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>6. Solve reuse: <span class=subtitle>Find flows resulting from water reuse</span></div>

<!--network solving-->
<script src=solveReuse.js></script>
<!--network solving-->

<div id=root class=flex>

	<!--left column-->
	<div id=left>
		<div id=botonera class=flex>
			<button onclick="recalculateFlows()">Reset Water Reuse Flows</button>
			<button onclick="solveReuse();init()">Solve Reuse</button>
		</div>

		<!--nodes i connexions-->
		<div class=flex id=taules>
			<div id=nodes></div>
			<div id=connections></div>
			<div id=reuse></div>
		</div>

	</div>

	<!--right column graph-->
	<div style="border:1px solid #ccc;border-top:none">
		<?php include'graph.php'?>
	</div>

</div>
