<?php
include("../../config/config.php");
include("../../include/classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

//if query contains an underscore, assume user is searching for usernames
if(strpos($query, '_') !== false)   //testing if it's the same type
   $userReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
//if there are two words, assume that they are first and last name respoectively
else if(count($names) == 2)
    $userReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%' ) AND user_closed='no' LIMIT 8");
//
else {
  $userReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%' ) AND user_closed='no' LIMIT 8");
}

if($query != ""){
  while ($row = mysqli_fetch_array($userReturnedQuery)) {
    $user = new User($con, $userLoggedIn);

    if($row['username'] != $userLoggedIn)
      $mutualFriends = $user->getMutualFriends($row['username']) . " friends in common";
    else
      $mutualFriends = "";



    echo "<div class='resultDisplay'>
          <a href='" . $row['username'] . "' style='color: #1485BD'>
          <div class='liveSearchProfilePic'>
             <img src='" . $row['profile_pic'] . "'>
          </div>

          <div class='liveSearchText'>
             " . $row['first_name'] . " " . $row['last_name'] . "
             <p>" . $row['username'] . " </p>
            <p id='gray'>" . $mutualFriends . " </p>
          </div>
     </a>
    </div>";

  }
}

 ?>
