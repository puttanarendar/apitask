<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class UserController extends REST_Controller  {
    public function __construct() {
		parent::__construct();
        $this->load->library('Authorization_Token');
		$this->load->model('API/AuthenticationModel','AuthenticationModelObj');
	}
	
	public function register_post() {
		$final_response = [];
	    try {
	        // set validation rules
    		$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|min_length[10]|max_length[11]|is_unique[users.phone_number]');
            $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|callback_valid_dob',["valid_dob" => "The {field} must be in Y-m-d format. "]);
    		if ($this->form_validation->run() == false) {
    			// validation not ok, send validation errors to the view
                throw new Exception(str_replace("\n", "", strip_tags(validation_errors())));
    		} else {
                // check user role is admin
                //$this->check_user_admin_and_token();
                
    			// set variables from the form
                $user_data = [
                    "username" => $this->input->post('username',TRUE),
                    "email"    => $this->input->post('email',TRUE),
                    "password" => $this->input->post('password',TRUE),
                    "dob"      => date("Y-m-d",strtotime($this->input->post('dob',TRUE))),
                    "phone"    => $this->input->post('phone',TRUE),
                    "is_admin"    => $this->input->post('is_admin',TRUE),
                ];
    			
    			if ($res = $this->AuthenticationModelObj->create_user($user_data)) {
                    $final_response['status']   = 1;
                    $final_response['message'] = 'Thank you for registering your new account!';
                    $final_response['note']    = 'You have successfully register. Please check your email inbox to confirm your email address.';
                    $this->response($final_response, REST_Controller::HTTP_OK); 
    			} else {
    				// login failed
                    throw new Exception("There was a problem creating your new account. Please try again.");
    			}
		    }
	    } catch (Exception $err) {
	        $final_response['status'] = 0;
            $final_response['message'] = $err->getMessage();
	        $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
	    }
	}

    public function valid_dob($str) {
        $date = trim($str);
        $format = 'Y-m-d'; // Define the expected date format
        $date_obj = DateTime::createFromFormat($format, $str);
    
        return $date_obj && $date_obj->format($format) === $str;
    }    

    public function update_put($id) {
        $final_response = [];
        $user_data = [] ;
        try {
            // set validation rules
            $put_data = file_get_contents("php://input");
            $data = json_decode($put_data, true);
            $data['id'] = $id;
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('id', 'Id', 'trim|required|alpha_numeric|callback_check_id_exist');
    		$this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|min_length[4]');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_check_email_exists['.$id.']',["check_email_exists" =>"Email already exist."]);
            $this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]');
            $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|callback_valid_dob',["valid_dob" => "The {field} must be in yyyy-mm-dd format. "]);
    		if ($this->form_validation->run() == false) {
    			// validation not ok, send validation errors to the view
                throw new Exception(str_replace("\n", "", strip_tags(validation_errors())));
    		} else {
                // check user role is admin
                $this->check_user_admin_and_token();
                $getUserData  = $this->AuthenticationModelObj->get_user($id,false);
                if(isset($getUserData->is_admin) && $getUserData->is_admin == 1) {
                    throw new Exception("Admin details can't be updated.");
                }
    			// set variables from the form
                
                isset($data['username']) ? $user_data["username"] = $data['username'] : "";
                isset($data['email'])    ? $user_data["email"]    = $data['email'] : "";
                isset($data['password']) ? $user_data["password"] = password_hash($data['password'], PASSWORD_BCRYPT) : "";
                isset($data['dob'])      ? $user_data["dob"]      = date("Y-m-d",strtotime($data['dob'])) : "";
                $user_data["updated_at"] = date('Y-m-j H:i:s');
    			if ($this->AuthenticationModelObj->update('users',$user_data,['id' => $id])) {
                    $final_response['status']   = 1;
                    $final_response['message'] = 'User updated successfully.';
                    $this->response($final_response, REST_Controller::HTTP_OK); 
    			} else {
    				// update data
                    throw new Exception("There was a problem while updating details. Please try again.");
    			}
		    }
        } catch (Exception $err) {
            $final_response['status'] = 0;
            $final_response['message'] = $err->getMessage();
	        $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function check_id_exist($id) {
        $get_user_data = $this->AuthenticationModelObj->get_user($id,false);
        if ($get_user_data) {
            return true; // The user with the provided ID exists
        } else {
            $this->form_validation->set_message('check_id_exist', 'User id does not exist.');
            return false;
        }
    }
    

    public function check_email_exists($email, $id) {
        $get_user_data = $this->AuthenticationModelObj->update_check_user_data($email, $id);
        if ($get_user_data) {
            $this->form_validation->set_message('check_email_exist', 'Email already exist.');
            return false; // The email already exists
        } else {
            return true; // The email does not exist or belongs to the current user
        }
    }

    private function check_user_admin_and_token() {
        $token = $this->authorization_token->validateToken();
    
        if (!isset($token["data"]->is_admin) || !$token["status"]) {
            throw new Exception("Token is not provided.");
        }
    
        if ($token["data"]->is_admin == 0) {
            throw new Exception("User is not admin.");
        }
    
        $checkTokenData = $this->AuthenticationModelObj->get_user($token["data"]->uid, true);
    
        if (empty($checkTokenData->token)) {
            throw new Exception("User is logged out.");
        }
    }    
    


	
}


?>