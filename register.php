<?php include('layouts/header.php') ?>
<div class="section big-55-height over-hide z-bigger">

    <div class="parallax parallax-top" ></div>
    <div class="dark-over-pages"></div>

    <div class="hero-center-section pages">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 parallax-fade-top">
                    <div class="hero-text">Login/Register</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section padding-top z-bigger padding-bottom">
    <div class="container">
        <div class="row justify-content-center padding-bottom-smaller">
            <div class="col-md-6 m-5">.
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-12 ajax-form">
                            <input name="fname" type="text" placeholder="First Name" required />
                        </div>
                        <div class="col-md-12 mt-4 ajax-form">
                            <input name="lname" type="text" placeholder="Last Name" required />
                        </div>
                        <div class="col-md-12 mt-4 ajax-form">
                            <input name="username" type="text" placeholder="Username" required />
                        </div>
                        <div class="col-md-12 mt-4 ajax-form">
                            <input name="email" type="email" placeholder="Email" required />
                        </div>
                        <div class="col-md-12 mt-4 ajax-form">
                            <input name="contact" type="text" placeholder="Contact Number" required />
                        </div>
                         <div class="col-md-12 mt-4 ajax-form">
                            <div class="mb-3">
                                        <select name="gender" class="wide" style="display: none;" required>
                                            <option data-display="gender" selected disabled> - Select Gender - </option>
                                            <option value="Male" >Male</option>
                                            <option value="Female" >Female</option>
                                        </select>
                                        <div class="nice-select wide" tabindex="0"><span class="current" style="color: black;"> - Select Gender - </span>
                                            <ul class="list">
                                                <li data-value="gender" data-display="gender" class="option selected disabled"> - Select Gender - </li>
                                                <li data-value="Male" class="option">Male</li>
                                                <li data-value="Female" class="option">Female</li>
                                            </ul>
                                        </div>
                                    </div>
                        </div>
                        <div class="col-md-12 mt-4 ajax-form">
                            <input name="password" type="password" placeholder="Password" required />
                        </div>

                        <div class="section clearfix"></div>
                        <div class="col-md-12 mt-3 ajax-checkbox">
                            <ul class="list">
                                <li class="list__item">
                                    <label class="label--checkbox">
                                        Already have an account? <a href="login.php">Login Now</a>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 mt-3 ajax-form text-center">
                            <button class="send_message" type="submit" id="send" name="signup"><span>Sign Up</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_POST['signup'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $username_check_sql = "SELECT * FROM `userinfo` WHERE `username` = '$username'";
    $username_result = $conn->query($username_check_sql);

    $email_check_sql = "SELECT * FROM `userinfo` WHERE `email` = '$email'";
    $email_result = $conn->query($email_check_sql);

    if ($username_result->num_rows > 0) {
        $_SESSION['alert'] = "<script>
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Username already exists'
              });
            </script>";
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    } elseif ($email_result->num_rows > 0) {
        $_SESSION['alert'] = "<script>
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Email already exists'
              });
            </script>";
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    } else {
        $sql = "INSERT INTO `userinfo`(`fname`, `lname`, `username`, `email`, `password`, contact, gender) VALUES ('$fname', '$lname', '$username', '$email', '$password', '$contact', '$gender')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['alert'] = "<script>
                Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: 'New account is registered successfully',
                  allowOutsideClick: false
                }).then((result) => {
                  if (result.isConfirmed) {
                    window.location.href = 'login.php';
                  }
                });
              </script>";
            echo '<meta http-equiv="refresh" content="0;url=login.php">';
            exit();
        } else {
            $_SESSION['alert'] = "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Error: " . $sql . "<br>" . $conn->error . "'
                });
              </script>";
            echo '<meta http-equiv="refresh" content="0;url=login.php">';
            exit();
        }
    }

    $conn->close();
}

?>
<?php include('layouts/footer.php') ?>