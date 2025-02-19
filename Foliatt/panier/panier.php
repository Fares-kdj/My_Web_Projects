<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo.png"/>
    <title>Cart</title>
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

        .order-btn {
    background-color: #003859;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    z-index: 1001;
    transition: 0.3s;}
    .order-btn:hover {
    background: #ddd;
    color: #003859;
}
    </style>
    
    <link rel="stylesheet" href="../Dash/admin/dash.css"> <!-- Add your CSS here -->
</head>
<body>

<div class="dashboard-container">
    <h1>Your Cart</h1>

    <table class="projects-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="cartTableBody">
            <!-- Products will be injected here -->
        </tbody>
    </table>

    <p id="cartTotal" class="success-message">Cart Total: 0 DA</p>
    <button id="checkoutButton" class="order-btn">Proceed to Checkout</button>

</div>

<script>
document.getElementById('checkoutButton').addEventListener('click', function() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Your cart is empty. Add products before proceeding to checkout.');
        return;
    }

    // Save product details with title and total
    let productDetails = cart.map(item => ({
        id: item.id,
        title: item.title,
        price: item.price,
        quantity: item.quantity,
        total: item.price * item.quantity
    }));

    localStorage.setItem('orderDetails', JSON.stringify(productDetails));

    window.location.href = 'orderForm.php'; 
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    displayCart();
});

function displayCart() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let tableBody = document.getElementById("cartTableBody");
    let totalAmount = 0;

    tableBody.innerHTML = "";

    if (cart.length === 0) {
        tableBody.innerHTML = "<tr><td colspan='5' class='error-message'>Your cart is empty.</td></tr>";
    } else {
        cart.forEach((item, index) => {
            let totalItem = item.price * item.quantity;
            totalAmount += totalItem;

            let row = `
                <tr>
                    <td>${item.title}</td>
                    <td>${item.price} DA</td>
                    <td>
                        <button onclick="modifyQuantity(${index}, -1)">-</button>
                        ${item.quantity}
                        <button onclick="modifyQuantity(${index}, 1)">+</button>
                    </td>
                    <td>${totalItem} DA</td>
                    <td>
                        <a href="#" class="delete-link" onclick="removeProduct(${index})">Remove</a>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    document.getElementById("cartTotal").textContent = `Cart Total: ${totalAmount} DA`;
}

function modifyQuantity(index, change) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (cart[index]) {
        cart[index].quantity += change;
        if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
        }
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

function removeProduct(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}
</script>

</body>
</html>
