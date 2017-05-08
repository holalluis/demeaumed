<!doctype html><html><head>
<?php include'imports.php'?>
<style>
	div#navbar a[page=reuse]{background:orange;color:black;}
	#root {
		display:flex;
		flex-wrap:wrap;
		justify-content:space-between;
	}
</style>

<script>
	function init()
	{
		Views.update();
		createGraph()
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
				newCell.innerHTML='~0 reuse connections'
				newCell.colSpan=4;
			}
			for(var i in Reuse)
			{
				var reuse=Reuse[i];
				var newRow=t.insertRow(-1)
				newRow.insertCell(-1).innerHTML=reuse.from;
				newRow.insertCell(-1).innerHTML=reuse.to;
				newRow.insertCell(-1).innerHTML=reuse.tec;
				newRow.insertCell(-1).innerHTML="<button>X</button>"
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
		//create new object
		var Con = {
			from:from,
			to:to,
			tec:tec,
		};
		//add it to Reuse
		Reuse.push(Con);
		init();
	}

</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>5. Water reuse: <span class=subtitle>Add water reuse connections</span></div>

<div id=root>
	<!--left: menus-->
	<div>
		<!--new connection menu-->
		<div id=newCon>
			<h3>+ New water reuse connection</h3>
				&emsp; From       <select id=from></select>
				&rarr; To 	      <select id=to></select>
				&rarr; Technology <select id=using></select>
			<button onclick=newConnection()>Add</button>
		</div>

		<!--reuse table-->
		<div style=" padding:1em; ">
			<table id=reuse>
				<tr><th colspan=4>Water reuse connections
				<tr><th>From<th>To<th>Technology<th>Options
			</table>
		</div>
	</div>

	<!--right: graph--> 
	<div style="border:1px solid #ccc;border-top:none"> <?php include'graph.php'?> </div>
</div>
