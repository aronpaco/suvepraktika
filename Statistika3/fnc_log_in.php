<?php
function sign_in($kasutajanimi, $parool) {
    session_start(); // starts a session

    $login_error = null;
    require_once "config.php";
    $conn = new mysqli($server_host, $server_user_name, $server_password, $dbname);
    $conn->set_charset("utf8");
    $stmt = $conn->prepare("SELECT id, parool FROM admin WHERE kasutajanimi = ?");
    echo $conn->error;
    $stmt->bind_param("s", $kasutajanimi);
    $stmt->bind_result($id_from_db, $password_from_db);
    $stmt->execute();
    if ($stmt->fetch()) {
        // Use password_verify to compare the input password with the hashed password
        if (password_verify($parool, $password_from_db)) {
            $_SESSION["kasutaja_id"] = $id_from_db; // set session variable
            $stmt->close();
            $conn->close();
            header("Location: http://greeny.cs.tlu.ee/~karsrocc/Tarkvara/Sisselogimine/tagasiside.php");
            exit();
        } else {
            $login_error = "Kasutajatunnus või salasõna oli vale!";
        }
    } else {
        $login_error = "Kasutajatunnus või salasõna oli vale!";
    }

    $stmt->close();
    $conn->close();

    return $login_error;
}
?>
