<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class AuthenticationModel extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	
	/**
	 * create_user function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function create_user($userData) {
		
		$data = array(
			'username'     => $userData["username"],
			'email'        => $userData["email"],
			'password'     => $this->hash_password($userData["password"]),
			'dob'          => $userData["dob"],
			'is_admin'     => $userData["is_admin"],
			'is_confirmed' => 1,
			'phone_number' => $userData["phone"],
			'created_at'   => date('Y-m-j H:i:s'),
		);
		
		$this->db->insert('users', $data);
		return $this->db->insert_id(); 
		
	}
	
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function resolve_user_login($email, $password) {
		
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('email', $email);
		$hash = $this->db->get()->row('password');
		return $hash ? $this->verify_password_hash($password, $hash) : false;
		
	}
	
	/**
	 * get_user_id_from_username function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @return int the user id
	 */
	public function get_user_id_from_email($email) {
		
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email', $email);
		return $this->db->get()->row('id');
		
	}
	
	/**
	 * get_user function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function get_user($user_id,$status) {
		$this->db->from('users');
		$this->db->where('id', $user_id);
		$status  ? $this->db->where('token!=', '') : " ";
		return $this->db->get()->row();
	}

    public function check_token_user($user_id) {
		$this->db->from('users');
		$this->db->where('id', $user_id);
        $this->db->where('token!=', '');
		return $this->db->get()->row();
	}

    public function update($table, $array, $wherearray) {
        $this->db->where($wherearray);
        $query = $this->db->update($table, $array);
		return $query ? 1 : 0;
    }
	
	/**
	 * hash_password function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}

	public function update_check_user_data($email,$id) {
		$this->db->from('users');
		$this->db->where('id!=', $id);
        $this->db->where('email',$email);
		return $this->db->get()->row();
	}
	
}