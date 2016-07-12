<!doctype html><html><head><?php include'imports.php'?></head>

<body><!--title--><?php include'navbar.php'?>

<div class=title>Start: <span class=subtitle>first steps</span></div>

<p id=paragraph>
	<style>
		#paragraph {
			margin:1em 4em 1em 2em;
		}
	</style>
	<img src=img/icra.jpg style="display:block;float:right">
	Introduction text here, Introduction text here,<br>
	Introduction text here, Introduction text here,<br>

	<ol>
		<li>Calculate water consumed
		<li>Connect hotel services for water reuse
		<li>Display results
	</ol>
</p>

<!--container-->
<div class=container style="margin:1em">
	What is SambaNet?<br>
	Bla, bla bla, bla bla<br>

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
