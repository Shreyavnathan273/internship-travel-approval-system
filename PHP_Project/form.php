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

$username = $_SESSION['username'];
?>

<?php
$conn = mysqli_connect("localhost", "root", "", "login_db");

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $designation = $_POST['designation'];
    $grade = $_POST['grade'];
    $date = $_POST['date'];
    $purpose = $_POST['purpose'];

    $stmt = $conn->prepare("INSERT INTO travel_requests 
    (name, dept, designation, grade, travel_date, purpose) 
    VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssss", $name, $dept, $designation, $grade, $date, $purpose);
    $stmt->execute();

    $request_id = $stmt->insert_id;

    foreach ($_POST['time'] as $i => $time) {

        $from = $_POST['from'][$i];
        $to = $_POST['to'][$i];
        $mode = $_POST['mode'][$i];
        $acc = $_POST['accommodation'][$i];

        if (empty($time) || empty($from) || empty($to)) continue;

        $stmt2 = $conn->prepare("INSERT INTO travel_details 
        (request_id, time, from_location, to_location, mode, accommodation) 
        VALUES (?, ?, ?, ?, ?, ?)");

        $stmt2->bind_param("isssss", $request_id, $time, $from, $to, $mode, $acc);
        $stmt2->execute();
    }

    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    foreach ($_FILES['documents']['tmp_name'] as $key => $tmp_name) {

        $file_name = $_FILES['documents']['name'][$key];
        $file_size = $_FILES['documents']['size'][$key];
        $file_tmp  = $_FILES['documents']['tmp_name'][$key];

        if ($file_size <= 3 * 1024 * 1024) {

            $new_name = time() . "_" . $file_name;

            move_uploaded_file($file_tmp, $upload_dir . $new_name);

            mysqli_query($conn, "
                INSERT INTO documents (request_id, file_name)
                VALUES ($request_id, '$new_name')"
            );
        }
    }

$roles = ["HR", "Manager", "Admin"];

foreach ($roles as $role) {
    $stmt = $conn->prepare("INSERT INTO approvals (request_id, role) VALUES (?, ?)");
    $stmt->bind_param("is", $request_id, $role);
    $stmt->execute();
}
    
    header("Location: success.php");
    exit();
}
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Your DB connection + insert code BELOW
$conn = mysqli_connect("localhost", "root", "", "login_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }

            </style>
</head>

<body>
<div style='text-align:right;'>
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
<div class="container">
    
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
    <div style="margin-bottom:15px;">
    <a href="javascript:history.back()" 
       style="text-decoration:none; color:#007BFF; font-weight:bold;">
        ⬅ Back
    </a>
    </div>
    
    </div>
    <h1><center>ABC Corporation</center></h1>
    <h2>Travel Authorization </h2>
    <h3><u>Travel Details:</u></h3>

    <form method="post" enctype="multipart/form-data">
        <!-- Employee Details -->
        <div class="section">
            <h3>Employee Details</h3>

            <input type="text" name="name" value="<?php echo $username; ?>" readonly>
            <input type="text" name="id" placeholder="Employee ID" required>
            <div class="form-group">
                <select name="dept" required >
                    <option value="">Select Department</option>
                    <option value="CS">CS</option>
                    <option value="sales">sales</option>
                    <option value="marketing">marketing</option>
                    <option value="Operations">Operations</option>
                    <option value="customer support">customer support</option>
                    <option value="HR">HR</option>
                    <option value="Finance">Finance</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <input type="text" name="designation" placeholder="Designation" required>
            <input type="text" name="grade" placeholder="Grade" required>
            <div class="form-group">
                <select name="manager" required >
                    <option value="">Select Manager</option>
                    <option value="">Robert</option>
                    <option value="">Nathan</option>
                    <option value="">Hemanth</option>
                    <option value="">Arjun</option>
                </select>
            </div>
            <input type="date" name="date" placeholder="Travel Date" required>
            <input type="text" name="purpose" placeholder="Purpose of Travel" required>
        </div>

        <!-- Travel Details Table -->
        <div class="section">
            <h3>Travel Details</h3>

            <table>
                <tr>
                    <th>Time</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Mode</th>
                    <th>Accommodation</th>
                </tr>

                <tr>
                    <td><input type="time" name="time[]"></td>
                    <td><input type="text" name="from[]"></td>
                    <td><input type="text" name="to[]"></td>
                    <td>
                        <select name="mode[]">
                            <option>Bus</option>
                            <option>Train</option>
                            <option>Flight</option>
                        </select>
                    </td>
                    <td>
                        <select name="accommodation[]">
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>

        <br>

        <!-- Document Upload -->
        <div class="form-group">
            <label>Upload Documents (Max 3MB each):</label>
            <input type="file" name="documents[]" multiple required>
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

        <button type="submit" name="submit">Submit</button>

    </form>

</div>

</body>
</html>