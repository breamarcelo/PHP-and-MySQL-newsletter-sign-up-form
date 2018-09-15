<?php
// message and class variables
  $msg = '';
  $msgClass = '';

// database variables
  $servername = "localhost";
  $username = "user";
  $password = "password";
  $dbname = "database";

// confirmation email to be sent
  $to = '';
  $subject = 'Thank you for subscribing';
  $body = '
  <html>
  // email content
  </html>
  ';

  $headers = "MIME-Version: 1.0" ."\r\n";
  $headers .= "Content-Type:text/html; charset=UTF-8" . "\r\n";
  $headers .= "From: webmaster@example.com";
  $headers .= "Cc: myboss@example.com";


// create database connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(filter_has_var(INPUT_POST, 'submit')){
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

// validate form
    if(!empty($name) && !empty($email)){
      if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
      $msg = '&#10006; Please enter a valid email address';
      $msgClass = 'error';
      } else {
        // check database if email already registered, echo error message
        mysqli_query($conn,"SELECT email FROM table WHERE email = '".$email."'");
        if(mysqli_affected_rows($conn) > 0){
          $msg = '&#10006; This email address is already registered';
          $msgClass = 'error';
        } else {
          // insert data to database, echo success message, send email, and empty form fields
          $sql = "INSERT INTO table (name, email) VALUES ('".$name."', '".$email."')";
          
          if($conn->query($sql) === TRUE){
            $msg = '&#10004; Registered successfully';
            $msgClass = 'success';

            $to = $email;
            mail($to,$subject,$body,$headers);

            $name = '';
            $email = '';
          } else {
            $msg = '&#10006; Unnable to complete registration';
            $msgClass = 'error';
          }
        }
      }

    } else {
      $msg = '&#10006; Please fill in all fields';
      $msgClass = 'error';
    }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <title>Subscribe to newsletter</title>
    <meta name="viewport" content="width=device-width, initial-scale1.0" />
    <link rel="stylesheet" href="styles.css">

  </head>
  <body>
    <div class="container">
      <div class="content">
        <h3>SUBSCRIBE TO OUR MAILING LIST</h3>
        <!-- display error or success message -->
        <?php if($msg != ''): ?>
          <span class="<?php echo $msgClass; ?>"><?php echo $msg ?></span>
        <?php endif; ?>
        <br>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input class="input" type="text" name="name" placeholder="name" value="<?php echo isset($_POST['name']) ? $name : ''; ?>" autofocus><br>
          <input class="input" type="text" name="email" placeholder="e-mail" value="<?php echo isset($_POST['email']) ? $email : ''; ?>"><br>
          <button class="submit" type="submit" name="submit">SUBMIT</button>
        </form>
      </div>
    </div>
  </body>
</html>
