/* Table of mg/use per service */

//guests and diners are used to calc uses/day of each hotel service
var guests=Inputs.Services.General.guests;
var diners=Inputs.Services.General.diners;

/*Loads structure*/
var Loads = {
	"Room Toilet":{
		uses:Inputs.Services.Room.Toilet.useFreq*guests,
		contaminants:{
			SST:1377.2,
			PO4:73.0286,
			SO4:174.182,
			TOC:2772.56,
			COD:6657,
			BOD:2315.4,
			TN:981.9768,
			Caffeine:2.8,
			Carbamazepine:0.0066,
			Diclophenac:0.078,
		}
	},
	"Room Sink":{
		uses:Inputs.Services.Room.Sink.useFreq*guests,
		contaminants:{
			SST:492.1,
			PO4:28.5,
			SO4:100.7,
			TOC:226.1,
			COD:733.4,
			BOD:389.5,
			TN:18.81,
			Caffeine:0,
			Carbamazepine:0,
			Diclophenac:0,
		},
	},
	"Room Bath":{
		uses:Inputs.Services.Room.Bath.useFreq*guests,
		contaminants:{
			SST:1591.333333,
			PO4:14.5175,
			SO4:538.8466667,
			TOC:3815.7,
			COD:13090.16667,
			BOD:4790,
			TN:426.2315,
			Caffeine:0,
			Carbamazepine:0,
			Diclophenac:0,
		},
	},
	"Room Shower":{
		uses:Inputs.Services.Room.Shower.useFreq*guests,
		contaminants:{
			SST:1591.333333,
			PO4:14.5175,
			SO4:538.8466667,
			TOC:3815.7,
			COD:13090.16667,
			BOD:4790,
			TN:426.2315,
			Caffeine:0,
			Carbamazepine:0,
			Diclophenac:0,
		},
	},
	"Laundry":{
		uses:Inputs.Services.Laundry.loadsDayGuest*guests,
		contaminants:{
			SST:1558.5,
			PO4:48.3945,
			SO4:278.19,
			TOC:6513.6,
			COD:22183.5,
			BOD:6030,
			TN:891.819,
			Caffeine:0,
			Carbamazepine:0,
			Diclophenac:0,
		},
	},
	"Lobby Toilet":{
		uses:Inputs.Services.Lobby.Toilet.useFreq*guests,
		contaminants:{
			SST:1377.2,
			PO4:73.0286,
			SO4:174.182,
			TOC:2772.56,
			COD:6657,
			BOD:2315.4,
			TN:981.9768,
			Caffeine:2.8,
			Carbamazepine:0.0066,
			Diclophenac:0.078,
		},
	},
	"Lobby Sink":{
		uses:Inputs.Services.Lobby.Sink.useFreq*guests,
		contaminants:{
			SST:492.1,
			PO4:28.5,
			SO4:100.7,
			TOC:226.1,
			COD:733.4,
			BOD:389.5,
			TN:18.81,
			Caffeine:0,
			Carbamazepine:0,
			Diclophenac:0,
		},
	},
	"Kitchen Dishwasher":{
		uses:Inputs.Services.Kitchen.Dishwasher.loadsDayDiner*diners,
		contaminants:{
			SST:1511.61179,
			PO4:13.2478336,
			SO4:46.5372617,
			TOC:1520.44367,
			COD:6070.2253,
			BOD:3162.83536,
			TN:131.79896,
			Caffeine:0,
			Carbamazepine:0,
			Diclophenac:0,
		},
	},
	"Kitchen Sink":{
		uses:Inputs.Services.Kitchen.Sink.useFreq*diners,
		contaminants:{
			SST:0,
			PO4:0,
			SO4:0,
			TOC:0,
			COD:0,
			BOD:0,
			TN:0,
			Caffeine:0,
			Carbamazepine:0,
			Diclophenac:0,
		},
	},
};
