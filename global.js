/** All Inputs for Sambanet **/
var Global =
{
	Services:{
		General:{
			guests:500,  // number of guests
			diners:1000, // number of diners
		},
		Room:{
			Toilet:{
				flushCap:6, // flush capacity (L/flush)
				useFreq:9,  // use frequency (uses/person/day)
			},
			Sink:{
				discRate:8,      // sink discharge rate (L/min)
				useDuration:0.3, // (min/use/person)
				useFreq:2.9,     // (uses/day/person)
			},
			Shower:{
				discRate:9, // shower discharge rate (L/min)
				avgRate:8,  // average shower rate (min/use)
				useFreq:1,  // (uses/day/person)
			},
			Bath:{
				tubVol:0.2, // bathtub volume (m3)
				perc:80,     // volume filled by bath water (%)
				freq:0.05,  // (uses/person/day)
			},
		},
		Pool:{
			area:0, // Surface Area (m2)
			ambTemp:0, // Ambient Temp (ºC)
			watTemp:0, // Water Temp (ºC)
			windVel:0, // Wind Velocity (m/s)
			dewPoin:0, // Dew Point (ºC)
		},
		Garden:{
			Calculation:{
				area:0,           // Garden Area (m2)
				irrigationRate:0, // (L/min)
			},
			Sprinkler:{
				amount:0,   // Number of sprinklers
				speed:0,    // sprinkler speed L/min
				duration:0, // min
			},
		},
		Laundry:{
			capacity:0, // L/load 
			loadpday:0, // load per day (kg/day)
			loadsPerGuest:0, // loads per guest
		},
		Lobby:{
			toiletUseFreq:0.3, // (uses/day)
			sinkUseFreq:0,     // (uses/person/day)
		},
		Kitchen:{
			watCons:0, //water consumption (L/person/day)
			ThreeSink:{
				vol:0,  // total volume of sinks (m3)
				perc:0, // % volume sinks filled
			},
			Dishwasher:{
				capacity:0,         // dishwasher capacity (L/load)
				loadsPdiner:0, // loads per diner
			},
		},
	},
}
