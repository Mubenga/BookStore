<?php
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload PHPMailer classes
require 'vendor/autoload.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['send'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'Message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('Query failed');
      $message[] = 'Message sent successfully!';

      // Send email notification
      $mail = new PHPMailer(true);

      try {
         //Server settings
         $mail->SMTPDebug = 0;                      // Enable verbose debug output
         $mail->isSMTP();                           // Set mailer to use SMTP
         $mail->Host       = 'smtp.gmail.com';      // Specify main and backup SMTP servers
         $mail->SMTPAuth   = true;                  // Enable SMTP authentication
         $mail->Username   = 'gedeon.mub@gmail.com';// SMTP username
         $mail->Password   = 'trjp yhdg ysga hgls'; // SMTP password
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` encouraged
         $mail->Port       = 587;                   // TCP port to connect to

         //Recipients
         $mail->setFrom('gedeon.mub@gmail.com', 'Mailer');
         $mail->addAddress('gedeon.mub@gmail.com'); // Add a recipient

         // Content
         $mail->isHTML(true);                       // Set email format to HTML
         $mail->Subject = 'New Contact Form Submission';
         $mail->Body    = "You have received a new message from the contact form on your website.<br><br>" .
                          "Name: $name<br>" .
                          "Email: $email<br>" .
                          "Number: $number<br>" .
                          "Message:<br>$msg";

         $mail->send();
      } catch (Exception $e) {
         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Contact Us</h3>
   <p> <a href="home.php">Home</a> / Contact </p>
</div>

<section class="contact">
   <form action="" method="post">
      <h3>Say Something!</h3>
      <input type="text" name="name" required placeholder="Enter your name" class="box">
      <input type="email" name="email" required placeholder="Enter your email" class="box">
      <input type="number" name="number" required placeholder="Enter your number" class="box">
      <textarea name="message" class="box" placeholder="Enter your message" id="" cols="30" rows="10"></textarea>
      <input type="submit" value="Send Message" name="send" class="btn">
   </form>
</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
