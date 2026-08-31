<?php
$conn = mysqli_connect("localhost", "root", "", "login_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $id = $_POST['id'];
    $role = $_POST['role'];
    
    $sql = "INSERT INTO users (username, password, id)
            VALUES ('$username', '$password', '$id')";

    if (mysqli_query($conn, $sql)) {
    echo "<br><h2 style='color:green;'><center>Registration Successful</center></h2>";
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
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>