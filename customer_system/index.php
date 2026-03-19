<?php include 'db.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <a href="add.php" class="btn btn-primary shadow-sm">+ Add Customer</a>
</div>

<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success text-center shadow-sm">
    <?= ucfirst($_GET['success']) ?> successful!
</div>
<?php endif; ?>

<?php
$search = $conn->real_escape_string($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'ASC';
$status = $_GET['status'] ?? '';

$limit = 5;
$page = $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;

$where = "WHERE (first_name LIKE '%$search%' 
OR last_name LIKE '%$search%' 
OR email LIKE '%$search%' 
OR contact_no LIKE '%$search%')";

if($status != ''){
    $where .= " AND status='$status'";
}

$total_res = $conn->query("SELECT COUNT(*) as t FROM customers $where");
$total = $total_res->fetch_assoc()['t'];
$pages = ceil($total / $limit);

$result = $conn->query("SELECT * FROM customers 
$where ORDER BY last_name $sort LIMIT $start,$limit");
?>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search name, email, or contact..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-3">
        <select name="sort" class="form-control">
            <option value="ASC" <?= $sort == 'ASC' ? 'selected' : '' ?>>A-Z</option>
            <option value="DESC" <?= $sort == 'DESC' ? 'selected' : '' ?>>Z-A</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="Active" <?= $status == 'Active' ? 'selected' : '' ?>>Active</option>
            <option value="Inactive" <?= $status == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-success w-100">Apply</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover text-center align-middle shadow-sm" style="table-layout: fixed; width: 100%; min-width: 900px;">
        <thead class="table-dark">
            <tr>
                <th style="width: 80px;">Photo</th>
                <th style="width: 110px;">Code</th>
                <th>Name</th>
                <th>Email</th>
                <th style="width: 140px;">Contact</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 160px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if($row['photo']): ?>
                            <img src="uploads/<?= $row['photo'] ?>" width="45" height="45" class="rounded-circle shadow-sm" style="object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-light border d-inline-block" style="width:45px; height:45px; line-height:45px; font-size:10px; color:#aaa;">N/A</div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold"><?= $row['customer_code'] ?></td>
                    <td class="text-start text-truncate" title="<?= $row['first_name']." ".$row['last_name'] ?>">
                        <?= $row['first_name']." ".$row['last_name'] ?>
                    </td>
                    <td class="text-truncate" title="<?= $row['email'] ?>"><?= $row['email'] ?></td>
                    <td><?= $row['contact_no'] ?></td>
                    <td>
                        <span class="badge <?= $row['status'] == 'Active' ? 'bg-success' : 'bg-danger' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td style="white-space: nowrap;">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm fw-bold">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm fw-bold" onclick="return confirm('Delete this customer?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="py-4 text-muted">No customers found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<nav class="mt-4">
    <ul class="pagination justify-content-center">
        <?php for($i = 1; $i <= $pages; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>&status=<?= $status ?>">
                <?= $i ?>
            </a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>

<?php include 'includes/footer.php'; ?>