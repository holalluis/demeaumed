<!doctype html><html><head><?php include'imports.php'?>
	<style>
		#newCon, #newTank, #allCon
		{
			padding:1em;
			border-bottom:1px solid #ccc;
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

	/** functions that update visual elements*/
	var Views = 
	{
		update:function()
		{
			this.updateNewConMenus();
			this.updateConList();
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
					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=service
				}
			}
			function updateTo()
			{
				var select=document.querySelector('#newCon #to')
				select.innerHTML=""
				for(var service in Inputs.Services)
				{
					var option = document.createElement('option');
					select.appendChild(option);
					option.innerHTML=service
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
	};

	function init()
	{
		Views.update();
		updateCookies();
	}
</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>2. Water reuse: <span class=subtitle>connect hotel services using technologies</span></div>

<!--new connection menu-->
<div id=newCon>
	<h3>New connection</h4>
	From  <select id=from>  </select>
	&rarr; To 	  <select id=to>    </select>
	&rarr; Using <select id=using> </select>
	<button onclick=newConnection()>Add</button>
</div>

<!--new tank menu-->
<div id=newTank>
	<h3>New tank</h4>
	Name   <input id=name   placeholder="Tank name">
	Volume <input id=volume placeholder="Volume" type=number> (L)
	<button onclick=newTank()>Add</button>
</div>

<!--connections created-->
<div id=allCon>
	<h3>All Connections
		<button onclick="Connections=[];updateCookies();init()">Remove all</button>
	</h3> 
	<div id=connections></div>
</div>
