<?php
    session_start();
$DB_DSN = 'localhost';
$DB_USER = 'root';
$DB_PASSWORD = '';
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


    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['gender']) && ($_POST['sexpref']) && ($_POST['bio']))
        {
            try{
                $sql = $conn->prepare("INSERT INTO userprofile(gender, sexpref, bio) VALUES(:gender, :sexpref, :bio)");
                $sql->bindParam(':gender', $_POST['gender']);
                $sql->bindParam(':sexpref', $_POST['sexpref']);
                $sql->bindParam(':bio', $_POST['bio']);
                $sql->execute();
            }catch(Exception $e)
            {
                echo 'Error: ' . $e->getMessage();
            }
        }
    }

    $sql = $conn->prepare("UPDATE users SET isActive='1'  WHERE id='9' ");
    $sql->execute();
    echo $sql->rowCount() . " records UPDATED successfully";

?>

<style>
<?php include 'css/style.css'; ?>
</style>

    <div class="questionnaire" text-align="center">
        <form action="" name="profile" method="post">
        <label for="pet-select">Tell us more about you</label><br>
            <select name="gender">
            <option value="" >--Please choose your gender--</option>
            <option value="female">Female</option>
            <option value="male">Male</option>
            </select><br>
            <select name="sexpref">
            <option value="" >--Please choose your sexual orientation--</option>
            <option value="straight">Straight</option>
            <option value="gay">Gay</option>
            <option value="bisexual">Bisexual</option>
            </select><br>
            <input id="main" type="text" name="bio" placeholder="Short bio about you :)"value="" maxlength="500" required> <br>
            <button  type="submit">Submit</button>
        </form>
    </div>



