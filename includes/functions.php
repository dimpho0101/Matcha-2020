<?php
    session_start();
    $DB_DSN = 'localhost';
    $DB_USER = 'root';
    $DB_PASSWORD = 'coding01';
    $DB_NAME = 'Matcha';
    //connect to the newly created database
    try {
        $conn = new PDO("mysql:host=$DB_DSN;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }


  //signup function
    function signup($user, $conn)
    {
        $token = md5(md5(time().$user[1].rand(0,9999)));
        //check if user exists
        try {
            $check = $conn->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
            $check->bindParam(':email', $user[1]);
            $check->bindParam(':username', $user[0]);
            $check->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        // check if user EXISTS
        $num = $check->rowCount();
        $usernamecheck = $check -> fetch(PDO::FETCH_ASSOC);
        if ($num > 0) {
            if ($usernamecheck['username'] == $user[0]) {
                header('Location: ../index.php?usernameexists=true');
            }else{
                header('Location: ../index.php?userexists=true');
            }   
        } else{
            try {
                $signup = $conn->prepare("INSERT INTO users(username,firstname, lastname, email, pwd, token)VALUES (:username, :email, :pwd, :token)");
                $signup->bindParam(':username');
                $signup->bindParam(':firstname', $user[1]);
                $signup->bindParam(':lastname', $user[2]);
                $signup->bindParam(':email', $user[3]);
                $signup->bindParam(':pwd', $user[4]);
                $signup->bindParam(':token', $token);
                $signup->execute();
            var_dump($signup);
            echo "action is done";
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            if ($signup) {
                regmail($user[1], $token);
                header('Location: ../index.php?register=true');
            }
            die();
        }
    }

    ?>