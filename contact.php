<?php
// Asegurar que la página responda 200 OK para crawlers
http_response_code(200);
header("Content-Type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./includes/assets.php") ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="">
    <meta name="description"
        content="F3 Construction Corp is the premier provider of full home improvement services in New York. We general contractors for interior and commercial.">
    <meta name="keywords" content="Architecture, Design, Interior Design, Constructions, New York">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://f3constructionny.com/contact.php">
    <link rel="stylesheet" href="style/contact.css?v=1.1">
    <title>Contact: Commercial and Home Renovation in Long Island, NY
    </title>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "ContactPage",
            "@id": "https://f3constructionny.com/contact.php#contact-page",
            "url": "https://f3constructionny.com/contact.php",
            "name": "Contact F3 Construction Corp",
            "description": "Contact F3 Construction Corp in Central Islip, NY for remodeling and construction services across Long Island.",
            "inLanguage": "en",
            "about": {
                "@id": "https://f3constructionny.com/#business"
            },
            "primaryImageOfPage": {
                "@type": "ImageObject",
                "url": "https://f3constructionny.com/media/webp/png/logo.webp"
            },
            "mainEntity": {
                "@type": "Organization",
                "@id": "https://f3constructionny.com/#business",
                "name": "F3 Construction Corp",
                "telephone": "+1-631-278-8693",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "290 Earle St",
                    "addressLocality": "Central Islip",
                    "addressRegion": "NY",
                    "postalCode": "11722",
                    "addressCountry": "US"
                },
                "sameAs": [
                    "https://www.facebook.com/f3construction/",
                    "https://www.instagram.com/f3constructionny?igsh=dTZ2cTZ4NXp2aHRt"
                ],
                "contactPoint": [{
                    "@type": "ContactPoint",
                    "telephone": "+1-631-278-8693",
                    "contactType": "customer service",
                    "areaServed": "US-NY",
                    "availableLanguage": ["en", "es"]
                }]
            }
        }
    </script>


</head>

<body>
    <?php include("./includes/header.php") ?>
    <main>
        <section class="contact-section">
            <?php include("./includes/breadcrumb.php") ?>
            <div class="contact-container">
                <div class="media-ctn">
                    <video muted playsinline loop autoplay>
                        <source src="./media/video/contact.mp4" type="video/mp4">
                    </video>
                </div>
                <div class="text-ctn">
                    <div class="text">
                        <h1>MAKING YOUR PROJECT
                            <br> <span>IDEAS A REALITY</span>
                        </h1>

                        <p>F3 Construction Corp located in New York with over 20 years of a long and successful history.
                            Our team of experts will help you in any area of remodeling that you require for your home,
                            whether it be interior or exterior remodeling. We work tirelessly for our clients, adapting
                            to their budgets, implementing the highest quality materials and guaranteeing the best
                            results. Your happiness is our success!</p>
                    </div>

                    <form id="contact_form_id" class="form-tag">
                        <div class="request-ctn">
                            <div class="title">
                                <h2>CONNECT WITH US</h2>
                            </div>
                            
                            <div class="form-ctn">
                                <input type="text" placeholder="Name" id="name" name="name">
                                <input type="text" placeholder="Last Name" id="lastName" name="lastName">
                                <input type="text" placeholder="Phone" id="phone" name="phone">
                                <input type="email" placeholder="Email" id="email" name="email">
                                <input type="text" placeholder="Message" id="message" name="message">
                            </div>

                            <div class="privacy-row">
                                <input type="checkbox" id="acept" name="accept">
                                <label for="acept">
                                    I have read and accept the <a href="privacy.php" target="_blank">Privacy Policy</a>.
                                </label>
                            </div>

                            <div class="g-recaptcha" data-sitekey="6Lc2YOsrAAAAANLQTo2_5XtXZqgH0neFNyTRMAhU"></div>

                            <div class="submit-btn">
                                <button type="button" onclick="sendmail(event)" id="submit_button" class="btn-submit-gtm">SEND REQUEST</button>
                            </div>

                            <div class="notify_box">
                                <div class="loader" id="load_form" style="display:none; margin: 10px auto;"></div>
                                <div class="message_box" id="message_box"></div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </section>
        <section class="find-us-section">
            <div class="box-map">
                <div class="info-map">
                    <h2>How to Find Us</h2>
                    <p>Visit us in Long Island, NY for a consultation and estimate on your crown molding or trim
                        project.</p>
                    <p><i class="fa-solid fa-location-dot"></i> View on Google Maps <i
                            class="fa-solid fa-arrow-right"></i></p>


                </div>
                <div class="map-section">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12319.910251301415!2d-73.2012657!3d40.7845822!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89e831603776bac3%3A0x3c6a06c628b18ae0!2sF3%20Construction%20Corp!5e1!3m2!1sen!2sco!4v1761323817490!5m2!1sen!2sco"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>
    </main>
    <?php include("./includes/footer.php") ?>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        function sendmail(event) {
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

            $.post("./sendmail/send_contact.php", formData, function(result) {
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
        }
        </script>
</body>

</html>