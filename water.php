<!doctype html><html><head><?php include'imports.php'?>
<style>
	/**general things*/
		input.input {
			margin-left:1em;
			width:50px;
		}
		ul {
			list-style-type:none;
			text-align:left;
			transition:all .2s;
			border-radius:0.3em;
		}
		li {
			margin:0.1em 0 0 0;
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
		}
	/**/

	/**header labels: can be active */
		label.header { 
			display:inline-block;
			width:110px;
			cursor:pointer; 
			background:#dadada;
			border-radius:0.3em;
			padding:0.2em;
			box-shadow: 0 1px 2px rgba(0,0,0,.1);
		}
		label.header:hover {background:#ccc}
		label.header:before {content:"\25b6\00a0";display:inline-block;transition:all 0.15s;font-size:15px}
		label.header.active:before {transform:rotate(90deg) }
		label.header.active {background:orange}
	/**/

	/** description labels*/
		label.description {display:inline-block;width:200px}
	/**/

	/*navbar*/
		div#navbar a[page=water]{background:orange;color:black;}
	/**/
	
	/*options menu*/
		div#title button {
			margin-left:0.5em;
			padding:0.5em;
			color:rgba(0,0,0,0.65);
		}
		div#title button:hover {
			color:rgba(0,0,0,0.85);
		}
		div#title button span#coll {transform:rotate(-90deg);display:inline-block;}
	/**/

	/*inputs and outputs*/
		div#io {text-align:center}
	/**/
</style>

<script>
	//body onload=init()
	function init() {
		allInputs()
		allOutputs()
	}

	//create a <input> element
	function createInput(obj,field) {
		/** obj: object, field: string */
		//new input element
		var input = document.createElement('input');
		input.classList.add('input');
		//set properties
		input.value=obj[field];
		input.onchange=function(){updateValue(obj,field,parseFloat(this.value))}
		return input
	}

	//model: update a value, cookies and outputs
	function updateValue(obj,field,newValue) {
		obj[field]=newValue
		updateCookies()
		allOutputs()
	}

	//view: expand all tabs
	function setAll(option) {
		var headers = document.querySelectorAll('label.header');
		var lists = document.querySelectorAll('label.header + ul');
		switch(option) {
			case 'active': 
				for(var i=0; i<headers.length; i++)
				{
					headers[i].classList.add('active');
					lists[i].classList.remove('invisible');
				}
				break;
			case 'inactive': 
				for(var i=0; i<headers.length; i++)
				{
					headers[i].classList.remove('active');
					lists[i].classList.add('invisible');
				}
				break;
			default: return 'error'; break;
		}
	}

	//view: inputs
	function allInputs(obj,name,destiny) {
		//initial conditions
		obj = obj || Inputs.Services
		name = name || "Services"
		destiny = destiny || document.querySelector('#inputs')

		//empty root ul the first time
		if(name=="Services") destiny.innerHTML=""

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
		if(name!="Services" && name!="General"){
			ul.classList.add('invisible');
		}
		else
			label.classList.toggle('active');

		for(var field in obj) {
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
				label.innerHTML="- " + ( Info[field] || field )
				//new input
				li.appendChild(createInput(obj,field))
			}
		}
	}

	//view: outputs
	function allOutputs(obj,name,destiny) {
		//initial conditions
		obj = obj || Outputs
		name = name || "Services"
		destiny = destiny || document.querySelector('ul#outputs')

		//empty root ul
		if(name=="Services") destiny.innerHTML=""

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

		//active by default
		label.classList.toggle('active');

		for(var field in obj) {
			//services
			if(typeof(obj[field])=="object") {
				allOutputs(obj[field],field,ul)
			}
			//normal fields inside services
			else {
				//new li
				li = document.createElement('li')
				ul.appendChild(li)
				//new label
				label = document.createElement('label')
				label.classList.add('description')
				li.appendChild(label)
				label.innerHTML="- "+(Info[field]||field)
				//evaluate obj[field]()
				li.innerHTML+=format(obj[field]())
			}
		}
	}
</script>

</head><body onload=init()><!--title--><?php include'navbar.php'?>

<!--menu visibility-->
<div id=title class=title>
	<span>1. Water use: <span class=subtitle>calculate water consumed per day</span></span>
	<div style="display:inline-block;float:right;font-size:16px">
		Options
		<button onclick=setAll('active')>&#9660; Expand all</button><!--
		--><button onclick=setAll('inactive')><span id=coll>&#9660;</span> Collapse all</button><!--
		--><button onclick="removeCookie('Inputs');window.location.reload()">Reset all inputs to default value</button> 
	</div>
</div>

<!--inputs and outputs container-->
<div id=io>
	<!--inputs-->
	<div class=inline style="width:49%;min-height:700px;border-right:1px solid #aaa">
		<h3>Inputs: data about the hotel</h3>
		<ul id=inputs></ul>
	</div>
	<!--outputs-->
	<div class=inline style="width:49%">
		<h3>Outputs (L/day) &mdash; <a href=js/outputs.js target=_blank>see equations</a></h4>
		<ul id=outputs></ul>
	</div>
</div>
