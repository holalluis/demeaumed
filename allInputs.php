<!doctype html><html><head>
	<?php include'imports.php'?>
	<style>
		/**general things*/
			input.input {margin-left:1em;width:50px}
			ul {
				list-style-type:none;
				text-align:left;
				transition:all 0s;
			}
			li {
				margin:0.1em 0 0 0;
				/*
				border:1px solid #abc;
				*/
				border-radius:0.3em;
				padding:0.1em;
			}
			ul.invisible
			{
				max-height:0;
				max-width:0;
				opacity:0;
				overflow:hidden;
				white-space:nowrap;
				border:none;
			}
			label.header + ul:not(.invisible) {margin-top:6px}

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
			input.setAttribute('onchange','alert(this.value)')
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
					for(var i=0; i<headers.length; i++)
					{
						headers[i].classList.remove('active');
						lists[i].classList.add('invisible');
					}
					break;
				default: return 'error'; break;
			}


		}

		function allInputs(obj,name,destiny)
		{
			//initial conditions
			obj = obj || Samba.Services
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
</head><body onload=init()>
<!--title--><div><h1>SambaNet</h1></div>

<!--inputs menu-->
<div style="text-align:center">
	<h3> 
		All inputs 
		<button onclick=setAll('active')>Expand All</button>
		<button onclick=setAll('inactive')>Collapse All</button>
	</h3>
	<ul id=root style="width:40%;display:inline-block;"></ul>
</div>
