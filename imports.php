<meta charset=utf-8>
<title>SambaNet</title>
<link rel=stylesheet href=css.css>
<link rel="icon" href="img/favicon.ico" type="image/x-icon">
<!--imports-->
<script src="js/cookies.js"></script>
<script src="js/inputs.js"></script>
<script src="js/info.js"></script>
<script src="js/outputs.js"></script>
<script src="js/connections.js"></script>
<script src="js/tanks.js"></script>
<script src="js/reuse.js"></script>
<script src="js/technologies.js"></script>

<!--funció per fer balanç E+G=S+A-->
<script src="balancMateria.js"></script>

<!--php utils--><?php include'utils.php'?>

<!--js utils-->
<script>
	/** return 3.999,4 instead of 3999.4*/
	function format(number) {
		var str = new Intl.NumberFormat('en-EN',{maximumFractionDigits:2}).format(number)
		if(str=="NaN" || !isFinite(number)){return number} 
		return str
	}

	//get a node object by its name
	function getNodeByName(name) {
		var node = null;
		Nodes_and_Tanks=Nodes.concat(Tanks);
		for(var i in Nodes_and_Tanks) {
			if(name==Nodes_and_Tanks[i].name) {
				node=Nodes_and_Tanks[i];
				break;
			}
		}
		return node;
	}

	/*for network solving functions*/
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
	/*for network solving functions*/
	
	/** Set cookies **/
	function updateCookies() {
		setCookie("Inputs",JSON.stringify(Inputs));
		setCookie("Connections",JSON.stringify(Connections));
		setCookie("Tanks",JSON.stringify(Tanks));
		setCookie("Reuse",JSON.stringify(Reuse));
	}

	/** Read cookies and update objects **/
	(function updateFromCookies() {
		if(getCookie('Inputs')!=null) {      Inputs=JSON.parse(getCookie('Inputs')) }
		if(getCookie('Connections')!=null) { Connections=JSON.parse(getCookie('Connections')) }
		if(getCookie('Tanks')!=null) {       Tanks=JSON.parse(getCookie('Tanks')) }
		if(getCookie('Reuse')!=null) {       Reuse=JSON.parse(getCookie('Reuse')) }
	})();//execute it
</script>

<!--update nodes after cookies-->
<script src="js/nodes.js"></script>

