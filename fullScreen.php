<!doctype html><html><head>

<?php include'imports.php'?>

<script>
	function init()
	{
		fullScreen()
	}

	function fullScreen()
	{
		var svg=document.querySelector('svg#main');
		svg.setAttribute('width','1200');
		svg.setAttribute('height','800');
		svg.style.border='1px solid black';
		svg.style.borderTop='none';
		createGraph();
	}
</script>

</head><body onload=init()>

<!--navbar--><?php include'navbar.php'?>

<!--graph--> <div style="text-align:center;border:1px solid #ccc"> <?php include'graph2.php'?> </div>
