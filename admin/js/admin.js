$(document).on('click' , '.good', function(){
    	var id = $(this).val();
        $.ajax({
            url: "../../page/functions.php?action=acceptreq",
    				type: "POST",
    				data: {id:id},
    				success: function(result) {
                alert("Done");
            }
        });
        return false;
    });
