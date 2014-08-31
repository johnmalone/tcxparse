function setProgress(progress)
{
	var progressBarWidth = progress*$(".pbarContainer").width()/ 100;  
	$(".progressbar").width(progressBarWidth).html(progress + "% ");
}

$("document").ready(function(){
	window.setInterval(function(){
		$.ajax({
			type: "GET",
			url : "uploadProgress",
			success : function(data){
				if (data == 0) 
					$(".pbarContainer").hide();
				else if (data == parseInt(data))
				{
					setProgress(data)
					$(".pbarContainer").show();
				}
				else 
					$(".pbarContainer").hide();
			}
		},"json");

	}, 5000);
});//end of document ready function

