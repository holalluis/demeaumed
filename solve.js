//number of not calculated nodes
function getNotCalcNodes() {
	return Nodes
		.concat(Tanks)
		.filter(function(n){return balMat(n.name)==false})
		.length
}

//number of not calculated connections
function getNotCalcCons() {
	return Connections
		.filter(function(c){
			if(balMat(c.from)==false || balMat(c.to)==false)
				return true;
			else 
				return false;
		})
		.length
}

/*
MAIN FUNCTION SOLVING THE NETWORK
*/
function solveNetwork(){

	//Reset tanks output 
	Tanks.forEach(function(tank){tank.value=null});

	//solve all easy cases: only 1 output
	(function(){
		//1. troba els nodes que tenen només un output: flow=value
		for(var i in Connections) {
			var from=Connections[i].from;
			if(getOutputs(from).length==1)
			{
				if(Connections[i].flow==null)
				{
					var flow=getNodeByName(from).value;
					if(flow!=null) Connections[i].flow=flow;
				}
			}
		}
	})();

	var notCalcItems=Infinity;//initial value that we have to get to 0

	//infinte loop for solving network
	var iteracio=1;

	while(true) {
		console.log("[+] new solver iteration ("+iteracio+")");

		//calc connection flows
		Connections.forEach(function(con){
			isConCalculable(con);
		})

		//calc tank outputs
		Tanks.forEach(function(tank){
			isCalculable(tank.name);
		})

		//check if we are solving new nodes
		var nci=getNotCalcNodes()+getNotCalcCons();
		console.log("items no calculats:"+nci)
		if(nci==0) {
			console.log("\nSUCCESS! ALL ITEMS CALCULATED");
			return;
		}
		if(nci==notCalcItems) {
			alert("ERROR! We are not solving new items (nodes and links). System is undetermined or is already calculated");
			return;
		}
		notCalcItems=nci;//update nonCalcNodes

		iteracio++;
	}
}
