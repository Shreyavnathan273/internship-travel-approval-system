<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "<h2>Please login first</h2>";
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
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "login_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM travel_requests WHERE request_id=$id");
$data = mysqli_fetch_assoc($result);
if (!$data) {
    die("No record found");
}

$username = $_SESSION['username'];
?>

<?php

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $designation = $_POST['designation'];
    $grade = $_POST['grade'];
    $date = $_POST['date'];
    $purpose = $_POST['purpose'];

    mysqli_query($conn, "
        UPDATE travel_requests 
        SET name='$name',
            dept='$dept',
            designation='$designation',
            grade='$grade',
            travel_date='$date',
            purpose='$purpose'
        WHERE request_id=$id
    ");

    // reset approvals
    mysqli_query($conn, "
    UPDATE approvals 
    SET status='Pending', remarks=NULL
    WHERE request_id=$id AND role='Admin' AND status='Reverted'
    ");
    // change request back to Pending
    mysqli_query($conn, "
    UPDATE approvals
    SET status='Pending', remarks=NULL
    WHERE request_id=$id
    AND role='Admin'
    ");

    // delete old travel details
mysqli_query($conn, "DELETE FROM travel_details WHERE request_id=$id");

// insert updated ones
foreach ($_POST['time'] as $i => $time) {

    $from = $_POST['from'][$i];
    $to = $_POST['to'][$i];
    $mode = $_POST['mode'][$i];
    $acc = $_POST['accommodation'][$i];

    if (empty($time) || empty($from) || empty($to)) continue;

    mysqli_query($conn, "
        INSERT INTO travel_details 
        (request_id, time, from_location, to_location, mode, accommodation) 
        VALUES ($id, '$time', '$from', '$to', '$mode', '$acc')
    ");
}
echo "<script>
        alert('Updated Successfully');
        window.location.href='view.php?id=$id';
    </script>";

}

?>

<html>
<head>
    <title>Travel Form</title>

    <style>
        body {
            font-family: Arial;
            background-color: #f0f2f5;
        }

        .container {
            width: 700px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
        }

        h2, h3 {
            text-align: center;
        }

        input, select {
            width: 95%;
            padding: 8px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid gray;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid gray;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        .section {
            margin-top: 20px;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        button {
            padding: 10px;
            width: 100%;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: darkgreen;
        }
    </style>
</head>

<body>

<div class="container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
    <div style="margin-bottom:15px;">
    <a href="javascript:history.back()" 
       style="text-decoration:none; color:#007BFF; font-weight:bold;">
        ⬅ Back
    </a>
    </div>
    <a href='logout.php' style='display:inline-block; text-decoration:none;'>
            <button style='
                padding:10px 15px;
                margin:5px;
                border:none;
                border-radius:5px;
                background-color:#007BFF;
                color:white;
                cursor:pointer;
            '>Logout</button>
        </a>
    </div>
    <h1><center>ABC Corporation</center></h1>
    <h2>Travel Authorization </h2>
    <h3><u>Travel Details:</u></h3>

    <form method="post" enctype="multipart/form-data">
        <!-- Employee Details -->
        <div class="section">
            <h3>Employee Details</h3>

            <input type="text" name="name" value="<?php echo $data['name']; ?>" readonly>
            <input type="text" name="id" value="<?php echo $data['request_id']; ?>" readonly>
            <div class="form-group">
                <select name="dept" required >
                    <option value="">Select Department</option>
                    <option value="CS" <?php if($data['dept']=="CS") echo "selected"; ?>>CS</option>
                    <option value="sales" <?php if($data['dept']=="sales") echo "selected"; ?>>sales</option>
                    <option value="marketing" <?php if($data['dept']=="marketing") echo "selected"; ?>>marketing</option>
                    <option value="Operations" <?php if($data['dept']=="Operations") echo "selected"; ?>>Operations</option>
                    <option value="customer support" <?php if($data['dept']=="customer support") echo "selected"; ?>>customer support</option>
                    <option value="HR" <?php if($data['dept']=="HR") echo "selected"; ?>>HR</option>
                    <option value="Finance" <?php if($data['dept']=="Finance") echo "selected"; ?>>Finance</option>
                    <option value="Admin" <?php if($data['dept']=="Admin") echo "selected"; ?>>Admin</option>
                </select>
            </div>
            <input type="text" name="designation" value="<?php echo $data['designation']; ?>" placeholder="Designation" required>
            <input type="text" name="grade" value="<?php echo $data['grade']; ?>" placeholder="Grade" required>
            <input type="date" name="date" value="<?php echo $data['travel_date']; ?>" required>
            <div class="form-group">
                <select name="manager" required >
                    <option value="">Select Manager</option>
                    <option value="Robert">Robert</option>
                    <option value="Nathan">Nathan</option>
                    <option value="Hemanth">Hemanth</option>
                    <option value="Arjun">Arjun</option>
                </select>
            </div>
            <input type="text" name="purpose" value="<?php echo $data['purpose']; ?>" placeholder="Purpose of Travel" required>
        </div>

        <!-- Travel Details Table -->
        <div class="section">
            <h3>Travel Details</h3>

            <table>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Mode</th>
                    <th>Accommodation</th>
                </tr>

                <?php
                $travel_q = mysqli_query($conn, "SELECT * FROM travel_details WHERE request_id=$id");

                if (mysqli_num_rows($travel_q) == 0) {
                    echo "<tr><td colspan='5'>No travel details</td></tr>";
                }

                while ($t = mysqli_fetch_assoc($travel_q)) {
                ?>
                <tr>
                    <td><input type="time" name="time[]" value="<?php echo $t['time']; ?>"></td>
                    <td><input type="text" name="from[]" value="<?php echo $t['from_location']; ?>"></td>
                    <td><input type="text" name="to[]" value="<?php echo $t['to_location']; ?>"></td>
                    <td>
                        <select name="mode[]">
                            <option <?php if($t['mode']=="Bus") echo "selected"; ?>>Bus</option>
                            <option <?php if($t['mode']=="Train") echo "selected"; ?>>Train</option>
                            <option <?php if($t['mode']=="Flight") echo "selected"; ?>>Flight</option>
                        </select>
                    </td>
                    <td>
                        <select name="accommodation[]">
                            <option <?php if($t['accommodation']=="Yes") echo "selected"; ?>>Yes</option>
                            <option <?php if($t['accommodation']=="No") echo "selected"; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <br>

        <!-- Document Upload -->
        <div class="form-group">
            <label>Upload Documents (Max 3MB each):</label>
            <input type="file" name="documents[]" multiple>
        </div>

        <!-- Signatures -->
        <div class="section">
            <h3>Approvals</h3>

            <p>Employee Signature: ________________________</p><br>
            <p>Recommended by HOD: ________________________</p><br>
            <p>Approved by Authority: ________________________</p>
        </div>

        <hr>

        <!-- Advance Requisition -->
        <div class="section">
            <h3>Advance Requisition</h3>

            <label>Advance Required?</label><br>
            <select>
                <option>No</option>
                <option>Yes</option>
            </select><br><br>

            <input type="text" placeholder="Amount Required"><br><br>

            <p>Employee Signature: ________________________</p><br>
            <p>HOD Signature: ________________________</p><br>
            <p>Finance Manager: ________________________</p>
        </div>

        <button type="submit" name="submit">Update</button>

    </form>

</div>

</body>
</html>