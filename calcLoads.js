
function calc_conc(load,uses,guests,flow) {
	/* inputs
		load    mg/use
		uses    uses/person/day
		guests  persons
		flow    L/day

		Concentration = load*uses*guests/flow = mg/L
	*/
	return load*uses*guests/flow;
}

loads: table loads
uses: from inputs
guests: from inputs
flows: from Connections.flow
