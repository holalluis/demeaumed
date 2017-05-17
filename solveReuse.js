//number of not calculated nodes
function getNotCalcNodes() {
	return Nodes
		.concat(Tanks)
		.filter(function(n){return n.value==null})
}

//number of not calculated connections
function getNotCalcCons() {
	return Connections
		.concat(Reuse)
		.filter(function(c){return c.flow==null})
}

/* MAIN FUNCTION SOLVING REUSE */
function solveReuse(){

	//reuse rules: set reuse flow
	Reuse.forEach(function(reu){
		
		reu.flow=null;

		var reuse_flow=0; //calc reuse flow here

		//reuse_flow = sum of other outputs (theoretical max reuse)
		Connections
			.filter(function(c){return reu.from == c.from})
			.forEach(function(c){reuse_flow += c.flow});

		//if the flow calculated is greater than required, shave it
		if( reuse_flow > getNodeByName(reu.to).value ) 
		{
			reuse_flow = getNodeByName(reu.to).value;
		}

		//if the flow is still greater than max flow, shave it
		if( reuse_flow > reu.maxFlow ) 
		{
			reuse_flow = reu.maxFlow;
		}

		//final step: set it
		reu.flow = reuse_flow;

	});

	//reset connections
	Connections.forEach(function(c){c.flow=null});

	//reset tanks
	Tanks.forEach(function(t){t.value=null});

	//add connections and reuse to the same array
	var Connections_and_Reuse = Connections.concat(Reuse);

	//1. troba els nodes que tenen només un output: flow=value
	Nodes
		.concat(Tanks)
		.map(function(node){return node.name})
		.forEach(function(name){
			var outputs = Connections_and_Reuse.filter(function(con){return name==con.from});
			if(outputs.length==1) {
				var flow=getNodeByName(name).value;
				if(flow!=null){
					console.log("nodes trobats: "+name);
					outputs[0].flow=flow;
				}
			}
	});


	var notCalcItems=Infinity;//initial value that we have to get to 0

	//infinte loop for solving network
	var iteracio=1;

	while(true) {

		console.log("[+] new solver iteration ("+iteracio+")");

		//this functions solve the connection or node
		Connections_and_Reuse.forEach(function(con){
			isConCalculable(con);
		});

		//calc tank outputs
		Tanks.forEach(function(tank){
			isCalculable(tank.name);
		})

		//check if we are solving new nodes
		var notCalcCons  = getNotCalcCons();
		var nci = notCalcCons.length;
		console.log("items no calculats:"+nci)
		if(nci==0) {
			console.log("\nALL ITEMS CALCULATED");
			return;
		}
		if(nci==notCalcItems) {
			//console.log(notCalcNodes);
			//console.log(notCalcCons);
			alert("System is undetermined. Some nodes/links could not get solved");
			return;
		}
		notCalcItems=nci;//update nonCalcItems

		//end iteration
		iteracio++;
	}
}

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

	//is already calculated?
	if(con.flow!=null){ 
		//console.log(con);
		//console.log("already calculated");
		return true;
	} 

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

	//mira si els inputs del node origen estan tots calculats
	var are_all_inputs_of_node_from_calc = true;
	Connections
		.filter(function(c){return c.to==con.from})
		.forEach(function(c){
			if(c.flow==null) {
				are_all_inputs_of_node_from_calc = false;
			}
		});
	//mira si els ALTRES outputs del node origen estan calculats
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
		sumInputsAndOtherOutputs(con);
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
		sumOutputsAndOtherInputs(con);
		return true;
	}

	//connection is not calculable
	return false;
}

function sumInputsAndOtherOutputs(con) {
	var sum = 0;
	Connections
		.concat(Reuse)
		.filter(function(c){return c.to==con.from})
		.forEach(function(c){ sum += c.flow; });
	Connections
		.concat(Reuse)
		.filter(function(c){return c.from==con.from})
		.forEach(function(c){  sum -= c.flow; });

	//set calc value
	con.flow=sum;
}

function sumOutputsAndOtherInputs(con) {
	var sum = 0;
	Connections
		.concat(Reuse)
		.filter(function(c){return c.to==con.to})
		.forEach(function(c){ sum -= c.flow; });
	Connections
		.concat(Reuse)
		.filter(function(c){return c.from==con.to})
		.forEach(function(c){ sum += c.flow; });

	//set calc value
	con.flow=sum;
}

//bool: is the node calculable (node:<string>)
function isCalculable(node) {
	//if its already calculated, return true
	if(getNodeByName(node).value!=null) { 
		return true;
	} 

	//get input connections with flow==null
	var inputs = Connections.filter(function(c){return c.to==node})

	var null_inputs = inputs
		.map(function(c){return c.flow})
		.filter(function(flow){return flow==null})
		.length

	if(inputs.length>0 && null_inputs==0) {
		getNodeByName(node).value=sumAllInputs(node);
		return true;
	}

	//get output connections with flow==null
	var outputs = Connections.filter(function(c){return c.from==node})

	var null_outputs = outputs
		.map(function(c){return c.flow})
		.filter(function(flow){return flow==null})
		.length

	if(outputs.length>0 && null_outputs==0) {
		getNodeByName(node).value=sumAllOutputs(node);
		return true;
	}
	/*debug
	console.log('inputs')
	console.log(inputs)
	console.log(null_inputs)
	console.log('outputs')
	console.log(outputs)
	console.log(null_outputs)
	*/
	return false;
}

function sumAllInputs(nodeName) {
	//console.log('summing all input connections of '+nodeName);
	return Connections                               //loop over connections
		.filter(function(c){return c.to==nodeName})    //connections that point to node
		.map(function(c){return c.flow})               //get flows
		.reduce(function(prev,curr){return prev+curr}) //sum flows
}

function sumAllOutputs(nodeName){
	//console.log('summing all output connections of '+nodeName);
	return Connections                               //loop over connections
		.filter(function(c){return c.from==nodeName})  //connections that point to node
		.map(function(c){return c.flow})               //get flows
		.reduce(function(prev,curr){return prev+curr}) //sum flows
}
