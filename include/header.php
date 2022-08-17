<!DOCTYPE html>
<?php
require 'config/config.php';
include("include/classes/User.php");
include("include/classes/Post.php");
include("include/classes/Message.php");
include("include/classes/Notification.php");



if(isset($_SESSION['username'])){
  $userLoggedIn = $_SESSION['username'];
  $user_detail_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
  $user = mysqli_fetch_array($user_detail_query);
}
else{
  header("Location: register.php");
}
?>


<html>
  <head>
    <meta charset="utf-8">
    <title>Welcome to Wire</title>

    <!--JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
    <script src="assets/js/wire.js"></script>
    <script src="assets/js/jquery.jcrop.js"></script>
    <script src="assets/js/jcrop_bits.js"></script>


    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">

  </head>
  <body>

      <div class="top_bar">
         <div class="logo">
           <!-- Insted of a, img can be put  -->
              <a href="index.php">WIRE</a>
         </div>

        <div class="search">
          <form class="" name="searchForm" action="search.php" method="GET">
            <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input" >
            <div class="button_holder">
              <img src="assets/images/icons/mag_glass.png" alt="">
            </div>
          </form>

          <div class="search_results">
          </div>

          <div class="search_results_footer_empty">
          </div>


        </div>



         <nav>
            <?php
               //unread mess
                $message = new Message($con, $userLoggedIn);
                $num_messages = $message->getUnreadNumber();

               //unread notification
                $notifications = new Notification($con, $userLoggedIn);
                $num_notifications = $notifications->getUnreadNumber();

                //unread friend requests
                $user_obj = new User($con, $userLoggedIn);
                $num_requests = $user_obj->getNumOfFriendRequests();
             ?>


           <a href="<?php echo $userLoggedIn; ?>">
             <?php echo $user['first_name']; ?>
           </a>
           <a href="index.php"><i class="fa fa-home"></i></a>
           <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
             <i class="fa fa-envelope"></i>
             <?php
         				if($num_messages > 0)
         				 echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
 				      ?>
            </a>
           <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
             <i class="fa fa-bell-o"></i>
             <?php
                 if($num_notifications > 0)
                  echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
               ?>
           </a>
           <a href="requests.php">
             <i class="fa fa-users"></i>
             <?php
                 if($num_requests > 0)
                  echo '<span class="notification_badge" id="unread_requests">' . $num_requests . '</span>';
               ?>
           </a>
           <a href="settings.php"><i class="fa fa-cog"></i></a>
           <a href="include/handlers/logout.php"><i class="fa fa-sign-out"></i></a>
         </nav>

         <div class="dropdown_data_window" style="height:0px; border:none;" >   </div>
            <input type="hidden" id="dropdown_data_type" name="" value="">


      </div>

      <script>
        var userLoggedIn = '<?php echo $userLoggedIn; ?>';

        $(document).ready(function() {

          $('.dropdown_data_window').scroll(function(){

            var inner_height = $('.dropdown_data_window').innerHeight(); //div containing posts
            var scroll_top = $('.dropdown_data_window').scrollTop();
            var page = $('.dropdown_data_window').find('.nextPageDropDownData').val();
            var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

            if((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false' ){

            var pageName; //holds name of page to send ajax request to
            var type = $('#dropdown_data_type').val();

            if(type == 'notification')
               pageName = "ajax_load_notifications.php";
            else if(type == 'message' )
               pageName = "ajax_load_messages.php"

            var ajaxReq =  $.ajax({
                url: "include/handlers/" + pageName,
                type: "POST",
                data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                cache:false,
                  success: function(response){
                    $('.dropdown_data_window').find('.nextPageDropDownData').remove(); //remove current nextPage
                    $('.dropdown_data_window').find('.noMoreDropdownData').remove();


                    $('.dropdown_data_window').append(response);
                  }
              });

            } //end if

            return false;
          });//End $(window).scroll(function()

        });

      </script>


      <div class="wrapper">
