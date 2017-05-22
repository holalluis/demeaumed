/*
	Solve contaminant concentration
*/

//get a string array array of contaminant names
var contaminants=[];
for(var node in Loads) { //Loads object from "js/loads.js"
	for(var contaminant in Loads[node].contaminants) {
		contaminants.push(contaminant);
	}
	break;
}

//Add a "contaminants" object to each connection
Connections.concat(Reuse).forEach(function(conn) {
	conn.contaminants = { };
	contaminants.forEach(function(c){
		conn.contaminants[c]=null; //initial value
	});
});

//now we have all we need to calculate concentrations at SERVICES connections at least
//first iteration: services in loads
function set_load(node,contaminant){
	/* calculate loads of SERVICES  
		Inputs:
			- load    [mg/use]
			- uses    [uses/day]
			- guests  [persons]
		Ouput:
			- load = load*uses [mg/day]
	*/
	//1: get mg/use
	var mg_per_use=Loads[node].contaminants[contaminant];
	//2: get uses per day
	var uses=Loads[node].uses;
	//3: calculate load (mg/day)
	var load = mg_per_use * uses;

	//calc total out flow from node
	var out_flow=0;
	Connections.concat(Reuse)
		.filter(conn=>{return conn.from==node})
		.forEach(conn=>{out_flow+=conn.flow})

	//4: set the load (mg/day) for all output connections from node "node"
	if(out_flow) {
		Connections.concat(Reuse)
			.filter(function(conn){return conn.from==node})
			.forEach(function(conn){
				//normal connections
				conn.contaminants[contaminant]=conn.flow*load/out_flow;
				//reuse connections have a "tec" string defined
				if(conn.tec) {
					var tech=Technologies.filter(function(t){return t.name==conn.tec})[0];
					var removal=tech.removal[contaminant]/100;
					console.log("Tech: "+conn.tec+", "+contaminant+"->"+removal+"%");
					conn.contaminants[contaminant] *= (1-removal);
				}
			});
	}
}

//go through known services and contaminants
for(var node in Loads){
	contaminants.forEach(function(contaminant){
		set_load(node,contaminant);
	});
}

//2nd iteration
function calc_load_propagated(nodeName,contaminant) {
	
	var not_calculable=false;

	//sum all loads that are connected to nodeName
	var load=0;
	Connections.concat(Reuse)
		.filter(function(conn){return conn.to==nodeName})
		.forEach(function(conn){
			//if input is null we cannot continue
			if(conn.contaminants[contaminant]==null){
				not_calculable=true;
			}
			else{
				load+=conn.contaminants[contaminant];
			}
	});//now we have the total load (mg/day)

	if(not_calculable){return}

	//since at this point we know all flows...
	//we can compute total flow, so we can calculate concentration
	var flow=0;
	Connections.concat(Reuse)
		.filter(conn=>{return conn.from==nodeName})
		.forEach(function(conn) {
			flow+=conn.flow
	});

	//calculate the concentration and multiply by the flow of the connection to set the load
	Connections.concat(Reuse)
		.filter(function(conn){return conn.from==nodeName})
		.filter(function(conn){return conn.contaminants[contaminant]==null})
		.forEach(function(conn){
			
			//calc mg/day
			conn.contaminants[contaminant] = (flow==0) ? 0 : load/flow*conn.flow;

			//reuse connections have a "tec" string defined
			if(conn.tec) {
				var tech=Technologies.filter(function(t){return t.name==conn.tec})[0];
				var removal=tech.removal[contaminant]/100;
				console.log("Tech: "+conn.tec+", "+contaminant+"->"+removal+"%");
				conn.contaminants[contaminant] *= (1-removal);
			}

		});
}

//check if we are done
function isAllCalculated() {
	var All_connections = Connections.concat(Reuse);
	for(var i in All_connections) {
		for(var co in All_connections[i].contaminants) {
			if(All_connections[i].contaminants[co]==null) {
				return false;
			}
		}
	}
	return true;
}

//main loop
while(!isAllCalculated()) {
	console.log("New iteration");
	Nodes.concat(Tanks).forEach(function(node){
		contaminants.forEach(function(contaminant) {
			calc_load_propagated(node.name,contaminant);
		});
	})
}

console.log('END - All loads calculated');
