<?php
$conn = mysqli_connect("localhost", "root", "", "login_db");

if (!$conn) {
    die("DB Connection failed");
}

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users 
            WHERE username='$username' 
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

    session_start();

    $row = mysqli_fetch_assoc($result);

    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];

    header("Location: dashboard.php");
    exit();
}
    else {
        echo "<br><h2 style='color:red;text-align:center;'>Invalid Login</h2>";
        echo "<a href='login.html'><center>
        <button style='
            padding:10px 15px;
            margin:5px;
            border:none;
            border-radius:5px;
            background-color:#007BFF;
            color:white;
            cursor:pointer;
        '>Login</button></center>
      </a>";
    }
}
?>