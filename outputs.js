/*Symbols Mark Santana notation word file "water use equations.docx"*/
	var G		= Inputs.Services.General.guests
	var D		= Inputs.Services.General.diners
	var TFC		= Inputs.Services.Room.Toilet.flushCap
	var TUF		= Inputs.Services.Room.Toilet.useFreq
	var SDR		= Inputs.Services.Room.Sink.discRate
	var SUD		= Inputs.Services.Room.Sink.useDuration
	var SUF		= Inputs.Services.Room.Sink.useFreq
	var RDR		= Inputs.Services.Room.Shower.discRate
	var RSR		= Inputs.Services.Room.Shower.avgRate
	var RF		= Inputs.Services.Room.Shower.useFreq
	var BV		= Inputs.Services.Room.Bath.tubVol
	var BP		= Inputs.Services.Room.Bath.perc
	var BF		= Inputs.Services.Room.Bath.freq
	var PSA		= Inputs.Services.Pool.area
	var TA		= Inputs.Services.Pool.ambTemp
	var TW		= Inputs.Services.Pool.watTemp
	var W		= Inputs.Services.Pool.windVel
	var TDP		= Inputs.Services.Pool.dewPoin
	var A		= Inputs.Services.Garden.Calculation.area
	var IR		= Inputs.Services.Garden.Calculation.irrigationRate
	var NS		= Inputs.Services.Garden.Sprinkler.amount
	var vsp		= Inputs.Services.Garden.Sprinkler.speed
	var t		= Inputs.Services.Garden.Sprinkler.duration
	var MC		= Inputs.Services.Laundry.capacity
	var ML		= Inputs.Services.Laundry.loadpday
	var MPC		= Inputs.Services.Laundry.loadsPerGuest
	var LTUF	= Inputs.Services.Lobby.toiletUseFreq
	var LSUF	= Inputs.Services.Lobby.sinkUseFreq
	var VS		= Inputs.Services.Kitchen.ThreeSink.vol
	var PV		= Inputs.Services.Kitchen.ThreeSink.perc
	var KC		= Inputs.Services.Kitchen.Dishwasher.capacity
	var KD		= Inputs.Services.Kitchen.Dishwasher.loadsPdiner
	var CD		= Inputs.Services.Kitchen.watCons
	function updateInputs()
	{
		G		= Inputs.Services.General.guests
		D		= Inputs.Services.General.diners
		TFC		= Inputs.Services.Room.Toilet.flushCap
		TUF		= Inputs.Services.Room.Toilet.useFreq
		SDR		= Inputs.Services.Room.Sink.discRate
		SUD		= Inputs.Services.Room.Sink.useDuration
		SUF		= Inputs.Services.Room.Sink.useFreq
		RDR		= Inputs.Services.Room.Shower.discRate
		RSR		= Inputs.Services.Room.Shower.avgRate
		RF		= Inputs.Services.Room.Shower.useFreq
		BV		= Inputs.Services.Room.Bath.tubVol
		BP		= Inputs.Services.Room.Bath.perc
		BF		= Inputs.Services.Room.Bath.freq
		PSA		= Inputs.Services.Pool.area
		TA		= Inputs.Services.Pool.ambTemp
		TW		= Inputs.Services.Pool.watTemp
		W		= Inputs.Services.Pool.windVel
		TDP		= Inputs.Services.Pool.dewPoin
		A		= Inputs.Services.Garden.Calculation.area
		IR		= Inputs.Services.Garden.Calculation.irrigationRate
		NS		= Inputs.Services.Garden.Sprinkler.amount
		vsp		= Inputs.Services.Garden.Sprinkler.speed
		t		= Inputs.Services.Garden.Sprinkler.duration
		MC		= Inputs.Services.Laundry.capacity
		ML		= Inputs.Services.Laundry.loadpday
		MPC		= Inputs.Services.Laundry.loadsPerGuest
		LTUF	= Inputs.Services.Lobby.toiletUseFreq
		LSUF	= Inputs.Services.Lobby.sinkUseFreq
		VS		= Inputs.Services.Kitchen.ThreeSink.vol
		PV		= Inputs.Services.Kitchen.ThreeSink.perc
		KC		= Inputs.Services.Kitchen.Dishwasher.capacity
		KD		= Inputs.Services.Kitchen.Dishwasher.loadsPdiner
		CD		= Inputs.Services.Kitchen.watCons
	}
/*Symbols end*/

// :ISSUE: marker for issues. Go to issues using *
var Outputs =
{
	Room:{
		Toilet:function() { return TFC*TUF*G },    // (L/flush)*(flushes/person/day)*person = L/day
		Sink:function() { return SDR*SUD*SUF*G },  // (L/min)*(min/use/person)*(uses/day/person)*person = L/day
		Shower:function() { return RDR*RSR*RF*G }, // (L/min)*(min/use)*(uses/day/person)*person = L/day
		Bath:function() { return BV*BP*BF*G },     // m3*(% per 1)*(uses/person/day)*person = :ISSUE:
	},	
	Pool:function(){
		var PA = 630.25*Math.exp(0.0644*TA)
		var PW = 630.25*Math.exp(0.0644*TDP)
		var Evp = PSA*(PW-PA)*(0.089+(0.0782*W))*2264
		var P = 86.4*Evp
		return P //units? 
	},	
	Garden:{
		ByArea:function() { return A*IR },           // m2 * (L/min) = :ISSUE:
		BySprinklers:function() { return NS*vsp*t }, // sprinklers*(L/min)*min = :ISSUE: 
	},	
	Laundry:{
		ByLoad:function() { return MC*ML },      // (L/load)*(kg/day) = :ISSUE:
		ByPerson:function() { return MC*MPC*G }, // (L/load)*(loads/guest)*guest = :ISSUE:
	},	
	Lobby:{
		Toilet:function() { return TFC*LTUF*G },   // (L/flush)*(flushes/person/day)*person = L/day 
		Sink:function() { return SDR*SUD*LSUF*G }, // (L/min)*(min/use/person)*(uses/day/person)*person = L/day 
	},	
	Kitchen:{
		ThreeSink:function() { return VS*PV*3 },  // 3*m3*(%) = :ISSUE:
		Dishwasher:function() { return KC*KD*D }, // (L/load)*(loads/diner)*diners = L :ISSUE:
		PerPerson:function() { return CD*D },     // (L/person/day)*diners = ?? :ISSUE:
	},	
}
