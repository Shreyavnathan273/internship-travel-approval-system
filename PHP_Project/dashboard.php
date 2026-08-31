<?php
session_start();
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = "user"; // fallback (temporary safety)
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "login_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$isPopup = isset($_GET['popup']);
$role = $_SESSION['role'];
$username = $_SESSION['username'];

$pending_count = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM approvals
WHERE role='$role'
AND status='Pending'
"))['total'];

$approved_count = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM approvals
WHERE role='$role'
AND status='Approved'
"))['total'];

$rejected_count = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM approvals
WHERE role='$role'
AND status='Rejected'
"))['total'];

$completed_count = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM approvals
WHERE role='$role'
AND status='Completed'
"))['total'];

$reverted_count = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM approvals
WHERE role='$role'
AND status='Reverted'
"))['total'];
?>

<?php if (!$isPopup) { ?>
<html>
<head>
    <title>Dashboard</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    margin-top: 20px;
}

.container {
    width: 80%;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    background-color: #007BFF;
    color: white;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th {
    background-color: #007BFF;
    color: white;
}

th, td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background-color: #f1f1f1;
}

a {
    text-decoration: none;
}
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
}

.modal-content {
    background: white;
    width: 90%;
    height: 90%;
    margin: 2% auto;
    padding: 15px;
    border-radius: 8px;
    position: relative;
}

.close {
    position: absolute;
    top: 1px;
    right: 3px;
    font-size: 30px;
    cursor: pointer;
}
</style>

<script>
function openForm(id) {
    document.getElementById("formFrame").src = "view.php?id=" + id + "&popup=1";
    document.getElementById("formModal").style.display = "block";
}

function closeForm() {
    document.getElementById("formModal").style.display = "none";
    document.getElementById("formFrame").src = "";
}

window.onclick = function(event) {
    if (event.target == document.getElementById("formModal")) {
        closeForm();
    }
}
</script>

</head>
<body>
    <?php } ?>
    <br>
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
<h2><center>Dashboard</center></h2><br>
<p>NAME: <b><?php echo $username; ?></b></p>
</div>

