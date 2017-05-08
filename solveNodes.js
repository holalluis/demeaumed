/*
	Solving the Network
	means finding a flow for each node
	Format:
	Nodes={"name":{value:<number>},}
	Connections=[{from:<string>,to:<string>,tec:<string>,flow:<number>},]
*/

//Add tanks from 'js/tanks.js' they are nodes as well (user-created)
Tanks.forEach(function(tank){tank.value=null})
Nodes_and_Tanks=Nodes.concat(Tanks);

//bool: is the node calculable (node:<string>)
function isCalculable(node) {
	if(getNodeByName(node).value!=null) { return true } //if its already calculated, return true

	//1. check if all inputs have a value
	var allInputs=true;
	var inputs=getInputs(node);
	for(var i in inputs) {
		var input=inputs[i];
		if(getNodeByName(input).value===null) {
			allInputs=false;
			break;
		}
	}
	if(allInputs) return true;

	//2. check if all outputs have a value
	var allOutputs=true;
	var outputs=getOutputs(node);
	for(var i in outputs) 
	{
		var output=outputs[i];
		if(getNodeByName(output).value===null) 
		{
			allOutputs=false;
			break;
		}
	}
	if(allOutputs) return true;

	//if we are here, node is not calculable
	return false;
}

//is calculable because of "inputs" or "outputs" of false
function causeOfCalc(node) {
	//node not calculable
	if(!isCalculable(node))return false;

	//check inputs, and no need to check outputs because it's calculable
	var cause_inputs=true;
	var inputs=getInputs(node);
	for(var i in inputs) {
		if(getNodeByName(inputs[i]).value===null) {
			cause_inputs=false;
			break;
		}
	}
	if(inputs.length>0 && cause_inputs)
		return "inputs";
	else
		return "outputs";
}

//find the flow through the node (property "value" from the node object)
//node:<string>
function calculate(node) {
	//make sure node has a null value
	if(getNodeByName(node).value!=null) {console.log('Warning! Attempted to calculate an already calculated node');return}

	//make sure node is calculable
	if(!isCalculable(node)) {console.log('Warning! Attempted to calculate a non-calculable node');return}
	
	//we'll sum all flows from all inputs here
	var value=0;

	//find out cause of calculability: "inputs" or "outputs" or false
	var cause=causeOfCalc(node); 
	if(!cause){console.log('error, node is not calculable');return;}

	if(cause=="inputs") {
		//find all inputs and flows
		var inputs=getInputs(node); //array of strings

		//input flows
		for(var i in inputs) {
			var input=inputs[i];//string
			var input_outputs=getOutputs(input).length;//int
			//TODO instead weight each input!
			var input_flow=getNodeByName(input).value/input_outputs;
			value+=input_flow;
		}
	}
	if(cause=="outputs") {
		//find all outputs
		var outputs=getOutputs(node); //array of strings

		//the flow will be sum of the values of the output nodes
		for(var i in outputs) {
			var output=outputs[i];//string
			var output_flow=getNodeByName(output).value;
			value+=output_flow;
		}
	}

	//end step: set the value
	getNodeByName(node).value=value;
	console.log("  "+node+" flow calculated ("+value+")");
}

//get the number of non calculable nodes to see if we are improving each calc iteration
function getNonCalcNodes() {
	var n=0;
	for(var i in Nodes_and_Tanks) if(!isCalculable(Nodes_and_Tanks[i].name)) n++;
	return n;
}

//---------------------------------------------------------
//main function solving the network
(function test(){
	var nonCalcNodes=Infinity;//initial value that we have to get to 0

	//infinte loop for solving network
	while(true)
	{
		console.log("[+] new solver iteration");
		for(var i in Nodes_and_Tanks)
		{
			//console.log(node);
			//console.log("    Inputs: "+getInputs(node));
			//console.log("    Outputs: "+getOutputs(node));
			if(Nodes_and_Tanks[i].value===null && isCalculable(Nodes_and_Tanks[i].name))
			{
				//console.log(node);
				//console.log("cause: "+causeOfCalc(node));
				calculate(Nodes_and_Tanks[i].name);
			}
		}
		//check if we are solving new nodes
		var nci=getNonCalcNodes();//non calc nodes of this iteration
		if(nci==0) {
			console.log("\nSUCCESS! NETWORK CALCULATION FINISHED");
			return;
		}
		if(nci==nonCalcNodes)
		{
			alert("ERROR! We are not solving new nodes. Network probably contains loops!");
			return;
		}
		nonCalcNodes=nci;//update nonCalcNodes
	}
})();
