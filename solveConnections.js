//Format:
//Nodes={"name":{value:<number>},}
//Connections=[{from,to,tec,flow},]

//problema: find "flow" from all connections
//at this point we know all nodes outputs
(function(){

//1. troba els nodes que tenen només un output: flow=value
for(var i in Connections)
{
	var from=Connections[i].from;
	if(getOutputs(from).length==1)
	{
		Connections[i].flow=Nodes[from].value;
	}
}

//2. troba els nodes que:
//a. només tenen un input
//b. tenen tots els outputs calculats
//--> flow=suma
for(var i in Connections)
{
	var to=Connections[i].to;
	if(getInputs(to).length==1)
	{
		for(var j in Connections)
		{
			if(Connections[j].from==to)
			{
				Connections[i].flow+=Connections[j].flow;
			}
		}
	}
}

})();
