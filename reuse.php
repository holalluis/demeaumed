<!doctype html><html><head><?php include'imports.php'?>
	<style>
		#defaultNet, #newCon, #newTank, #allCon, #allTanks
		{
			padding:0.5em;
			border-bottom:1px solid #ccc;
			border-right:1px solid #ccc;
			font-size:11px;
		}
		#newCon, #newTank { background:#eee; }
		#newTank input, #newCon input { width:65px; }
		#allCon, #allTanks {
			max-height:200px;
			overflow-y:auto;
		}
		span.small {font-size:12px}

		#navbar a[page=reuse]{background:orange;color:black;}
	</style>

<script>
	function newConnection()
	{
		//inputs
		var from = document.querySelector('#newCon #from').value;
		var to   = document.querySelector('#newCon #to').value;
		var tec  = document.querySelector('#newCon #using').value;

		//create new object
		var Con = {
			from:from,
			to:to,
			tec:tec,
			flow:0,
		};

		//add it to Connections
		Connections.push(Con);

		init();
	}

	function newTank()
	{
		//inputs
		var name   = document.querySelector('#newTank #name').value;
		var volume = document.querySelector('#newTank #volume').value;

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
			//wrapper for all functions that update views
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
				for(var node in Nodes)
				{
					var option=document.createElement('option');
					option.innerHTML=node;
					select.appendChild(option);
				}
				for(var i in Tanks)
				{
					var option=document.createElement('option');
					option.innerHTML=Tanks[i].name;
					select.appendChild(option);
				}
			}
			function updateTo()
			{
				var select=document.querySelector('#newCon #to')
				select.innerHTML=""
				for(var node in Nodes)
				{
					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=node
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
					"none",
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
				var to =  Connections[i].to
				var tec = Connections[i].tec
				var n=parseInt(i)+1;
				con.innerHTML="&emsp;"+n+". "+from+" &rarr; "+to+" [using "+tec+"] "

				//button for removing the connection
				var button = document.createElement('button')
				con.appendChild(button)
				button.innerHTML="X"
				button.setAttribute('onclick','Connections.splice('+i+',1);init()')
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
				tank.innerHTML="&emsp;"+n+". "+name+" ["+volume+" L] ";
				//button for removing the tank
				var button = document.createElement('button')
				tank.appendChild(button)
				button.innerHTML="X"
				button.setAttribute('onclick','Tanks.splice('+i+',1);init()')
			}
		},
	};

	function initialData()
	{
		if(Tanks.length==0 && Connections.length==0)
		{
			//create new tanks
			var tank={name:"Tap" ,   volume:100};Tanks.push(tank);
			var tank={name:"Room" ,  volume:100};Tanks.push(tank);
			var tank={name:"Lobby",  volume:100};Tanks.push(tank);
			var tank={name:"Kitchen",volume:100};Tanks.push(tank);
			var tank={name:"Sewer",  volume:100};Tanks.push(tank);
			var tank={name:"INPUT",  volume:100};Tanks.push(tank);
			var tank={name:"OUTPUT", volume:100};Tanks.push(tank);
			//create new connections
			var con={from:"INPUT",              to:"Tap",                tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Room Bath",          tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Room Shower",        tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Room Sink",          tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Room Toilet",        tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Lobby Sink",         tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Lobby Toilet",       tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Kitchen Sink",       tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Kitchen Dishwasher", tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Pool",               tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Laundry",            tec:"none", flow:0}; Connections.push(con);
			var con={from:"Tap",                to:"Garden",             tec:"none", flow:0}; Connections.push(con);
			var con={from:"Room Bath",          to:"Room",               tec:"none", flow:0}; Connections.push(con);
			var con={from:"Room Shower",        to:"Room",               tec:"none", flow:0}; Connections.push(con);
			var con={from:"Room Sink",          to:"Room",               tec:"none", flow:0}; Connections.push(con);
			var con={from:"Room Toilet",        to:"Room",               tec:"none", flow:0}; Connections.push(con);
			var con={from:"Lobby Sink",         to:"Lobby",              tec:"none", flow:0}; Connections.push(con);
			var con={from:"Lobby Toilet",       to:"Lobby",              tec:"none", flow:0}; Connections.push(con);
			var con={from:"Kitchen Sink",       to:"Kitchen",            tec:"none", flow:0}; Connections.push(con);
			var con={from:"Kitchen Dishwasher", to:"Kitchen",            tec:"none", flow:0}; Connections.push(con);
			var con={from:"Room",               to:"Sewer",              tec:"none", flow:0}; Connections.push(con);
			var con={from:"Lobby",              to:"Sewer",              tec:"none", flow:0}; Connections.push(con);
			var con={from:"Kitchen",            to:"Sewer",              tec:"none", flow:0}; Connections.push(con);
			var con={from:"Pool",               to:"Sewer",              tec:"none", flow:0}; Connections.push(con);
			var con={from:"Laundry",            to:"Sewer",              tec:"none", flow:0}; Connections.push(con);
			var con={from:"Garden",             to:"Sewer",              tec:"none", flow:0}; Connections.push(con);
			var con={from:"Sewer",              to:"OUTPUT",             tec:"none", flow:0}; Connections.push(con);
		}
		else alert("Network must be empty");
		init();
	}

	function init()
	{
		//Add tanks from 'js/tanks.js' they are nodes as well (user-created)
		(function(){
			for(var i in Tanks){
				var name=Tanks[i].name;
				var node={value:null};
				if(Nodes[name]===undefined)
					Nodes[name]=node;
			}
		})();

		Views.update();
		createGraph(); //inside "graph.php"
		updateCookies();
	}
</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>2. Create network: <span class=subtitle>Connect hotel services using technologies. Optional: create tanks</span></div>

<!--column-->
<div class=inline style=width:50%>
	<!--Default network-->
	<div id=defaultNet style="padding:1em 0.5em;text-align:center">
		<button 
			onclick=initialData() 
			style="display:inline-block;margin:auto;padding:1em 4em"
			>Create a default network</button>
		<button 
			onclick="Connections=[];Tanks=[];updateCookies();init()"
			style="display:inline-block;margin:auto;padding:1em 4em"
			>Clear network</button>
	</div>

	<!--new connection menu-->
	<div id=newCon>
		<h3>+ New connection <span class=small>(=connect nodes)</span></h4>
		&emsp; From   <select id=from>  </select>
		&rarr; To 	  <select id=to>    </select>
		&rarr; Technology  <select id=using> </select>
		<button onclick=newConnection()>Add</button>
	</div>

	<!--new tank menu-->
	<div id=newTank>
		<h3>+ New tank <span class=small>(=new node)</span></h4>
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
