<?php
// upload.php
$host = 'localhost';
$dbname = 'email_project';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['email_csv'])) {
    $fileTmpPath = $_FILES['email_csv']['tmp_name'];
    $fileName = $_FILES['email_csv']['name'];
    $fileSize = $_FILES['email_csv']['size'];
    $fileType = $_FILES['email_csv']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('csv');

    if (in_array($fileExtension, $allowedfileExtensions)) {
        $handle = fopen($fileTmpPath, 'r');
        fgetcsv($handle); // Skip the header row
        while (($data = fgetcsv($handle)) !== FALSE) {
            $email = $data[0]; // Email is in the first column
            $comp_name = $data[1]; // Company name is in the second column
            $query = "INSERT INTO email_list (email, comp_name) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $email, $comp_name);
            $stmt->execute();
        }
        fclose($handle);
        echo "Emails and company names uploaded successfully.";
    } else {
        echo "Invalid file type. Please upload a CSV file.";
    }
} else {
    echo "No file uploaded.";
}

$conn->close();
?>
