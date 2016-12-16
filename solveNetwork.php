<!doctype html><html><head><?php include'imports.php'?>

<script>
	/** functions that update visual elements*/
	var Views = 
	{
		update:function()
		{
			//wrapper
			this.updateNodList()
			this.updateConList()
		},

		//update node list
		updateNodList:function()
		{
			var div = document.querySelector('#nodes')
			div.innerHTML=""
			var Nodes = Inputs.Services

			if(Nodes.length==0)
				div.innerHTML="<i style='color:#ccc'>~No nodes</i>"

			for(var node in Nodes)
			{
				//new div == connection
				var con = document.createElement('div')
				div.appendChild(con)
				//get fields
				con.innerHTML="&emsp;"+node;
			}
			for(var i in Tanks)
			{
				//new div == connection
				var con = document.createElement('div')
				div.appendChild(con)
				//get fields
				con.innerHTML="&emsp;"+Tanks[i].name;
			}
		},

		//update connections list
		updateConList:function()
		{
			var div = document.querySelector('#connections')
			div.innerHTML=""
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
				con.innerHTML="&emsp;"+n+". "+from+" &rarr; "+to+" [using "+tec+"] [Volume: "+vol+"] "
			}
		},
	};

	function init()
	{
		Views.update()
		createGraph() //inside graph.php
	}
</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>3. Solve network: <span class=subtitle>Find volumes</span></div>

<!--column-->
<div class=inline style="width:50%">
	Nodes 
	<div id=nodes></div>
	Connections 
	<div id=connections></div>

	<div>
		<h1>Calcular</h1>
		<ul>
			<li>Cabals de cada connexió i de cada aparell, i de l'aigua residual (final)
			<li>Calcular estalvis d'aigua reutilitzada
			<li>Calcular concentracions dels contaminants (càrregues)
	</div>

</div>
<!--graph--><div class=inline style="width:50%;border:1px solid #ccc;border-top:none"><?php include'graph.php'?></div>
