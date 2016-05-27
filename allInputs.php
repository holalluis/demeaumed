<!doctype html><html><head>
	<?php include'imports.php'?>
	<style>
		/**general things*/
			input.input {margin-left:1em;width:50px}
			ul {
				list-style-type:none;
				text-align:left;
				transition:all .2s;
			}
			li {
				margin:0.1em 0 0 0;
				/*
				border:1px solid #abc;
				*/
				border-radius:0.3em;
				padding:0.1em;
			}
			ul.invisible {
				max-height:0;
				max-width:0;
				opacity:0;
				overflow:hidden;
				white-space:nowrap;
				border:none;
				visibility:hidden;
			}
			label.header + ul:not(.invisible) {
				margin-top:3px;
				border:1px solid #abc;
			}

		/**header labels*/
			label.header { 
				display:inline-block;
				width:100px;
				cursor:pointer; 
				background:#dadada;
				border-radius:0.3em;
				padding:0.2em;
			}
			label.header:hover {background:#ccc}
			label.header:before {content:"+ "; color:blue}
			label.header.active:before { content:"- "; }

			label.header.active {background:orange}

		/** description labels*/
			label.description {display:inline-block;width:200px}
	</style>

	<script>
		function createInput(obj,field)
		{
			/** obj: object, field: string */
			//new input element
			var input = document.createElement('input');
			input.classList.add('input');
			//set properties
			input.value=obj[field];
			input.onchange=function(){obj[field]=parseFloat(this.value)}
			return input
		}

		function setAll(option)
		{
			var headers = document.querySelectorAll('label.header');
			var lists = document.querySelectorAll('label.header + ul');
			switch(option)
			{
				case 'active': 
					for(var i=0; i<headers.length; i++)
					{
						headers[i].classList.add('active');
						lists[i].classList.remove('invisible');
					}
					break;
				case 'inactive': 
					document.querySelector('#root').innerHTML=""
					init();
					break;
				default: return 'error'; break;
			}
		}

		function allInputs(obj,name,destiny)
		{
			//initial conditions
			obj = obj || Global.Services
			name = name || "Services"
			destiny = destiny || document.querySelector('#root')

			//container <li>
			var li = document.createElement('li')
			destiny.appendChild(li)

			//header clickable
			var label = document.createElement('label')
			li.appendChild(label)
			label.classList.add('header')
			label.innerHTML=name

			//new <ul>
			var ul = document.createElement('ul');
			li.appendChild(ul);

			//click action
			label.onclick=function(){this.classList.toggle('active');ul.classList.toggle('invisible')}

			//if not initial case
			if(name!="Services"){
				ul.classList.add('invisible');
			}
			else
				label.classList.toggle('active');

			for(var field in obj)
			{
				//services
				if(typeof(obj[field])=="object")
				{
					allInputs(obj[field],field,ul)
				}
				//normal fields inside services
				else
				{
					//new li
					li = document.createElement('li')
					ul.appendChild(li)
					//new label
					label = document.createElement('label')
					label.classList.add('description')
					li.appendChild(label)
					label.innerHTML= Info[field] || field
					//new input
					li.appendChild(createInput(obj,field))
				}
			}
		}

		function init()
		{
			allInputs()
		}
	</script>
</head><body onload=init()><!--title--><div><h1>SambaNet</h1></div>

<!--inputs menu-->
<div style="text-align:center">
	<!--title-->
	<h3> 
		All inputs 
		<button onclick=setAll('active')>Expand All</button>
		<button onclick=setAll('inactive')>Collapse All</button>
	</h3>

	<!--input tree goes inside here-->
	<ul id=root style="width:40%;display:inline-block;"></ul>
</div>

<!--outputs-->
<div>
	<h3>Outputs</h4>
	<table style=display:inline-block>
		<tr><th rowspan=4> Room
			<td> Toilet	(TFC×TUF×G) 
				<td formula="Room.Toilet">
			<tr><td> Sink	(SDR×SUD×SUF×G) 
				<td formula="Room.Sink">
			<tr><td> Shower	(RDR×RSR×RF×G) 
				<td formula="Room.Shower">
			<tr><td> Bath	(BV×BP×BF×G) 
				<td formula="Room.Bath">

		<tr><th>Pool<td>Pool 
			PA=630.25e^(0.0644TA )
			PW=630.25e^(0.0644TDP )
			Evp=A(PW-PA )(0.089+(0.0782W) )*2264
			Pool=86.4*Evp 
				<td formula="Pool">

		<tr><th rowspan=2>Garden	
			<td>By Area	G=A×IR 
				<td formula="Garden.ByArea">
			<tr><td>By Sprinklers	G=Ns×v×t 
				<td formula="Garden.BySprinklers">

		<tr><th rowspan=2>Laundry	
			<td> By Load	M=MC×ML 
				<td formula="Laundry.ByLoad">
			<tr><td> By Person	M=MC×MPC×G 
				<td formula="Room.Shower">

		<tr><th rowspan=2>Lobby	
			<td>Toilet	T=TFC×TUF×G <td formula="Room.Shower">
			<tr><td>Sink	S=SDR×SUD×SUF×G <td formula="Room.Shower">

		<tr><th rowspan=3>Kitchen	
			<td>3-Sink	K=Vs×Pv×3 <td formula="Room.Shower">
			<tr><td>Dishwasher	K=Kc×KD×D <td formula="Room.Shower">
			<tr><td>Per-Person	K=CD×D <td formula="Room.Shower">
	</table>
</div>
