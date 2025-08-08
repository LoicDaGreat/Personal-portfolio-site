$(document).ready(function() {
    
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidPhone(phone) {
        var cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
        var phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        return phoneRegex.test(cleanPhone);
    }
    
    $("#name, #email, #phone, #message").click(function() {
        $(this).removeClass("error_input");
    });
    
  
    $("#send_message").click(function(event) {
        event.preventDefault();
        
        var hasError = false;
        var name = $("#name").val().trim();
        var email = $("#email").val().trim();
        var phone = $("#phone").val().trim();
        var message = $("#message").val().trim();
        
        if (name.length === 0) {
            hasError = true;
            $("#name").addClass("error_input");
        } else {
            $("#name").removeClass("error_input");
        }
        
        if (email.length === 0 || !isValidEmail(email)) {
            hasError = true;
            $("#email").addClass("error_input");
        } else {
            $("#email").removeClass("error_input");
        }
        
        if (phone.length === 0 || !isValidPhone(phone)) {
            hasError = true;
            $("#phone").addClass("error_input");
        } else {
            $("#phone").removeClass("error_input");
        }
        
        if (message.length === 0) {
            hasError = true;
            $("#message").addClass("error_input");
        } else {
            $("#message").removeClass("error_input");
        }
        
        if (!hasError) {
            $("#send_message").attr({
                "disabled": "true",
                "value": "Sending..."
            });
            
            $.post("email.php", $("#contact_form").serialize())
                .done(function(response) {
                    if (response.trim() === "sent") {
                        $("#submit").remove();
                        $("#mail_success").fadeIn(500);
                        $("#contact_form")[0].reset();
                        
                    } else {
                        $("#mail_fail").fadeIn(500);
                        $("#send_message").removeAttr("disabled").attr("value", "Send");
                        console.log("Server response:", response);
                    }
                })
                .fail(function(xhr, status, error) {
                    $("#mail_fail").fadeIn(500);
                    $("#send_message").removeAttr("disabled").attr("value", "Send");
                    console.log("AJAX request failed:", error);
                    console.log("Status:", status);
                    console.log("Response:", xhr.responseText);
                });
        }
    });
    
    $("#name").on('blur', function() {
        var name = $(this).val().trim();
        if (name.length === 0) {
            $(this).addClass("error_input");
        } else {
            $(this).removeClass("error_input");
        }
    });
    
    $("#email").on('blur', function() {
        var email = $(this).val().trim();
        if (email.length === 0 || !isValidEmail(email)) {
            $(this).addClass("error_input");
        } else {
            $(this).removeClass("error_input");
        }
    });
    
    $("#phone").on('blur', function() {
        var phone = $(this).val().trim();
        if (phone.length === 0 || !isValidPhone(phone)) {
            $(this).addClass("error_input");
        } else {
            $(this).removeClass("error_input");
        }
    });
    
    $("#message").on('blur', function() {
        var message = $(this).val().trim();
        if (message.length === 0) {
            $(this).addClass("error_input");
        } else {
            $(this).removeClass("error_input");
        }
    });
    
});