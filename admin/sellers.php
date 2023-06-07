<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_seller'])){
   $seller_id = $_POST['seller_id'];
   $seller_status = $_POST['seller_status'];
   $seller_status = filter_var($seller_status, FILTER_SANITIZE_STRING);
   $seller_payment = $conn->prepare("UPDATE `sellers` SET seller_status = ? WHERE id = ?");
   $seller_payment->execute([$seller_status, $seller_id]);
   $message[] = 'seller status updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_seller = $conn->prepare("DELETE FROM `sellers` WHERE id = ?");
   $delete_seller->execute([$delete_id]);
   header('location:sellers.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sellers Details</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="sellers">

<h1 class="heading">available sellers</h1>

<div class="box-container">

   <?php
      $select_sellers = $conn->prepare("SELECT * FROM `sellers`");
      $select_sellers->execute();
      if($select_sellers->rowCount() > 0){
         while($fetch_sellers = $select_sellers->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> placed on : <span><?= $fetch_sellers['placed_on']; ?></span> </p>
      <p> name : <span><?= $fetch_sellers['name']; ?></span> </p>
      <p> number : <span><?= $fetch_sellers['number']; ?></span> </p>
      <p> address : <span><?= $fetch_sellers['address']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="seller_id" value="<?= $fetch_sellers['id']; ?>">
         <select name="seller_status" class="select">
            <option selected disabled><?= $fetch_sellers['seller_status']; ?></option>
            <option value="pending">pending</option>
            <option value="completed">completed</option>
         </select>
        <div class="flex-btn">
         <input type="submit" value="update" class="option-btn" name="update_seller">
         <a href="sellers.php?delete=<?= $fetch_sellers['id']; ?>" class="delete-btn" onclick="return confirm('delete this seller?');">delete</a>
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no seller registered yet!</p>';
      }
   ?>

</div>

</section>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>