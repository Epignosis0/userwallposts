<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
	
			$this->load->helper('form');
			$this->load->library('session');
			$this->load->model('post_model');
			$this->load->library('email');
			$this->load->helper('url');
		
    }
	
	public function index()
	{
		if ($this->session->userdata('logged_in') == ''){
			redirect('user');
		}

		$results = $this->post_model->list_post();
		$data =  array();	
		foreach ($results as  $result) {
		 	
		 	$postid =  $result->Id; 
		 	$resultdata['posts'] = $result;
			$resultdata['comments'] = $this->post_model->list_comment($postid);
			//$resultdata['counts'] = $this->post_model->comment_counts();
			$data['results'][] = $resultdata;

			//echo "<pre>";print_r($result); die;
		}
			//echo "<pre>": print_r($data); die;
	 		$this->load->view('userswall', $data);
	}

	public function add()
	{
		
		$this->load->view('add-post');
	}

	public function insert_post()
	{
		$sessionArray = $this->session->all_userdata();
		$userid = $sessionArray['logged_in']['userid'];
		$status = 'publish';
			$data = array(
					'UserId' => $userid,
					// 'Title' => $this->input->post('title'),
					'Description' => $this->input->post('description'),
					'Status' => $status
				);
		
		$result = $this->post_model->insert_post($data);
			if ($result == TRUE) {
				 $last_insertid = $this->db->insert_id();
				 $message = 'post added successfully!';
				 $this->session->set_flashdata('message_data', $message);
				 redirect('post');
			}else {
				$data['message'] = 'post could note be insert!';
				$this->load->view('userswall' , $data);
			}		
	}

	public function insert_comment()
	{
		$sessionArray = $this->session->all_userdata();
		$userid = $sessionArray['logged_in']['userid'];
		
		$status = 'publish';
			$data = array(
					'UserId' => $userid,
					'PostId' => $this->input->post('postid'),
					'Comment' => $this->input->post('comment'),
					'Status' => $status
				);
		
		$result = $this->post_model->insert_comment($data);
			if ($result == TRUE) {
				 $last_insertid = $this->db->insert_id();
				 $message = 'you have added a comment successfully!';
				 $this->session->set_flashdata('message_data', $message);
				 redirect('post');
			}else {
				$data['message'] = 'comment could note be insert!';
				$this->load->view('userswall' , $data);
			}		
	}

	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */