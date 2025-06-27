<?php
require_once __DIR__ . '/../vendor/autoload.php'; // adjust path if needed
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




class Contact extends dbobject {
    public function contactUs($data) {
        // Validation
        $validation = $this->validate(
            $data,
            [
                'first_name' => 'required',
                'last_name'  => 'required',
                'email'      => 'required|email',
                'phone'      => 'required',
                'message'    => 'required'
            ],
            [
                'first_name' => 'First Name',
                'last_name'  => 'Last Name',
                'email'      => 'Email',
                'phone'      => 'Phone Number',
                'message'    => 'Message'
            ]
        );

        if ($validation['error']) {
            return json_encode([
                'response_code' => 13,
                'response_message' => $validation['messages'][0]
            ]);
        }

        // Collect and sanitize
        $name    = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
        $email   = htmlspecialchars($data['email']);
        $phone   = htmlspecialchars($data['phone']);
        $message = nl2br(htmlspecialchars($data['message']));

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'danassco360@gmail.com';        // ✅ your Gmail
            $mail->Password   = 'gdziwavvjxpigqyk';           // ✅ your app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Sender & recipient
            $mail->setFrom($email, $name);
            $mail->addAddress('danassco360@gmail.com', 'DESD'); // ✅ where to receive messages
            $mail->addReplyTo($email, $name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "New Contact Us Message from $name";
            $mail->Body    = "
                <h3>New Contact Message</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Message:</strong><br>$message</p>
            ";

            $mail->send();

            return json_encode([
                'response_code' => 0,
                'response_message' => 'Thank you! Your message has been sent successfully.'
            ]);
        } catch (Exception $e) {
            return json_encode([
                'response_code' => 1,
                'response_message' => 'Mailer Error: ' . $mail->ErrorInfo
            ]);
        }
    }
}
