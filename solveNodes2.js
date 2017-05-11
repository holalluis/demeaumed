/*
Solving the Network
means finding a flow for each node
*/

//bool: is the node calculable (node:<string>)
function isCalculable(node) {
	//if its already calculated, return true
	if(getNodeByName(node).value!=null) { return true } 

	//find null inputs
	var inputs = Connections
		.filter(function(c){return c.to==node})
	var null_inputs = inputs
		.map(function(c){return c.flow})
		.filter(function(flow){return flow==null})
		.length
	if(inputs.length>0 && null_inputs==0)
	{
		getNodeByName(node).value=sumAllInputs(node);
		return true;
	}
	//find null outputs
	var outputs = Connections
		.filter(function(c){return c.from==node})
	var null_outputs = outputs
		.map(function(c){return c.flow})
		.filter(function(flow){return flow==null})
		.length
	if(outputs.length>0 && null_outputs==0)
	{
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
	return Connections      //loop over connections
		.filter(function(c){return c.to==nodeName})    //connections that point to node
		.map(function(c){return c.flow})               //get flows
		.reduce(function(prev,curr){return prev+curr}) //sum flows
}

function sumAllOutputs(nodeName){
	//console.log('summing all output connections of '+nodeName);
	return Connections      //loop over connections
		.filter(function(c){return c.from==nodeName})    //connections that point to node
		.map(function(c){return c.flow})               //get flows
		.reduce(function(prev,curr){return prev+curr}) //sum flows
}
