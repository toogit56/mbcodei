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
	
	public function form01($p1 = null) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['page_title'] = 'form01';
		

		echo ENVIRONMENT;
		
		// エラーメッセージ用ラベル言語ファイルをロード
		$this->lang->load('mbtest_lang');

		// 検証ルールを設定
		$this->form_validation->set_rules('title', 'lang:mbtest_title', 'required|max_length[5]');
		$this->form_validation->set_rules('text', 'lang:mbtest_text', 'required|max_length[7]');
		
//		show_error("エラーエラー", 500);
		
		if(0) {
			// システムエラーのとき
		mbexception(
			'エラーログ 検証ルールのすぐ下',	// ログメッセージ
			'',								// 表示メッセージ
			array(							// ログにダンプする変数の配列
				'posts' => $this->input->post(),
				'gets' => $this->input->get(),
				$data
			)
		);
		}
		
		mblog_debug("デバッグログです");



	
		// 指定したキーでpostされたデータのviewセット用配列を取得
		$posts = $this->input->get_setup_view_data(
			array('title', 'text')
		);
			
		// postsデータをマージ
		$data = array_merge($data, $posts);
		// hidden設定用に配列もセット
		$data['posts'] = $posts;
		
		if(is_null($p1)) {
			$this->load->view('mbtest/form01', $data);
		}
		else if(strcmp("confirm", $p1) == 0) {
			// 確認画面
			
			if($this->form_validation->run() === false) {
				$this->load->view('mbtest/form01', $data);
			}
			else {
				// ビューにセット
				$this->load->view('mbtest/form01_confirm', $data);
			}
		}
		else if(strcmp("return", $p1) == 0) {
			// 戻る
			$this->load->view('mbtest/form01', $data);
		}
		else if(strcmp("commit", $p1) == 0) {
			if($this->form_validation->run() === false) {
				$this->load->view('mbtest/form01', $data);
			}
			else {
				// 確定
				$this->load->view('mbtest/form01_finish', $data);
			}
			
			var_dump($posts);
		}
		else {
			throw new Exception("error");
		}

/*
		if($this->form_validation->run() === false) {
			$this->load->view('mbtest/create', $data);
		}
		else {
			$this->mbtest_model->set_news();
			$this->load->view('mbtest/success');
		
*/
	}
}

