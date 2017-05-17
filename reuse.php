<!doctype html><html><head>
<?php include'imports.php'?>
<style>
	div#navbar a[page=reuse]{background:orange;color:black;}
	#root {
		display:flex;
		flex-wrap:wrap;
		justify-content:space-between;
	}
	#newCon {
		padding:0.5em;
		border-bottom:1px solid #ccc;
		font-size:11px;
	}
	table#reuse
	{
		font-size:12px;
		width:100%;
	}
	table#reuse td:first-child,
	table#reuse th:first-child
	{
		border-left:none;
	}
	table#reuse td:last-child,
	table#reuse th:last-child
	{
		border-right:none;
	}

	button.X {
		display:block;
		margin:auto;
	}
</style>

<script>
	var arrows = false;
	function init()
	{
		Views.update();
		createGraph(60,arrows);
		updateCookies();
	}

	var Views={}
	Views.update=function()
	{
		//update reuse connections table
		(function(){
			var t=document.querySelector('#reuse');
			while(t.rows.length>2)t.deleteRow(-1);
			if(Reuse.length==0){
				var newCell=t.insertRow(-1).insertCell(-1);
				newCell.innerHTML='<center style=color:#666>~0 reuse connections</center>'
				newCell.colSpan=5;
			}
			for(var i in Reuse)
			{
				var reuse=Reuse[i];
				var newRow=t.insertRow(-1)
				newRow.insertCell(-1).innerHTML=reuse.from;
				newRow.insertCell(-1).innerHTML=reuse.to;
				newRow.insertCell(-1).innerHTML=reuse.tec;
				newRow.insertCell(-1).innerHTML=reuse.maxFlow;
				newRow.insertCell(-1).innerHTML="<button class=X onclick=Reuse.splice("+i+",1);init()>X</button>"
			}
		})();

		//populate technology selection menu
		(function updateUsing() {
			var select=document.querySelector('#newCon #using')
			select.innerHTML=""
			Technologies.forEach(function(tec){
				var option=document.createElement('option');
				select.appendChild(option);
				option.innerHTML=tec.name;
			});
		})();

		//populate "from" selection menu
		(function updateFrom() {
			var select=document.querySelector('#newCon #from')
			select.innerHTML=""
			Nodes.concat(Tanks).forEach(function(node){
				var option=document.createElement('option');
				select.appendChild(option);
				option.innerHTML=node.name;
			});
		})();

		//populate "to" selection menu
		(function updateTo() {
			var select=document.querySelector('#newCon #to')
			select.innerHTML=""
			Nodes.concat(Tanks).forEach(function(node){
				var option=document.createElement('option');
				select.appendChild(option);
				option.innerHTML=node.name;
			});
		})();
	}

	function newConnection() {
		//inputs
		var from = document.querySelector('#newCon #from').value;
		var to   = document.querySelector('#newCon #to').value;
		var tec  = document.querySelector('#newCon #using').value;
		var maxF = document.querySelector('#newCon #maxFlow').value;
		
		/*input errors*/
		if(from==to){alert("Error - you cannot connect a node to itself");return;}
		if(parseFloat(maxF)<=0){alert("Error - max flow cannot be zero or negative");document.querySelector('#newCon #maxFlow').select();return;}
		//connection already exists
		for(var i=0; i<Connections.length; i++) {
			var c=Connections[i];
			if(c.from==from && c.to==to) {
				alert("Error - Connection already exists");
				return;
			}
		}
		/*input errors*/

		//create new object
		var Con = {
			from:from,
			to:to,
			tec:tec,
			flow:null,
			maxFlow:parseFloat(maxF),
		};
		//add it to Reuse
		Reuse.push(Con);
		init();

		//reselect items
		document.querySelector('#newCon #from').value=from;
		document.querySelector('#newCon #to').value=to;
	}
</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>4. Water reuse: <span class=subtitle>Water reuse connections</span></div>

<div id=root style="justify-content:center">

	<!--left: menus-->
	<div style="border:1px solid #ccc;border-top:none">
		<!--reuse table-->
		<div>
			<table id=reuse>
				<tr><th colspan=5>Water reuse connections
				<tr><th>From<th>To<th>Technology<th>Max flow (L/day)<th>Options
			</table>
		</div>

		<!--new connection menu-->
		<div id=newCon>
			<h3>+ New water reuse connection</h3>
			<div class=flex style="justify-content:center">
				<table>
					<tr><td>From       <td><select id=from></select>
					<tr><td>To 	       <td><select id=to></select>
					<tr><td>Technology <td><select id=using></select>
					<tr>
						<td>Max flow (L/day)
						<td><input id=maxFlow value=0 onclick=this.select()>
					</tr>
					<tr>
						<td colspan=2>
						<button onclick=newConnection() style="display:block;margin:auto;padding:0.5em 4em">Add</button>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<!--right: graph--> 
	<div style="margin-left:-1px;border:1px solid #ccc;border-top:none"> <?php include'graph.php'?> </div>
</div>
