$(window).load(function(data) { 

	$(".voteYesButton").click(function(data) { 
		doVote(1);
	});
	$(".voteNoButton").click(function(data) { 
		doVote(0);
	});
	$(".prev").click(function(data) { 
		changeImageTo($("#prevImage").val());
	});
	$(".next").click(function(data) { 
		changeImageTo($("#nextImage").val());
	});
});

// Change image 

function changeImageTo(imageId) {
	// If no image, error.
	if(imageId == null ) { 
		alert("No image found!");
		return;
	}

	// Call AJAX end point, get image data and update data

	$.ajax({
		url: "index.php",
		data:  { 
			action: 'changeTo',
			imageId: imageId,
		},
		type: 'post',
		dataType: 'json',
	}
	).done(function(data) { 
		console.log(data);
		if(typeof data.error == 'undefined') { 
			$(".yesPercent").html(data.yes + "%");
			$(".noPercent").html(data.no + "%");
			$("#prevImage").val(data.prev);
			$("#nextImage").val(data.next);
			$("#imageArea").remove();
			$("#imageLanding").html("<img src=\""+data.src+"\" id=\"imageArea\" />");
			$("#imageId").val(imageId);
		} else  {
			alert(data.error);
		}
	});

}


// Perform vote.
function doVote(voteValue) { 
	var imageId = $("#imageId").val();
	$.ajax({
		url: "index.php",
		data:  { 
			action: 'vote',
			imageId: imageId,
			vote: voteValue,
		},
		type: 'post',
		dataType: 'json',
	}
	).done(function(data) { 
		if(typeof data.error == 'undefined') { 
			$(".yesPercent").html(data.yes + "%");
			$(".noPercent").html(data.no + "%");
		} else  {
			alert(data.error);
		}
	});
}
