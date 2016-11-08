<?php

class Mbtest extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('mbtest_model');
		$this->load->helper('url_helper');
	}
	
	public function index() {
		$data['news'] = $this->mbtest_model->get_news();

		$data['title'] = "News archive";

//		$this->load->view('templates/header', $data);
		$this->load->view('mbtest/index', $data);
//		$this->load->view('templates/footer', $data);
	}

	public function view($slug = null) {
		$data['news_item'] = $this->mbtest_model->get_news($slug);

		if(empty($data['news_item'])) {
			show_404();
		}

		$data['title'] = $data['news_item']['title'];

//		$this->load->view('templates/header', $data);
		$this->load->view('mbtest/view', $data);
//		$this->load->view('templates/footer', $data);
	}

	public function create() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Create a news item';

		$this->form_validation->set_rules('title', 'タイトル', 'required');
		$this->form_validation->set_rules('text', 'Text', 'required');

		if($this->form_validation->run() === false) {
//			$this->load->view('templates/header', $data);
			$this->load->view('mbtest/create', $data);
//			$this->load->view('templates/footer');
		}
		else {
			$this->mbtest_model->set_news();
			$this->load->view('mbtest/success');
		}
	}
}


