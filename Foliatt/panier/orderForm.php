<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.png"/>
    <title>Order Form</title>
    <style>
        body {
    background-image: url("../assets/bg.png");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    background-attachment: fixed; 
    height: 100vh;
    min-height: 100vh;
    background-color: #f4f4f4; 
}
.logo {
    position: fixed;
    color: white;
    text-decoration: none;
}

/* Image du logo */
.logo-img {
    width: 200px;
    height: auto;
    margin: 50px;
}

        /* Styles pour le formulaire de commande */
        #orderForm {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #003859;
            margin-bottom: 15px;
            padding-bottom: 10px;
            text-align: center;
        }

        #orderForm h2 {
            font-size: 1.5rem;
            color: #003859;
            margin-bottom: 15px;
            border-bottom: 2px solid #003859;
            padding-bottom: 10px;
        }

        #orderForm label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        #orderForm input[type="text"],
        #orderForm input[type="tel"],
        #orderForm textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        #orderForm input[type="text"]:focus,
        #orderForm input[type="tel"]:focus,
        #orderForm textarea:focus {
            border-color: #003859;
            outline: none;
        }

        #orderForm textarea {
            resize: vertical;
            min-height: 100px;
        }

        #orderForm button {
    display: block;
    width: 25%;
    padding: 12px;
    background-color: #003859;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin: 20px auto; /* Centre horizontalement */
    text-align: center;
}


        #orderForm button:hover {
            background: #ddd;
            color: #003859;
        }

        /* Styles pour le bouton de retour */
        .return-button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            margin-top: 10px;
        }

        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .success-message {
            color: green;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        @media screen and (max-width: 768px) {
    .logo {
        display: none;
    }
}

    </style>
</head>
<body>
        <a href="index.php">
            <div class="logo"> 
                <img src="../assets/Folia-white.png" alt="Logo" class="logo-img">
            </div>
        </a>    
<div class="dashboard-container">
    <form id="orderForm">
        <h1>Order Form</h1>
        <div id="successMessage" class="success-message"></div>

        <h2>Order Details</h2>
        <div id="orderDetails"></div>

        <h2>Customer Information</h2>
        <div>
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>
        </div>
        <div>
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>
        </div>
        <div>
            <label for="phoneNumber">Phone Number:</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" required>
        </div>
        <div>
            <label for="address">Address:</label>
            <textarea id="address" name="address" required></textarea>
        </div>

        <button type="submit" id="submitOrderButton">Submit Order</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let orderDetails = JSON.parse(localStorage.getItem('orderDetails')) || [];
    let orderDetailsContainer = document.getElementById("orderDetails");
    let totalAmount = 0;

    if (orderDetails.length === 0) {
        orderDetailsContainer.innerHTML = "<p class='error-message'>Your cart is empty. Cannot proceed to order.</p>";
        return;
    }

    orderDetails.forEach(item => {
        totalAmount += item.total;

        let productRow = `
            <div class="order-item">
                <p><strong>Product:</strong> ${item.title}</p>
                <p><strong>Price:</strong> ${item.price} DA</p>
                <p><strong>Quantity:</strong> ${item.quantity}</p>
                <p><strong>Total:</strong> ${item.total} DA</p>
            </div>
        `;
        orderDetailsContainer.innerHTML += productRow;
    });

    let totalRow = `
        <div class="order-total">
            <p><strong>Order Total: ${totalAmount} DA</strong></p>
        </div>
    `;
    orderDetailsContainer.innerHTML += totalRow;
});

document.getElementById('orderForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let firstName = document.getElementById('firstName').value;
    let lastName = document.getElementById('lastName').value;
    let phoneNumber = document.getElementById('phoneNumber').value;
    let address = document.getElementById('address').value;
    let orderDetails = JSON.parse(localStorage.getItem('orderDetails')) || [];

    let orderData = {
        firstName,
        lastName,
        phoneNumber,
        address,
        orderDetails
    };

    fetch('./submitorder.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData),
    })
    .then(response => response.json())
    .then(data => {
        let successMessage = document.getElementById('successMessage');
        if (data.success) {
            successMessage.innerHTML = "✅ Order successfully submitted!";
            localStorage.removeItem('cart');
            localStorage.removeItem('orderDetails');

            // Ajouter le bouton de retour
            let backButton = document.createElement("button");
            backButton.textContent = "Return to Home";
            backButton.classList.add("return-button");
            backButton.onclick = function() {
                window.location.href = "../index.php";
            };
            successMessage.appendChild(backButton);
        } else {
            successMessage.innerHTML = "❌ Error: " + data.message;
            successMessage.style.color = "red";
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let successMessage = document.getElementById('successMessage');
        successMessage.innerHTML = "❌ An error occurred while submitting your order.";
        successMessage.style.color = "red";
    });
});
</script>

</body>
</html>
