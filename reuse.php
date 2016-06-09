<!doctype html><html><head><?php include'imports.php'?>

	<style>
	</style>

	<script>
		var Connections = [];

		function newConnection(from,to,tec) //3 strings
		{
			console.log("New connection from "+from+" to "+to+" using "+tec)

			var Conn = {
				from:from,
				to:to,
				tec:tec,
			}

			Connections.push(Conn)

			updateConnectionsView()
		}

		function updateConnectionsView()
		{
		}

		/* Create a menu for selecting a technology */
		function createSelect(from,to)
		{
			if(from==to) return document.createElement('br')

			var select = document.createElement('select')
			var Techs = [
				"--none--",
				"MBR",
				"Tec 1",
				"Tec 2",
			];

			//what happens if we select something
			select.onchange=function(){newConnection(from,to,this.value)}

			for(var i in Techs)
			{
				var option = document.createElement('option')
				select.appendChild(option)
				option.innerHTML=Techs[i]
			}
			return select
		}

		/* Fill table reuse */
		function reuse()
		{
			var t = document.querySelector('#reuse')

			//TO
			for(var field in Inputs.Services)
			{
				if(field=="General") continue //skip "General"
				//append new th to row 0
				var newCell = t.rows[0].insertCell(-1)
				newCell.innerHTML=field
				newCell.setAttribute('field',field)
			}

			//FROM
			for(var field in Inputs.Services)
			{
				//skip "General"
				if(field=="General") continue
				//new row
				var newRow = t.insertRow(-1)
				newRow.setAttribute('field',field)
				var newCell = newRow.insertCell(-1)
				newCell.innerHTML=field
			}

			//SELECT TECH
			for(var i=1; i<t.rows.length; i++)
			{
				var from = t.rows[i].getAttribute('field')

				for(var j=1; j<t.rows[0].cells.length; j++)
				{
					var to = t.rows[0].cells[j].getAttribute('field')
					var newCell = t.rows[i].insertCell(-1)
					newCell.title="From "+from+" To "+to
					//create new select
					var select = createSelect(from,to)
					newCell.appendChild(select)
				}
			}
		}

		function init()
		{
			reuse()
		}
	</script>

</head><body onload=init()><!--title--><?php include'navbar.php'?>
<!--title--><div class=title>2. Water reuse: <span class=subtitle>connect hotel services with technologies</span></div>

<!--reuse table-->
<div>
	<style>
		#reuse {
			margin:1em;
		}
		#reuse tr:first-child,
		#reuse tr td:first-child {
			background:#abc;
			color:white;
			text-align:center;
		}
		#reuse tr:first-child td:first-child {
			color:black;
			font-weight:bold;
		}
	</style>
	<table id=reuse>
		<tr><td> &darr; From | To &rarr;
	</table>
</div>

<div>
	Connections
	<div id=connections>
		HERE
	</div>
</div>
