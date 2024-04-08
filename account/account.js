//////////* Account.js - Javascript functions for account pages *//////////
/////*  Profile Functions */////
if (document.body.classList.contains('profile-body')) {
    // increase bio font size based on length
    window.onload = function() {
        var textElement = document.querySelector('.bio-container p');
        var textLength = textElement.textContent.length;

        if (textLength > 600) {
            textElement.style.fontSize = '1em'; // smaller font size
        } else if (textLength > 300) {
            textElement.style.fontSize = '1.2em'; // medium font size
        } else {
            textElement.style.fontSize = '1.5em'; // larger font size
        }
    };
}

/////*  Register & Verify Functions */////
$(".send-email").on("click", function () {
    var email = $('.email-input').val();

    //check email
    $.ajax({
        url: '/functions/reset-functions',
        type: 'POST',
        data: { function: "email", email: email },
        success: function (response) {
            if (response == "true") {
                checkPassword(email);
            }
            else {
                $(".error").html("The email entered is either invalid or not associated with any account.").show();
            }
        }
    });
});

function checkPassword(email) {
    $(".error").hide();
    $(".email-container").toggle();
    $(".password-container").toggle();

    $(".submit-pass").on("click", function () {
        var code = $('.code-input').val();
        var password1 = $('.password-input').val(), password2 = $('.password2-input').val();
        
        if (code.length == 0) {
            $(".error").html("No code was entered.").show();
        }
        else if (!(password1 === password2 && password1.length > 0 && password2.length > 0)) {
            $(".error").html("Paswords do not match.").show();
        }
        else {
            $(".error").hide();

            $.ajax({
                url: '/functions/reset-functions',
                type: 'POST',
                data: { function: "password", email: email, code: code, password: password1 },
                success: function (response) {
                    console.log(response);
                    if (response == "valid") {
                        window.location.replace("/account/login.php")
                    }
                    else if (response == "expired") {
                        $(".error").html("The code entered has expired. Please refresh the page to restart the process.").show();
                    }
                    else if (response == "invalid") {
                        $(".error").html("The password entered is not a valid password.").show();
                    }
                    else if (response == "wrong") {
                        $(".error").html("The code entered is invalid.").show();
                    }
                }
            });
        }
    });
}

function updateCountdown(endTimeUnix) {
    var nowUnix = Math.floor(Date.now() / 1000);
    var timeLeft = endTimeUnix - nowUnix;
    if (timeLeft <= 0) {
        $(".countdown").html("Expired");
        clearInterval(timer);
        return;
    }

    var minutes = Math.floor((timeLeft % 3600) / 60);
    var seconds = timeLeft % 60;
    $(".countdown").html(minutes + "m " + seconds + "s");
}
