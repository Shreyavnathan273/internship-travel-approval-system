<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "login_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$role = $_SESSION['role'] ?? '';
$approval_q = mysqli_query($conn, "
    SELECT * FROM approvals 
    WHERE request_id=$id AND role='$role'
");

$approval = mysqli_fetch_assoc($approval_q);
var_dump($approval);

$sql = "";

if ($role == "HR") {
    $sql = "SELECT approvals.*, travel_requests.* 
            FROM approvals 
            JOIN travel_requests 
            ON approvals.request_id = travel_requests.request_id
            WHERE approvals.role='HR' AND approvals.status='Pending'";
}

elseif ($role == "Manager") {
    $sql = "SELECT approvals.*, travel_requests.* 
            FROM approvals 
            JOIN travel_requests 
            ON approvals.request_id = travel_requests.request_id
            WHERE approvals.role='Manager'
            AND approvals.status='Pending'
            AND approvals.request_id IN (
                SELECT request_id FROM approvals 
                WHERE role='HR' AND status='Approved'
            )";
}

elseif ($role == "Admin") {
    $sql = "SELECT approvals.*, travel_requests.* 
            FROM approvals 
            JOIN travel_requests 
            ON approvals.request_id = travel_requests.request_id
            WHERE approvals.role='Admin'
            AND approvals.status='Pending'
            AND approvals.request_id IN (
                SELECT request_id FROM approvals 
                WHERE role='Manager' AND status='   '
            )";
}

// ❗ safety check
if ($sql == "") {
    echo "No role assigned";
    exit();
}

$result = mysqli_query($conn, $sql);

echo "<table border='1' cellpadding='10'>
<tr>
    <th>Request ID</th>
    <th>Name</th>
    <th>Department</th>
    <th>Action</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {

    echo "<tr>";
    echo "<td>" . $row['request_id'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['dept'] . "</td>";

    echo "<td>
        <form action='action.php' method='POST' style='display:inline;'>
            <input type='hidden' name='approval_id' value='" . $row['approval_id'] . "'>
            <button name='approve'>Approve</button>
        </form>

        <form action='action.php' method='POST' style='display:inline;'>
            <input type='hidden' name='approval_id' value='" . $row['approval_id'] . "'>
            <button name='reject' style='background:red;color:white;'>Reject</button>
        </form>
    </td>";

    echo "</tr>";
}

echo "</table>";
?>