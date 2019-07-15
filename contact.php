<?php
/* Sets what email address the form will be sending the email to */
$myemail  = "alexandra.robinson.2016@outlook.com";

/* Checks what the user enters into the form */
$yourname = check_input($_POST['yourname'], "Enter your name");
$subject  = check_input($_POST['subject'], "Write a subject");
$email    = check_input($_POST['email']);
$comments = check_input($_POST['comments'], "Write your comments");

/* Validates the email address */
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email))
{
    show_error("E-mail address not valid");
}

/* Checks if the recaptcha has been checked */
$captcha;

if(isset($_POST['g-recaptcha-response'])){
    $captcha=$_POST['g-recaptcha-response'];
}

if(!$captcha){
    echo '<h2>Please tick the recaptcha box in the form</h2>';
    exit();
}

/* Sets up the recaptcha */
$secretKey = "6LdPK3oUAAAAABVPWrc2ybsqkNXV7zNAJ2dqudgl";
$ip = $_SERVER['REMOTE_ADDR'];
$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
$responseKeys = json_decode($response,true);

/* Checks how many times a form has been submitted to prevent spamming */
if(intval($responseKeys["success"]) !== 1) {
    echo '<h2>You are spammer ! Get the @$%K out</h2>';
}


else {
    /* Sets up the message in the email */
    $message = "Hi Alex,

    Your contact form has been submitted by $yourname.
    
    You can contact them at $email.
    
    Here is their inquiry:
    
    $comments
    
    ---
    
    Submitted from your website
    
    ";
    
    /* Sends the message to the email address */
    mail($myemail, $subject, $message);
    
    /* Sends the user to the thankyou page and then redirects them back to the main page */
    header('Location: thanks.html');
    exit();
}

function check_input($data, $problem='')
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($problem && strlen($data) == 0)
    {
        show_error($problem);
    }
    return $data;
}

function show_error($myError)
{
?>
    <html>
    <body>

    <b>Please correct the following error:</b><br />
    <?php echo $myError; ?>

    </body>
    </html>
<?php
exit();
}
?>
