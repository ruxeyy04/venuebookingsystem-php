<?php include('layouts/header.php') ?>
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Locations</h1>

<?php
// Sanitize input to prevent SQL injection
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Get total rows
$total_rows_sql = "SELECT COUNT(*) AS total FROM locations";
if (!empty($search)) {
    $total_rows_sql .= " WHERE location_name LIKE '%$search%'";
}

$total_rows_result = mysqli_query($conn, $total_rows_sql);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

$limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
$sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

$pages = ceil($total_rows / $limit);
$offset = ($current_page - 1) * $limit;

$sort_column = 'locations.loc_id';
$sort_order = ($sorting == 'desc') ? 'DESC' : 'ASC';

$sql = "SELECT * FROM locations";

if (!empty($search)) {
    $sql .= " WHERE location_name LIKE '%$search%'";
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
                    <button type="button" class="btn btn-primary custom-btn" data-toggle="modal" data-target="#addLocationModal" style="margin-left: 10px ;width: 150px; padding: 0px;">Add Location</button>
                </form>
            </div>
        </div>
    </div>
    <div class="client-table">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Location No.</th>
                    <th scope="col">Location Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date Created</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $modal_id_update = 'updateInfo_' . $row['loc_id'];
                        $modal_id_delete = 'deleteLocation_' . $row['loc_id'];
                ?>
                        <tr>
                            <th scope="row"><?= $row['loc_id'] ?></th>
                            <td><?= $row['location_name'] ?></td>
                            <td><?= $row['address'] ?></td>
                            <td><?= $row['description'] ?></td>
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
                                        <h5 class="modal-title" id="exampleModalLabel">Update Location #<?= $row['loc_id'] ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="loc_id" class="form-label">Location ID</label>
                                                <input type="text" class="form-control" name="loc_id" value="<?= $row['loc_id'] ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="location_name" class="form-label">Location Name</label>
                                                <input type="text" class="form-control" name="location_name" value="<?= $row['location_name'] ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" class="form-control" name="address" value="<?= $row['address'] ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control" name="description"><?= $row['description'] ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Location Image</label>
                                                <input type="file" class="form-control" name="location_image">
                                                <input type="hidden" name="current_image" value="<?= $row['image'] ?>">
                                                <img src="/location/<?= $row['image'] ?>" alt="Current Image" class="img-thumbnail mt-2" width="100">
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="update_info">Save changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Delete Location Modal -->
                        <div class="modal fade" id="<?= $modal_id_delete ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Location #<?= $row['loc_id'] ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="">
                                            <p>Are you sure you want to delete Location #<?= $row['loc_id'] ?>?</p>
                                            <input type="hidden" name="loc_id" value="<?= $row['loc_id'] ?>">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger" name="delete_location">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center">No data available</td></tr>';
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
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLocationModalLabel">Add New Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="location_name" class="form-label">Location Name</label>
                        <input type="text" class="form-control" name="location_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="location_address" class="form-label">Location Address</label>
                        <input type="text" class="form-control" name="location_address" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location_image" class="form-label">Location Image</label>
                        <input type="file" class="form-control" name="location_image" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_location">Add Location</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_POST['update_info'])) {
    // Get the form data
    $location_id = $_POST['loc_id'];
    $location_name = $_POST['location_name'];
    $location_address = $_POST['address'];
    $description = $_POST['description'];
    $current_image = $_POST['current_image'];

    // Check if a new image has been uploaded
    if (!empty($_FILES['location_image']['name'])) {
        $image = $_FILES['location_image']['name'];
        $target_dir = "../location/";
        $image_unique_name = uniqid() . '_' . $image;
        $target_file = $target_dir . $image_unique_name;

        if (move_uploaded_file($_FILES['location_image']['tmp_name'], $target_file)) {
            $image_to_save = $image_unique_name;
        } else {
            $image_to_save = $current_image;
        }
    } else {
        $image_to_save = $current_image;
    }

    $update_sql = "UPDATE locations SET 
                   location_name = '$location_name',
                   address = '$location_address',
                   description = '$description',
                   image = '$image_to_save'
                   WHERE loc_id = '$location_id'";

    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'Location updated successfully.',
        });
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=location.php">';
        exit();
    } else {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'error',
            title: 'Error updating location',
        });
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=location.php">';
        exit();
    }
}

if (isset($_POST['delete_location'])) {
    $location_id = mysqli_real_escape_string($conn, $_POST['loc_id']);

    $fetch_image_sql = "SELECT image FROM locations WHERE loc_id = '$location_id'";
    $fetch_image_result = mysqli_query($conn, $fetch_image_sql);
    $image_row = mysqli_fetch_assoc($fetch_image_result);
    $image_filename = $image_row['image'];

    $delete_sql = "DELETE FROM locations WHERE loc_id = '$location_id'";

    if (mysqli_query($conn, $delete_sql)) {
        if (unlink("../location/$image_filename")) {
            $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'success',
                title: 'Location and associated image deleted successfully!',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=location.php">';
            exit();
        } else {
            $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'error',
                title: 'Error deleting image file',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=location.php">';
            exit();
        }
    } else {
        $_SESSION['alert'] = "<script>
          Toast.fire({
              icon: 'error',
              title: 'Error deleting location. Please Try Again',
          });
          </script>";
        echo '<meta http-equiv="refresh" content="0;url=location.php">';
        exit();
    }
}

if (isset($_POST['add_location'])) {
    // Get the form data
    $location_name = $_POST['location_name'];
    $location_address = $_POST['location_address'];
    $description = $_POST['description'];
    $image = $_FILES['location_image']['name'];

    // Handle the image upload
    $target_dir = "../location/";
    $image_unique_name = uniqid() . '_' . $image;
    $target_file = $target_dir . $image_unique_name;

    // Check if the file is a real image and move it to the target directory
    if (move_uploaded_file($_FILES['location_image']['tmp_name'], $target_file)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO locations (location_name, address, `description`, created_at, `image`) VALUES (?, ?, ?, NOW(),?)");
        $stmt->bind_param("ssss", $location_name, $location_address, $description, $image_unique_name);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'success',
                title: 'New location added successfully.',
            });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=location.php">';
            exit();
        } else {
            echo "Error adding location: " . $stmt->error;
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
        echo '<meta http-equiv="refresh" content="0;url=location.php">';
        exit();
    }
}
?>

<?php include('layouts/footer.php') ?>