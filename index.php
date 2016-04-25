<!doctype html><html><head>
	<meta charset=utf-8>
	<title>demEAUmed</title>
	<style>
		*{margin:0}
		table{border-collapse:collapse}
		th,td{border:1px solid #ccc}

		div{
			background:#fafafa;
			border:1px solid black;
		}

		div.container{ 
			display:inline-block;
		}
		div.column{
			display:inline-block;
			vertical-align:top;
		}
		div.cell{ 
			padding:1em;
			margin:-1px;
		}
		div.clearfix{clear:both}
	</style>
	<script>
		function toggleDisplay(element)
		{
			var display = element.style.display=='none' ? '' : 'none';
			element.style.display=display;
		}
	</script>
</head><center><body>

<!--main menu-->
<div>
	<h1>demEAUmed</h1>
	Main menu
</div>

<br>

<!--content-->
<div class=container>
	<!--left-->
	<div class=column>
		<div class=column>
			<div class=cell>Basic hotel info</div>
			<div class=cell>Laundry</div>
		</div>
		<div class=column>
			<div class=cell>Room</div>
			<div class=cell>Garden</div>
		</div>
		<div class=cell style=background:#4bacc6;color:white> Pool </div>
	</div>

	<!--right-->
	<div class=column>
		<div class=cell>
			Reuse table
			<table>
				<tr><th>    <th>App1<th>App2<th>App3
				<tr><th>App1<td>    <td>    <td>
				<tr><th>App2<td>    <td>    <td>
				<tr><th>App3<td>    <td>    <td>
			</table>
			<button>Create table</button>
		</div>
		<div class=cell>
			kitchen
		</div>
	</div>
</div>
