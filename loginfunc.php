<?php
    session_start();
    $DB_DSN = 'localhost';
    $DB_USER = 'root';
    $DB_PASSWORD =''; 
    $DB_NAME = 'Matcha';

    try {
        $conn = new PDO("mysql:host=$DB_DSN;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
    
//password ver
    function login($user, $conn)
    {
        if (isset($_POST['login']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            try
            {
                $sel_user = $conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
                $sel_user->bindParam(':username', $username);
                $sel_user->execute();
                header("location: profile.php");
                
    
            } catch(Exception $e)
            {
                echo 'Error: ' . $e->getMessage();
            }
        }
     
    }
?>