<meta charset=utf-8>
<title>SambaNet</title>
<link rel=stylesheet href=css.css>

<!--imports-->
<script src=inputs.js></script>
<script src=info.js></script>
<script src=outputs.js></script>

<!--utils-->
<script>
	/** return 3.999,4 instead of 3999.4*/
	function format(number)
	{
		var str = new Intl.NumberFormat('en-IN',{maximumFractionDigits:1}).format(number);
		if(str=="NaN" || !isFinite(number)) return null 
		return str;
	}
</script>
