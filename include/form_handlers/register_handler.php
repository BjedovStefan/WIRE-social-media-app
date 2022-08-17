<?php

//variables
$fname = "";  //first name
$lname = "";  //Last name
$email = "";  //Email
$email2 = "";  //confirm Email
$password = "";  //Password
$password2 = "";  //Confirm password
$date = "";  //sign up date
$error_array = array();  //holds error messages

if(isset($_POST['reg_button'])){
  //reg form VALUES

  //First name
  $fname = strip_tags($_POST['reg_fname']); //remove html tags
  $fname = str_replace(' ', '',$fname); //remove spaces
  $fname = ucfirst(strtolower($fname)); //only first letter in uppercase
  $_SESSION['reg_fname'] = $fname; //stores first name into session

  //Last name
  $lname = strip_tags($_POST['reg_lname']); //remove html tags
  $lname = str_replace(' ', '',$lname); //remove spaces
  $lname = ucfirst(strtolower($lname)); //only first letter in uppercase
  $_SESSION['reg_lname'] = $lname; //stores first name into session

  //Email
  $email = strip_tags($_POST['reg_email']); //remove html tags
  $email = str_replace(' ', '',$email); //remove spaces
  $email = ucfirst(strtolower($email)); //only first letter in uppercase
  $_SESSION['reg_email'] = $email; //stores first name into session
  //Email confirmation
  $email2 = strip_tags($_POST['reg_email2']); //remove html tags
  $email2 = str_replace(' ', '',$email2); //remove spaces
  $email2 = ucfirst(strtolower($email2)); //only first letter in uppercase
  $_SESSION['reg_email2'] = $email2; //stores first name into session
  //password
  $password = strip_tags($_POST['reg_password']); //remove html tags

  //password confirmation
  $password2 = strip_tags($_POST['reg_password2']); //remove html tags

  //date
  $date = date("Y-m-d"); //Current date

if($email == $email2){
  //if email is in valid format
  if(filter_var($email, FILTER_VALIDATE_EMAIL)){
     $email = filter_var($email, FILTER_VALIDATE_EMAIL);

     //check if email already exists
     $em_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");

     //count the number of rows returned
     $num_rows = mysqli_num_rows($em_check);
     if($num_rows > 0){
       array_push($error_array, "Email already in use!<br>") ;
     }
  }
  else {
    array_push($error_array,  "Invalid email format!<br>") ;
  }
}
else{
  array_push($error_array,  "Emails don't match<br>") ;
}
// checking the lenght
if(strlen($fname) > 25 || strlen($fname) < 2){
  array_push($error_array, "Your first name must be between 2 and 25 characters long<br>");
}
if(strlen($lname) > 25 || strlen($lname) < 2){
  array_push($error_array, "Your last name must be between 2 and 25 characters long<br>");
}
if($password != $password2){
  array_push($error_array, "Your passwords don't match<br>");
}
else {
  if(preg_match('/[^A-Za-z0-9]/', $password)){
    array_push($error_array, "Your password must only contatin english characters or numbers<br>");
  }
}

if(strlen($password) > 30 || strlen($password) < 5){
   array_push($error_array, "Your password must be between 5 and 30 characters<br>");
}

 if(empty($error_array)){
   $password = md5($password); //encrypt password before sending to database

   //Generate username by concatenating first and last name
   $username = strtolower($fname . "_" . $lname);
   $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

   $i = 0;
   //if username exists add number to $username
   while(mysqli_num_rows($check_username_query) != 0){
     $i++; //add 1 to i
     $username = $username . "_" . $i;
     $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
   }

   //profile picture assigment
   $random = rand(1,2);

   if($random == 1)
   $profile_pic = "assets/images/profile_pics/default/default1.png";
   else if($random == 2)
   $profile_pic = "assets/images/profile_pics/default/default2.png";

   $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username','$email', '$password', '$date', '$profile_pic', '0','0','no', ',')");

  array_push($error_array, "<span style='color: #14C800;'>Registration succesful!</span><br>");

  //clear session
  $_SESSION['reg_fname'] = "";
  $_SESSION['reg_lname'] = "";
  $_SESSION['reg_email'] = "";
  $_SESSION['reg_email2'] = "";
 }

}

?>
