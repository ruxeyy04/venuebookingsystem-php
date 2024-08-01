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
                            <input name="username" type="text" placeholder="Username" required />
                        </div>
                        <div class="col-md-12 mt-4 ajax-form">
                            <input name="password" type="password" placeholder="Password" required />
                        </div>

                        <div class="section clearfix"></div>
                        <div class="col-md-12 mt-3 ajax-checkbox">
                            <ul class="list">
                                <li class="list__item">
                                    <label class="label--checkbox">
                                        <a href="register.php">Register Now</a>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 mt-3 ajax-form text-center">
                            <button class="send_message" type="submit" id="send" name="login"><span>Login</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check username and password
    $sql = "SELECT userid, usertype FROM userinfo WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usertype = $row['usertype'];
        $userid = $row['userid'];
        $_SESSION['userid'] = $userid;
        $_SESSION['usertype'] = $usertype;
        switch ($usertype) {
            case 'Admin':
                $_SESSION['alert'] = "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome Admin',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/admin/';
                        }
                    });
                </script>";
                echo '<meta http-equiv="refresh" content="0;url=login.php">';
                exit();
                break;
            case 'Incharge':
                $_SESSION['alert'] = "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome Incharge',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/incharge/';
                        }
                    });
                </script>";
                echo '<meta http-equiv="refresh" content="0;url=login.php">';
                exit();
                break;
            case 'Client':
                $_SESSION['alert'] = "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome Client',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/index.php';
                        }
                    });
                </script>";
                echo '<meta http-equiv="refresh" content="0;url=login.php">';
                exit();
                break;
            default:
                $_SESSION['alert'] = "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'error',
                                                    text: 'Unknown usertype',
                                                    allowOutsideClick: false
                                                })
                                            </script>";
                echo '<meta http-equiv="refresh" content="0;url=login.php">';
                exit();
        }
    } else {
        $_SESSION['alert'] = "<script>
        Swal.fire({
            icon: 'info',
            title: 'Invalid',
            text: 'Sign In Failed, Incorrect Email or Password',
        })
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit();
    }

    $conn->close();
}
?>

<?php include('layouts/footer.php') ?>