//fes un balanç de matèria a tots els nodes 
//per comprovar si la xarxa està ben calculada

//BM: E+G=S+A -> G=0, A=0 -> E=S
function balMat(node) //node: string
{
	var E=0,S=0;

	//1. troba les connexions que apunten a node
	for(var i in Connections)
	{
		var to=Connections[i].to;
		if(to==node)
		{
			E+=Connections[i].flow;
		}
	}

	//2. troba les connexions que surten de node
	for(var i in Connections)
	{
		var from=Connections[i].from;
		if(from==node)
		{
			S+=Connections[i].flow;
		}
	}

	//return
	var ret=E==S;
	if(!ret) {
		console.log(node+' -  E: '+E+', S: '+S)
	}
	return ret;
}

//fes un balanç a tots els nodes
function balanços()
{
	for(var node in Nodes) 
	{
		var bal=balMat(node);
	}
}