<?php
if ($role == "user") {
    echo "<div style='margin-bottom:20px; text-align:center;'>
            <a href='form.php'><button>Apply Travel Form</button></a>
          </div>";
}
echo "<br>";
if ($role == "user") {
     echo "<p style='margin-left:25px; font-size:18px;'><b><i>My Applications</i></b></p>";
    $sql = "SELECT * FROM travel_requests 
            WHERE name='$username'
            ORDER BY request_id DESC
            LIMIT 10";

    $result = mysqli_query($conn, $sql);
    $role = $_SESSION['role'];


    echo "<table border='1' cellpadding='10'>
    <tr>
        <th>Request ID</th>
        <th>Purpose</th>
        <th>Status</th>
        <th>View</th>
    </tr>";
    if (mysqli_num_rows($result) == 0) {
    echo "<tr>
        <td colspan='4' style='text-align:center; padding:20px; color:dark gray;'>
            <i>No Applications Yet</i>
        </td>
    </tr>";
}
    
    $pending_count = 0;
    $approved_count = 0;
    $rejected_count = 0;
    $bookingcompleted_count = 0;
    $reverted_count = 0;

    $filter = isset($_GET['status']) ? $_GET['status'] : "";

    while ($row = mysqli_fetch_assoc($result)) {

        $rid = $row['request_id'];

        $status_q = mysqli_query($conn, "
    SELECT role, status FROM approvals 
    WHERE request_id=$rid
    ");

     
        $statuses = [];

        while ($s = mysqli_fetch_assoc($status_q)) {
            $statuses[$s['role']] = $s['status'];
        }

        if (!empty($statuses['HR']) && $statuses['HR'] == "Rejected") {
            $final_status = "Rejected by HR";
        }
        elseif (!empty($statuses['Manager']) && $statuses['Manager'] == "Rejected") {
            $final_status = "Rejected by Manager";
        }
        elseif (!empty($statuses['Admin']) && $statuses['Admin'] == "Rejected") {
            $final_status = "Rejected by Admin";
        }

        elseif (!empty($statuses['HR']) && $statuses['HR'] == "Pending") {
            $final_status = "Pending at HR";
        }
        elseif (!empty($statuses['Manager']) && $statuses['Manager'] == "Pending") {
            $final_status = "Pending at Manager";
        }
        elseif (!empty($statuses['Admin']) && $statuses['Admin'] == "Pending") {
            $final_status = "Pending at Admin";
        }
        elseif (!empty($statuses['Admin']) && $statuses['Admin'] == "Reverted") {
            $final_status = "Reverted by Admin";
        }

        elseif (!empty($statuses['Admin']) && $statuses['Admin'] == "Completed") {
            $final_status = "Booking Completed";
        }

        elseif (!empty($statuses['Admin']) && $statuses['Admin'] == "Approved") {
            $final_status = "Approved by Admin";
        }
        elseif (!empty($statuses['Manager']) && $statuses['Manager'] == "Approved") {
            $final_status = "Approved by Manager";
        }
        elseif (!empty($statuses['HR']) && $statuses['HR'] == "Approved") {
            $final_status = "Approved by HR";
        }

        else {
            $final_status = "Pending";
        }
        if (strpos($final_status, "Pending") !== false) {
            $pending_count++;
        }
        elseif (strpos($final_status, "Approved") !== false) {
            $approved_count++;
        }
        elseif (strpos($final_status, "Rejected") !== false) {
            $rejected_count++;
        }
        elseif ($final_status == "Booking Completed") {
            $bookingcompleted_count++;
        }
        elseif ($final_status == "Reverted by Admin") {
            $reverted_count++;
        }

        if ($filter != "") {

            if ($filter == "Pending" && strpos($final_status, "Pending") === false) {
                continue;
            }

            if ($filter == "Approved" && strpos($final_status, "Approved") === false) {
                continue;
            }

            if ($filter == "Rejected" && strpos($final_status, "Rejected") === false) {
                continue;
            }

            if ($filter == "Reverted" && $final_status != "Reverted by Admin") {
                continue;
            }

            if ($filter == "Booking Completed" && $final_status != "Booking Completed") {
                continue;
            }
        }

        echo "<tr>
                    <td>{$rid}</td>
                    <td>{$row['purpose']}</td>
                    <td>{$final_status}</td>
                    <td><a href='javascript:void(0);' onclick='openForm($rid)'>View</a></td>
                </tr>";
    
    }

    echo "<br>
                <div style='display:flex; gap:20px; margin-bottom:20px; center; justify-content:center;'>

                <a href='dashboard.php?status=Pending'
                style='text-decoration:none;color:black;'>
                <div style='width:150px;background:#FFD966;padding:15px;border-radius:8px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.2);'>
                <b>Pending</b><br><br>
                <span style='font-size:28px;font-weight:bold;'>$pending_count</span>
                </div>
                </a>

                <a href='dashboard.php?status=Approved'
                style='text-decoration:none;color:black;'>
                <div style='width:150px;background:#A5D6A7;padding:15px;border-radius:8px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.2);'>
                <b>Approved</b><br><br>
                <span style='font-size:28px;font-weight:bold;'>$approved_count</span>
                </div>
                </a>

                <a href='dashboard.php?status=Rejected'
                style='text-decoration:none;color:black;'>
                <div style='width:150px;background:#EF9A9A;padding:15px;border-radius:8px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.2);'>
                <b>Rejected</b><br><br>
                <span style='font-size:28px;font-weight:bold;'>$rejected_count</span>
                </div>
                </a>

                <a href='dashboard.php?status=Reverted'
                style='text-decoration:none;color:black;'>
                <div style='width:150px;background:#EF9A9A;padding:15px;border-radius:8px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.2);'>
                <b>Reverted</b><br><br>
                <span style='font-size:28px;font-weight:bold;'>$reverted_count</span>
                </div>
                </a>

                <a href='dashboard.php?status=Booking Completed'
                style='text-decoration:none;color:black;'>
                <div style='width:140px;background:#81C784;padding:14px;border-radius:8px;text-align:center;box-shadow:0 2px 4px rgba(0,0,0,0.2);'>
                <b>Booking Completed</b><br><br>
                <span style='font-size:25px;font-weight:bold;'>$bookingcompleted_count</span>
                </div>
                </a>

                </div>
                ";

    
    echo "</table>";
    echo "<p style='text-align:center; color:gray;'>Showing latest 10 records</p>";
    
    }
?>
<?php  
 
if ($role != "user") {

    echo "<h2 style='text-align:center;'>$role Dashboard</h2>";

    $status = $_GET['status'] ?? "Pending";
    if ($role == "HR") {
        $sql = "SELECT a.*, t.*
        FROM approvals a
        JOIN travel_requests t ON a.request_id = t.request_id
        WHERE a.role='HR'
        AND a.status='$status'
        LIMIT 10";
    }

    elseif ($role == "Manager") {
        $sql = "SELECT a.*, t.*
        FROM approvals a
        JOIN travel_requests t ON a.request_id = t.request_id
        JOIN approvals h ON h.request_id = a.request_id
        WHERE a.role='Manager'
        AND a.status='$status'
        AND h.role='HR'
        AND h.status='Approved'
        LIMIT 10";
    }

    elseif ($role == "Admin") {
        $sql = "SELECT a.*, t.*
        FROM approvals a
        JOIN travel_requests t ON a.request_id = t.request_id
        JOIN approvals m ON m.request_id = a.request_id
        WHERE a.role='Admin'
        AND a.status='$status'
        AND m.role='Manager'
        AND m.status='Approved'
        LIMIT 10";
    }

    // ONLY RUN QUERY IF ROLE MATCHED
    if (isset($sql)) {

        $result = mysqli_query($conn, $sql);
        echo "<br>
        <div style='display:flex; gap:20px; margin-bottom:20px; center; justify-content:center;'>

        <a href='dashboard.php?status=Pending'
        style='text-decoration:none;color:black;'>
        <div style='width:150px;background:#FFD966;padding:15px;border-radius:8px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.2);'>
        <b>Pending</b><br><br>
        <span style='font-size:28px;font-weight:bold;'>$pending_count</span>
        </div>
        </a>

        <a href='dashboard.php?status=Approved'
        style='text-decoration:none;color:black;'>
        <div style='width:150px;background:#A5D6A7;padding:15px;border-radius:8px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.2);'>
        <b>Approved</b><br><br>
        <span style='font-size:28px;font-weight:bold;'>$approved_count</span>
        </div>
        </a>

        <a href='dashboard.php?status=Rejected'
        style='text-decoration:none;color:black;'>
        <div style='width:150px;background:#EF9A9A;padding:15px;border-radius:8px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.2);'>
        <b>Rejected</b><br><br>
        <span style='font-size:28px;font-weight:bold;'>$rejected_count</span>
        </div>
        </a>

        </div>
        ";
       
        echo "<table border='1' cellpadding='10'>
        <tr>
            <th>Request ID</th>
            <th>Travel Date</th>
            <th>Name</th>
            <th>Purpose</th>
            <th>View</th>
        </tr>";

        if (mysqli_num_rows($result) == 0) {
            echo "<tr>
                <td colspan='5' style='text-align:center; padding:20px; color:gray;'>
                    No Pending Approvals
                </td>
            </tr>";
        }

        while ($row = mysqli_fetch_assoc($result)) {

            echo "<tr>
                <td>{$row['request_id']}</td>
                <td>" . date("d-m-y", strtotime($row['travel_date'])) . "</td>
                <td>{$row['name']}</td>
                <td>{$row['purpose']}</td>
                <td>
                    <button
                        type='button'
                        onclick='openForm({$row['request_id']})'
                        style='background:#007BFF; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer;'>
                        View
                    </button>
                </td>
            </tr>";
        }

        echo "</table>";
        echo "<p style='text-align:center; color:gray;'>Showing latest 10 records</p>";
    }
}
?>

<div id="formModal" class="modal">
    <div class="modal-content">

        <span class="close" onclick="closeForm()">&times;</span>

        <iframe id="formFrame"
                width="100%"
                height="95%"
                style="border:none;">
        </iframe>

    </div>
</div>
<?php if (!$isPopup) { ?>

</body>
</html>

<?php } ?>