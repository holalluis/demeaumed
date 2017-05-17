<!--figure to be included anywhere (needs imports.php)-->
<script src="d3js/d3.v4.min.js"></script>

<!--btns zoom-->
<div id=graph_zoom>
	<style>
		#graph_zoom { 
			padding:0.2em;
			border-bottom:1px solid #ccc;
			text-align:center;
		}
		#graph_zoom button { 
			border-radius:1em;
			padding:0.5em 2em;
			background:#abc;
			border:none;
			font-size:18px;
			outline:none;
		}
		#graph_zoom button:hover {background:#bca}
	</style>
	Zoom
	&#128270; 
	<button onclick=zoom("-")>-</button>
	<button onclick=zoom("+")>+</button>

	<script>
		function zoom(option) {
			var gravetat=parseInt(document.querySelector('svg').getAttribute('gravetat'))
			var augments=750;
			switch(option)
			{
				case "-":
					gravetat-=augments;break;
				case "+":
					gravetat+=augments;break;
				default:return;break;
			}
			createGraph(gravetat,arrows);
		}
	</script>

	<!--full-screen-btn-->
	<button onclick=window.location='fullScreen.php' style=float:right>full screen</button>
</div>
<style>
	.links line { stroke-opacity: 0.6;}
	.nodes circle { stroke:#fff; stroke-width: 1.5px; }
	.node text { pointer-events: none; font: 10px sans-serif; }

	.arrow{
		stroke-width:5;
		stroke:#000;
		stroke-dasharray:5, 5;
	}
</style>

<!--main svg-->
<svg id=main width="700" height="800"></svg>

<!--create graph-->
<script>
	//called in <body onload=init()>
	function dibuixa(json,gravetat,arrows) {
		gravetat = gravetat || 60;
		arrows = arrows || false;

		var svg = d3.select("svg"),
		width   = svg.attr("width"),
		height  = svg.attr("height");
		document.querySelector('svg').setAttribute('gravetat',gravetat)
		var color = d3.scaleOrdinal(d3.schemeCategory20);

		var node = svg.append("g")
			.attr("class","nodes")
			.selectAll("node")
			.data(json.nodes)
			.enter().append("g")
			.attr("class", "node")

		node.append("circle")
			.attr("r", 7)
			.attr("fill", function(d) { return color(d.group); })
			.call(d3.drag()
				.on("start", dragstarted)
				.on("drag", dragged)
				.on("end", dragended));

		node.append("text")
			.attr("dx", 12)
			.attr("dy", ".35em")
			.text(function(d) { return d.name; });

		// build the arrow.
		svg.append("svg:defs").selectAll("marker")
				.data(["end"]) //Different link/path types can be defined here
			.enter().append("svg:marker") //This section adds in the arrows
				.attr("id", String)
				.attr("viewBox", "0 -5 10 10")
				.attr("refX", 15)
				.attr("refY", -1.5)
				.attr("markerWidth", 6)
				.attr("markerHeight", 6)
				.attr("orient", "auto")
			.append("svg:path")
				.attr("d", "M0,-5 L10,0 L0,5");

		var simulation = d3.forceSimulation()
			.force("link", d3.forceLink().id(function(d){return d.name}))
			.force("charge",d3.forceManyBody())
			.force("center",d3.forceCenter(width/2,height/2))

    //set link color
		function linkColor(d) {
			if(d.reuse== 1){ return "#800080" }
			else if(d.value==-1){ return "#f00" }
      else if(d.value==0){ return "green" }
			else{
				return "#999"
			}
		}
			
		var link = svg.append("g")
			.attr("class", "links")
			.attr("marker-end",function(){return arrows ? "url(#end)" : ""})
			.selectAll("line")
			.data(json.links)
			.enter().append("line")
			.attr("stroke-width", function(d) { return Math.sqrt(d.value)||1; })
			.attr("stroke",function(d){return linkColor(d)})

		//controla distancia dels nodes
		simulation.force('charge',d3.forceManyBody().strength(function(){return -1*gravetat}))

		simulation
			.nodes(json.nodes)
			.on("tick", ticked);
		simulation.force("link")
			.links(json.links)

		function ticked(){
			link
				.attr("x1", function(d) {return d.source.x;})
				.attr("y1", function(d) {return d.source.y;})
				.attr("x2", function(d) {return d.target.x;})
				.attr("y2", function(d) {return d.target.y;})
			node.selectAll('circle')
				.attr("cx", function(d) {return d.x;})
				.attr("cy", function(d) {return d.y;});
			node.selectAll('text')
				.attr("x", function(d) {return d.x;})
				.attr("y", function(d) {return d.y;});
		}
		function dragstarted(d) {
			if (!d3.event.active) simulation.alphaTarget(0.3).restart();
				d.fx = d.x;
				d.fy = d.y;
		}
		function dragged(d) {
			d.fx = d3.event.x;
			d.fy = d3.event.y;
		}
		function dragended(d) {
			if (!d3.event.active) simulation.alphaTarget(0);
			d.fx = null;
			d.fy = null;
		}
	}

	function createGraph(gravetat,arrows) {
		gravetat = Math.abs(gravetat)+0.01 || 60;
		arrows = arrows || false;

		//empty element
		document.querySelector('svg').innerHTML="";
		//load data
			var json = { 
				nodes: [
					// {"name": "Napoleon", "group": 1},
				],
				links: [
					// {"source": "Napoleon", "target": "Myriel", "value": 1},
				],
			};
			function existsTank(tank) { for(var i in Tanks) { if(Tanks[i].name==tank) return true } return false }
			Tanks.forEach(function(tank){
				json.nodes.push( {name:tank.name, group:1} )
			});
			//add only if not exists in tanks
			Nodes.forEach(function(node){
				if(!existsTank(node.name))	
				{
					json.nodes.push( {name:node.name, group:0} ) 
				}
			});
			//add links
			//find max flow
			var max_flow = Connections.map(function(con){return con.flow}).reduce(function(max,item){if(item>max){max=item};return max},0) // O_O
			var divisor=max_flow/300||1;
			for(var i in Connections){
				var flow=Connections[i].flow;
				var value = flow/divisor;
				if(flow==null){value=-1}
				json.links.push( { source:Connections[i].from, target:Connections[i].to, value:value } )
			}
			//add reuse connections
			Reuse.forEach(function(con){
				var flow=con.flow;
				var value=flow/divisor||1;
				if(flow==null){value=-1}
				json.links.push( { source:con.from, target:con.to, value:value, reuse:1} )
			})
		//load data end
		
		//draw
		dibuixa(json,gravetat,arrows);
	}
</script>
