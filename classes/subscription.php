<?php
class Subscription extends dbobject
{
    public function subscribeEmail($data)
    {
        $email = trim($data['sub_email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['response_code' => 1, 'response_message' => 'Invalid email address.'];
        }

        $check = $this->doSelect("id", "email_subscriptions", ["email" => $email]);
        if (!empty($check)) {
            return ['response_code' => 1, 'response_message' => 'You are already subscribed.'];
        }

        $insert = $this->subEmail("email_subscriptions", [
            "email" => $email,
            "subscribed_at" => date("Y-m-d H:i:s")
        ]);

        if ($insert > 0) {
            return ['response_code' => 0, 'response_message' => 'Subscription successful.'];
        } else {
            return ['response_code' => 1, 'response_message' => 'Subscription failed. Try again.'];
        }
    }
}

?>
