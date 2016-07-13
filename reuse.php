<!doctype html><html><head><?php include'imports.php'?>
	<style>
		#newCon, #newTank, #allCon, #allTanks
		{
			padding:1em;
			border-bottom:1px solid #ccc;
			border-right:1px solid #ccc;
		}
	</style>

<script>
	function newConnection()
	{
		//inputs
		var from = document.querySelector('#newCon #from').value;
		var to   = document.querySelector('#newCon #to').value;
		var tec  = document.querySelector('#newCon #using').value;

		//create new object
		var Con = {from:from,to:to,tec:tec};

		//add it to Connections
		Connections.push(Con);

		init();
	}

	function newTank()
	{
		//inputs
		var name = document.querySelector('#newTank #name').value;
		var volume   = document.querySelector('#newTank #volume').value;

		//create new object
		var tank = {name:name,volume:volume};

		//add it to Connections
		Tanks.push(tank);

		init();
	}

	/** functions that update visual elements*/
	var Views = 
	{
		update:function()
		{
			this.updateNewConMenus();
			this.updateConList();
			this.updateTankList();
		},
		updateNewConMenus:function()
		{
			updateFrom();
			updateTo();
			updateUsing();

			function updateFrom()
			{
				var select=document.querySelector('#newCon #from')
				select.innerHTML=""
				for(var service in Inputs.Services)
				{
					//skip "General"
					if(service=="General")continue;

					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=service
				}
				for(var i in Tanks)
				{
					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=Tanks[i].name
				}
			}
			function updateTo()
			{
				var select=document.querySelector('#newCon #to')
				select.innerHTML=""
				for(var service in Inputs.Services)
				{
					//skip "General"
					if(service=="General")continue;

					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=service
				}
				for(var i in Tanks)
				{
					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=Tanks[i].name
				}
			}
			function updateUsing()
			{
				var Techs = [
					"MBR",
					"Tec 1",
					"Tec 2",
					"Tec nova",
				];

				var select=document.querySelector('#newCon #using')
				select.innerHTML=""
				for(var i in Techs)
				{
					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=Techs[i];
				}
			}
		},
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
				var n=parseInt(i)+1;
				con.innerHTML="&emsp;"+n+". "+from+" &rarr; "+to+" (using "+tec+")"
			}
		},
		updateTankList:function()
		{
			var div = document.querySelector('#tanks')
			div.innerHTML=""
			if(Tanks.length==0)
				div.innerHTML="<i style='color:#ccc'>~No tanks</i>"
			for(var i in Tanks)
			{
				//new div == tank
				var tank = document.createElement('div')
				div.appendChild(tank)
				//get fields
				var name = Tanks[i].name
				var volume = Tanks[i].volume
				var n=parseInt(i)+1;
				tank.innerHTML="&emsp;"+n+". "+name+" ["+volume+" L]";
			}
		},
	};

	function init()
	{
		Views.update();
		updateCookies();
		createGraph(); //inside graph.php
	}
</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>2. Water reuse: <span class=subtitle>connect hotel services using technologies. Optional: create tanks</span></div>

<!--column-->
<div class=inline style=width:50%>
	<!--new connection menu-->
	<div id=newCon>
		<h3>New connection</h4>
		&emsp; From  <select id=from>  </select>
		&rarr; To 	  <select id=to>    </select>
		&rarr; Using <select id=using> </select>
		<button onclick=newConnection()>Add</button>
	</div>

	<!--new tank menu-->
	<div id=newTank>
		<h3>New tank</h4>
		&emsp;Name   <input id=name   placeholder="Tank name"> &emsp;
		Volume <input id=volume placeholder="Volume" type=number value=100> (L)
		<button onclick=newTank()>Add</button>
	</div>

	<!--connections created-->
	<div id=allCon>
		<h3>All Connections
			<button onclick="Connections=[];updateCookies();init()">Remove all</button>
		</h3> 
		<div id=connections></div>
	</div>

	<!--connections created-->
	<div id=allTanks>
		<h3>All Tanks
			<button onclick="Tanks=[];updateCookies();init()">Remove all</button>
		</h3> 
		<div id=tanks></div>
	</div>
</div>

<!--graph--> <div class=inline style="width:50%;border:1px solid #ccc;border-top:none"> <?php include'graph.php'?> </div>
