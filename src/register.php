<?php
    session_start();
    require_once 'conn.php';

    if(isset($_POST['register'])){
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['passwordRepeat'];
        if( $login != "" &&
            strlen($login) >= 5 &&
            preg_match('/^[a-zA-Z0-9]+$/', $login) &&
            $email != "" &&
            filter_var($email, FILTER_VALIDATE_EMAIL) &&
            $password != "" &&
            $passwordRepeat != "" &&
            $password === $passwordRepeat &&
            strlen($password) >= 5 &&
            preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)
        ){
            try {
                $token = bin2hex(random_bytes(32));
                $createdAt = date('Y-m-d H:i:s');
                $link = "http://localhost:8000/activate.php?token=$token";
                $message = "Kliknij w link aby aktywowac konto: \n\n$link";
                mail($email, "Aktywacja konta Garden", $message, "From: no-reply@garden.pl");

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("INSERT INTO users (login, email, password, activation_token, activation_token_created_at, confirmed) VALUES (?, ?, ?, ?, ?, ?)");

                $stmt->execute([$login, $email, $password, $token, $createdAt, 0]);

            }catch (PDOException $e){
                if($e->getCode() == "23000"){
                    echo"<script>alert('Użytkownik o podanym loginie lub emailu już istnieje.');</script><script>window.location='index.php'</script>)";
                }else{
                    echo $e->getMessage();
                }
            $conn = null;
            header('Location:loginPanel.php?msg=user_added');
            }
        }else {
            echo"<script>alert('Coś poszło nie tak, spróbuj ponownie');</script>)<script>window.location='index.php'</script>";
        }
    }