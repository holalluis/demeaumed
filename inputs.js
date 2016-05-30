/** All Inputs for Sambanet **/
var Inputs =
{
	Services:{
		General:{
			guests:500,  // number of guests
			diners:1000, // number of diners
		},
		Room:{
			Toilet:{
				flushCap:6, // flush capacity (L/flush)
				useFreq:9,  // use frequency (uses/day/person)
			},
			Sink:{
				discRate:8,      // sink discharge rate (L/min)
				useDuration:0.3, // min/use
				useFreq:2.9,     // uses/day/person
			},
			Shower:{
				discRate:9, // shower discharge rate (L/min)
				avgRate:8,  // average shower rate (min/use)
				useFreq:1,  // (uses/day/person)
			},
			Bath:{
				tubVol:0.2,   // bathtub volume (L)
				perc:80,      // % filled by use (%/use)
				useFreq:0.05, // (uses/person/day)
			},
		},
		Pool:{
			area:20,    // Surface Area (m2)
			ambTemp:22, // Ambient Temp (ºC)
			watTemp:20, // Water Temp (ºC)
			windVel:1,  // Wind Velocity (m/s)
			dewPoin:20, // Dew Point (ºC)
		},
		Garden:{
			Area:{
				area:0,           // Garden Area (m2)
				irrigationRate:0, // (L/m2/day)
			},
			Sprinklers:{
				amount:1,   // Number of sprinklers
				flow:1,     // sprinkler flow (L/min)
				duration:10, // duration (min/day)
			},
		},
		Laundry:{
			capacity:10,       // L/load 
			loadsDayGuest:0.3, // load per day per guest (loads/day/guest)
		},
		Lobby:{
			Toilet:{useFreq:0.3}, // (uses/person/day)
			Sink:{useFreq:0.3},   // (uses/person/day)
		},
		Kitchen:{
			Sink:{
				sinkVol:20,  // total volume of sinks (L)
				perc:80,     // % volume sinks filled per use (%/use)
				useFreq:0.5, // uses/day/person
			},
			Dishwasher:{
				capacity:0,      // dishwasher capacity (L/load)
				loadsDayDiner:0, // loads per day per diner (loads/day/diner)
			},
		},
	},
}
