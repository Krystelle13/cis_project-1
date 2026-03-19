<?php include 'db.php'; ?>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $first = $conn->real_escape_string($_POST['first_name']);
    $last = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact_no']); // Captured
    $address = $conn->real_escape_string($_POST['address']);
    $status = $_POST['status'];

    $photo = "";
    if(!empty($_FILES['photo']['name'])){
        $photo = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
    }

    // Generate Customer Code
    $res = $conn->query("SELECT id FROM customers ORDER BY id DESC LIMIT 1");
    $row = $res->fetch_assoc();
    $next = $row ? $row['id'] + 1 : 1;
    $code = "CUST-" . str_pad($next, 4, "0", STR_PAD_LEFT);

    // Insert to DB including contact_no
    $conn->query("INSERT INTO customers 
    (customer_code, first_name, last_name, email, contact_no, address, photo, status)
    VALUES ('$code','$first','$last','$email','$contact','$address','$photo','$status')");

    header("Location:index.php?success=added");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<h4 class="mb-3">Add Customer</h4>

<form method="POST" enctype="multipart/form-data" class="row g-3 shadow-sm p-3 bg-light rounded">
    <div class="col-md-6">
        <label class="form-label">First Name</label>
        <input name="first_name" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Last Name</label>
        <input name="last_name" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input name="email" type="email" class="form-control" placeholder="example@email.com" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Contact Number</label>
        <input name="contact_no" class="form-control" placeholder="09XXXXXXXXX" required>
    </div>

    <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3" placeholder="Full address..." required></textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Upload Photo</label>
        <input type="file" name="photo" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
    </div>

    <div class="col-12">
        <button class="btn btn-success">Save Customer</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php include 'includes/footer.php'; ?>