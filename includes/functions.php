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
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['logout']))
            logout();
        elseif (isset($_GET['delete'])) {
            if ($_GET['delete'] == 'account') {
                if ($_GET['user']) {
                    if (deleteuser(trim($_GET['user']), $conn)) {
                        session_destroy();
                        header('Location: ../index.php?accountdelete=true');
                    }
                }
            }
        }elseif (isset($_GET['reset'])) {
            $email = trim($_GET['email']);
            $token = trim($_GET['token']);
            pwdreset($email, $token, $conn);
        }elseif(isset($_GET['comments'])) {
            getcomments(trim($_GET['imgkey']), $conn);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['comment'])) {
            $imgId = trim($_POST['imgkey']);
            $comment = trim($_POST['data']);
            comment($imgId, $comment, $conn);
        } elseif (isset($_POST['update'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $notify = ($_POST['notify'] == 'yes') ? 1 : 0;
            $user = array($username, $email, $notify, $password);
            // print_r($user);
            // die();
            updateuser($user, $conn);
        } elseif (isset($_POST['delimg'])) {
            $img = trim($_POST['key']);
            deleteimg($img, $conn);
        } elseif (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            try {
                $sel_user = $conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
                $sel_user->bindParam(':username', $username);
                $sel_user->execute();
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            $row = $sel_user->fetch(PDO:: FETCH_ASSOC);
            $num = $sel_user->rowCount();
            $hash = $row['password'];

            if ($num > 0) {
                if ($row['isActive'] != 1){
                    header("Refresh:0; ../index.php?inactive=true");
                    exit();
                }
                if (password_verify($password, $hash)) {
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['loggedin'] = true;
                    session_write_close();
                    if ($row['role'] == 'admin') {
                        header("Refresh:0; ../index.php");
                    }
                    else {
                        header("Refresh:0; ../index.php");
                    }
                    exit();
                } else {
                    header("Refresh:0; url=../index.php?login_error=true");
                }
            } else {
                header("Refresh:0; url=../index.php?login_error=true");
            }
        } elseif (isset($_POST['register'])) {
            $username = trim($_POST['username']);
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $email = trim($_POST['email']);
            $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT, array('cost' => 5));
            $user = array($username, $firstname, $lastname, $email, $password );
            signup($user, $conn);
        }elseif (isset($_POST['img'])) {
            saveimg(trim($_POST['key']), trim($_POST['filter']), $conn);
        }elseif (isset($_POST['pwdreset'])) {
            $email = trim($_POST['email']);
            $token = md5(md5(time().$email.rand(0,9999)));
            passreset($email, $token, $conn);
        }elseif (isset($_POST['like'])) {
            $imgId = trim($_POST['imgkey']);
            like($imgId, $conn);
        }elseif (isset($_POST['unlike'])) {
            $imgId = trim($_POST['imgkey']);
            unlike($imgId, $conn);
        }elseif (isset($_POST['email'])) {
            $img = trim($_POST['imgkey']);
            commentemail($img, $conn);
            // echo $img;
        }elseif (isset($_POST['manual'])) {  
            $errors= array();
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            
            if(empty($errors)==true) {
                if(move_uploaded_file($file_tmp,"../images/raw/".$file_name)) {
                    saveimg($file_name, trim($_POST['imgoverlay']), $conn);
                }
            }
        }
    }

function regmail($email, $token) {
        $to = $email;
        
        // Subject
        $subject = 'Activate your Camagru account';

        // Message
        $message = '
        <html>
        <head>
        <title>Activate your Camagru account</title>
        </head>
        <body>
        <p>To activate your Camagru account click <a href="http://localhost:8080/camagru/includes/activate.php?activate='. $token.'">here.</a></p>
        </body>
        </html>
        ';
        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        $headers[] = 'From: Camagru <no-reply@camagru.africa>';
        // Mail it
        if (mail($to, $subject, $message, implode("\r\n", $headers)))
            return true;
        else
            return false;
    }


    /*
    * Functions for adding
    */

    //signup function
    function signup($user, $conn)
    {
        $token = md5(md5(time().$user[1].rand(0,9999)));
        //check if user exists
        try {
            $check = $conn->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
            $check->bindParam(':email', $user[3]);
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
                $signup = $conn->prepare("INSERT INTO users(username, firstname, lastname, email, `password`, token)VALUES (:username, :firstname, :lastname, :email, :pwd, :token)");
                $signup->bindParam(':username', $user[0]);
                $signup->bindParam(':firstname', $user[1]);
                $signup->bindParam(':lastname', $user[2]);
                $signup->bindParam(':email', $user[3]);
                $signup->bindParam(':pwd', $user[4]);
                $signup->bindParam(':token', $token);
                $signup->execute();
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            if ($signup) {
                regmail($user[3], $token);
                header('Location: ../index.php?register=true');
            }
            die();
        }
    }


    ?>
