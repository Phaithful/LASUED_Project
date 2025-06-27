<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Users extends dbobject
{

public function loginUser($data)
{
    $validation = $this->validate(
        $data,
        [
            'matric'   => 'required',
            'password' => 'required'
        ],
        [
            'matric'   => 'Matric Number',
            'password' => 'Password'
        ]
    );

    if ($validation['error']) {
        return json_encode([
            'response_code' => 13,
            'response_message' => $validation['messages'][0]
        ]);
    }

    $matric   = $data['matric'];
    $password = $data['password'];

    $sql = "SELECT matric, firstname, lastname, password, pin_missed, user_locked FROM students WHERE matric = '$matric' LIMIT 1";
    $result = $this->db_query($sql, true);

    if (count($result) === 0) {
        return json_encode(['response_code' => 20, 'response_message' => 'Invalid matric or password']);
    }

    $user = $result[0];

    if ($user['user_locked'] == 1) {
        return json_encode([
            'response_code' => 61,
            'response_message' => 'Your account has been locked due to multiple failed login attempts. Please contact the administrator.'
        ]);
    }

    // Decrypt and compare password
    require_once('C:/xampp/htdocs/school-project/LASUED_Project/libs/desencrypt.php');
    $desencrypt = new DESEncryption();
    $key = $matric;
    $cipher_password = $desencrypt->des($key, $password, 1, 0, null, null);
    $str_cipher_password = $desencrypt->stringToHex($cipher_password);

    if ($str_cipher_password !== $user['password']) {
        $pin_missed = $user['pin_missed'] + 1;

        if ($pin_missed >= 5) {
            $this->lockUser($matric); // 🔐 lock account
            return json_encode([
                'response_code' => 62,
                'response_message' => 'Too many failed attempts. Your account has been locked.'
            ]);
        } else {
            $this->updatePinMissed($matric, $pin_missed); // 📌 update failed count
            $remaining = 5 - $pin_missed;
            return json_encode([
                'response_code' => 90,
                'response_message' => "Invalid matric or password. $remaining attempt(s) remaining."
            ]);
        }
    }

    // ✅ Reset login status on success
    $this->resetLoginStatus($matric);

    // ✅ Login successful
    $_SESSION['matric_sess']    = $user['matric'];
    $_SESSION['firstname_sess'] = $user['firstname'];
    $_SESSION['lastname_sess']  = $user['lastname'];

    return json_encode([
        "response_code" => 0,
        "response_message" => "Login successful"
    ]);
}





   
public function registerUser($data)
{
    $confirm_password = isset($data['confirm_password']) ? $data['confirm_password'] : '';
    $data['confirm_password'] = $confirm_password;
    // Optional: Debug session
    // file_put_contents("debug.log", print_r($_SESSION, true));


    // Validation (adjust fields to match your form)
    $validation = $this->validate(
        $data,
        array(
            'firstname' => 'required|min:2',
            'lastname' => 'required',
            'email' => 'required|email',
            'matric' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|matches:password'
        ),
        array(
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'matric' => 'Matric Number',
            'password' => 'Password'
        )
    );

    if ($validation['error']) {
        $msg = is_array($validation['messages']) ? $validation['messages'][0] : 'Validation failed';
        return json_encode(array("response_code" => 20, "response_message" => $msg));
    }

    // Encrypt password
    require_once('C:/xampp/htdocs/school-project/LASUED_Project/libs/desencrypt.php');
    $desencrypt = new DESEncryption();
    $key = $data['matric'];
    $cipher_password = $desencrypt->des($key, $data['password'], 1, 0, null, null);
    $data['password'] = $desencrypt->stringToHex($cipher_password);

    // Add timestamp
    $data['created'] = date('Y-m-d H:i:s');
    $data['terms'] = isset($data['terms']) ? 1 : 0;

    // Insert into DB
    try {
        $count = $this->doInsert('students', $data, ['op', 'confirm_password', 'operation', 'nrfa-csrf-token-label']);
        if ($count == 1) {
            return json_encode(array("response_code" => 0, "response_message" => 'User registered successfully'));
        } else {
            return json_encode(array("response_code" => 78, "response_message" => 'Failed to register user'));
        }
    } catch (Exception $e) {
        return json_encode(array("response_code" => 500, "response_message" => "Error: " . $e->getMessage()));
    }
}


    public function passwordHash($secret)
    {
        $hashvalue = password_hash($secret, PASSWORD_DEFAULT);
        return $hashvalue;
        //		echo "<br/>".password_verify($secret,'$2y$10$s4N.5vNNy5iniEQ2Pycn.uE.OJJ69p.1eT9W6JOce7j9TAgzjrxJS');
        //		var_dump( password_get_info('$2y$10$s4N.5vNNy5iniEQ2Pycn.uE.OJJ69p.1eT9W6JOce7j9TAgzjrxJS') );
    }
}
