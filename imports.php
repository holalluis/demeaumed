<meta charset=utf-8>
<title>SambaNet</title>
<link rel=stylesheet href=css.css>

<!--imports-->
<script src=js/cookies.js></script>
<script src=js/inputs.js></script>
<script src=js/info.js></script>
<script src=js/outputs.js></script>
<script src=js/connections.js></script>

<!--utils-->
<script>
	/** return 3.999,4 instead of 3999.4*/
	function format(number)
	{
		var str = new Intl.NumberFormat('en-EN',{maximumFractionDigits:2}).format(number);
		if(str=="NaN" || !isFinite(number)){return number} 
		return str
	}
	
	/** Set cookies **/
	function updateCookies()
	{
		setCookie("Inputs",JSON.stringify(Inputs));
		setCookie("Connections",JSON.stringify(Connections));
	}

	/** Read cookies and update objects **/
	(function updateFromCookies()
	{
		if(getCookie('Inputs')!=null) Inputs=JSON.parse(getCookie('Inputs'))
		if(getCookie('Connections')!=null) Connections=JSON.parse(getCookie('Connections'))
	})(); //execute it

</script>
