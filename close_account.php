<?php
include("include/header.php");

if(isset($_POST['cancel'])){
  header("Location: settings.php");
}

if(isset($_POST['close_account'])){
  $close_query = mysqli_query($con, "UPDATE users SET user_closed='yes' WHERE username='$userLoggedIn'");
  session_destroy();
  header("Location: register.php");
}

 ?>

 <div class="main_column column">
   <h4>Close Account</h4>
   You are leaving us? :( <br><br>
   I hope you have a good reason, but if you want you can always come back!<br><br>

   <form class="" action="close_account.php" method="post">
     <input type="submit" name="close_account" id="close_account" value="Close It!" class="danger settings_submit">
     <input type="submit" name="cancel" id="update_details" value="I'm Staying!" class="info settings_submit">
   </form>
 </div>
