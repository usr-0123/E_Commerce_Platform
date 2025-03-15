<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    include "../config/database_connect.php";

    $user_id = $_SESSION['user_id'];

    // Calculate total price
    $total_sql = "SELECT SUM(p.price * c.quantity) AS total_price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$user_id'";

    $total_order_amount = $connect->query($total_sql);

    $price = $total_order_amount->fetch_assoc()['total_price'];

    $connect->close();
?>

<style>
    .payments-body-container {
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
    .total-amount {
        background-color: #007bff;
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        border-radius: 10px 10px 0 0;
        margin: 20px 20px 20px 20px;
    }
    .payment-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        width: 400px;
    }
    .input-group {
        margin-bottom: 15px;
    }
    #paymentMethodLabel,
    #cardNumberLabel,
    #cardHolderLabel,
    #expiryDateLabel,
    #cvvLabel,
    #paypalEmailLabel,
    #phoneNumberLabel {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    #paymentMethod,
    #paymentMethod,
    #cardNumber,
    #cardHolder,
    #expiryDate,
    #cvv,
    #paypalEmail,
    #phoneNumber {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .error {
        color: red;
        font-size: 14px;
        display: none;
    }
    .submit-btn {
        background-color: #28a745;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        width: 100%;
        font-size: 18px;
        cursor: pointer;
    }
    .submit-btn:disabled {
        background-color: gray;
        cursor: not-allowed;
    }
</style>

<section class="payments-body-container">
    <div class="total-amount">
        Total Amount: <span id='totalAmount'>Ksh <?php echo "{$price}"?></span>
    </div>
    <div class="payment-container">
    <h2>Select Payment Method</h2>

    <div class="input-group">
        <label id="paymentMethodLabel" for="paymentMethod">Payment Method</label>
        <select id="paymentMethod" onchange="togglePaymentFields()">
            <option value="creditCard">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="phone">MPesa payment</option>
        </select>
    </div>

    <!-- Credit Card Section -->
    <div id="creditCardFields">
        <div class="input-group">
            <label id="cardNumberLabel" for="cardNumber">Card Number</label>
            <input type="text" id="cardNumber" maxlength="19" placeholder="1234 5678 9012 3456" oninput="formatCardNumber(this)">
        </div>

        <div class="input-group">
            <label for="cardHolder" id="cardHolderLabel" >Cardholder Name</label>
            <input type="text" id="cardHolder" placeholder="John Doe">
        </div>

        <div class="input-group">
            <label for="expiryDate" id="expiryDateLabel">Expiry Date (MM/YY)</label>
            <input type="text" id="expiryDate" maxlength="5" placeholder="MM/YY" oninput="formatExpiry(this)">
        </div>

        <div class="input-group">
            <label for="cvv" id="cvvLabel">CVV</label>
            <input type="password" id="cvv" maxlength="3" placeholder="123">
        </div>
    </div>

    <!-- PayPal Section -->
    <div id="paypalFields" style="display: none;">
        <div class="input-group">
            <label for="paypalEmail" id="paypalEmailLabel">PayPal Email</label>
            <input type="email" id="paypalEmail" placeholder="example@paypal.com">
        </div>
    </div>

    <!-- Phone Payment Section -->
    <div id="phoneFields" style="display: none;">
        <div class="input-group">
            <label for="phoneNumber" id="phoneNumberLabel">Phone Number</label>
            <input type="text" id="phoneNumber" maxlength="13" placeholder="Enter your phone number">
            <span class="error" id="phoneError">Invalid phone number format</span>
        </div>
    </div>

    <button class="submit-btn" onclick="processPayment()">Submit Payment</button>
</div>
</section>

<script>
    function togglePaymentFields() {
        let method = document.getElementById("paymentMethod").value;
        document.getElementById("creditCardFields").style.display = method === "creditCard" ? "block" : "none";
        document.getElementById("paypalFields").style.display = method === "paypal" ? "block" : "none";
        document.getElementById("phoneFields").style.display = method === "phone" ? "block" : "none";
    }

    function formatCardNumber(input) {
        let value = input.value.replace(/\D/g, '');
        input.value = value;
    }

    function formatExpiry(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }
        input.value = value;
    }

    function validatePhoneNumber() {
        let phoneNumber = document.getElementById("phoneNumber").value.trim();
        let phoneError = document.getElementById("phoneError");

        let validPrefixes = [
            "072", "070", "071", "079", "012", "010", "011", "019",
            "+25472", "+25470", "+25471", "+25479", "+25412", "+25410", "+25411", "+25419"
        ];

        let isValid = validPrefixes.some(prefix => phoneNumber.startsWith(prefix));

        phoneError.style.display = isValid ? "none" : "block";
        return isValid;
    }

    function processPayment() {
        let method = document.getElementById("paymentMethod").value;

        if (method === "creditCard") {
            let cardNumber = document.getElementById("cardNumber").value.replace(/\s/g, '');
            let cardHolder = document.getElementById("cardHolder").value;
            let expiryDate = document.getElementById("expiryDate").value;
            let cvv = document.getElementById("cvv").value;

            if (cardNumber.length < 16) {
                alert("Invalid card number");
                return;
            }

            if (cvv.length !== 3) {
                alert("Invalid CVV");
                return;
            }

            if (cardHolder.trim() === "") {
                alert("Cardholder name is required");
                return;
            }
            window.location.href = `../layout/main.php?page=orders.php&success=true`;
            alert("Credit Card Payment Successful!");
        }
        else if (method === "paypal") {
            let paypalEmail = document.getElementById("paypalEmail").value;
            if (!paypalEmail.includes("@") || !paypalEmail.includes(".")) {
                alert("Enter a valid PayPal email");
                return;
            }
            window.location.href = `../layout/main.php?page=orders.php&success=true`;
            alert("PayPal Payment Successful!");
        }
        else if (method === "phone") {
            if (!validatePhoneNumber()) {
                alert("Enter a valid phone number");
                return;
            }
            window.location.href = `../layout/main.php?page=orders.php&success=true`;
            alert("Mobile Payment Successful!");
        }
    }

    document.getElementById("phoneNumber").addEventListener("input", validatePhoneNumber);
</script>