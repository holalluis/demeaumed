/*Symbols*/
	var G		= Global.Services.General.guests
	var D		= Global.Services.General.diners
	var TFC		= Global.Services.Room.Toilet.flushCap
	var TUF		= Global.Services.Room.Toilet.useFreq
	var SDR		= Global.Services.Room.Sink.discRate
	var SUD		= Global.Services.Room.Sink.useDuration
	var SUF		= Global.Services.Room.Sink.useFreq
	var RDR		= Global.Services.Room.Shower.discRate
	var RSR		= Global.Services.Room.Shower.avgRate
	var RF		= Global.Services.Room.Shower.useFreq
	var BV		= Global.Services.Room.Bath.tubVol
	var BP		= Global.Services.Room.Bath.perc
	var BF		= Global.Services.Room.Bath.freq
	var PSA		= Global.Services.Pool.area
	var TA		= Global.Services.Pool.ambTemp
	var TW		= Global.Services.Pool.watTemp
	var W		= Global.Services.Pool.windVel
	var TDP		= Global.Services.Pool.dewPoin
	var A		= Global.Services.Garden.Calculation.area
	var IR		= Global.Services.Garden.Calculation.irrigationRate
	var NS		= Global.Services.Garden.Sprinkler.amount
	var vsp		= Global.Services.Garden.Sprinkler.speed
	var t		= Global.Services.Garden.Sprinkler.duration
	var MC		= Global.Services.Laundry.capacity
	var ML		= Global.Services.Laundry.loadpday
	var MPC		= Global.Services.Laundry.loadsPerGuest
	var LTUF	= Global.Services.Lobby.toiletUseFreq
	var LSUF	= Global.Services.Lobby.sinkUseFreq
	var VS		= Global.Services.Kitchen.ThreeSink.vol
	var PV		= Global.Services.Kitchen.ThreeSink.perc
	var KC		= Global.Services.Kitchen.Dishwasher.capacity
	var KD		= Global.Services.Kitchen.Dishwasher.loadsPdiner
	var CD		= Global.Services.Kitchen.watCons
	function updateInputs()
	{
		G		= Global.Services.General.guests
		D		= Global.Services.General.diners
		TFC		= Global.Services.Room.Toilet.flushCap
		TUF		= Global.Services.Room.Toilet.useFreq
		SDR		= Global.Services.Room.Sink.discRate
		SUD		= Global.Services.Room.Sink.useDuration
		SUF		= Global.Services.Room.Sink.useFreq
		RDR		= Global.Services.Room.Shower.discRate
		RSR		= Global.Services.Room.Shower.avgRate
		RF		= Global.Services.Room.Shower.useFreq
		BV		= Global.Services.Room.Bath.tubVol
		BP		= Global.Services.Room.Bath.perc
		BF		= Global.Services.Room.Bath.freq
		PSA		= Global.Services.Pool.area
		TA		= Global.Services.Pool.ambTemp
		TW		= Global.Services.Pool.watTemp
		W		= Global.Services.Pool.windVel
		TDP		= Global.Services.Pool.dewPoin
		A		= Global.Services.Garden.Calculation.area
		IR		= Global.Services.Garden.Calculation.irrigationRate
		NS		= Global.Services.Garden.Sprinkler.amount
		vsp		= Global.Services.Garden.Sprinkler.speed
		t		= Global.Services.Garden.Sprinkler.duration
		MC		= Global.Services.Laundry.capacity
		ML		= Global.Services.Laundry.loadpday
		MPC		= Global.Services.Laundry.loadsPerGuest
		LTUF	= Global.Services.Lobby.toiletUseFreq
		LSUF	= Global.Services.Lobby.sinkUseFreq
		VS		= Global.Services.Kitchen.ThreeSink.vol
		PV		= Global.Services.Kitchen.ThreeSink.perc
		KC		= Global.Services.Kitchen.Dishwasher.capacity
		KD		= Global.Services.Kitchen.Dishwasher.loadsPdiner
		CD		= Global.Services.Kitchen.watCons
	}
/*Symbols end*/

var Formulas =
{
	Room:{
		Toilet:function() { return TFC*TUF*G },
		Sink:function() { return SDR*SUD*SUF*G },
		Shower:function() { return RDR*RSR*RF*G },
		Bath:function() { return BV*BP*BF*G },
	},	
	Pool:function(){
		var PA = 630.25*Math.exp(0.0644*TA)
		var PW = 630.25*Math.exp(0.0644*TDP)
		var Evp = PSA*(PW-PA)*(0.089+(0.0782*W))*2264
		var P = 86.4*Evp
		return P
	},	
	Garden:{
		ByArea:function() { return A*IR },
		BySprinklers:function() { return NS*vsp*t },
	},	
	Laundry:{
		ByLoad:function() { return MC*ML },
		ByPerson:function() { return MC*MPC*G },
	},	
	Lobby:{
		Toilet:function() { return TFC*LTUF*G },
		Sink:function() { return SDR*SUD*LSUF*G },
	},	
	Kitchen:{
		ThreeSink:function() { return VS*PV*3 },
		Dishwasher:function() { return KC*KD*D },
		PerPerson:function() { return CD*D },
	},	
}
