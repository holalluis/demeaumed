<!doctype html><html><head><?php include'imports.php'?>

<script>
	/** functions that update visual elements */
	var Views= 
	{
		update:function()
		{
			//wrapper
			this.updateNodList()
			//this.updateConList()
		},

		//update node list
		updateNodList:function()
		{
			var div = document.querySelector('#nodes')

			if(Nodes.length==0){ div.innerHTML="<i style='color:#ccc'>~No nodes</i>";return}

			div.innerHTML="";
			var table=document.createElement('table');
			div.appendChild(table);
			
			var newRow=table.insertRow(-1);
			newRow.insertCell(-1).innerHTML="<b>Nodes</b>";
			newRow.insertCell(-1).innerHTML="<b>Name</b>";
			newRow.insertCell(-1).innerHTML="<b>Flow (L/day)</b>";

			var n=1;
			for(var node in Nodes)
			{
				var newRow=table.insertRow(-1);
				newRow.insertCell(-1).innerHTML=n;
				newRow.insertCell(-1).innerHTML=node;
				newRow.insertCell(-1).innerHTML=format(Nodes[node].value);
				n++;
			}
		},

		//update connections list
		updateConList:function()
		{
			var div = document.querySelector('#connections')
			div.innerHTML="<b>Connections</b><br>"
			if(Connections.length==0)
				div.innerHTML="<i style='color:#ccc'>~No connections</i>"

			for(var i in Connections)
			{
				//new div == connection
				var con = document.createElement('div')
				div.appendChild(con)
				//get fields
				var from = Connections[i].from
				var to = Connections[i].to
				var tec = Connections[i].tec
				var vol = Connections[i].vol
				var n=parseInt(i)+1;
				con.innerHTML="&emsp;"+n+". "+from+" &rarr; "+to;
			}
		},
	};

	function init()
	{
		Views.update()
		createGraph() //inside graph.php
	}
</script>

<script src="solveNetwork.js"></script><!--this script solves the network-->

</head><body onload=init()>

<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>3. Solve network: <span class=subtitle>Find flows</span></div>

<!--column-->
<div class=inline style="width:50%">
	<div id=nodes       class=inline style="padding:0.5em;font-size:10px;max-width:50%"></div>
	<div id=connections class=inline style="padding:0.5em;font-size:10px;max-width:50%"></div>

	<div class=inline style=padding:0.5em>
		<h3>TO DO</h3>
		<ul>
			<li>Cabals de cada connexió &mdash; <b>FET</b>
			<li>Calcular càrregues contaminants
			<li>Calcular estalvis d'aigua reutilitzada
	</div>
</div>
<!--graph--><div class=inline style="width:50%;border:1px solid #ccc;border-top:none"><?php include'graph.php'?></div>
