<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="about.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">
            <div class="about-container">
                <h2>About Us</h2>
                <p>Welcome to DeawScents - your destination for exquisite fragrances that capture your essence. We curate a unique selection of perfumes, each crafted to tell its own story and evoke a range of emotions.</p>
                
                <div class="about-content">
                <img src="ds.png">
                    <div class="about-text">
                        <h3>Our Story</h3>
                        <p>Born from a passion for the art of perfumery, DeawScents started with a simple goal: to bring you the perfect scent. Our journey has led us to explore and select the finest fragrances, blending tradition with modern elegance.</p>
                        
                    </div>
                </div>

                <div class="mission-vision">
                    <h3>Our Mission</h3>
                    <p>Our mission is to help you find a fragrance that truly represents who you are. We strive to offer a collection of perfumes that inspire and delight, making every scent a personal experience.</p>

                    <h3>Our Vision</h3>
                    <p>We aim to be a global leader in luxury fragrances, known for our exceptional quality and unique selection. Our vision is to connect with fragrance enthusiasts worldwide, offering them an unforgettable olfactory experience.</p>
                </div>
            </div>
        </section>
    </main>
<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
        slidesPerView:1,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>