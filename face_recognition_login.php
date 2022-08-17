<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style>
        body {
          background-color: #ffffff;
            margin: 0;
            padding: 0;
            width: 100vw;
            height:100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        canvas  {position: absolute;}
        .title{
           font-family: "Roboto", sans-serif;
          text-transform: uppercase;
          position: absolute;
          top: 3%;
          left: 33%;
          cursor: pointer;
          font-size: 30px;
        }
        button {
          font-family: "Roboto", sans-serif;
          text-transform: uppercase;
          outline: 0;
          background: #000;
          border: 0;
          padding: 10px 5px;
          color: #FFFFFF;
          font-size: 14px;
          transition: all 0.3 ease;
          cursor: pointer;
          font-size: 100%;
          position: absolute;
          top: 92%;
          left: 32.5%;
          width: 35%;
        }


</style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

  </head>
  <body>
      <p class="title">Face Recognition Login to WIRE</p>
      <br>
      <video autoplay id="vid" width="720" height="560" muted></video>
      <br>
      <a href="register.php">
         <button type="button" class="face_btn"><< Back to login page</button>
      </a>

    <script src="assets/lib/face-api/face-api.min.js"></script>
    <script src="assets/lib/face-api/face.js"></script>
  </body>
</html>
