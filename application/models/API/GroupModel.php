<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class GroupModel extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function insert_data($table,$data) {
		$this->db->insert($table, $data);
		return $this->db->insert_id(); 
	}
	
	public function get_group_data_from_id($id) {
		$this->db->select('id');
		$this->db->from('group');
		$this->db->where('id', $id);
		return $this->db->get()->row();
	}

    public function fetch_search_group($keyword) {
        $this->db->select('id, name, status');
		$this->db->from('group');
		$this->db->where("name LIKE '%$keyword%' OR name = '$keyword'");
		$query = $this->db->get();
		return $query->result_array();
    }
	
	
	public function delete_group($id) {
		$this->db->where('id', $id);
		return $this->db->delete('group');
	}

    public function check_data_exist($table,$id) {
		$this->db->from($table);
		$this->db->where('id', $id);
		return $this->db->get()->row();
	}

	public function check_group_members($table,$data) {
		$this->db->from($table);
		$this->db->where($data);
		return $this->db->get()->row();
	}

    public function update($table, $array, $wherearray) {
        $this->db->where($wherearray);
        $query = $this->db->update($table, $array);
		return $query ? 1 : 0;
    }
	
	
	
}