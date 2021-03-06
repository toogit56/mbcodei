<?php

class Mbtest2 extends Mb_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('mbtest_model');
		$this->load->helper('url_helper');

		$this->load->library('session');
	}
	
	// フォームの設定
	// 言語ファイルの初期化、postデータの調整、hidden値用データの準備も行う
	private function setup_form(&$data) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		// エラーメッセージ用ラベル言語ファイルをロード
		$this->lang->load('mbtest_lang');

		// バリデートの設定
		$this->validation_set_rules();

		// 指定したキーでpostされたデータのviewセット用配列を取得
		$posts = $this->input->get_setup_view_data(
			array('title', 'text')
			,"post"
			,null
			,array('text')	// xss_cleanを行わないキーを指定できる
		);
			
		// postsデータをマージ
		$data = array_merge($data, $posts);
		// hidden設定用に配列もセット
		$data['posts'] = $posts;
	}

	// バリデートの設定
	private function validation_set_rules() {
		// 検証ルールを設定
		$this->form_validation->set_rules('title', 'lang:mbtest_title', 'required|max_length[5]|double');
		$this->form_validation->set_rules('text', 'lang:mbtest_text', 'required|max_length[75]');


		// 入力チェックを独自処理で実装。クロージャの例。
		$this->form_validation->set_rules('title', 'lang:mbtest_title',
			array(
				'required',
				'max_length[5]',
				'double',
				array(
					'duplicate_slug',
					function($value) {
						if($value === "") { return true; }

						$this->form_validation->set_message(
							'duplicate_slug', 
							$this->lang->line('mbtest_duplicate_slug')
						);

						// slugの重複チェック
						$obj = $this->mbtest_model->get_news($value);
						if( ! is_null($obj)) {
							return false;
						}

						return true;
					}
				)
			)
		);
	}

	private function config_one_two() {
		$this->config->load('mbtest');

		return $this->config->item('mbtest_one_two');
	}

	public function form01() {
		$data['page_title'] = 'form01';

		// フォームの設定
		$this->setup_form($data);


		// 設定変更のチェック例のための分岐
		if($this->config_one_two() == 2) {
			mbexception('debug config_one_two is 2', 'config_one_two -> 2');
		}

		
		$this->load->view('mbtest2/form01', $data);
	}

	public function form01_confirm() {
		$data['page_title'] = 'form01';

		// フォームの設定
		$this->setup_form($data);

		if($this->form_validation->run() === false) {
			$this->load->view('mbtest2/form01', $data);
		}
		else {
			// ビューにセット
			$this->load->view('mbtest2/form01_confirm', $data);
		}
	}


	public function form01_commit($p1 = null) {
		$data['page_title'] = 'form01';

		// フォームの設定
		$this->setup_form($data);
		
		if($this->form_validation->run() === false) {

			// データ改変検知 システムエラー
			mbexception(
				'commit時データ改変を検知',			// ログメッセージ
				'System error',								// 表示メッセージ
				array(							// ログにダンプする変数の配列
					'posts' => $this->input->post(),
					'gets' => $this->input->get(),
					'data' => $data
				)
			);
		}
		else {
			// トークンチェック
			if( ! $this->check_token()) {
				mbexception('token error', $this->lang->line('mbtest_invalid_token'));
			}

			$this->db->trans_start();
			if( ! $this->mbtest_model->set_news($data['posts'])) {
				$this->db->trans_rollback();

				mbexception(
					'Newsデータ挿入エラー'
				);
			}

			$this->db->trans_commit();

			// 確定
			$this->load->view('mbtest2/form01_finish', $data);
		}
		
		/*
		var_dump($data['posts']);

		$this->db->trans_start();
        if( ! $this->mbtest_model->set_news($data['posts'])) {
        	$this->db->trans_rollback();

			mbexception(
				'Newsデータ挿入エラー'
			);
		}

        $this->db->trans_commit();
		*/
	}
}

