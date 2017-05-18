<!doctype html><html><head><?php include'imports.php'?>
<style>
	div#defaultNet, #newCon, #newTank, #allCon, #allTanks
	{
		padding:0.5em;
		border:1px solid #ccc;
		border-top:none;
		font-size:11px;
	}
	#newCon, #newTank { background:#eee; }
	#newTank input, #newCon input { width:65px; }
	#allCon, #allTanks {
		max-height:200px;
		overflow-y:scroll;
	}
	span.small {font-size:12px}
	div#navbar a[page=create]{background:orange;color:black;}

	.connection, .tank {
		display:flex;
		justify-content:space-between;
	}

	.connection:hover, .tank:hover {
		background:orange;
	}
</style>

<script>
	var arrows = true;
	if(Connections.length && Connections.map(function(con){return con.flow}).filter(function(flow){return flow!=null}).length)
	{
		arrows = false;
	}

	function init() {
		Views.update();
		createGraph(60,arrows); //inside "graph.php"
		updateCookies();
	}

	function newConnection() {
		//inputs
		var from = document.querySelector('#newCon #from').value;
		var to   = document.querySelector('#newCon #to').value;

		//error if from==to
		if(from==to){alert("Error - you cannot connect a node to itself");return;}

		//error if connection already exists
		for(var i=0; i<Connections.length; i++) {
			var c=Connections[i];
			if(c.from==from && c.to==to) {
				alert("Error - Connection already exists");
				return;
			}
		}

		//create new object
		var Con = {
			from:from,
			to:to,
			flow:null,
		};

		//add it to Connections
		Connections.push(Con);

		init();

		document.querySelector('#newCon #from').value=from;
		document.querySelector('#newCon #to').value=to;
	}

	function newTank() {
		//inputs
		var name   = document.querySelector('#newTank #name').value;
		var volume = document.querySelector('#newTank #volume').value;

		//create new object
		var tank = {name:name,volume:volume};

		//add it to Connections
		Tanks.push(tank);

		init();
	}

	//default network 1
	function initialData() {
		if(Tanks.length==0 && Connections.length==0) {
			//create new tanks
			Tanks.push({name:"TAP" ,   volume:100});
			Tanks.push({name:"ROOM" ,  volume:100});
			Tanks.push({name:"LOBBY",  volume:100});
			Tanks.push({name:"KITCHEN",volume:100});
			Tanks.push({name:"SEWER",  volume:100});
			Tanks.push({name:"INPUT",  volume:100});
			Tanks.push({name:"OUT",    volume:100});
			Tanks.push({name:"OUTPUT", volume:100});
			//create new connections
			Connections.push({from:"INPUT",              to:"TAP",                flow:null});
			Connections.push({from:"TAP",                to:"Room Bath",          flow:null});
			Connections.push({from:"TAP",                to:"Room Shower",        flow:null});
			Connections.push({from:"TAP",                to:"Room Sink",          flow:null});
			Connections.push({from:"TAP",                to:"Room Toilet",        flow:null});
			Connections.push({from:"TAP",                to:"Lobby Sink",         flow:null});
			Connections.push({from:"TAP",                to:"Lobby Toilet",       flow:null});
			Connections.push({from:"TAP",                to:"Kitchen Sink",       flow:null});
			Connections.push({from:"TAP",                to:"Kitchen Dishwasher", flow:null});
			Connections.push({from:"TAP",                to:"Pool Flow",          flow:null});
			Connections.push({from:"TAP",                to:"Pool Evaporation",   flow:null});
			Connections.push({from:"TAP",                to:"Laundry",            flow:null});
			Connections.push({from:"TAP",                to:"Garden",             flow:null});
			Connections.push({from:"Pool Evaporation",   to:"OUT",                flow:null});
			Connections.push({from:"Room Bath",          to:"ROOM",               flow:null});
			Connections.push({from:"Room Shower",        to:"ROOM",               flow:null});
			Connections.push({from:"Room Sink",          to:"ROOM",               flow:null});
			Connections.push({from:"Room Toilet",        to:"ROOM",               flow:null});
			Connections.push({from:"Lobby Sink",         to:"LOBBY",              flow:null});
			Connections.push({from:"Lobby Toilet",       to:"LOBBY",              flow:null});
			Connections.push({from:"Kitchen Sink",       to:"KITCHEN",            flow:null});
			Connections.push({from:"Kitchen Dishwasher", to:"KITCHEN",            flow:null});
			Connections.push({from:"ROOM",               to:"SEWER",              flow:null});
			Connections.push({from:"LOBBY",              to:"SEWER",              flow:null});
			Connections.push({from:"KITCHEN",            to:"SEWER",              flow:null});
			Connections.push({from:"Pool Flow",          to:"SEWER",              flow:null});
			Connections.push({from:"Laundry",            to:"SEWER",              flow:null});
			Connections.push({from:"Garden",             to:"SEWER",              flow:null});
			Connections.push({from:"SEWER",              to:"OUT",                flow:null});
			Connections.push({from:"OUT",                to:"OUTPUT",             flow:null});
			Reuse.push({from:"ROOM",    to:"Garden",    tec:"none", maxFlow:60e3, flow:null});
			Reuse.push({from:"KITCHEN", to:"Pool Flow", tec:"none", maxFlow:15e3, flow:null});
		}
		else alert("Network must be empty");
		init();
	}
