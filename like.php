<!DOCTYPE html>


<html >
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  </head>
  <body>
     <style type="text/css">
      *{
        font-family: Arial, Helvetica, sans-serif;
      }

     body{
       background-color: #fff;
     }

      form{
       position: absolute;
       top: 0;
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




      //get post id
      if(isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
      }
           $get_likes = mysqli_query($con,"SELECT likes, added_by FROM posts WHERE id='$post_id'");
           $row = mysqli_fetch_array($get_likes);
           $total_likes = $row['likes'];
           $user_liked = $row['added_by'];

           $user_detail_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user_liked'");
           $row = mysqli_fetch_array($user_detail_query);
           $total_user_likes = $row['num_likes'];

           //like button
            if(isset($_POST['like_button'])){
              $total_likes++;
              $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
              $total_user_likes++;
              $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
              $insert_user = mysqli_query($con,"INSERT INTO likes VALUES('', '$userLoggedIn', '$post_id')");

              //insert notification
              if($user_liked != $userLoggedIn){
                $notifications = new Notification($con, $userLoggedIn);
                $notifications->insertNotification($post_id, $user_liked, "like");
              }

          }



           //unlike button
           if(isset($_POST['unlike_button'])){
             $total_likes--;
             $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
             $total_user_likes--;
             $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
             $insert_user = mysqli_query($con,"DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
         }

           //Check for previous likes
           $check_query = mysqli_query($con, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
           $num_rows = mysqli_num_rows($check_query);

           if($num_rows > 0){
             echo '<form action="like.php?post_id=' . $post_id . '" method="post">
                  <input type="submit" class="comment_like" name="unlike_button" value="Unlike">
                  <div class="like_value">
                     '. $total_likes .' Likes
                  </div>
                </form>
                  ';
           }
           else {
             echo '<form action="like.php?post_id=' . $post_id . '" method="post">
                  <input type="submit" class="comment_like" name="like_button" value="Like">
                  <div class="like_value">
                     '. $total_likes .' Likes
                  </div>
                </form>
                  ';
           }
       ?>
  </body>
</html>