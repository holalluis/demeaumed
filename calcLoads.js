
//get array of contaminants (strings)
//and add "contaminants" object that will have inside mg/L for each 
var contaminants=[];
for(var node in Loads) { //Loads defined in "js/loads.js"
	for(var contaminant in Loads[node].contaminants) {
		contaminants.push(contaminant);
	}
	break;
}
Connections.forEach(function(connection) {
	connection.contaminants={};
	contaminants.forEach(function(contaminant){
		connection.contaminants[contaminant]=undefined; //initial value
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
	//4: set the load for all connections from node
	for(var i in Connections){
		if(Connections[i].from==node){
			Connections[i].contaminants[contaminant]=load;
		}
	}
}

//go through services and contaminants
for(var node in Loads){
	contaminants.forEach(function(contaminant){
		set_load(node,contaminant);
	});
}

//2nd iteration
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

function calc_load_propagated(nodeName,contaminant)
{
	//sum all loads that are connected to nodeName
	var load=0;
	Connections.forEach(function(connection){
		if(connection.to==nodeName){
			//if input is undefined we cannot continue
			if(connection.contaminants[contaminant]==undefined){
				return;
			}
			else{
				load+=connection.contaminants[contaminant];
			}
		}
	});//now we have the total load

	//at this point we know all flows
	var flow=0;
	Connections.forEach(function(connection)
	{
		if(connection.from==nodeName){flow+=connection.flow}
	});//now we have the total flow, so we can calculate concentration

	//here we calculate the concentration and multiply by the flow of the connection to set the load
	//only if not defined
	Connections.forEach(function(connection){
		if(connection.from==nodeName){
			if(connection.contaminants[contaminant]==undefined){
				connection.contaminants[contaminant]=load/flow*connection.flow;
			}
		}
	});
}

//check if we are done
function isAllCalculated()
{
	for(var i in Connections)
	{
		for(var contaminant in Connections[i].contaminants)
		{
			if(Connections[i].contaminants[contaminant]==undefined)
			{
				return false;
			}
		}
	}
	return true;
}

//main loop
while(!isAllCalculated())
{
	console.log("New iteration");
	for(var node in Nodes)
	{
		contaminants.forEach(function(contaminant)
		{
			calc_load_propagated(node,contaminant);
		});
	}
}

console.log('SUCCESS! All loads calculated');
