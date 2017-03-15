//Solve the Network
//means finding a flow for each connection or node
//Construct new object "Network"
//Format:
//Nodes={"name":{value:<number>},}
//Connections=[{from:<string>,to:<string>,tec:<string>,flow:<number>},]

var Network={
	Nodes:Nodes,             //from 'js/nodes.js'
	Connections:Connections, //from 'js/connections.js'
};
//Add tanks from 'js/tanks.js' they are nodes as well (user-created)
(function(){
	for(var i in Tanks){
		var name=Tanks[i].name;
		var node={value:null};
		if(Nodes[name]===undefined)
		{
			Nodes[name]=node;
		}
	}
})();

//get a string array of nodes connected to node:<string>
function getInputs(node){ 
	var nodes=[];
	for(var i in Connections){
		if(node==Connections[i].to){
			nodes.push(Connections[i].from);
		}
	}
	return nodes; //string array
}

//get a string array of nodes connected to node:<string>
function getOutputs(node){ 
	var nodes=[];
	for(var i in Connections){
		if(node==Connections[i].from){
			nodes.push(Connections[i].to);
		}
	}
	return nodes; //string array
}

//bool: is the node calculable (node:<string>)
function isCalculable(node) {
	if(Nodes[node].value!=null) { return true } //if its already calculated, return true

	//1. check if all inputs have a value
	var allInputs=true;
	var inputs=getInputs(node);
	for(var i in inputs) 
	{
		var input=inputs[i];
		if(Nodes[input].value===null) 
		{
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
		if(Nodes[output].value===null) 
		{
			allOutputs=false;
			break;
		}
	}
	if(allOutputs) return true;
	//if we reach here, node is not calculable
	return false;
}

//is calculable because of "inputs" or "outputs" of false
function causeOfCalc(node) {
	//node not calculable
	if(!isCalculable(node))return false;

	//check inputs, and no need to check outputs because it's calculable
	var cause_inputs=true;
	var inputs=getInputs(node);
	for(var i in inputs)
	{
		if(Nodes[inputs[i]].value===null)
		{
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
function calculate(node) 
{
	//make sure node has a null value
	if(Nodes[node].value!=null) {console.log('Warning! Attempted to calculate an already calculated node');return}

	//make sure node is calculable
	if(!isCalculable(node)) {console.log('Warning! Attempted to calculate a non-calculable node');return}
	
	//we'll sum all flows from all inputs here
	var value=0;

	//find out cause of calculability: "inputs" or "outputs" or false
	var cause=causeOfCalc(node); 
	if(!cause){console.log('error, node is not calculable');return;}

	if(cause=="inputs")
	{
		//find all inputs and flows
		var inputs=getInputs(node); //array of strings

		//input flows
		for(var i in inputs)
		{
			var input=inputs[i];//string
			var input_outputs=getOutputs(input).length;//int
			var input_flow=Nodes[input].value/input_outputs;
			value+=input_flow;
		}
	}
	if(cause=="outputs")
	{
		//find all outputs
		var outputs=getOutputs(node); //array of strings

		//the flow will be sum of the values of the output nodes
		for(var i in outputs)
		{
			var output=outputs[i];//string
			var output_flow=Nodes[output].value;
			value+=output_flow;
		}
	}

	//end step: set the value
	Nodes[node].value=value;
	console.log("  "+node+" flow calculated ("+value+")");
}

//get the number of non calculable nodes to see if we are improving each calc iteration
function getNonCalcNodes() 
{
	var n=0;
	for(var node in Nodes) if(!isCalculable(node)) n++;
	return n;
}

//---------------------------------------------------------
//main function that solves the network
(function test(){
	var nonCalcNodes=Infinity;//initial value that we have to get to 0
	//infinte loop for solving network
	while(true)
	{
		console.log("[+] new solver iteration");
		for(var node in Nodes)
		{
			//console.log(node);
			//console.log("    Inputs: "+getInputs(node));
			//console.log("    Outputs: "+getOutputs(node));
			if(Nodes[node].value===null && isCalculable(node))
			{
				//console.log(node);
				//console.log("cause: "+causeOfCalc(node));
				calculate(node);
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
			console.log("ERROR! We are not solving new nodes. Network probably contains loops!");
			return;
		}
		nonCalcNodes=nci;
	}
})();
