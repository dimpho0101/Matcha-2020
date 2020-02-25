
<div class="index">
            <?php if ($_GET['userexists']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account already exists!</p>
            <?php elseif ($_GET['usernameexists']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>The username is taken!</p>
            <?php elseif ($_GET['login_error']): ?>
            <?php elseif ($_GET['login_error']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>There was an error signing in!</p>
            <?php elseif ($_GET['register']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account created successfully! Follow instructions sent to your email to activate account.</p>
            <?php elseif ($_GET['active']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account is aleady activated!</p>
            <?php elseif ($_GET['activated']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account successfully activated!</p>
            <?php elseif ($_GET['inactive']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account is not activated! Check your email for link</p>
            <?php elseif ($_GET['loggedin'] == 'false'): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>You need to log in to access the dashboard</p>
            <?php elseif ($_GET['notoken']): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>The activation token does not exists!!</p>
            <?php elseif ($_GET['reset'] == 'false'): ?>
            <p class="alert red"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Password Reset failed</p>
            <?php elseif ($_GET['pwdreset']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Password Reset email sent!</p>
            <?php elseif ($_GET['accountdelete']): ?>
            <p class="alert success"><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>Account Deleted successfully!! Pheeeeeeew!!! finally you are gone</p>
            <?php endif; ?>
</div>
<!-- 
        <div id="login" class="modal container">
                <form class="modal-content animate" action="./includes/functions.php" method="post">
                    <span onclick="document.getElementById('login').style.display='none'"
                    class="close" title="Close Modal">&times;</span>
                    <div class="container">
                        <p><h3>Login</h3></p>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <input type="password" placeholder="Enter Password" name="password" required>
                        <input type="hidden" name="login" value="login">
                        <button type="submit">Login</button>
                    </div>
                    <div class="container">
                        <button type="button" onclick="document.getElementById('login').style.display='none'" class="cancelbtn">Cancel</button>
                        <span class="psw">Forgot <a href="#pwdreset" onclick="document.getElementById('pwdreset').style.display='block';document.getElementById('login').style.display='none'">password?</a></span>
                    </div>
                </form>
            </div> -->

    <!-- Signup-->
    <div id="signup" class="modal container">
    <!-- Modal Content -->
        <form class="modal-content animate" action="./includes/functions.php" method="post">
            <span onclick="document.getElementById('signup').style.display='none'"
            class="close" title="Close Modal">&times;</span>
            <div class="container">
                <p><h3>Register</h3></p>
                <input type="text" placeholder="Enter Username" name="username" required>
                <input type="text" placeholder="Enter Firstname" name="firstname" required>
                <input type="text" placeholder="Enter Lastname" name="lastname" required>
                <input type="email" placeholder="Enter Email" name="email" required>
                <input type="password" id="psw" placeholder="Enter Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                <input type="hidden" name="register" value="register">
                <button type="submit">Register</button>
            </div>

            <div class="container" style="background-color:#f1f1f1">
                <button type="button" onclick="document.getElementById('signup').style.display='none'" class="cancelbtn">Cancel</button>
                <span class="psw">Forgot <a href="#">password?</a></span>
                <div id="message">
                    <h3>Password must contain the following:</h3>
                    <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                    <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                    <p id="number" class="invalid">A <b>number</b></p>
                    <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                </div>
            </div>
        </form>
    </div> 

        <!-- pwdreset-->
        <div id="pwdreset" class="modal container">
    <!-- Modal Content -->
        <form class="modal-content animate" action="./includes/functions.php" method="post">
            <span onclick="document.getElementById('pwdreset').style.display='none'"
            class="close" title="Close Modal">&times;</span>
            <div class="container">
                <p><h3>Password reset</h3></p>
                <input type="email" placeholder="Enter Email" name="email" required>
                <input type="hidden" name="pwdreset" value="pwdreset">
                <button type="submit">Reset password</button>
            </div>
        </form>
    </div> 