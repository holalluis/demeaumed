var Outputs =
{
	Room:{
		Toilet:function(){ 
			var TFC = Inputs.Services.Room.Toilet.flushCap // L/flush
			var TUF = Inputs.Services.Room.Toilet.useFreq  // flush/person/day
			var G   = Inputs.Services.General.guests       // person
			return TFC*TUF*G 							   // L/day
		},
		Sink:function() { 
			var SDR = Inputs.Services.Room.Sink.discRate    // L/min
			var SUD = Inputs.Services.Room.Sink.useDuration // min/use
			var SUF = Inputs.Services.Room.Sink.useFreq     // uses/day/person
			var G   = Inputs.Services.General.guests        // person
			return SDR*SUD*SUF*G 							// L/day
		},
		Shower:function(){
			var RDR = Inputs.Services.Room.Shower.discRate // L/min
			var RSR = Inputs.Services.Room.Shower.avgRate  // min/use
			var RF  = Inputs.Services.Room.Shower.useFreq  // uses/day/person
			var G   = Inputs.Services.General.guests  // person
			return RDR*RSR*RF*G						  // L/day
		},
		Bath:function(){
			var BV = Inputs.Services.Room.Bath.tubVol    // L
			var BP = Inputs.Services.Room.Bath.perc      // %/use
			var BF = Inputs.Services.Room.Bath.useFreq   // uses/person/day
			var G  = Inputs.Services.General.guests // person
			return 0.01*BV*BP*BF*G;                 // L/day
		},
	},	
	Pool:{
		Evaporation:function(){
			var airtemp = Inputs.Services.Pool.ambTemp // ºC
			var wtemp   = Inputs.Services.Pool.watTemp // ºC
			var humid   = Inputs.Services.Pool.humid   // %
			var area    = Inputs.Services.Pool.area    // m2

			//First, get dew point
			var dew = airtemp - 0.2*(100-humid);  //ºC
			//Then, get the vapor pressures
			var pw = 0.7198*Math.exp(0.058*wtemp);
			var pa = 0.0323*humid+0.0953*dew-2.374;
			//Finally caluculate evaporation assuming water density is 1 kg/L
			var evp = 0.0000416*area*(pw-pa)*0.8; // L/s
			var p = evp*60*60*24; // L/day
			return p
		},
		Flow:function(){
			var A = Inputs.Services.Pool.area         // m2
			var D = Inputs.Services.Pool.avgDepth     // m
			var P = Inputs.Services.Pool.prcDivToFlow // %/day
			return 10*A*D*P; // L/day (10 is 1000/100, to convert m3 to L and percentage to rate per 1)
		}
	},	
	Garden:{
		Area:function(){
			var A  = Inputs.Services.Garden.Area.area           // m2
			var IR = Inputs.Services.Garden.Area.irrigationRate // L/m2/day
			return A*IR;								   // L/day
		},
		Sprinklers:function(){
			var NS  = Inputs.Services.Garden.Sprinklers.amount   // sprinklers
			var vsp = Inputs.Services.Garden.Sprinklers.flow     // L/min/sprinklers
			var t   = Inputs.Services.Garden.Sprinklers.duration // min/day
			return NS*vsp*t;                                     // L/day
		},
	},
	Laundry:{
		Laundry:function(){
			var MC  = Inputs.Services.Laundry.capacity      // L/load
			var MPC = Inputs.Services.Laundry.loadsDayGuest // loads/day/person
			var G   = Inputs.Services.General.guests        // person
			return MC*MPC*G;                                // L/day
		},
	},	
	Lobby:{
		Toilet:function(){ 
			var TFC = Inputs.Services.Room.Toilet.flushCap  // L/flush
			var TUF = Inputs.Services.Lobby.Toilet.useFreq  // flushes/person/day
			var G   = Inputs.Services.General.guests        // person
			return TFC*TUF*G                                // L/day
		},
		Sink:function(){ 
			var SDR = Inputs.Services.Room.Sink.discRate    // L/min
			var SUD = Inputs.Services.Room.Sink.useDuration // min/use
			var SUF = Inputs.Services.Lobby.Sink.useFreq    // uses/day/person
			var G   = Inputs.Services.General.guests        // person
			return SDR*SUD*SUF*G                            // L/day
		},
	},	
	Kitchen:{
		Sink:function(){
			var VS = Inputs.Services.Kitchen.Sink.sinkVol // L
			var PV = Inputs.Services.Kitchen.Sink.perc    // %/use
			var UF = Inputs.Services.Kitchen.Sink.useFreq // uses/day/person
			var G  = Inputs.Services.General.guests       // person
			return 0.01*VS*PV*UF*G;	                      // L/day
		},
		Dishwasher:function()
		{
			var KC = Inputs.Services.Kitchen.Dishwasher.capacity      // L/load
			var KD = Inputs.Services.Kitchen.Dishwasher.loadsDayDiner // loads/day/diner
			var D  = Inputs.Services.General.diners                   // diners
			return KC*KD*D
		},
	},	
	TOTAL:function(obj)
	//sum all functions inside OUTPUTS
	{
		obj = obj || this; //object iterated. this (Outputs) by default
		var sum=0;
		for(var field in obj)
		{
			//except TOTAL
			if(field=="TOTAL")continue;

			//if object: recursive call
			//if function: sum
			//else: error
			if(typeof(obj[field])=='object')
				sum+=this.TOTAL(obj[field])
			else if(typeof(obj[field])=='function')
				sum+=obj[field]()
			else
			{
				alert('error in Outputs.TOTAL function');
				return "error"
			}
		}
		return sum
	}
}
