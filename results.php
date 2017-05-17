<!doctype html><html><head>
	<?php include'imports.php'?>
	<style>
		#navbar a[page=results]{background:orange;color:black}
		#root {padding:1em}
	</style>

	<script>
		function init() {
			calcWaterPotential();
			calcWaterSaved();
		}

		function calcWaterPotential() {
			var sum=0;
			sum+=Reuse.map(function(con){return con.maxFlow}).reduce(function(prev,curr){return prev+curr});
			document.querySelector('#waterPotential').innerHTML=sum;

		}
		function calcWaterSaved() {
			var sum=0;
			sum+=Reuse.map(function(con){return con.flow}).reduce(function(prev,curr){return prev+curr});
			document.querySelector('#waterSaved').innerHTML=sum;
		}
	</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>Results: <span class=subtitle>Summary</span></div>

<div id=root>

	<!--results-->
	<ul id=results>
		<li> 
			Potential water saved from reuse: <b id=waterPotential></b> L/day
		</li>
		<li> 
			Actual water saved from reuse: <b id=waterSaved></b> L/day
		</li>
		<li>
			Contaminants: APPLY REMOVAL RATES TO EACH CONNECTION PENDING
		</li>
	</ul>

</div root>

