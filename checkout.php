<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['country'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'order placed successfully!';
   }else{
      $message[] = 'your cart is empty';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* CSS to hide/show QR code */
        #qr-code {
            display: none; /* Initially hidden */
            margin-top: 10px;
        }
    </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

    <form action="" method="POST">

        <h3>Your Orders</h3>

        <div class="display-orders">
            <?php
                $grand_total = 0;
                $cart_items = [];
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if($select_cart->rowCount() > 0){
                    while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                        $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
                        $total_products = implode($cart_items);
                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
            <p> <?= htmlspecialchars($fetch_cart['name']); ?> <span>(<?= '$'.htmlspecialchars($fetch_cart['price']).' x '. htmlspecialchars($fetch_cart['quantity']); ?>)</span> </p>
            <?php
                    }
                } else {
                    echo '<p class="empty">Your cart is empty!</p>';
                }
            ?>
            <input type="hidden" name="total_products" value="<?= htmlspecialchars($total_products); ?>">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($grand_total); ?>">
            <div class="grand-total">Grand total : <span>THB.<?= htmlspecialchars($grand_total); ?>-</span></div>
        </div>

        <h3>Place Your Orders</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Your name :</span>
                <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20" required>
            </div>
            <div class="inputBox">
                <span>Your number :</span>
                <input type="number" name="number" placeholder="Enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
            </div>
            <div class="inputBox">
                <span>Your email :</span>
                <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
                <span>Payment method :</span>
                <select name="method" id="payment-method" class="box" required>
                    <option value="select">Select Payment Method</option>
                    <option value="promtpay">Promtpay</option>
                    <!-- Add more payment methods if needed -->
                </select>
            </div>
            <!-- Address input fields -->
            <div class="inputBox">
                <span>Address line 01 :</span>
                <input type="text" name="flat" placeholder="Address line 01" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
                <span>Address line 02 :</span>
                <input type="text" name="street" placeholder="Address line 02" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
                <span>City :</span>
                <input type="text" name="city" placeholder="City" class="box" maxlength="50" required>
            </div>  
            <div class="inputBox">
                <span>Country :</span>
                <input type="text" name="country" placeholder="Country" class="box" maxlength="50" required>
            </div>
        </div>

        <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Place Order">

    </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
    // JavaScript to handle QR code visibility
    document.getElementById('payment-method').addEventListener('change', function() {
        var qrCodeContainer = document.getElementById('qr-code');
        if (this.value === 'promtpay') {
            qrCodeContainer.style.display = 'block';
        } else {
            qrCodeContainer.style.display = 'none';
        }
    });
</script>

</body>
</html>
