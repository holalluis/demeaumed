<!--navbar on top-->
<div id=navbar class=flex>
	<h1 onclick=window.location='index.php'>
		SambaNet
	</h1>
	<a href="water.php"        page=water class=left>1. Water use</a>
	<a href="create.php"       page=create>2. Create network</a>
	<a href="solveNetwork.php" page=solveNetwork>3. Solve network</a>
	<a href="reuse.php"        page=reuse>4. Create water reuse</a>
	<a href="solveReuse.php"   page=solveReuse>5. Solve water reuse</a>
	<a href="loads.php"        page=loads>6. Contaminants </a>
	<a href="results.php"      page=results class=right>99. Results</a>
</div>

<style>
	#navbar {
		padding:1em;
		background:#abc;
		background:linear-gradient(to right,#abc,#bbb);
		box-shadow: 0 1px 2px rgba(0,0,0,.1);
		font-size:12px;
	}
	#navbar h1 {
		margin:0;
		padding:0;
		margin-right:0.5em;
		cursor:pointer;
	}
	#navbar a {
		display:block;
		border-right:1px solid #abc;
		text-decoration:none;
		background:#bca;
		box-shadow:0 1px 2px rgba(0,0,0,.1);
		padding:0.5em;
	}
	#navbar a.right {
		border-right:none;
		border-radius:0 0.5em 0.5em 0;
	}
	#navbar a.left {
		border-radius:0.5em 0 0 0.5em;
	}
	#navbar a:hover {
		text-decoration:underline;
	}
</style>
