<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class AuthenticationController extends REST_Controller  {
    public function __construct() {
		parent::__construct();
        $this->load->library('Authorization_Token');
		$this->load->model('API/AuthenticationModel','AuthenticationModelObj');
	}
	
	public function login_post() {	
		$final_response = [];
	    try {
	        // set validation rules
    		$this->form_validation->set_rules('email', 'email', 'required|trim|valid_email');
    		$this->form_validation->set_rules('password', 'Password', 'required|trim');
    		if ($this->form_validation->run() == false) {
    			// validation not ok, send validation errors to the view
    			$final_response['status'] = 0;
    			$final_response['message'] = str_replace("\n", "", strip_tags(validation_errors()));
                $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
    		} else {
    			// set variables from the form
    			$email = $this->input->post('email',TRUE);
    			$password = $this->input->post('password',TRUE);
    			
    			if ($this->AuthenticationModelObj->resolve_user_login($email, $password)) {
    				$user_id = $this->AuthenticationModelObj->get_user_id_from_email($email);
    				$user    = $this->AuthenticationModelObj->get_user($user_id,false);
    			
    				// user login ok
                    $token_data['uid'] = $user_id;
                    $token_data['username'] = $user->username; 
					$token_data['is_admin'] = $user->is_admin; 
                    $tokenData = $this->authorization_token->generateToken($token_data);
                    $this->AuthenticationModelObj->update('users',array('token' => $tokenData),['id' => $user_id ]);
                    $final_response['status']       = 1;
                    $final_response['message']      = 'Login success!';
                    $final_response['access_token'] = $tokenData;
					$final_response['data']         = [];
                    $this->response($final_response, REST_Controller::HTTP_OK); 
    			} else {
    				// login failed
    				$final_response['status']  = 0;
                    $final_response['message'] = 'Wrong username or password.';
                    $this->response($final_response, REST_Controller::HTTP_UNAUTHORIZED);
    			}
		    }
	    } catch (Exception $err) {
	        $final_response['status'] = 0;
            $final_response['message'] = $err->getMessage();
	        $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
	    }
	}


	public function logout_post() {
		$final_response = [];
		try {
			$token = $this->authorization_token->validateToken();
			$getTokenData  = $this->AuthenticationModelObj->check_token_user($token["data"]->uid);
			if (!empty($getTokenData->token)) {
				$this->AuthenticationModelObj->update('users',array('token' =>''),['id' => $getTokenData->id ]);
			} else {
				throw new Exception("Token is invalid.");
			}
			$final_response['status'] = 1;
			$final_response['message'] = 'Logout!';
			$this->response($final_response, REST_Controller::HTTP_OK);
		} catch (Exception $err) {
			$final_response['status'] = 0;
            $final_response['message'] = $err->getMessage();
	        $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}


?>