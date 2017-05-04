<!doctype html><html><head>
<style>
	path.link {
		fill:none;
		stroke:#666;
		stroke-width:1.5px;
	}

	circle {
		fill:#ccc;
		stroke:#ccc;
		stroke-width:5px;
	}

	text {
		fill:#000;
		font:12px sans-serif;
		pointer-events:none;
	}
</style>
</head><body>

<!--main svg-->
<svg id=main width="700" height="600"></svg>

<script src="http://d3js.org/d3.v3.js"></script>

<script>
	// get the data
	function createGraph(){
		//load data
		links=[
			/*{source:"Mario",target:"Peter",value:1.2}*/
		];
		for(var i in Connections) {
			var value=Connections[i].flow;
			links.push({
				source:Connections[i].from,
				target:Connections[i].to,
				value:value
			});
		}

		function dibuixa(links) {
			var nodes = {};
			//compute the distinct nodes from the links.
			links.forEach(function(link) {
					link.source = nodes[link.source] || 
							(nodes[link.source] = {name: link.source});
					link.target = nodes[link.target] || 
							(nodes[link.target] = {name: link.target});
					link.value = +link.value;
			});

			//empty element
			document.querySelector('svg').innerHTML="";

			var svg = d3.select("svg"),
			width   = svg.attr("width"),
			height  = svg.attr("height");

			var force = d3.layout.force()
					.nodes(d3.values(nodes))
					.links(links)
					.size([width, height])
					.linkDistance(80)
					.charge(-800)
					.on("tick", tick)
					.start();


			// build the arrow.
			svg.append("svg:defs").selectAll("marker")
					.data(["end"])      // Different link/path types can be defined here
				.enter().append("svg:marker")    // This section adds in the arrows
					.attr("id", String)
					.attr("viewBox", "0 -5 10 10")
					.attr("refX", 15)
					.attr("refY", -1.5)
					.attr("markerWidth", 6)
					.attr("markerHeight", 6)
					.attr("orient", "auto")
				.append("svg:path")
					.attr("d", "M0,-5L10,0L0,5");

			// add the links and the arrows
			var path = svg.append("svg:g").selectAll("path")
					.data(force.links())
				.enter().append("svg:path")
					.attr("class", "link")
					.attr("marker-end", "url(#end)");

			// define the nodes
			var node = svg.selectAll(".node")
					.data(force.nodes())
				.enter().append("g")
					.attr("class", "node")
					.call(force.drag);

			// add the nodes
			node.append("circle")
					.attr("r", 5);

			// add the text 
			node.append("text")
					.attr("x", 12)
					.attr("dy", ".35em")
					.text(function(d) { return d.name; });

			// add the curvy lines
			function tick() {
					path.attr("d", function(d) {
							var dx = d.target.x - d.source.x,
									dy = d.target.y - d.source.y,
									dr = 0; //Math.sqrt(dx * dx + dy * dy);
							return "M" + 
									d.source.x + "," + 
									d.source.y + "A" + 
									dr + "," + dr + " 0 0,1 " + 
									d.target.x + "," + 
									d.target.y;
					});
					node
							.attr("transform", function(d) { 
							return "translate(" + d.x + "," + d.y + ")"; });
			}
		}
		dibuixa(links);
	};
</script>
