// Global Contact Form Handler - F3 Construction Corp
(function() {
  function getBaseUrl(formEl) {
    if (formEl && formEl.getAttribute("data-base")) {
      var b = formEl.getAttribute("data-base");
      return b.endsWith("/") ? b : b + "/";
    }
    return "/";
  }

  window.sendmail = function(event) {
    if (event) event.preventDefault();

    var contactForm = document.getElementById("contact_form_id");
    var base = getBaseUrl(contactForm);

    var aceptCheck = document.getElementById("acept");
    if (aceptCheck && !aceptCheck.checked) {
      alert("Please accept the Privacy Policy.");
      return false;
    }

    var captchaResponse = typeof grecaptcha !== "undefined" ? grecaptcha.getResponse() : "";
    if (!captchaResponse) {
      alert("Please verify the reCAPTCHA.");
      return false;
    }

    var submitBtn = document.getElementById("submit_button");
    var loaderEl = document.getElementById("load_form");

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = "SENDING...";
    }
    if (loaderEl) {
      loaderEl.style.display = "block";
    }

    var nameVal = document.getElementById("name") ? document.getElementById("name").value : "";
    var lastNameVal = document.getElementById("lastName") ? document.getElementById("lastName").value : "";
    var phoneVal = document.getElementById("phone") ? document.getElementById("phone").value : "";
    var emailVal = document.getElementById("email") ? document.getElementById("email").value : "";
    var messageVal = document.getElementById("message") ? document.getElementById("message").value : "";

    var urlParams = new URLSearchParams({
      name: nameVal,
      lastName: lastNameVal,
      phone: phoneVal,
      email: emailVal,
      message: messageVal,
      "g-recaptcha-response": captchaResponse
    });

    fetch(base + "sendmail/send_contact.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: urlParams
    })
    .then(function(response) {
      return response.text();
    })
    .then(function(result) {
      if (loaderEl) loaderEl.style.display = "none";
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = "SEND REQUEST";
      }

      try {
        var res = JSON.parse(result);
        if (res.success) {
          if (contactForm) {
            contactForm.reset();
          }
          if (typeof grecaptcha !== "undefined") {
            grecaptcha.reset();
          }
          window.location.href = base + "thank-you/";
        } else {
          alert(res.message || "Error");
          if (typeof grecaptcha !== "undefined") {
            grecaptcha.reset();
          }
        }
      } catch (e) {
        alert("Sent, but check server response.");
      }
    })
    .catch(function(err) {
      if (loaderEl) loaderEl.style.display = "none";
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = "SEND REQUEST";
      }
      alert("An error occurred during submission.");
    });
  };

  function initContactForm() {
    var submitBtn = document.getElementById("submit_button");
    if (submitBtn) {
      submitBtn.removeEventListener("click", window.sendmail);
      submitBtn.addEventListener("click", window.sendmail);
    }

    var contactForm = document.getElementById("contact_form_id");
    if (contactForm) {
      contactForm.addEventListener("submit", function(e) {
        e.preventDefault();
        window.sendmail(e);
      });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initContactForm);
  } else {
    initContactForm();
  }
})();
