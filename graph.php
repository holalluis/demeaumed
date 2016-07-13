
<!--to be included somewhere (needs imports.php)-->
<style>
	.links line {
		stroke:#999;
		stroke-opacity: 0.6;
	}

	.nodes circle {
		stroke:#fff;
		stroke-width: 1.5px;
	}

	.node text {
		pointer-events: none;
		font: 10px sans-serif;
	}
</style>

<script src="https://d3js.org/d3.v4.min.js"></script>

<svg width="400" height="300"></svg>

<script>
	createGraph()
	function createGraph()
	{
		//empty element
		document.querySelector('svg').innerHTML="";

		var json = 
		{ 
			nodes: [
				// {"name": "Napoleon", "group": 1},
				// {"name": "Myriel",   "group": 1},
			],
			links: [
				// {"source": "Napoleon", "target": "Myriel", "value": 1},
				// {"source": "Myriel",  "target": "Myriel", "value": 8},
			],
		};

		//add nodes: services and tanks
		for(var node in Inputs.Services)
		{
			if(node=="General")continue; //skip "General"
			json.nodes.push( {name:node, group:0} )
		}
		for(var i in Tanks)
		{
			json.nodes.push( {name:Tanks[i].name, group:1} )
		}

		//add links
		for(var i in Connections)
		{
			json.links.push( {source:Connections[i].from, target:Connections[i].to, value:10} )
		}
		
		//draw
		dibuixa(json);

		function dibuixa(json) 
		{
			var svg = d3.select("svg"),
			width   = +svg.attr("width"),
			height  = +svg.attr("height");

			var color = d3.scaleOrdinal(d3.schemeCategory20);

			var simulation = d3.forceSimulation()
				.force("link", d3.forceLink().id(function(d) { return d.name; }))
				.force("charge",d3.forceManyBody())
				.force("center",d3.forceCenter(width/2,height/2));

			var link = svg.append("g")
				.attr("class", "links")
				.selectAll("line")
				.data(json.links)
				.enter().append("line")
				.attr("stroke-width", function(d) { return Math.sqrt(d.value); });

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

			simulation
				.nodes(json.nodes)
				.on("tick", ticked);

			simulation.force("link")
				.links(json.links);

			function ticked(){
				link
					.attr("x1", function(d) { return d.source.x; })
					.attr("y1", function(d) { return d.source.y; })
					.attr("x2", function(d) { return d.target.x; })
					.attr("y2", function(d) { return d.target.y; });
				node.selectAll('circle')
					.attr("cx", function(d) { return d.x; })
					.attr("cy", function(d) { return d.y; });
				node.selectAll('text')
					.attr("x", function(d) { return d.x; })
					.attr("y", function(d) { return d.y; });
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
	}
</script>
