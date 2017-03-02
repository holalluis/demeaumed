
function calc_conc(load,uses,guests,flow) {
	/* 
		Inputs:
			- load    [mg/use]
			- uses    [uses/day]
			- guests  [persons]
			- flow    [L/day]
		Ouput:
			- Concentration = load*uses/flow [mg/L]
	*/
	return load*uses*guests/flow;
}

//add "contaminants" object with mg/L to each Connection


