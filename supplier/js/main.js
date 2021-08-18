$(document).ready(function(){

	$(".login-btn").on("click", function(){

		$.ajax({
			url : './classes/Credentials.php',
			method : "POST",
			data : $("#supplier-login-form").serialize(),
			success : function(response){
				console.log(response);
				var resp = $.parseJSON(response);
				if (resp.status == 202) {
					$("#supplier-login-form").trigger("reset");
					//$(".message").html('<span class="text-success">'+resp.message+'</span>');
					window.location.href = window.origin+"/lirs/supplier/index.php";
					console.log(window.location.href);
				}else if(resp.status == 303){
					$(".message").html('<span class="text-danger">'+resp.message+'</span>');
				}
			}
		});

	});

});