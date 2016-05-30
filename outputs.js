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
			var TW  = Inputs.Services.Pool.watTemp // ºC
			var TDP = Inputs.Services.Pool.dewPoin // ºC
			var PSA = Inputs.Services.Pool.area    // m2
			var AT  = Inputs.Services.Pool.ambTemp // ºC
			var W   = Inputs.Services.Pool.windVel // m/s

			var PA = 630.25*Math.exp(0.0644*TW);  //Vapor pressure water
			var PW = 630.25*Math.exp(0.0644*TDP); //Vapor pressure dp
			var Evp = PSA*(PW-PA)*(0.089+(0.0782*W))*2264.76;
			var P = Evp*60*60*24/1000;

			return 0 //units? 
		},
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
	Laundry:function(){
		var MC  = Inputs.Services.Laundry.capacity      // L/load
		var MPC = Inputs.Services.Laundry.loadsDayGuest // loads/day/person
		var G   = Inputs.Services.General.guests        // person
		return MC*MPC*G;                                // L/day
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
	Total:function()
	{
		return 0 
			+this.Room.Toilet()
			+this.Room.Sink()
			+this.Room.Shower()
			+this.Room.Bath()
			+this.Pool.Evaporation()
			+this.Garden.Area()
			+this.Garden.Sprinklers()
			+this.Laundry()
			+this.Lobby.Toilet()
			+this.Lobby.Sink()
			+this.Kitchen.Sink()
			+this.Kitchen.Dishwasher()
	}
}
