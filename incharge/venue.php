<?php include('layouts/header.php') ?>
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Users</h1>

<?php
// Sanitize input to prevent SQL injection
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Get total rows
$total_rows_sql = "SELECT COUNT(*) AS total FROM venues
                   INNER JOIN locations ON venues.loc_id = locations.loc_id";
if (!empty($search)) {
    $total_rows_sql .= " WHERE locations.location_name LIKE '%$search%' OR venues.venue_name LIKE '%$search%'";
}

$total_rows_result = mysqli_query($conn, $total_rows_sql);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

$limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
$sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

$pages = ceil($total_rows / $limit);
$offset = ($current_page - 1) * $limit;

$sort_column = 'venues.venue_id';
$sort_order = ($sorting == 'desc') ? 'DESC' : 'ASC';

$sql = "SELECT venues.*, locations.location_name FROM venues
        INNER JOIN locations ON venues.loc_id = locations.loc_id";

if (!empty($search)) {
    $sql .= " WHERE locations.location_name LIKE '%$search%' OR venues.venue_name LIKE '%$search%'";
}

$sql .= " ORDER BY $sort_column $sort_order
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $sql);
?>


<div class="main p-3">
    <div class="search-bar">
        <div class="row">
            <div class="col-lg-4">
                <form class="d-flex" role="search" style="width: 100%; padding: 40px 30px;" method="get">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                    <button class="btn btn-outline-success" type="submit" style="font-size: 18px; font-weight: bold;">Search</button>
                    <button type="button" class="btn btn-primary custom-btn" data-toggle="modal" data-target="#addVenueModal" style="margin-left: 10px ;width: 150px; padding: 0px;">Add Venue</button>
                </form>

            </div>
        </div>
    </div>
    <div class="client-table">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Venue No.</th>
                    <th scope="col">Venue Name</th>
                    <th scope="col">Location Name</th>
                    <th scope="col">Min. Capacity</th>
                    <th scope="col">Max. Capacity</th>
                    <th scope="col">Price per Hour</th>
                    <th scope="col">Status</th>
                    
                    <th scope="col">Date Created</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $modal_id_update = 'updateInfo_' . $row['venue_id'];
                        $modal_id_delete = 'deleteVenue_' . $row['venue_id'];
                        $status = $row['status'];
                ?>
                        <tr>
                            <th scope="row"><?= $row['venue_id'] ?></th>
                            <td><?= $row['venue_name'] ?></td>
                            <td><?= $row['location_name'] ?></td>
                            <td><?= $row['min_capacity'] ?></td>
                            <td><?= $row['max_capacity'] ?></td>
                            <td><?= $row['priceperhour'] ?></td>
                            <td class="<?php echo ($row['status'] == 'Available') ? 'text-success fw-bold' : 'text-warning fw-bold'; ?>">
                                <?= ucfirst($row['status']); ?>
                            </td>
                            <td><?= date('F j, Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-primary" data-target="#<?= $modal_id_update ?>" data-toggle="modal">Update Info</button>
                                <button class="btn btn-danger" data-target="#<?= $modal_id_delete ?>" data-toggle="modal">Delete</button>
                            </td>
                        </tr>

                        <!-- Update Info Modal -->
                        <div class="modal fade" id="<?= $modal_id_update ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Update Venue #<?= $row['venue_id'] ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="venue_id" class="form-label">Venue ID</label>
                                                <input type="text" class="form-control" name="venue_id" value="<?= $row['venue_id'] ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="venue_name" class="form-label">Venue Name</label>
                                                <input type="text" class="form-control" name="venue_name" value="<?= $row['venue_name'] ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Location</label>
                                                <select class="form-control" name="category">
                                                    <?php
                                                    // Fetch locations for the select options
                                                    $category_sql = "SELECT * FROM locations";
                                                    $category_result = mysqli_query($conn, $category_sql);
                                                    while ($category_row = mysqli_fetch_assoc($category_result)) {
                                                        $selected = $category_row['loc_id'] == $row['loc_id'] ? 'selected' : '';
                                                        echo "<option value='{$category_row['loc_id']}' $selected>{$category_row['location_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="min_capacity" class="form-label">Minimum Capacity</label>
                                                <input type="number" class="form-control" name="min_capacity" value="<?= $row['min_capacity'] ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="max_capacity" class="form-label">Maximum Capacity</label>
                                                <input type="number" class="form-control" name="max_capacity" value="<?= $row['max_capacity'] ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="priceperhour" class="form-label">Price per Hour</label>
                                                <input type="text" class="form-control" name="priceperhour" value="<?= $row['priceperhour'] ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Venue Image</label>
                                                <input type="file" class="form-control" name="image">
                                                <input type="hidden" name="current_image" value="<?= $row['image'] ?>">
                                                <img src="/venue/<?= $row['image'] ?>" alt="Current Image" class="img-thumbnail mt-2" width="100">
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Venue Status</label>
                                                <select class="form-control" name="status">
                                                    <option value="Available" <?= $row['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                                                    <option value="Unavailable" <?= $row['status'] == 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="update_info">Save changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Delete Room Modal -->
                        <div class="modal fade" id="<?= $modal_id_delete ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Venue #<?= $row['venue_id'] ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <p>Are you sure you want to delete Venue #<?= $row['venue_id'] ?>?</p>
                                            <input type="hidden" name="venue_id" value="<?= $row['venue_id'] ?>">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger" name="delete_venue">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center">No data available</td></tr>';
                }
                ?>
            </tbody>
        </table>


    </div>
    <div class="d-flex justify-content-center">
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page - 1])); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $pages; $i++) : ?>
                    <li class="page-item <?php echo $current_page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $current_page == $pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page + 1])); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- Add Venue Modal -->
