<!doctype html><html><head>
	<?php include'imports.php'?>
	<style>
		#navbar a[page=results]{background:orange;color:black}
		#root {padding:1em}
		li {
			margin:1em 0em;
		}
	</style>
	<script src=js/loads.js></script>

	<script>
		function init() {
			document.querySelector('#waterReuseConnections').innerHTML=Reuse.length;
			calcWaterPotential();
			calcWaterSaved();
			calc_contaminants_rmvd();
		}

		//water saved from reuse: potential and actual
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
		function calc_contaminants_rmvd() {
			
			var ul=document.querySelector('#contaminants_rmvd');

			//array of contaminant names
			var conts = []; 
			for(var s in Loads) {
				for(var c in Loads[s].contaminants) {
					conts.push(c);
				}
				break;
			}

			conts.forEach(c=>{
				var li=document.createElement('li');
				ul.appendChild(li);
				li.innerHTML=c+": (under development)";
			});
		}
	</script>

</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>Results: <span class=subtitle>Summary</span></div>

<div id=root>

	<!--results-->
	<ul id=results>
		<li>Water reuse summary
			<ul>
				<li>Water reuse connections: <b id=waterReuseConnections>0</b>
				<li>
					Potential water savings: <b id=waterPotential></b> L/day
				</li>
				<li> 
					Actual water savings: <b id=waterSaved></b> L/day
				</li>
				<li>Contaminants removed (mg/day):
					<ul id=contaminants_rmvd></ul>
				</li>
			</ul>
		</li>
	</ul>
	<!--/results-->

</div root>

