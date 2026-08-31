<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "login_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$request_id = intval($_POST['request_id'] ?? 0);
$role = $_SESSION['role'] ?? '';
$remarks = $_POST['remarks'] ?? '';
if (!$request_id || !$role) {
    die("Invalid request");
}

mysqli_query($conn, "
    UPDATE approvals 
    SET status='Approved', remarks='$remarks'
    WHERE request_id=$request_id  AND role='".$role."' 
    AND status='Pending'
");


if (isset($_POST['approve'])) {

    mysqli_query($conn, "
        UPDATE approvals 
        SET status='Approved', remarks='$remarks'
        WHERE request_id=$request_id AND role='".$role."'
    ");
    
    header("Location: view.php?id=".$_POST['request_id']);
    exit();
}

elseif (isset($_POST['reject'])) {

    mysqli_query($conn, "
        UPDATE approvals 
        SET status='Rejected', remarks='$remarks'
        WHERE request_id=$request_id AND role='".$role."' 
        AND status='Pending'
    ");

    header("Location: view.php?id=".$_POST['request_id']);
    exit();
}

if (isset($_POST['complete'])) {

    $request_id = $_POST['request_id'];

    // upload files
    foreach ($_FILES['admin_docs']['tmp_name'] as $key => $tmp_name) {

        $file_name = $_FILES['admin_docs']['name'][$key];
        $file_tmp  = $_FILES['admin_docs']['tmp_name'][$key];

        if (!empty($file_name)) {
            $new_name = time() . "_" . $file_name;

            move_uploaded_file($file_tmp, "uploads/" . $new_name);

            mysqli_query($conn, "
                INSERT INTO documents (request_id, file_name, uploaded_by)
                VALUES ($request_id, '$new_name', 'admin')
            ");
        }
    }

    // update status
    mysqli_query($conn, "
        UPDATE approvals 
        SET status='Completed', remarks='$remarks'
        WHERE request_id=$request_id AND role='Admin'
    ");

    echo "<script>
        alert('Booking Completed');
        window.location.href='dashboard.php';
    </script>";
    exit();
}

elseif (isset($_POST['revert'])) {

    mysqli_query($conn, "
        UPDATE approvals 
        SET status='Reverted', remarks='$remarks'
        WHERE request_id=$request_id AND role='Admin' 
    ");

    echo "<script>
        alert('Reverted to user');
        window.location.href='dashboard.php';
    </script>";
    exit();
}

exit();
if (isset($_POST['upload_admin'])) {

    $request_id = $_POST['request_id'];
    $upload_dir = "uploads/";

    foreach ($_FILES['admin_docs']['tmp_name'] as $key => $tmp_name) {

        $file_name = $_FILES['admin_docs']['name'][$key];
        $file_size = $_FILES['admin_docs']['size'][$key];
        $file_tmp  = $_FILES['admin_docs']['tmp_name'][$key];

        if ($file_size <= 3 * 1024 * 1024) {

            $new_name = time() . "_" . $file_name;

            move_uploaded_file($file_tmp, $upload_dir . $new_name);

            mysqli_query($conn, "
                INSERT INTO documents (request_id, file_name, uploaded_by)
                VALUES ($request_id, '$new_name', 'admin')
            ");
        }
    }

    echo "<script>
        alert('Admin documents uploaded');
        window.location.href='view.php?id=$request_id';
    </script>";
    exit();
}
?>