<?php
include("../../config/config.php");
include("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

if(strpos($query, "_") != false) {
   $userReturned = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");

}
elseif (count($names) == 2) {
  $userReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%') AND user_closed='no' LIMIT 8");
}
else {
  $userReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' OR last_name LIKE '%$names[0]%') AND user_closed='no' LIMIT 8");
}

if($query != "" ){
  while($row = mysqli_fetch_array($userReturned)){
    $user = new User($con, $userLoggedIn);

    if($row['username'] != $userLoggedIn){
      $mutualFriends = $user->getMutualFriends($row['username']) . " friend in common";
    }
    else {
      $mutualFriends = "";
    }

    if($user->isFriend($row['username'])){
      echo "<div class='resultDisplay'>
            <a href='messegas.php?u='" . $row['username'] . "'style='color:#00'>
                <div class='liveSearchProfilePic'>
                  <img src='" . $row['profile_pic'] . "'>
                </div>

                <div class='liveSearchText'>
                  " .$row['first_name'] . " " . $row['last_name'] . "
                  <p style='margin:0;'>" . $row['username'] . "</p>
                  <p id='grey'>" .$mutualFriends . "</p>
                </div>
            </a>
           </div>";
    }
  }
}

 ?>
