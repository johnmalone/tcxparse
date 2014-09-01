function setProgress(progress)
{
	$("#uploadProgressbar .progress-bar").removeClass("progress-bar-info progress-bar-striped active").addClass("progress-bar progress-bar-success");
	$("#uploadProgressbar .progress-bar").css('width',progress + '%');
	$("#uploadProgressbar .progress-bar").attr('aria-valuenow',progress);
	$("#uploadProgressbar .sr-only").html(progress + '% complete');
	$("#uploadProgressbarContents ").html(progress + '%');

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
					{
						setProgress(100);
						setTimeout(function() { $("#uploadProgressbar").hide('slow'); },5000);
						clearInterval(pbarIntervalID);
						return;
					}
					
					if (data == parseInt(data))
					{
						setProgress(data)
						$("#uploadProgressbar").show();
						if (data == 100)
						{
							setTimeout(function() { $("#uploadProgressbar").hide('slow'); },5000);
							clearInterval(pbarIntervalID);
							doUploadProgress = false;
						}

					}
					else 
					{
						$("#uploadProgressbar").hide();
						clearInterval(pbarIntervalID);
					}
				}
			},"json");
		}

	}, 1000);
});//end of document ready function

