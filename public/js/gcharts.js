function drawAltChartForActivity(id) {
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(function() { drawChart(id)});
}

function drawChart(id) {
	var jsonData = $.ajax({
		url: "/parsedActivityData/"+id+"/googChartAlt",
		dataType:"json",
		async: false
	}).responseText;
	var data = new google.visualization.DataTable(jsonData);

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.LineChart(document.getElementById('alt_chart_'+id));
	chart.draw(data, {width: 400, height: 240, hAxis: {title:'Distance'}, vAxis: {title:'Alt'}});

	google.visualization.events.addListener(chart, 'onmouseover', function (rowColumn) {
	console.log(rowColumn.row);
	console.log(data.getValue(rowColumn.row, rowColumn.column));           
	});

}
