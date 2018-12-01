<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct() {
	    parent::__construct();
	    $this->load->library('session');
	    if(isset($_SESSION['username'])){
			$this->sess($_SESSION['username']);
		}
	}
	public function index()
	{
		
		if(isset($_SESSION['username']) && $this->sess($_SESSION['username'])){
			$this->tasks();
		}
		else{
			$this->load->view('login_form');
		}
		
	}
	public function user_auth(){ 
		/*if(isset($_SESSION['username'])){
			$this->sess($_SESSION['username']);
		}*/

		if($_POST){
			$this->load->database();
			$email = $_POST['email'];
			$pass = $_POST['pass'];
			$db_email = $this->db->get_where('user',array('user_email'=>$email));
			if($db_email->num_rows()==1){
				print_r($db_email->row()->id);
				$this->load->library('encryption');
				$this->encryption->initialize(
				        array(
				                'cipher' => 'aes-256',
				                'mode' => 'ctr',
				                'key' => '<a 32-character random string>'
				        )
				);
				$ciphertext = $db_email->row()->user_password;
				$decrpt_pass = $this->encryption->decrypt($ciphertext);
				print_r('ponka');
				if ($decrpt_pass==$pass){
					$this->sess($db_email->row()->user_name, true);
					redirect('welcome/tasks', 'refresh');
				} 
			}
			
		}
		redirect('/', 'refresh');
		return false;
	}
	public function sess($username,$status=false){
		
		if(isset($_SESSION['username']) && $_SESSION['username']==$username && $_SESSION['logged_in']==true){
			//$this->load->helper('url');
			
			return true;
		}
		elseif($status){
			$newdata = array(
		        'username'  => $username,
		        'logged_in' => TRUE
			);
			$this->session->set_userdata($newdata);
			return true;
		}
		return false;

	}
	public function tasks(){
		$this->load->database();
		$query = $this->db->get_where('todo', array("user_id"=>"1"));
		$result['result'] = $query->result_object();
		$this->load->view('home', $result);
       // print_r($query->result_array());
	}
	public function add_task(){
		$this->load->database();
		//$task = json_decode($_POST['task']);
		$task = $_POST['task'];
		$value = array('task'=>$task, "user_id"=>"1"); 
		$i =	$this->db->insert('todo',$value);
		if($i==true){
			$id = $this->db->insert_id();
			echo json_encode( array("id"=>$id) );
		}
		else{
			echo 'error';
		}

	}
	public function delete_task(){
		$this->load->database();
		$task_id = $_POST['task_id'];
		$arr = array('id'=>$task_id); 
		$i =	$this->db->delete('todo',$arr);
		if($i==true){
			echo json_encode('deleted');
		}
		else{
			echo 'error';
		}
		
	}

	public function update_task(){
		$this->load->database();
		$task_id = $_POST['task_id'];
		$task_name = $_POST['task_name']; 
		$i =	$this->db->update('todo',array('task' => $task_name ), array('id' => $task_id));
		if($i==true){
			echo json_encode('updated');
		}
		else{
			echo 'error';
		}
		
	}

	public function signout()
	{
		$this->session->sess_destroy();
		redirect($this->index(),'refresh');
	}
}
