<!doctype html><html><head><?php include'imports.php'?>

	<style>
		/**general things*/
			input.input {margin-left:1em;width:50px}
			ul {
				list-style-type:none;
				text-align:left;
				transition:all .2s;
				border-radius:0.3em;
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
				/*
				border:1px solid #abc;
				*/
			}

		/**header labels*/
			label.header { 
				display:inline-block;
				width:110px;
				cursor:pointer; 
				background:#dadada;
				border-radius:0.3em;
				padding:0.2em;
			}
			label.header:hover {background:#ccc}
			label.header:before {content:"+ "; color:blue; font-size:20px}
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
			input.onchange=function(){updateValue(obj,field,parseFloat(this.value))}
			return input
		}

		function updateValue(obj,field,newValue)
		{
			obj[field]=newValue
			allFormulas()
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
			obj = obj || Inputs.Services
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

		function allFormulas()
		{
			var cells = document.querySelectorAll('td[formula]')
			for(var i=0;i<cells.length;i++)
			{
				var formula = cells[i].getAttribute('formula')
				formula = "Outputs."+formula+"()"
				var value = eval(formula)
				cells[i].innerHTML=format(value)
			}
		}

		function toggleInputVisibility()
		{
			var button=document.querySelector('#toggleInputVisibility')
			if(button.getAttribute('action')=="expand")
			{
				setAll('active')
				button.setAttribute('action','collapse')
				button.innerHTML="Collapse all"
			}
			else
			{
				setAll('inactive')
				button.setAttribute('action','expand')
				button.innerHTML="Expand all"
			}
		}

		function init()
		{
			allInputs()
			allFormulas()
		}
	</script>

</head><body onload=init()><!--title--><?php include'navbar.php'?>

<!--inputs menu-->
<div class=inline style="width:45%">
	<!--title--><h3>All inputs <button id=toggleInputVisibility onclick=toggleInputVisibility() action=expand>Expand All</button></h3>
	<!--input tree--><ul id=root style=display:inline-block></ul>
</div>

<!--outputs-->
<div class=inline style="max-width:45%">

	<h3>All outputs (<a href=js/outputs.js>see equations</a>)</h4><table style=display:inline-block>
		<tr><th colspan=2>Service<th>Water (L/day)

		<tr><th rowspan=4> Room
			    <td>Toilet	<td formula="Room.Toilet">
			<tr><td>Sink	<td formula="Room.Sink">
			<tr><td>Shower	<td formula="Room.Shower">
			<tr><td>Bath	<td formula="Room.Bath">

		<tr><th rowspan=2>Pool
			    <td>Evaporation <td formula="Pool.Evaporation">
			<tr><td>Flow <td formula="Pool.Flow">

		<tr><th rowspan=2>Garden
			    <td>Area 	   <td formula="Garden.Area">
			<tr><td>Sprinklers <td formula="Garden.Sprinklers">

		<tr><th>Laundry
			<td>Laundry <td formula="Laundry">

		<tr><th rowspan=2>Lobby	
			<td>Toilet   <td formula="Lobby.Toilet">
			<tr><td>Sink <td formula="Lobby.Sink">

		<tr><th rowspan=2>Kitchen	
			<td title="(K=Vs×Pv×3)">Sink
				<td formula="Kitchen.Sink">
			<tr><td title="(K=Kc×KD×D)">Dishwasher
				<td formula="Kitchen.Dishwasher">

		<tr><td colspan=2 style=text-align:center><b>TOTAL</b><td style="font-weight:bold" formula="Total">
	</table>
</div>
