<!doctype html><html><head>
	<meta charset=utf-8>
	<title>demEAUmed</title>
	<style>
		*{margin:0}
		body{font-family:Calibri}
		table{border-collapse:collapse}
		th,td{
			border:1px solid #ccc
		}

		div{
			background:#fafafa;
		}
		div.container{ 
			display:inline-block;
		}
		div.inline{
			display:inline-block;
			vertical-align:top;
			margin-right:-5px;
		}
	</style>
</head><center><body>

<!--main menu--><div><h1>demEAUmed</h1>Main menu</div><br>

<!--container-->
<div class=container>

	<!--left column-->
	<div class=inline>
		<table id="t1">
			<style>
				table#t1 td{
					text-align:center;
					padding:4em;
					border: 2px solid black;
				}
			</style>
			<tr>
				<td style="background:#4F81BD;color:white">Basic Hotel Information
				<td style="background:#9BBB59;color:white">Room appliances
			<tr>
				<td style="background:#8064A2;color:white">Laundry
				<td style="background:#F79646;color:white">Garden
			<tr><td style="background:#4bacc6;color:white" colspan=2>Pool
		</table>
	</div>

	<!--right column-->
	<div class=inline>
		<div id=reuse>
			<style>
				div#reuse {
					padding:2em;
					background:#FFBE86;
					border:2px solid black;
				}
				div#reuse td, div#reuse th{
					padding:.5em;
				}
				div#reuse tr:nth-child(even) 
				{
					background:#eee;
				}
				div#reuse tr:nth-child(odd) 
				{
					background:white;
				}
				div#reuse th{
					background:black;
					color:white;
				}
			</style>
			Reuse table
			<table>
				<tr><th>    <th>App1<th>App2<th>App3
				<tr><th>App1<td>    <td>    <td>
				<tr><th>App2<td>    <td>    <td>
				<tr><th>App3<td>    <td>    <td>
			</table>
			<button style="
				margin:1em;
				padding:1em;
			">Create table</button>
		</div>
		<div id=kitchen>
			<style>
				div#kitchen{
					padding:6em;
					background:#bcbcbc;
					color:white;
					border:2px solid black;
					margin-top:-2px;
				}
			</style>
			Kitchen
		</div>
	</div>

</div>
