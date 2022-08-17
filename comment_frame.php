<!DOCTYPE html>


<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  </head>
  <body>

    <style type="text/css">
    *{
       font-size: 12px;
       font-family: Arial,Helvetica, sans-serif;
    }
    </style>

    <?php
        require 'config/config.php';
        include("include/classes/User.php");
        include("include/classes/Post.php");
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


    <script>
       function toggle() {
         var element = document.getElementById("comment_section");

         if(element.style.display == "block")
           element.style.display = "none";
         else
           element.style.display = "block";
       }
    </script>

    <?php
        //get post id
        if(isset($_GET['post_id'])) {
          $post_id = $_GET['post_id'];
        }

        $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
        $row = mysqli_fetch_array($user_query);

        $posted_to = $row['added_by'];
        $user_to = $row['user_to'];

        if(isset($_POST['postComment' . $post_id])){
          $post_body = $_POST['post_body'];
          $post_body = mysqli_escape_string($con, $post_body);
          $date_time_now = date("Y-m-d H:i:s");
          $insert_post = mysqli_query($con, "INSERT INTO comments VALUES ('', '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no','$post_id')");

          if($posted_to != $userLoggedIn){
            $notifications = new Notification($con, $userLoggedIn);
            $notifications->insertNotification($post_id, $posted_to, "comment");
          }

         if($user_to != 'none' && $user_to != $userLoggedIn){
            $notifications = new Notification($con, $userLoggedIn);
            $notifications->insertNotification($post_id, $user_to, "profile_comment");
          }

          $get_commenters = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id'");
          $notified_users = array();
          while ($row = mysqli_fetch_array($get_commenters)) {
            if($row['posted_by'] != $posted_to && $row['posted_by'] != $user_to && $row['posted_by'] != $userLoggedIn && !in_array($row['posted_by'], $notified_users)){
              $notifications = new Notification($con, $userLoggedIn);
              $notifications->insertNotification($post_id, $row['posted_by'], "comment_non_owner");

              array_push($notified_users, $row['posted_by']);
            }
          }


          echo "<p>Comment Posted! </p>";
        }

     ?>

     <form id="comment_form" action="comment_frame.php?post_id=<?php echo $post_id; ?>" name="postComment<?php echo $post_id; ?>"  method="post" >
          <textarea name="post_body"  placeholder='Write your comment...'></textarea>
          <input type="submit" name="postComment<?php echo $post_id; ?>" value="Comment">
     </form>

    <!-- Load comments  -->

     <?php
       $get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
       $count = mysqli_num_rows($get_comments);

       if($count != 0){
         while ($comment = mysqli_fetch_array($get_comments)) {
           $comment_body = $comment['post_body'];
           $posted_to = $comment['posted_to'];
           $posted_by = $comment['posted_by'];
           $date_added = $comment['date_added'];
           $removed = $comment['removed'];

           //timeframe
           $date_time_now = date("Y-m-d H:i:s");
           $start_date = new DateTime($date_added); //Time of post
           $end_date = new DateTime($date_time_now); //Current time
           $interval = $start_date->diff($end_date); //diffenrence between dates
           if($interval->y >= 1){
             if($interval == 1)
               $time_message = $interval->y . " year ago"; // 1 year ago
              else
               $time_message = $interval->y . " year ago"; // 1+ year ago
           }
           elseif ($interval->m >= 1) {
             if($interval->d == 0){
               $days = " ago";
             }
             elseif ($interval->d == 1) {
               $days = $interval->d . " day ago";
             }
             else {
               $days = $interval->d . " days ago";
             }

             if($interval->m == 1){
               $time_message = $interval->m . " month". $days;
             }
             else {
               $time_message = $interval->m . " months". $days;
             }
           }
          elseif ($interval->d >= 1) {
            if ($interval->d == 1) {
              $time_message = "yesterday";
            }
            else {
              $time_message = $interval->d . " days ago";
            }
          }
          elseif ($interval->h >= 1) {
            if ($interval->h == 1) {
              $time_message = $interval->h . " hour ago";
            }
            else {
              $time_message = $interval->h . " hours ago";
            }
          }
          elseif ($interval->i >= 1) {
            if ($interval->i == 1) {
              $time_message = $interval->i . " minute ago";
            }
            else {
              $time_message = $interval->i . " minutes ago";
            }
          }
          else{
            if ($interval->s < 30) {
              $time_message = "just now";
            }
            else {
              $time_message = $interval->s . " seconds ago";
            }
          }

          $user_obj = new User($con, $posted_by);

          ?>
              <div class="comment_section">
                    <a href="<?php echo $posted_by ?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic(); ?>" title="<?php echo $posted_by; ?>" style="float:left;" height="30px"></a>
                    <a href="<?php echo $posted_by ?>" target="_parent"><b><?php echo $user_obj->getFirstAndLastName(); ?></b></a>
                    &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message . "<br>" . $comment_body; ?>
                    <hr>
              </div>
         <?php
         }
       }
       else {
         echo "<center><br><p style='color:#aaaaaa;'>No Comments to Show!<p></center>";
       }
      ?>



  </body>
</html>
