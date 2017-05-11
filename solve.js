
//number of not calculated nodes
function getNotCalcNodes() {
	return Nodes
		.concat(Tanks)
		.filter(function(n){return n.value==null})
		.length
}

//number of not calculated connections
function getNotCalcCons() {
	return Connections
		.filter(function(c){return c.flow==null})
		.length
}

/*
MAIN FUNCTION SOLVING THE NETWORK
*/
function solveNetwork(){

	//Add tanks from 'js/tanks.js' they are nodes as well (user-created)
	Tanks.forEach(function(tank){tank.value=null});

	//solve all easy cases: only 1 output
	(function(){
		//1. troba els nodes que tenen només un output: flow=value
		for(var i in Connections)
		{
			var from=Connections[i].from;
			if(getOutputs(from).length==1)
			{
				if(Connections[i].flow==null)
				{
					var flow = getNodeByName(from).value
					console.log(from+": "+flow)
					Connections[i].flow = (flow==null) ? null : flow;
				}
			}
		}
	})();

	var notCalcItems=Infinity;//initial value that we have to get to 0

	//infinte loop for solving network
	iteracio=1;

	while(true) {
		console.log("[+] new solver iteration ("+iteracio+")");

		Connections.forEach(function(con){
			isConCalculable(con);
		})

		//calcula nous nodes
		Nodes.concat(Tanks).forEach(function(node){
			isCalculable(node.name);
		})

		//check if we are solving new nodes
		var nci=getNotCalcNodes()+getNotCalcCons();
		console.log("items no calculats:"+nci)
		if(nci==0) {
			console.log("\nSUCCESS! ALL ITEMS CALCULATED");
			return;
		}
		if(nci==notCalcItems) {
			alert("ERROR! We are not solving new items (nodes and links). System is undetermined");
			return;
		}
		notCalcItems=nci;//update nonCalcNodes

		iteracio++;
	}
}
