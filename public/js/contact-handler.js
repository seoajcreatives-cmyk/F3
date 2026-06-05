if (typeof sendmail === "undefined") {
    window.sendmail = function(event) {
        if (event) event.preventDefault();
        if (!$("#acept").is(":checked")) {
            alert("Please accept the Privacy Policy.");
            return false;
        }
        var captchaResponse = grecaptcha.getResponse();
        if (!captchaResponse) {
            alert("Please verify the reCAPTCHA.");
            return false;
        }

        $("#submit_button").prop("disabled", true).text("SENDING...");
        $("#load_form").show();

        var formData = {
            name: $("#name").val(),
            lastName: $("#lastName").val(),
            phone: $("#phone").val(),
            email: $("#email").val(),
            message: $("#message").val(),
            "g-recaptcha-response": captchaResponse
        };

        fetch("https://hooks.zapier.com/hooks/catch/26423574/uelrxfz/", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                name: $("#name").val(),
                last_name: $("#lastName").val(),
                phone: $("#phone").val(),
                email: $("#email").val(),
                message: $("#message").val(),
                privacy_consent: $("#privacy_consent").is(":checked")
            })
        }).catch(err => console.log("Zapier error:", err));

        $.post("/sendmail/send_contact.php", formData, function(result) {
            $("#load_form").hide();
            $("#submit_button").prop("disabled", false).text("SEND REQUEST");

            try {
                var res = JSON.parse(result);
                if (res.success) {
                    $("#message_box").html("<p style='color:#b71c1c; font-weight:700;'>Sent successfully!</p>").show();
                    $("#contact_form_id")[0].reset();
                    grecaptcha.reset();
                } else {
                    alert(res.message || "Error");
                    grecaptcha.reset();
                }
            } catch (e) {
                alert("Sent, but check server response.");
            }
        });
    };
}
