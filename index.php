<!doctype html><html><head>
	<?php include'imports.php'?>
	<script>
		function init()
		{
			browser_warning();
		}

		//check if browser is google chrome
		function browser_warning(){
			if(-1==navigator.userAgent.search('Chrome'))
			{
				var div=document.querySelector('#browser_warning');
				div.style.display='block';
			}
		}
	</script>
	<style>
		#browser_warning {
			background:yellow;
			text-align:center;
			padding:1em 0;
		}
		#root {
			padding:1em;
		}
		#root div {
			margin-bottom:1em;
		}
	</style>
</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>

<!--title-->
<div class=title>Get started: <span class=subtitle>Overview</span></div>

<!--warning-->
<div id=browser_warning style=display:none>
	Warning: Your browser is not Google Chrome, this web app may not work as expected
</div>

<!--root-->
<div id=root>
	<div>
		What is SambaNet? [Description here]
	</div>
	<div>
		Simulation steps:
		<ol>
			<li>Enter information about the water use.
			<li>Create a water network of the hotel services.
			<li>Calculate the water consumption.
			<li>Calculate the water quality (concentration of contaminants).
			<li>Add water reuse with water treatment technologiesa.
			<li>See results
		</ol>
	</div>
	<div>
		<img src=img/icra.jpg style="display:block;margin:auto">
	</div>
</div root>
