<!doctype html><html><head>
	<?php include'imports.php'?>
	<script src=solveNodes2.js></script>
	<script>
		function init()
		{
			createGraph(60,false)
		}
	</script>
</head><body onload=init()>
<!--navbar--><?php include'navbar.php'?>

<div style="border:1px solid #ccc;border-top:none;margin-left:-1px">
	<?php include'graph.php'?>
</div>
