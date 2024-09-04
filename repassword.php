<?php

include 'components/connect.php';

if(isset($_GET['token'])){
   $token = $_GET['token'];

   // ตรวจสอบโทเค็น
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE reset_token = ? AND reset_expiry > NOW()");
   $select_user->execute([$token]);

   if($select_user->rowCount() > 0){
      if(isset($_POST['submit'])){
         $new_pass = sha1($_POST['new_pass']);
         $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
         $confirm_pass = sha1($_POST['confirm_pass']);
         $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

         if($new_pass == $confirm_pass){
            // อัปเดตรหัสผ่านในฐานข้อมูล
            $update_pass = $conn->prepare("UPDATE `users` SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
            $update_pass->execute([$new_pass, $token]);

            $message[] = 'รีเซ็ตรหัสผ่านเรียบร้อยแล้ว!';
         }else{
            $message[] = 'รหัสผ่านไม่ตรงกัน!';
         }
      }
   }else{
      $message[] = 'โทเค็นไม่ถูกต้องหรือหมดอายุ!';
   }
}else{
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>รีเซ็ตรหัสผ่าน</title>
   <link rel="stylesheet" href="user.css"> <!-- ใส่ไฟล์ CSS ของคุณ -->
</head>
<body>

<section class="form-container">
   <form action="" method="post">
      <h3>รีเซ็ตรหัสผ่าน</h3>
      <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '
            <div class="message">
               <span>'.$msg.'</span>
               <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
         }
      }
      ?>
      <br>
      <input type="password" name="new_pass" required placeholder="กรอกรหัสผ่านใหม่" maxlength="20" class="box">
      <input type="password" name="confirm_pass" required placeholder="ยืนยันรหัสผ่านใหม่" maxlength="20" class="box">
      <input type="submit" value="รีเซ็ตรหัสผ่าน" class="btn" name="submit">
   </form>
</section>

</body>
</html>
