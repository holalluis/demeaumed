<!doctype html><html><head>
<?php include'imports.php'?>
<style>
	#navbar a[page=solveNetwork]{background:orange;color:black}
	#nodes button {font-size:10px}
</style>

<script>
	function init() {
		Views.update()
		createGraph() //inside graph.php
		updateCookies()
	}

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

	//recalculate the flows for current network
	function recalculateFlows() {
		//reset flows
		Connections.forEach(function(con){con.flow=0})
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
				newRow.insertCell(-1).innerHTML=format(node.value);
				newRow.insertCell(-1).innerHTML="<button onmouseenter=\"see('"+node.name+"')\" onmouseout=unsee()>see</button>";;
				n++;
			});
		},
		//update connections list
		updateConList:function() {
			var div = document.querySelector('#connections')
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
				for(var i in Connections)
				{
					var from = Connections[i].from;
					var to = Connections[i].to;
					var flow = Connections[i].flow;
					var newRow=table.insertRow(-1);
					newRow.setAttribute('from',from);
					newRow.setAttribute('to',to);
					newRow.insertCell(-1).innerHTML=from;
					newRow.insertCell(-1).innerHTML="&rarr;";
					newRow.insertCell(-1).innerHTML=to;
					newRow.insertCell(-1).outerHTML="<td title='"+flow+"'>"+format(flow)+"</td>";
				}
			}
		},
	};
</script>

<!--network solving-->
<script src="solveNodes.js"></script><!--solve nodes outputs ('value' property)-->
<script src="solveConnections.js"></script><!--solve connection flows ('flow' property)-->
<!--network solving-->

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>3. Solve network: <span class=subtitle>Find flows</span></div>

<!--column-->
<div class=inline style="width:50%">
	<div style=padding:0.5em;text-align:center>
		<button 
			onclick=recalculateFlows() 
			style="display:inline-block;margin:auto;padding:1em 4em"
			>Recalculate Flows</button>
	</div>

	<ul>
		<li>
			Note: Pool evaporation (
				<script>
					document.write(format(Outputs.Pool.Evaporation()))
				</script>
			L/day) is not accounted (discuss)
		</li>
		<li>
			Connection Loops still not implemented
		</li>
	</ul>

	<div id=nodes       class=inline style="padding:0.5em;font-size:10px;max-width:50%"></div>
	<div id=connections class=inline style="padding:0.5em;font-size:10px;max-width:50%"></div>
</div>

<!--graph-->
<div class=inline style="width:50%;border:1px solid #ccc;border-top:none">
	<?php include'graph.php'?>
</div>
