/*
si
	tots els inputs del node con.from estan calculats
		&&
	tots els ALTRES outputs del node con.from estan calculats

	la connexio es calculable

si 
	tots els ALTRES inputs de node.to estan calculats
		&&
	tots els outputs de node.to estan calculats

	la connexio es calculable

la connexio no es calculable
*/

function isConCalculable(con) {

	if(con.flow!=null){
		return true;
	} //already calculated

	//cas especial: quan no hi ha inputs al node from només es pot mirar els outputs del node to 
	//EXEMPLE: INPUT--->TAP->[altres nodes]
	//               ^
	//               |_aquesta connexio
	//
	if( Connections
				.filter(function(c){return c.to==con.from})
				.length==0
		) { 
			//si tots els outputs del node to tenen valor...
			if( Connections
						.filter(function(c){return c.from==con.to})
						.filter(function(c){return c.flow==null}).length==0
			) {
				//calcula-ho i retorna true
				con.flow = Connections
					.filter(function(c){return c.from==con.to})
					.map(function(c){return c.flow})
					.reduce(function(prev,curr){return prev+curr})
				return true;
			}
			return false;
	}

	//1: NODE FROM
	//mira si els inputs del node con.from estan tots calculats
	var are_all_inputs_of_node_from_calc = true;
	Connections
		.filter(function(c){return c.to==con.from})
		.forEach(function(c){
			if(c.flow==null) {
				are_all_inputs_of_node_from_calc = false;
			}
		});
	//mira si els ALTRES outputs del node con.from estan calculats
	var are_all_other_outputs_of_node_from_calc = true;
	Connections
		.filter(function(c){return c.from==con.from})
		.forEach(function(c){
			if(c.flow==null && c!=con) {
				are_all_other_outputs_of_node_from_calc = false;
			}
		});
	if(
		are_all_inputs_of_node_from_calc
			&& 
		are_all_other_outputs_of_node_from_calc
	)
	{
		con.flow=sumInputsAndOtherOutputs(con)
		return true;
	}

	//2: NODE TO
	//mira si altres inputs del node con.to estan tots calculats
	var are_all_other_inputs_of_node_to_calc = true;
	Connections
		.filter(function(c){return c.to==con.to})
		.forEach(function(c){
			if(c.flow==null && c!=con) {
				are_all_other_inputs_of_node_to_calc = false;
			}
		});
	//tots els outputs de node.to estan calculats
	var are_all_outputs_of_node_to_calc = true;
	Connections
		.filter(function(c){return c.from==con.to})
		.forEach(function(c){
			if(c.flow==null) {
				are_all_outputs_of_node_to_calc = false;
			}
		});
	if(
		are_all_other_inputs_of_node_to_calc 
			&&
		are_all_outputs_of_node_to_calc
	)
	{
		con.flow=sumOutputsAndOtherInputs(con);
		return true;
	}

	//connection is not calculable
	return false;
}

function sumInputsAndOtherOutputs(con) {
	var sum = 0;
	Connections
		.filter(function(c){return c.to==con.from})
		.forEach(function(c){
			sum += c.flow;
		});
	Connections
		.filter(function(c){return c.from==con.from})
		.forEach(function(c){
			sum -= c.flow;
		});
	return sum
}

function sumOutputsAndOtherInputs(con) {
	var sum = 0;
	Connections
		.filter(function(c){return c.to==con.to})
		.forEach(function(c){
			sum -= c.flow;
		});
	//tots els outputs de node.to estan calculats
	var are_all_outputs_of_node_to_calc = true;
	Connections
		.filter(function(c){return c.from==con.to})
		.forEach(function(c){
			sum += c.flow;
		});
	return sum
}
