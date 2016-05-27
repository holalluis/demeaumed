/** All Inputs for Sambanet **/
var Samba =
{
	Services:{
		General:{
			guests:500,  // number of guests
			diners:1000, // number of diners
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
			Freq:{
				duration:0, // min
				loadpday:0, // load per day (kg/day)
			},
			PerGuest:{
				duration:0,      // min
				loadsPerGuest:0, // loads per guest
			}
		},
		Lobby:{
			Toilet:{
				flushCap:6,  // toilet flush capacity (L/min)
				useFreq:0.3, // (uses/day)
			},
			Sink:{
				discRate:0,    // discharge rate (L/min)
				useDuration:0, // use duration (min/use)
				useFreq:0,     // use (uses/person/day)
			},
		},
		Kitchen:{
			watCons:0, //water consumption (L/person/day)
			ThreeSink:{
				vol:0,  // total volume of sinks (m3)
				perc:0, // % volume sinks filled
			},
			Dishwasher:{
				cap:0,         // dishwasher capacity (L/load)
				loadsPdiner:0, // loads per diner
			},
		},
	},
}
