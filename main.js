function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

$(document).ready(function() {

	$("#submit_email").click(function() {			
		var email = $("#email_text").val();	
		var referer = getUrlVars()["referer"];

		$.ajax({
			dataType: "json",
            type: "POST",
            url: "/test/submit_email.php",
            data: {email: email, referer: referer},
            success: function(data){
            	if (data.error) {
            		var error = data.error;

            		$("#success").html("<h2>" + error + "</h2>");
            	} else {
	            	var submitted_email = data.email;
	            	var unique_id = data.unique_id;
	            	var position = data.position;
	            	var referer = data.referer;
	            	var rank = data.rank;

	            	$("#success").html("<h2>Thanks " + submitted_email + ". </h2> You've referred " + position + " people. <br>Send <a href=\"http://localhost/test/?referer=" 
	            		+ unique_id + "\">this link</a> to your friends to climb in rank. <br>Click <a href=\"http://localhost/test/check_rank.php?id=" 
	            		+ unique_id + "\">this link</a> to check the status of your registration. " + "<h2>Your current rank on our list is " + rank + "!</h2>");
	            	$("#input_email_form h1").text("Thanks!");
	            	$("#input_email_form form").hide();
            	}
            }
        });
		return false;

	});
});