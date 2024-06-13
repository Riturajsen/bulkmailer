<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host = 'localhost';
$dbname = 'email_project';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM email_list";
$result = $conn->query($query);

if ($result->num_rows > 0) {


    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'YOUR_EMAIL';
        $mail->Password = 'YOUR_PASS';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS ;
        $mail->Port = 465;

        $mail->setFrom('YOUR_EMAIL', 'YOUR_NAME');
        $mail->isHTML(true);

        while ($row = $result->fetch_assoc()) {
            if ($row['mailsent'] == 0){
            $mail->clearAddresses(); // Clear all addresses before each email
            $mail->addAddress($row['email']);
            $mail->Subject = 'Collaboration Opportunity with IIAHM Aviation Academy';
            $mail->Body = 'Dear ' . htmlspecialchars($row['comp_name']) . ',<br><br>' .

                'I hope this message finds you well. My name is Rituraj Sen, and I am the Outreach Manager at YOUR_COMPANY_NAME. We are one of the leading institutions in aviation education from Bhopal Madhya Pradesh, dedicated to training the next generation of professionals in the hospitality and aviation industries.<br><br>' .

                'I am reaching out to explore the possibility of a collaboration between '.$row['comp_name'].' and YOUR_COMPANY_NAME. We believe that a partnership with your esteemed company could provide our students with invaluable real-world experience and offer you access to a talented and motivated pool of future professionals.<br><br>' .

                'We are particularly interested in discussing opportunities for internships, job placements, and any other collaborative initiatives that could benefit both our students and your organization. I have attached a detailed proposal outlining the potential benefits of this collaboration. I would be delighted to arrange a meeting at your earliest convenience to discuss this opportunity further.<br><br>' .

                'Thank you for considering this proposal. I look forward to the possibility of working together to create valuable opportunities for our students and support your company’s talent needs.<br><br>' .

                'Best regards,<br><br>' .

             ;

            try {
                $mail->send();
                $query_change = "UPDATE email_list SET mailsent=1";
                $return_change = mysqli_query($conn,$query_change);
                if($return_change){
                echo "Message sent to {$row['email']} at {$row['comp_name']} And Status Changed<br>";
            } 
            else{
                echo "Message sent to {$row['email']} at {$row['comp_name']} But no status Change<br>";

            }}
            catch (Exception $e) {
                echo "Message could not be sent to {$row['email']}. Mailer Error: {$mail->ErrorInfo}<br>";
            } 
        }else{
         echo "Message Already sent to {$row['comp_name']} at {$row['email']}<br>";
        }

        }

        echo 'All emails processed.';
    } catch (Exception $e) {
        echo "Mailer setup failed: {$mail->ErrorInfo}";
    }
// If Condition ends


} else {
    echo "No emails found in the database.";
}

$conn->close();
?>
