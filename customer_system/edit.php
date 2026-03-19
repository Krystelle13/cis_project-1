<?php include 'db.php'; ?>

<?php
if(!isset($_GET['id'])){
    header("Location:index.php");
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM customers WHERE id=$id");
if($result->num_rows == 0){ header("Location:index.php"); exit; }
$data = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_no']; // Captured from form
    $address = $_POST['address'];
    $status = $_POST['status'];

    if(!empty($_FILES['photo']['name'])){
        $photo = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
    } else {
        $photo = $data['photo'];
    }

    // FIXED: Explicitly checked the UPDATE syntax for contact_no
    $sql = "UPDATE customers SET 
            first_name='$first', 
            last_name='$last', 
            email='$email', 
            contact_no='$contact', 
            address='$address', 
            photo='$photo', 
            status='$status' 
            WHERE id=$id";
    
    $conn->query($sql);

    header("Location:index.php?success=updated");
    exit;
}
?>

<?php include 'includes/header.php'; ?>
<h4 class="mb-3">Edit Customer</h4>
<form method="POST" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">First Name</label>
        <input name="first_name" value="<?= $data['first_name'] ?>" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Last Name</label>
        <input name="last_name" value="<?= $data['last_name'] ?>" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input name="email" type="email" value="<?= $data['email'] ?>" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Contact Number</label>
        <input name="contact_no" value="<?= $data['contact_no'] ?>" class="form-control" required>
    </div>
    <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3" required><?= $data['address'] ?></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Current Photo</label><br>
        <?php if(!empty($data['photo'])): ?>
            <img src="uploads/<?= $data['photo'] ?>" width="80" height="80">
        <?php else: ?>
            <p>No Image</p>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <label class="form-label">Change Photo</label>
        <input type="file" name="photo" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="Active" <?= ($data['status']=='Active')?'selected':'' ?>>Active</option>
            <option value="Inactive" <?= ($data['status']=='Inactive')?'selected':'' ?>>Inactive</option>
        </select>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Update Customer</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
<?php include 'includes/footer.php'; ?>