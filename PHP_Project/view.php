<?php
session_start();
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = "user"; // fallback (temporary safety)
}
$conn = mysqli_connect("localhost", "root", "", "login_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_GET['id'];

$result = mysqli_query($conn, "
    SELECT * FROM travel_requests WHERE request_id=$id
");

$data = mysqli_fetch_assoc($result);
?>

<html>
<head>
    <title>Travel Form</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 0;
}

.container {
    width: 70%;
    margin: 30px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

h3 {
    margin-top: 20px;
    color: #333;
}

a {
    text-decoration: none;
    color: #007BFF;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
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

button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    margin: 5px;
    cursor: pointer;
    color: white;
}

button[name="approve"] {
    background-color: green;
}

button[name="reject"] {
    background-color: red;
}

textarea {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
.details {
    margin-top: 15px;
}

.details p {
    margin: 8px 0;
    font-size: 15px;
}

.label {
    display: inline-block;
    width: 180px;
    font-weight: bold;
    color: #333;
}
.details {
    background: #f9fafb;
    padding: 15px;
    border-radius: 8px;
}
.status-box {
    margin-top: 20px;
    padding: 18px;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
}

.status-box h3 {
    margin-bottom: 12px;
    color: #333;
}

.status-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0 2px 0;
    border-bottom: none; /* remove old line */
    font-size: 15px;
}

/* Remarks styling */
.remarks {
    font-size: 13px;
    color: #555;
    margin-top: 3px;
    margin-bottom: 8px; /* space before line */
}

/* 🔥 MAIN FIX: line after FULL block */
.remarks,
.status-row:not(:has(+ .remarks)) {
    border-bottom: 1px solid #e5e5e5;
    padding-bottom: 10px;
}

.status-row:last-child {
    border-bottom: none;
}

.role {
    font-weight: 600;
    color: #574242;
}

.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}


/* soft modern colors */
.approved {
    background: #e8f5e9;
    color: #2e7d32;
}

.pending {
    background: #fff3e0;
    color: #ef6c00;
}

.rejected {
    background: #fdecea;
    color: #c62828;
}

.file-btn {
    display: inline-block;
    padding: 6px 12px;
    background: orange;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-size: 13px;
}

.file-btn:hover {
    background: darkorange;
}
.print-btn {
    background: #18732d;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    float: right;
    margin-bottom: 10px;
}
.edit-container {
    text-align: right;
    margin-top: 20px;
}
.edit-btn {
    background: gray;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
}
.edit-btn:hover {
    background: black;
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
    width: 80%;
    height: 85%;
    margin: 3% auto;
    padding: 15px;
    border-radius: 8px;
    position: relative;
}

.close {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 30px;
    cursor: pointer;
    color: white;
}
</style>

<script>
function openFile(file) {
    document.getElementById("fileFrame").src = file;
    document.getElementById("fileModal").style.display = "block";
}

function closeFile() {
    document.getElementById("fileModal").style.display = "none";
    document.getElementById("fileFrame").src = "";
}

window.onclick = function(event) {
    if (event.target == document.getElementById("fileModal")) {
        closeFile();
    }
}
</script>

<body>
<div style='text-align:right; display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;'>
    <a href="dashboard.php">
        <button class="dashboard" style='background-color: grey; text-decoration:none;'>Go to Dashboard</button>
    </a>
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

<div style="margin-bottom:15px;">
    <a href="javascript:history.back()">⬅ Back</a>
    <button onclick="window.print()" class="print-btn">Print / Save</button>
</div>

<h2>Travel Form</h2>

<h3>Employee Details</h3>

<?php
echo "<div class='details'>

<p><span class='label'>Name:</span> {$data['name']}</p>
<p><span class='label'>Department:</span> {$data['dept']}</p>
<p><span class='label'>Designation:</span> {$data['designation']}</p>
<p><span class='label'>Grade:</span> {$data['grade']}</p>
<p><span class='label'>Date of Travel:</span> " . date("d-m-y", strtotime($data['travel_date'])) . "</p>
<p><span class='label'>Purpose:</span> {$data['purpose']}</p>

</div>";
?>

<br>

<h3>Travel Details</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Time</th>
    <th>From</th>
    <th>To</th>
    <th>Mode</th>
    <th>Accommodation</th>
</tr>

<?php
$travel_q = mysqli_query($conn, "
    SELECT * FROM travel_details WHERE request_id=$id
");

while ($t = mysqli_fetch_assoc($travel_q)) {
    echo "<tr>
        <td>{$t['time']}</td>
        <td>{$t['from_location']}</td>
        <td>{$t['to_location']}</td>
        <td>{$t['mode']}</td>
        <td>{$t['accommodation']}</td>
    </tr>";
}
?>
</table>

<?php
echo "<div class='status-box'>";
echo "<h3>Approval Status</h3>";

$status_q = mysqli_query($conn, "
    SELECT role, status,remarks FROM approvals 
    WHERE request_id=$id
");

if (mysqli_num_rows($status_q) == 0) {
    echo "<p>No approval yet</p>";
}

while ($s = mysqli_fetch_assoc($status_q)) {
    $class = "";

    if ($s['status'] == "Approved") {
        $class = "approved";
    } elseif ($s['status'] == "Rejected") {
        $class = "rejected";
    } else {
        $class = "pending";
    }

    echo "<div class='status-row'>
        <span class='role'>".$s['role']."</span>
        <span class='badge $class'>".$s['status']."</span>
      </div>";

    if (!empty($s['remarks'])) {
        echo "<div class='remarks' style='margin-left:10px; margin-bottom:10px; color:#555;'>
                <b>Remarks:</b> ".$s['remarks']."
            </div>";
    }
          
}

echo "</div>";
?>

<?php
echo "<h3>Attachments</h3>";

$docs = mysqli_query($conn, "
    SELECT * FROM documents WHERE request_id=$id AND uploaded_by='user'
");

if (mysqli_num_rows($docs) == 0) {
    echo "No files uploaded";
}
?>
<?php
while ($d = mysqli_fetch_assoc($docs)) {
?>
    <button type="button"
        class="file-btn"
        onclick="openFile('uploads/<?php echo $d['file_name']; ?>')">
        View File
    </button><br>
<?php
}
?>
<?php 
echo "<br><br>";?>

<?php
$status_q = mysqli_query($conn, "
    SELECT role, status FROM approvals 
    WHERE request_id=$id
");
$sdata = mysqli_fetch_assoc($status_q);
$sdata = $sdata ?? [];

$admin_check = mysqli_query($conn, "
    SELECT status FROM approvals 
    WHERE request_id=$id AND role='Admin'
");

$admin_data = mysqli_fetch_assoc($admin_check);

if (
    isset($_SESSION['role']) &&
    $_SESSION['role'] == "user" &&
    !empty($admin_data['status']) &&
    $admin_data['status'] == "Reverted"
) {
    echo "<div class='edit-container'>
            <a href='edit.php?id=$id'>
                <button class='edit-btn'>Edit & Resubmit</button>
            </a>
          </div>";
}

$approval = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT status
    FROM approvals
    WHERE request_id=$id
    AND role='{$_SESSION['role']}'
"));

if ($_SESSION['role'] == "user" && !empty($sdata['status']) && $sdata['status'] == "Reverted") {
?>
    <div class="edit-container">
        <a href="edit.php?id=<?php echo $id; ?>">
            <button class="edit-btn">Edit & Resubmit</button>
        </a>
    </div>
<?php
}
elseif ($_SESSION['role'] != "user") {
?>

<form action="action.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="request_id" value="<?php echo $id; ?>">

    <br><br>

    <textarea name="remarks" placeholder="Enter remarks here..." rows="4" style="width:100%;"></textarea><br><br>

    <?php if ($_SESSION['role'] == "Admin") { ?>

        <label><b>Upload Tickets / Documents:</b></label><br><br>

        <input type="file" name="admin_docs[]" multiple><br><br>

        <button type="submit" name="complete" style="background:green; color:white;">
            Complete
        </button>

        <button type="submit" name="revert" style="background:orange;">
            Revert
        </button>

    <?php } elseif (
        ($_SESSION['role'] == "HR" || $_SESSION['role'] == "Manager")
        && $approval['status'] == "Pending"
    ) { ?>

        <button type="submit" name="approve">Accept</button>
        <button type="submit" name="reject">Reject</button>

    <?php } else { ?>

        <p><b>Action already taken</b></p>

    <?php } ?>

</form>
<?php
}
?>
<div id="fileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeFile()">&times;</span>

        <iframe id="fileFrame"
                src=""
                width="100%"
                height="600px"
                style="border:none;">
        </iframe>
    </div>
</div>
</script>
</body>
</html>