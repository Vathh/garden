<?php
    session_start();
    require_once 'conn.php';

    if(isset($_POST['register'])){
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['passwordRepeat'];
        if( $login != "" &&
            strlen($login) > 5 &&
            preg_match('/^[a-zA-Z0-9]+$/', $login) &&
            $email != "" &&
            filter_var($email, FILTER_VALIDATE_EMAIL) &&
            $password != "" &&
            $passwordRepeat != "" &&
            $password === $passwordRepeat &&
            strlen($password) > 5 &&
            preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)
        ){
            try {
                $token = bin2hex(random_bytes(32));
                $link = "http://localhost:8000/activate.php?token=$token";
                $message = "Kliknij w link aby aktywowac konto: \n\n$link";
                mail($email, "Aktywacja konta Garden", $message, "From: no-reply@garden.pl");

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "INSERT INTO users (login, email, password, activation_token, confirmed) VALUES ('$login', '$email', '$password', '$token', false)";
                $conn->exec($sql);
            }catch (PDOException $e){
                if($e->getCode() == "23000"){
                    echo"<script>alert('Użytkownik o podanym loginie lub emailu już istnieje.');</script><script>window.location='index.php'</script>)";
                }else{
                    echo $e->getMessage();
                }
            $_SESSION['message']=array("text"=>"Pomyślnie dodano użytkownika.","alert-type"=>"success");
            $conn = null;
            header('location:index.php');
            }
        }else {
            echo"<script>alert('Coś poszło nie tak, spróbuj ponownie');</script>)<script>window.location='index.php'</script>";
        }
    }