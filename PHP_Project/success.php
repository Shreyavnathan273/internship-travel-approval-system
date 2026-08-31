<?php
session_start();
?>

<html>
<head>
<title>Success</title>
<style>
body {
    font-family: Arial;
    background-color: #f4f6f8;
    text-align: center;
}

.box {
    width: 40%;
    margin: 100px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

button {
    padding: 10px 15px;
    margin: 10px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}

.dashboard {
    background-color: #007BFF;
}

.logout {
    background-color: red;
}
</style>
<script>
setTimeout(function(){
    window.location.href = "dashboard.php";
}, 3000);
</script>

</head>

<body>

<div class="box">

<h2>Form Submitted Successfully</h2>

<a href="javascript:history.back()" >
    <button style="background:gray;">⬅ Back</button>
</a>
<a href="dashboard.php">
    <button class="dashboard">Go to Dashboard</button>
</a>
<a href="logout.php">
    <button class="logout">Logout</button>
</a>
</div>

</body>
</html>