<!doctype html><html><head><?php include'imports.php'?>

	<script>
		function newConnection(from,to,tec) //3 strings
		{
			//check if connection already exists
			for(var i in Connections)
			{
				if(Connections[i].from==from && Connections[i].to==to)
				{
					if(tec=='--none--')
						Connections.splice(i,1); //remove connection i if tec is --none--
					else 
						Connections[i].tec=tec; //else, update it

					updateConnectionsView();
					updateCookies();
					return 
				}
			}

			//if not exists, create new object
			var Conn = {
				from:from,
				to:to,
				tec:tec,
			}
			//add it to Connections
			Connections.push(Conn)
			//update view and cookies
			updateConnectionsView()
			updateCookies();
		}

		function updateConnectionsView()
		{
			var div = document.querySelector('#connections')
			div.innerHTML=""
			for(var i in Connections)
			{
				//new div == connection
				var con = document.createElement('div')
				div.appendChild(con)
				//get fields
				var from = Connections[i].from
				var to = Connections[i].to
				var tec = Connections[i].tec
				con.innerHTML=from+" &rarr; "+tec+" &rarr; "+to
			}
		}

		/* Create a menu for selecting a technology */
		function createSelect(from,to)
		{
			if(from==to) return document.createElement('br')
			var select = document.createElement('select')
			//what happens if we select something
			select.onchange=function(){newConnection(from,to,this.value)}

			var Techs = [
				"--none--",
				"MBR",
				"Tec 1",
				"Tec 2",
			];

			for(var i in Techs)
			{
				var option = document.createElement('option')
				select.appendChild(option)
				option.innerHTML=Techs[i]
				option.value=Techs[i]
			}

			//check if already exists connection
			var index=existsConnection(from,to)
			if(index)select.value=Connections[index].tec

			return select
		}

		/* Fill table reuse */
		function reuse()
		{
			var t = document.querySelector('#reuse')
			while(t.rows.length>1)t.deleteRow(-1)

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

		//check if connection exists, and return index
		function existsConnection(from,to)
		{
			for(var i in Connections)
			{
				if(Connections[i].from==from && Connections[i].to==to)
					return i
			}
			return false
		}

		function init()
		{
			reuse()
			updateConnectionsView()
		}
	</script>

</head><body onload=init()><!--title--><?php include'navbar.php'?>
<!--title--><div class=title>2. Water reuse: <span class=subtitle>connect hotel services using technologies</span></div>

<!--reuse table--><div>
	<table id=reuse>
		<tr><td> &darr; From | To &rarr;
	</table>
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
</div>

<!--Connections--><div style="padding:1em">
	<b>Connections</b>
	<div id=connections></div>
</div>
