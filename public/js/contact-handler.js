if (typeof sendmail === "undefined") {
  window.sendmail = function(event) {
    if (event) event.preventDefault();

    const aceptCheck = document.getElementById("acept");
    if (aceptCheck && !aceptCheck.checked) {
      alert("Please accept the Privacy Policy.");
      return false;
    }

    const captchaResponse = typeof grecaptcha !== "undefined" ? grecaptcha.getResponse() : "";
    if (!captchaResponse) {
      alert("Please verify the reCAPTCHA.");
      return false;
    }

    const submitBtn = document.getElementById("submit_button");
    const loaderEl = document.getElementById("load_form");

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = "SENDING...";
    }
    if (loaderEl) {
      loaderEl.style.display = "block";
    }

    const nameVal = document.getElementById("name")?.value || "";
    const lastNameVal = document.getElementById("lastName")?.value || "";
    const phoneVal = document.getElementById("phone")?.value || "";
    const emailVal = document.getElementById("email")?.value || "";
    const messageVal = document.getElementById("message")?.value || "";
    const privacyConsentEl = document.getElementById("privacy_consent");

    const urlParams = new URLSearchParams({
      name: nameVal,
      lastName: lastNameVal,
      phone: phoneVal,
      email: emailVal,
      message: messageVal,
      "g-recaptcha-response": captchaResponse
    });

    // Envío principal al script PHP
    const contactForm = document.getElementById("contact_form_id");

    fetch("/sendmail/send_contact.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: urlParams
    })
    .then(response => response.text())
    .then(result => {
      if (loaderEl) loaderEl.style.display = "none";
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = "SEND REQUEST";
      }

      try {
        const res = JSON.parse(result);
        if (res.success) {
          const msgBox = document.getElementById("message_box");
          if (msgBox) {
            msgBox.innerHTML = "<p style='color:#b71c1c; font-weight:700;'>Sent successfully!</p>";
            msgBox.style.display = "block";
          }
          if (contactForm) {
            contactForm.reset();
          }
          grecaptcha.reset();
        } else {
          alert(res.message || "Error");
          grecaptcha.reset();
        }
      } catch (e) {
        alert("Sent, but check server response.");
      }
    })
    .catch(err => {
      if (loaderEl) loaderEl.style.display = "none";
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = "SEND REQUEST";
      }
      alert("An error occurred during submission.");
    });
  };
}
