<!doctype html><html><head><?php include'imports.php'?>

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

		function allFormulas()
		{
			updateInputs() //see formulas.js
			var cells = document.querySelectorAll('td[formula]')
			for(var i=0;i<cells.length;i++)
			{
				var formula = cells[i].getAttribute('formula')
				formula = "Formulas."+formula+"()"
				var value = eval(formula)
				cells[i].innerHTML=format(value)
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
	<!--title--><h3> 
		All inputs 
		<button id=toggleInputVisibility
						onclick=toggleInputVisibility()
						action="expand"
		>Expand All</button>
		<script>
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
		</script>
	</h3>
	<!--inputs tree here--><ul id=root style="display:inline-block;"></ul>
</div>

<!--outputs-->
<div class=inline style="max-width:45%">
	<h3>All outputs</h4>
	<table style=display:inline-block>
		<tr><th colspan=2>Service<th>Water (m<sup>3</sup>)
		<tr><th rowspan=4> Room
			<td title="(TFC×TUF×G)"> Toilet	<td formula="Room.Toilet">
			<tr><td title="(SDR×SUD×SUF×G)" > Sink	<td formula="Room.Sink">
			<tr><td title="(RDR×RSR×RF×G)" > Shower	<td formula="Room.Shower">
			<tr><td title="(BV×BP×BF×G)" > Bath	<td formula="Room.Bath">

		<tr><th>Pool<td
			title="
			PA=630.25e^(0.0644TA )
			PW=630.25e^(0.0644TDP )
			Evp=A(PW-PA )(0.089+(0.0782W) )*2264
			Pool=86.4*Evp " >Pool <td formula="Pool">

		<tr><th rowspan=2>Garden	
			<td title="(G=A×IR" >By Area	<td formula="Garden.ByArea">
			<tr><td title="(G=Ns×v×t" >By Sprinklers	<td formula="Garden.BySprinklers">

		<tr><th rowspan=2>Laundry	
			<td title="(M=MC×ML" > By Load	<td formula="Laundry.ByLoad">
			<tr><td title="(M=MC×MPC×G)" > By Person	<td formula="Laundry.ByPerson">

		<tr><th rowspan=2>Lobby	
			<td title="(T=TFC×TUF×G)" >Toilet	<td formula="Lobby.Toilet">
			<tr><td title="(S=SDR×SUD×SUF×G)" >Sink	<td formula="Lobby.Sink">

		<tr><th rowspan=3>Kitchen	
			<td title="(K=Vs×Pv×3" >3-Sink	<td formula="Kitchen.ThreeSink">
			<tr><td title="(K=Kc×KD×D)" >Dishwasher	<td formula="Kitchen.Dishwasher">
			<tr><td title="(K=CD×D)" >Per-Person	<td formula="Kitchen.PerPerson">
	</table>
</div>
