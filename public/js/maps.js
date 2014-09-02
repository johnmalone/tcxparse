var map = new Array();
var ajaxRequest;
var plotlist;
var plotlayers=[];
function initmap(id) {
	// set up the map
	map[id] = new L.Map('map_'+id);

	// create the tile layer with correct attribution
	var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
	var osm = new L.TileLayer(osmUrl, {minZoom: 8, maxZoom: 12, attribution: osmAttrib});		

	// start the map in South-East England
	map[id].setView(new L.LatLng(53.3, -6.20),9);
	map[id].addLayer(osm);
}

function getAndLoadLatLongData(id)
{
	$.ajax({
		type: "GET",
		url : "/parsedActivityData/"+id+"/leafletJSLatLong",
		success : function(data){
			initmap(id);
			eval(data);
			eval("var thisTrack = track_" + id );
			if (thisTrack.length != 0)
			{
				var polyline = L.polyline(thisTrack,{color:'red'}).addTo(map[id]);
				map[id].fitBounds(polyline.getBounds());
			}

		}
	},"script");

}
