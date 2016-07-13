<script>
	function dibuixa(json)
	{

		var node = svg.selectAll(".node")
			.enter().append("g")
			.attr("class", "node")

		node.append("circle")
			.attr("r", 10)
			.attr("fill", function(d) { return color(d.group); })

		node.append("text")
			.attr("dx", 12)
			.attr("dy", ".35em")
			.text(function(d) { return d.name });

		force.on("tick", function() 
		{
			link.attr("x1", function(d) { return d.source.x; })
				.attr("y1", function(d) { return d.source.y; })
				.attr("x2", function(d) { return d.target.x; })
				.attr("y2", function(d) { return d.target.y; });

			node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
		});
	}

	load_and_draw()

</script>
