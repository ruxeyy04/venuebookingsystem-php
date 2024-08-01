<?php

$servername = "localhost";
            $username = "u659181579_venuebooking";
            $password = "=3!6WOv8xgZ";
            $database = "u659181579_venuebooking";


$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Arrays for random data
$first_names = ["John", "Jane", "Alex", "Emily", "Michael", "Sarah", "David", "Emma", "Chris", "Olivia", "James", "Sophia", "Daniel", "Isabella", "Matthew", "Mia", "Andrew", "Charlotte", "Joseph", "Amelia", "Joshua", "Harper", "Ryan", "Evelyn", "Brandon", "Ava", "Ethan", "Liam", "Noah", "William", "Benjamin", "Elijah", "Lucas", "Mason", "Logan", "Oliver", "Jacob", "Jack", "Henry", "Thomas"];
$middle_names = ["Aiden", "Grace", "Taylor", "Morgan", "Parker", "Jordan", "Lee", "Marie", "Rose", "Paul", "Joseph", "James", "Elizabeth", "Ann", "Lynn", "Ray", "Scott", "Joan", "Kate", "Jane", "Brett", "Dean", "Faith", "Elliott", "Claire", "Jack", "Ruth", "Louise", "Michael", "Max", "Charles", "Toby", "Eden", "Chloe", "Hannah", "Riley", "Brooke", "Samantha", "Ryan", "Blake"];
$last_names = ["Smith", "Johnson", "Brown", "Taylor", "Anderson", "Thomas", "Jackson", "White", "Harris", "Martin", "Thompson", "Garcia", "Martinez", "Robinson", "Clark", "Rodriguez", "Lewis", "Lee", "Walker", "Hall", "Allen", "Young", "King", "Wright", "Scott", "Green", "Baker", "Adams", "Nelson", "Hill", "Ramirez", "Campbell", "Mitchell", "Roberts", "Carter", "Phillips", "Evans", "Turner", "Torres", "Parker"];
$email_domains = ["example.com", "testmail.com", "myemail.com", "randommail.com", "fakemail.com", "email.com", "mail.com", "domain.com", "webmail.com", "service.com"];
$user_types = ["Client", "Incharge", "Admin"];

// Function to generate random email
function generateRandomEmail($firstName, $lastName, $domains)
{
    $domain = $domains[array_rand($domains)];
    return strtolower($firstName . "." . $lastName . "@" . $domain);
}

// Function to generate random username
function generateRandomUsername($firstName, $lastName)
{
    return strtolower($firstName . "." . $lastName);
}

// Function to generate random contact number
function generateRandomContactNumber()
{
    return '09' . rand(100000000, 999999999); // Assuming a 9-digit contact number starting with 09
}

// Insertion loop
for ($i = 0; $i < 495; $i++) {
    $firstName = $first_names[array_rand($first_names)];
    $middleName = $middle_names[array_rand($middle_names)];
    $lastName = $last_names[array_rand($last_names)];
    $email = generateRandomEmail($firstName, $lastName, $email_domains);
    $username = generateRandomUsername($firstName, $lastName);
    $gender = (rand(0, 1) == 1) ? 'Male' : 'Female';
    $contactNumber = generateRandomContactNumber();
    $password = '1234';
    $userType = $user_types[array_rand($user_types)];
    $image = 'default.png'; // Assuming a default image for all users

    $sql = "INSERT INTO userinfo (fname, midname, lname, gender, email, username, password, usertype, contact, image)
            VALUES ('$firstName', '$middleName', '$lastName', '$gender', '$email', '$username', '$password', '$userType', '$contactNumber', '$image')";

    if ($conn->query($sql) === TRUE) {
        echo "Record $i inserted successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
