//nodes per afegir a "reuse.php"
var Nodes={
	"Room Toilet"        :{value:Outputs.Room.Toilet()},
	"Room Sink"          :{value:Outputs.Room.Sink()},
	"Room Shower"        :{value:Outputs.Room.Shower()},
	"Room Bath"          :{value:Outputs.Room.Bath()},
	"Pool"               :{value:Outputs.Pool.Flow()},
	"Garden"             :{value:Outputs.Garden.Area()+Outputs.Garden.Sprinklers()},
	"Laundry"            :{value:Outputs.Laundry.Laundry()},
	"Lobby Toilet"       :{value:Outputs.Lobby.Toilet()},
	"Lobby Sink"         :{value:Outputs.Lobby.Sink()},
	"Kitchen Sink"       :{value:Outputs.Kitchen.Sink()},
	"Kitchen Dishwasher" :{value:Outputs.Kitchen.Dishwasher()},
};
