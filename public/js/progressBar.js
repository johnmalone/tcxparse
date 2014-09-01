function setProgress(progress)
{
	var progressBarWidth = progress*$(".pbarContainer").width()/ 100;  
	$(".progressbar").width(progressBarWidth).html(progress + "% ");
}

$("document").ready(function(){
	var pbarIntervalID = window.setInterval(function(){
		if (typeof doUploadProgress !== 'undefined' && doUploadProgress)
		{
			$.ajax({
				type: "GET",
				url : "uploadProgress",
				success : function(data){
					console.log(data);
					if (data == 'n')
						clearInterval(pbarIntervalID);
					
					if (data == parseInt(data))
					{
						setProgress(data)
						$(".pbarContainer").show();
						if (data == 100)
						{
							setTimeout(function() { $(".pbarContainer").hide('slow'); },3000);
							clearInterval(pbarIntervalID);
						}

					}
					else 
					{
						$(".pbarContainer").hide();
						clearInterval(pbarIntervalID);
					}
				}
			},"json");
		}

	}, 5000);
});//end of document ready function

