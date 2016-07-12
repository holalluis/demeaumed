<!doctype html><html><head><?php include'imports.php'?>

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
		
	svg {border:1px solid #ccc;margin:0 5em}
</style>

</head><body>
<!--navbar--><?php include'navbar.php'?>
<!--title--><div class=title>3. Graph connections: <span class=subtitle>Visual view of hotel water connections</span></div>

<svg width="960" height="600"></svg>

<script src="https://d3js.org/d3.v4.min.js"></script>

<script>
	var svg = d3.select("svg"),
		width = +svg.attr("width"),
		height = +svg.attr("height");

	var color = d3.scaleOrdinal(d3.schemeCategory20);

	var simulation = d3.forceSimulation()
		.force("link", d3.forceLink().id(function(d) { return d.id; }))
		.force("charge", d3.forceManyBody())
		.force("center", d3.forceCenter(width / 2, height / 2));

	function loadData(graph) 
	{
		var link = svg.append("g")
			.attr("class", "links")
			.selectAll("line")
			.data(graph.links)
			.enter().append("line")
			.attr("stroke-width", function(d) { return Math.sqrt(d.value); });

		var node = svg.append("g")
			.attr("class", "nodes")
			.selectAll("circle")
			.data(graph.nodes)
			.enter().append("circle")
			.attr("r", 10)
			.attr("fill", function(d) { return color(d.group); })
			.call(d3.drag()
					.on("start", dragstarted)
					.on("drag", dragged)
					.on("end", dragended));

		node.append("title")
			.text(function(d) { return d.id; });

		simulation
			.nodes(graph.nodes)
			.on("tick", ticked);

		simulation.force("link")
			.links(graph.links);

		function ticked() 
		{
		  link
			  .attr("x1", function(d) { return d.source.x; })
			  .attr("y1", function(d) { return d.source.y; })
			  .attr("x2", function(d) { return d.target.x; })
			  .attr("y2", function(d) { return d.target.y; });
		  node
			  .attr("cx", function(d) { return d.x; })
			  .attr("cy", function(d) { return d.y; });
		}
	}

	function dragstarted(d) {
		if (!d3.event.active) simulation.alphaTarget(0.3).restart();
		d.fx = d.x;
		d.fy = d.y;
	}

	function dragged(d) { d.fx = d3.event.x; d.fy = d3.event.y; }

	function dragended(d) {
		if (!d3.event.active) simulation.alphaTarget(0);
		d.fx = null;
		d.fy = null;
	}
</script>

<script>
	var data = { 
		"nodes": [
			/*
			{"id": "Napoleon", "group": 1},
			{"id": "Myriel",   "group": 1},
			{"id": "Labarre",  "group": 2},
			{"id": "Valjean",  "group": 2},
			*/
		],
		"links": [
			/*
			{"source": "Napoleon", "target": "Myriel", "value": 1},
			{"source": "Labarre",  "target": "Myriel", "value": 8},
			{"source": "Valjean",  "target": "Napoleon", "value": 1},
			*/
		],
	};

	//add nodes
	for(var node in Inputs.Services)
		data.nodes.push({id:node, group:0})

	//add links
	for(var i in Connections)
		data.links.push({source:Connections[i].from, target:Connections[i].to, value:10})
	
	//draw
	loadData(data);
</script>