</script>

<script>
	/** functions that update visual elements*/
	var Views = {
		update:function() {
			//wrapper for all functions that update views
			this.updateNewConMenus();
			this.updateConList();
			this.updateTankList();
		},
		updateNewConMenus:function() {
			updateFrom();
			updateTo();

			function updateFrom() {
				var select=document.querySelector('#newCon #from')
				select.innerHTML=""
				Nodes.concat(Tanks).forEach(function(node){
					var option=document.createElement('option');
					option.innerHTML=node.name;
					select.appendChild(option);
				})
			}
			function updateTo() {
				var select=document.querySelector('#newCon #to')
				select.innerHTML=""
				Nodes.concat(Tanks).forEach(function(node){
					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=node.name;
				})
			}
		},
		updateConList:function() {
			var div = document.querySelector('#connections')
			div.innerHTML=""
			if(Connections.length==0) { div.innerHTML="<i style='color:#ccc'>~No connections</i>" }
			for(var i in Connections) {
				//new div == connection
				var con = document.createElement('div')
				con.classList.add('connection');
				div.appendChild(con)
				//get fields
				var from = Connections[i].from
				var to =  Connections[i].to
				var n=parseInt(i)+1;
				con.innerHTML="&emsp;"+n+". "+from+" &rarr; "+to+"&emsp;";

				//button for removing the connection
				var button = document.createElement('button')
				con.appendChild(button)
				button.innerHTML="X"
				button.setAttribute('onclick','Connections.splice('+i+',1);init()')
			}
		},
		updateTankList:function() {
			var div = document.querySelector('#tanks')
			div.innerHTML=""
			if(Tanks.length==0) {
				div.innerHTML="<i style='color:#ccc'>~No tanks</i>"
			}
			for(var i in Tanks) {
				//new div == tank
				var tank = document.createElement('div')
				tank.classList.add('tank');
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
</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>2. Create network: <span class=subtitle>Connect hotel services. Optional: create tanks</span></div>

<div id=root class=flex style="justify-content:center">
	<!--left column-->
	<div>
		<!--Default network btn-->
		<div id=defaultNet style="padding:1em 0.5em;text-align:center">
			<button 
				onclick=initialData() 
				style="display:inline-block;margin:auto;padding:1em 4em"
				>Create example network</button>
			<button 
				onclick="Connections=[];Tanks=[];Nodes=[];Reuse=[];init();window.location.reload()"
				style="display:inline-block;margin:auto;padding:1em 4em"
				>Clear network</button>
		</div>

		<!--new connection menu-->
		<div id=newCon>
			<h3>+ New connection <span class=small>(=connect nodes)</span></h4>
			&emsp; From   <select id=from>  </select>
			&rarr; To 	  <select id=to>    </select>
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
				<button onclick="Connections=[];init()">Remove all</button>
			</h3> 
			<div id=connections></div>
		</div>

		<!--connections created-->
		<div id=allTanks>
			<h3>All Tanks
				<button onclick="Tanks=[];init();">Remove all</button>
			</h3> 
			<div id=tanks></div>
		</div>
	</div>

	<!--right col graph--> 
	<div style="min-width:49%;border:1px solid #ccc;border-top:none;margin-left:-1px"><?php include'graph.php'?></div>
</div>