<div class="modal fade" id="addVenueModal" tabindex="-1" aria-labelledby="addVenueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVenueModalLabel">Add New Venue</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="venue_name" class="form-label">Venue Name</label>
                        <input type="text" class="form-control" name="venue_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" name="category" required>
                            <?php
                            // Fetch locations for the select options
                            $category_sql = "SELECT * FROM locations";
                            $category_result = mysqli_query($conn, $category_sql);
                            while ($category_row = mysqli_fetch_assoc($category_result)) {
                                echo "<option value='{$category_row['loc_id']}'>{$category_row['location_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="min_capacity" class="form-label">Minimum Capacity</label>
                        <input type="number" class="form-control" name="min_capacity" required>
                    </div>
                    <div class="mb-3">
                        <label for="max_capacity" class="form-label">Maximum Capacity</label>
                        <input type="number" class="form-control" name="max_capacity" required>
                    </div>
                    <div class="mb-3">
                        <label for="priceperhour" class="form-label">Price per Hour</label>
                        <input type="text" class="form-control" name="priceperhour" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Venue Image</label>
                        <input type="file" class="form-control" name="image" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Venue Status</label>
                        <select class="form-control" name="status" required>
                            <option value="Available">Available</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_venue">Add Venue</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_POST['update_info'])) {
    // Get the form data
    $venue_id = $_POST['venue_id'];
    $venue_name = $_POST['venue_name'];
    $category = $_POST['category'];
    $min_capacity = $_POST['min_capacity'];
    $max_capacity = $_POST['max_capacity'];
    $priceperhour = $_POST['priceperhour'];
    $status = $_POST['status'];
    $current_image = $_POST['current_image'];

    // Check if a new image has been uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../venue/";
        $image_unique_name = uniqid() . '_' . $image;
        $target_file = $target_dir . $image_unique_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_to_save = $image_unique_name;
        } else {
            $image_to_save = $current_image;
        }
    } else {
        $image_to_save = $current_image;
    }

    $update_sql = "UPDATE venues SET 
                   venue_name = '$venue_name',
                   loc_id = '$category',
                   min_capacity = '$min_capacity',
                   max_capacity = '$max_capacity',
                   priceperhour = '$priceperhour',
                   status = '$status',
                   image = '$image_to_save'
                   WHERE venue_id = '$venue_id'";

    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'Venue updated successfully.',
        });
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=venue.php">';
        exit();
    } else {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'error',
            title: 'Error updating',
        });
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=venue.php">';
        exit();
    }
}
if (isset($_POST['delete_venue'])) {

    $venue_id = mysqli_real_escape_string($conn, $_POST['venue_id']);

    $fetch_image_sql = "SELECT image FROM venues WHERE venue_id = '$venue_id'";
    $fetch_image_result = mysqli_query($conn, $fetch_image_sql);
    $image_row = mysqli_fetch_assoc($fetch_image_result);
    $image_filename = $image_row['image'];


    $delete_sql = "DELETE FROM venues WHERE venue_id = '$venue_id'";

    if (mysqli_query($conn, $delete_sql)) {

        if (unlink("../venue/$image_filename")) {
            $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'success',
                title: 'Venue and associated image deleted successfully!',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=venue.php">';
            exit();
        } else {

            $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'error',
                title: 'Error deleting image file',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=venue.php">';
            exit();
        }
    } else {
        $_SESSION['alert'] = "<script>
          Toast.fire({
              icon: 'error',
              title: 'Error. Please Try Again',
          });
          </script>";
        echo '<meta http-equiv="refresh" content="0;url=venue.php">';
        exit();
    }
}

if (isset($_POST['add_venue'])) {
    // Get the form data
    $venue_name = $_POST['venue_name'];
    $category = $_POST['category'];
    $min_capacity = $_POST['min_capacity'];
    $max_capacity = $_POST['max_capacity'];
    $priceperhour = $_POST['priceperhour'];
    $status = $_POST['status'];
    $image = $_FILES['image']['name'];

    // Handle the image upload
    $target_dir = "../venue/";
    $image_unique_name = uniqid() . '_' . $image;
    $target_file = $target_dir . $image_unique_name;

    // Check if the file is a real image and move it to the target directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO venues (venue_name, loc_id, min_capacity, max_capacity, priceperhour, status, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiisss", $venue_name, $category, $min_capacity, $max_capacity, $priceperhour, $status, $image_unique_name);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'success',
                title: 'New venue added successfully.',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=venue.php">';
            exit();
        } else {
            echo "Error adding venue: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'error',
            title: 'There is an error. Please try again',
        });
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=venue.php">';
        exit();
    }
}
?>

<?php include('layouts/footer.php') ?>