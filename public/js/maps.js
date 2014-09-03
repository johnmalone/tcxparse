var map = new Array();
var ajaxRequest;
var plotlist;
var plotlayers=[];
function initmap(id) {
	// set up the map
	map[id] = new L.Map('map_'+id, {dragging: false, scrollWheelZoom: false, doubleClickZoom: false, touchZoom: false, boxZoom: false, zoomControl: false});

	// create the tile layer with correct attribution
	var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
	var osm = new L.TileLayer(osmUrl, { attribution: osmAttrib});		

	// start the map in South-East England
	
	map[id].addLayer(osm);
}

function getAndLoadLatLongData(id)
{
	$.getJSON(
		"/parsedActivityData/"+id+"/leafletJSLatLong",
		function(geoJsonData){
			initmap(id);

			if (Object.keys(geoJsonData).length != 0)
			{
				var geoJson = L.geoJson(geoJsonData);
				map[id].fitBounds(geoJson.getBounds());
				geoJson.addTo(map[id]);
			}

		}
	);

}
