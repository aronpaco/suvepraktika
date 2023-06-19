<?php
require_once "fnc_log_in.php"; 
$password_error = null;
$email_error = null;
$login_error = null;
if(isset($_POST["login_submit"])){
    $login_error = sign_in($_POST["user_name"], $_POST["password_input"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="logi_sisse.css">
    <title>Logi sisse</title>
</head>
<body>
    <div id="main">
        <div class="container"> <!-- Here is the added container div -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="text" name="user_name" placeholder="Kasutajanimi">
                <input type="password" name="password_input" placeholder="salasõna">
                <input type="submit" name="login_submit" value="Logi sisse"><span><strong><?php echo $login_error; ?></strong></span>
            </form>
        </div>
    </div>
</body>
</html>
