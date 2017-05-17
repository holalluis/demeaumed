//fes un balanç de matèria a tots els nodes 
//per comprovar si la xarxa està ben calculada

//BM: E+G=S+A -> G=0, A=0 -> E=S
function balMat(node) {
	//inputs: node is a string

	//mass balance will be always incorrect for extreme nodes (input & output), so return true
	var n_ins = Connections
		.concat(Reuse)
		.filter(function(con){return con.to==node})
		.length
	
	var n_outs = Connections
		.concat(Reuse)
		.filter(function(con){return con.from==node})
		.length
	
	if(n_ins==0 || n_outs==0)
	{
		//console.log("Skipping mass balance for node "+node)
		return true;
	}

	var E=null, S=null;	

	//1. suma les connexions que apunten a node
	Connections
		.filter(function(c){return c.to==node})
		.forEach(function(c){E+=c.flow});

	//2. troba les connexions que surten de node
	Connections
		.filter(function(c){return c.from==node})
		.forEach(function(c){S+=c.flow});

	//return if E==S
	return (E!=null && S!=null && E==S);
}
