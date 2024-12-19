<!-- Author: Justine Lucas -->
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="gstyle.css">
    <title>Gcash Merchant</title>
    <link rel="icon" type="image/png" href="/view/assets/Gcash-logo.png">
</head>
<body>
    <div class="upperLogo">
        <img src="/view/assets/Gcash.png" alt="GcashMerchant">
    </div>
    <div class="paymentContainer"> <!-- Payment container with border -->
        <div class="paymentAmount">PHP 15.00</div>
        <div class="paymentStatus">Payment successful</div>
        <hr></hr>
        <div class="paymentDetails">
    <div class="row">
        <div class="label">Pay to</div>
        <div class="value">Gogora</div>
    </div>
    <div class="row">
        <div class="label">Payment method</div>
        <div class="value">GCash</div>
    </div>
    <div class="row">
        <div class="label">Order info</div>
        <div class="value">Gogora Booking</div>
    </div>
    <div class="row">
        <div class="label">Order amount</div>
        <div class="value">PHP 15.00</div>
    </div>
    <div class="row">
        <div class="label">Transaction no.</div>
        <div class="value">1111999555</div>
    </div>
</div>

            <!-- <div class="breakdown-details">
                <div>Gogora</div>
                <div>GCash</div>
                <div>Gogora Booking</div>
                <div>PHP 13.00</div>
                <div>1111999555</div>
            </div> -->
        </div>
    </div>
    <div class="returnToMerchant">
        <button id="returnBtn" class="returnbutn" onclick="window.location.href='confirmation.php'">
            Return to Merchant (5)
        </button>
    </div>

    <script>
        // Countdown Timer Script
        let countdown = 5;
        const button = document.getElementById("returnBtn");

        const timer = setInterval(function() {
            button.innerHTML = `Return to Merchant (${countdown})`;
            countdown--;

            if (countdown < 0) {
                clearInterval(timer); // Stop the timer
                window.location.href = "confirmation.php"; // Redirect to the confirmation page
            }
        }, 1000); // Update the timer every second
    </script>
</body>
</html>
