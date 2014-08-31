

<div class="pbarContainer"><div class="progressbar"></div></div>
<script>
function setProgress(progress)
{
	var progressBarWidth = progress*$(".pbarContainer").width()/ 100;  
	$(".progressbar").width(progressBarWidth).html(progress + "% ");
}

$("document").ready(function(){
	window.setInterval(function(){
		$.ajax({
			type: "POST",
			url : "uploadProgress",
			success : function(data){
				console.log(data);
			}
		},"json");

	});
});//end of document ready function

</script>
