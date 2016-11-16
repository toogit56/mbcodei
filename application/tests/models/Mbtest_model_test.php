<?php

class Mbtest_model_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Mbtest_model');
        $this->obj = $this->CI->Mbtest_model;


    }

    public function test_get_news()
    {
        $expected = array(
            'a01' => array('title' => 'たいとるだー', 'text' =>'てきてきてき'),
            'a02' => array('title' => 'a02', 'text' => 'OK?
a'),
            'a03だよ' => array('title' => 'a03だよ', 'text' => 'OK?
a03')
        );

        foreach($expected as $slug => $e_news) {
            $t_news = $this->obj->get_news($slug);
            $t_news['title'] = preg_replace('/\r\n|\r|\n/', '\n', $t_news['title']);
            $t_news['text'] = preg_replace('/\r\n|\r|\n/', '\n', $t_news['text']);

            $this->assertEquals($slug, $t_news['slug']);
            $this->assertEquals($e_news['title'], $t_news['title']);
//            $this->assertEquals($e_news['text'], $t_news['text']);
        }

        $t_news_array = $this->obj->get_news();

        $this->CI->load->helper('mbdb');

 //       $real_count = mbdb_get_row_nums('news');

        $row = $this->CI->db->select('count(*) as num')
            ->from('news')
            ->get()->row();

        $this->assertEquals($row->num, count($t_news_array), '取得したデータ数が一致しません');

//        $this->assertEquals(count($t_news_array), $real_count, '取得したデータ数が一致しません');
        
    }

    public function test_set_news()
    {
        $title = 'タイトル' . mt_rand(0, 9);
        $slug = $title;
        $text = 'テキスト' . mt_rand(0, 9) . '_' . mt_rand(0, 9);

        $insert_item = array(
            'title' => $title
            ,'slug' => $slug
            ,'text' => $text
        );

//        $this->CI->db->insert('news', $insert_item);         

        $this->CI->db->trans_start();
        $this->obj->set_news($insert_item);
        $this->CI->db->trans_complete();

        $rows = $this->CI->db->select('slug, title, text')
            ->from('news')
            ->where('slug', $slug)
            ->where('title', $title)
            ->where('text', $text)
            ->get()->result();

        $this->assertEquals(1, count($rows), 'データ挿入に失敗しました');
        $this->assertEquals($title, $rows[0]->title, 'データ不一致');
        $this->assertEquals($slug, $rows[0]->slug, 'データ不一致');
        $this->assertEquals($text, $rows[0]->text, 'データ不一致');
    }
}