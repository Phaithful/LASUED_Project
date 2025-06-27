<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Users extends dbobject
{

    // public function login($data)
	// {
        
	// 	$matric = $data['matric'];
	// 	$password = $data['password'];
    //     $validate = $this->validate(
    //         $data,
    //         array('matric' => 'required|email', 'password' => 'required'),
    //         array('matric' => 'matric', 'password' => 'Password')
    //     );
    //     if($validate['error'])
    //     {
    //         return json_encode(array('response_code'=>13,'response_message'=>$validate['messages'][0]));
    //     }
	// 	$sql      = "SELECT matric,firstname,lastname,sex,role_id,password,user_locked,user_disabled,pin_missed,day_1,day_2,day_3,day_4,day_5,day_6,day_7,passchg_logon,photo,church_id FROM students WHERE matric = '$matric' LIMIT 1";
	// 	$result   = $this->db_query($sql,true);
	// 	$count    = count($result); 
	// 	if($count > 0)
	// 	{
    //         if($result[0]['pin_missed'] < 5)
    //         {
    //             $encrypted_password = $result[0]['password'];
    //             $is_locked     = $result[0]['user_locked'];
    //             $is_disabled     = $result[0]['user_disabled'];
    //             // $verify_pass   = password_verify($password,$hash_password);

    //             $desencrypt = new DESEncryption();
    //             $key = $matric;
    //             $cipher_password = $desencrypt->des($key, $password, 1, 0, null,null);
    //             $str_cipher_password = $desencrypt->stringToHex ($cipher_password);
    //             if($str_cipher_password == $encrypted_password)
    //             // if(1 == 1)
    //             {
    //                 if($is_disabled != 1)
    //                 {
    //                     if($is_locked != 1)
    //                     {
    //                         $work_day = $this->workingDays($result[0]);
    //                         if($work_day['code'] != "44")
    //                         {
    //                             if($result[0]['church_id'] != "99")
    //                             {
    //                                 $church_details = $this->getItemLabelArr('church_table',array('church_id'),array($result[0]['church_id']),array('church_type','state','church_name'));
    //                                 $_SESSION['matric_sess']   = $result[0]['matric'];
    //                                 $_SESSION['firstname_sess']  = $result[0]['firstname'];
    //                                 $_SESSION['lastname_sess']   = $result[0]['lastname'];
    //                                 $_SESSION['photo_file_sess']  = $result[0]['photo'];
    //                                 $_SESSION['photo_path_sess']  = "img/profile_photo/".$result[0]['photo'];
    //                                 //update pin missed and last_login
    //                                 $this->resetpinmissed($matric);
    //                                 return json_encode(array("response_code"=>0,"response_message"=>"Login Successful"));
    //                             }
    //                             else
    //                             {
    //                                 return json_encode(array("response_code"=>779,"response_message"=>"You can't login now... A profile transfer is currently ongoing. Try again at a later time or contact the Administrator"));
    //                             }

    //                         }
    //                         else
    //                         {
    //                             return json_encode(array("response_code"=>61,"response_message"=>$work_day['mssg']));
    //                         }
    //                     }
    //                     else
    //                     {
    //                         //inform the user that the account has been locked, and to contact admin, user has to provide useful info b4 he is unlocked
    //                         return json_encode(array("response_code"=>60,"response_message"=>"Your account has been locked, kindly contact the administrator."));
    //                     }
    //                 }
    //                 else
    //                 {
    //                     return json_encode(array("response_code"=>610,"response_message"=>"Your user privilege has been revoked. Kindly contact the administrator"));
    //                 }
    //             }
    //             else	
    //             {
    //                 $this->updatepinmissed($matric);
                    
    //                 $remaining = (($result[0]['pin_missed']+1) <= 5)?(5-($result[0]['pin_missed']+1)):0;
    //                 return json_encode(array("response_code"=>90,"response_message"=>"Invalid matric or password, ".$remaining." attempt remaining"));
    //             }
    //         }
    //         elseif($result[0]['pin_missed'] == 5)
    //         {
    //             $this->updateuserlock($matric,'1');
    //             return json_encode(array("response_code"=>64,"response_message"=>"Your account has been locked, kindly contact the administrator."));
    //         }
    //         else
    //         {
    //              return json_encode(array("response_code"=>62,"response_message"=>"Your account has been locked, kindly contact the administrator."));
    //         }
	// 	}
    //     else
	// 	{
	// 		return json_encode(array("response_code"=>20,"response_message"=>"Invalid matric or password"));
	// 	}
    // }



   
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
