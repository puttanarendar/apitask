<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class GroupController extends REST_Controller  {
    public function __construct() {
		parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('API/AuthenticationModel','AuthenticationModelObj');
		$this->load->model('API/GroupModel','GroupModelObj');
	}
	
	public function create_post() {
		$final_response  = [];
	    try {
	        // set validation rules
    		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|is_unique[group.name]');
            if ($this->form_validation->run() == false) {
    			// validation not ok, send validation errors to the view
                throw new Exception(str_replace("\n", "", strip_tags(validation_errors())));
    		} else {
                // check user role is admin
                $this->check_user_admin_and_token();
    			// set variables from the form
                $token = $this->authorization_token->validateToken();
                $insert_group_data = [
                    "name" => $this->input->post('name',TRUE),
                    "added_by"    => $token["data"]->uid,
                    "created_at" => date('Y-m-j H:i:s')
                ];
    			
    			if ($res = $this->GroupModelObj->insert_data('group',$insert_group_data)) {
                    $final_response['status']   = 1;
                    $final_response['message']  = 'Group Added Successfully!';
                    $final_response['data']     = [];
                    $this->response($final_response, REST_Controller::HTTP_OK); 
    			} else {
    				// login failed
                    throw new Exception("There was a problem creating your new group. Please try again.");
    			}
		    }
	    } catch (Exception $err) {
	        $final_response['status']   = 0;
            $final_response['message']  = $err->getMessage();
            $final_response['data']     = [];
	        $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
	    }
	}

    public function group_delete($id) {
        $final_response = [];
        try {
            if (empty($id)) {
                throw new Exception(str_replace("\n", "", strip_tags(validation_errors())));
    		}
            $this->check_user_admin_and_token();
            if ($res = $this->GroupModelObj->delete_group($id)) {
                $final_response['status']   = 1;
                $final_response['message']  = 'Group Deleted Successfully!';
                $final_response['data']     = [];
                $this->response($final_response, REST_Controller::HTTP_OK); 
            } else {
                // login failed
                throw new Exception("There was a problem deleting your  group. Please try again.");
            }
        } catch (Exception $err) {
            $final_response['status']   = 0;
            $final_response['message']  = $err->getMessage();
            $final_response['data']     = [];
	        $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
  
    public function check_id_exist($id) {
        $get_group_data = $this->GroupModelObj->get_group_data_from_id($id);
        if ($get_group_data) {
            return true; // The user with the provided ID exists
        } else {
            return false;
        }
    }

    public function group_search_post() {
        $final_response = [];
        try {
            $this->form_validation->set_rules('keyword', 'Keyword', 'trim|required|min_length[1]');
            if ($this->form_validation->run() == false) {
    			// validation not ok, send validation errors to the view
                throw new Exception(str_replace("\n", "", strip_tags(validation_errors())));
    		} else {
                $this->check_user_admin_and_token();
                if ($res = $this->GroupModelObj->fetch_search_group($this->input->post('keyword'))) {
                    $final_response['status']   = 1;
                    $final_response['message']  = 'Group List Found!';
                    $final_response['data']     = $res;
                    $this->response($final_response, REST_Controller::HTTP_OK); 
                } else {
                    // No Group found
                    throw new Exception("No Group List Found");
                }
            } 
        }
        catch (Exception $err) {
            $final_response['status']   = 0;
            $final_response['message']  = $err->getMessage();
            $final_response['data']     = [];
            $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function add_memeber_post() {
        try {
            $this->form_validation->set_rules('group_id', 'Group Id', 'trim|required|callback_check_id_exist',['check_id_exist'=> 'Group Id does not exist.']);
            $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
            if ($this->form_validation->run() == false) {
    			// validation not ok, send validation errors to the view
                throw new Exception(str_replace("\n", "", strip_tags(validation_errors())));
    		} else {
                $this->check_user_admin_and_token();
                if (!$this->GroupModelObj->check_group_members('users',['id'=>$this->input->post('user_id')])) {
                    throw new Exception("User id doesn't exist.");
                }
                if ($this->GroupModelObj->check_group_members('group_members',['group_id'=>$this->input->post('group_id'),'user_id'=>$this->input->post('user_id')])) {
                    throw new Exception("Already member exist.");
                }
                $token = $this->authorization_token->validateToken();
                $insert_params = [
                    'group_id' => $this->input->post('group_id'),
                    'user_id' => $this->input->post('user_id'),
                    "added_by"    => $token["data"]->uid,
                    "created_at" => date('Y-m-j H:i:s')
                ];
                if($res = $this->GroupModelObj->insert_data('group_members',$insert_params)) {
                    $final_response['status']   = 1;
                    $final_response['message']  = 'Member Added Successfully!';
                    $final_response['data']     = [];
                    $this->response($final_response, REST_Controller::HTTP_OK); 
                } else {
                    throw new Exception("There was a problem creating your new group memeber. Please try again.");
                }
            } 
        } catch (Exception $err) {
            $final_response['status']   = 0;
            $final_response['message']  = $err->getMessage();
            $final_response['data']     = [];
            $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function send_message_post() {
        try {
            $this->form_validation->set_rules('group_id', 'Group Id', 'trim|required|callback_check_id_exist',['check_id_exist'=> 'Group Id does not exist.']);
            $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            if ($this->form_validation->run() == false) {
    			// validation not ok, send validation errors to the view
                throw new Exception(str_replace("\n", "", strip_tags(validation_errors())));
    		} else {
                $this->check_user_admin_and_token();
                if (!$this->GroupModelObj->check_group_members('users',['id'=>$this->input->post('user_id')])) {
                    throw new Exception("User id doesn't exist.");
                }
                $token = $this->authorization_token->validateToken();
                $insert_params = [
                    'group_id' => $this->input->post('group_id'),
                    'user_id' => $this->input->post('user_id'),
                    'mesage' => $this->input->post('message'),
                    "added_by"    => $token["data"]->uid,
                    "created_at" => date('Y-m-j H:i:s')
                ];
                if($res = $this->GroupModelObj->insert_data('group_message',$insert_params)) {
                    $final_response['status']   = 1;
                    $final_response['message']  = 'Message Added Successfully!';
                    $final_response['data']     = [];
                    $this->response($final_response, REST_Controller::HTTP_OK); 
                } else {
                    throw new Exception("There was a problem creating your new message. Please try again.");
                }
            } 
        } catch (Exception $err) {
            $final_response['status']   = 0;
            $final_response['message']  = $err->getMessage();
            $final_response['data']     = [];
            $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function add_like_message_post() {
        try {
            $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
            $this->form_validation->set_rules('message_id', 'Message', 'trim|required');
            if ($this->form_validation->run() == false) {
    			// validation not ok, send validation errors to the view
                throw new Exception(str_replace("\n", "", strip_tags(validation_errors())));
    		} else {
                $this->check_user_admin_and_token();
                $token = $this->authorization_token->validateToken();
                if (!$this->GroupModelObj->check_group_members('users',['id'=>$this->input->post('user_id')])) {
                    throw new Exception("User id doesn't exist.");
                }
                if ($grpRes = $this->GroupModelObj->check_group_members('group_message',['id'=>$this->input->post('message_id')])) {
                    $grpRes->user_id ==  $token["data"]->uid ? throw new Exception("Logined user can't like the message") : "";
                } else {
                    throw new Exception("Message id doesn't exist.");
                }
                if ($this->GroupModelObj->check_group_members('group_message_likes',['group_message_id'=>$this->input->post('message_id'),'user_id'=>$this->input->post('user_id')])) {
                    throw new Exception("Already message was liked.");
                }
                
                $insert_params = [
                    'group_message_id' => $this->input->post('message_id'),
                    'user_id' => $this->input->post('user_id'),
                    "added_by"    => $token["data"]->uid,
                    "created_at" => date('Y-m-j H:i:s')
                ];
                if($res = $this->GroupModelObj->insert_data('group_message_likes',$insert_params)) {
                    $dataCount = $this->GroupModelObj->check_group_members('group_message',['id' => $this->input->post('message_id')]);
                    $count = $dataCount->likes_count+1;
                    $this->GroupModelObj->update('group_message',['likes_count'=> $count],['id' => $this->input->post('message_id')]);
                    $final_response['status']   = 1;
                    $final_response['message']  = 'Message Was Liked Successfully!';
                    $final_response['data']     = [];
                    $this->response($final_response, REST_Controller::HTTP_OK); 
                } else {
                    throw new Exception("There was a problem creating while like the message. Please try again.");
                }
            } 
        } catch (Exception $err) {
            $final_response['status']   = 0;
            $final_response['message']  = $err->getMessage();
            $final_response['data']     = [];
            $this->response($final_response, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    private function check_user_admin_and_token() {
        $token = $this->authorization_token->validateToken();
    
        if (!isset($token["data"]->is_admin) || !$token["status"]) {
            throw new Exception("Token is not provided.");
        }
    
        if ($token["data"]->is_admin == 1) {
            throw new Exception("Admin can't have permissions to do this action.");
        }
    
        $checkTokenData = $this->AuthenticationModelObj->get_user($token["data"]->uid, true);
    
        if (empty($checkTokenData->token)) {
            throw new Exception("User is logged out.");
        }
    }   

	
}


?>