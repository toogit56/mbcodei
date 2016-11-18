<?php

class Mbtest2_test extends TestCase
{
    public function test_form01() 
    {
        $output = $this->request('GET', "mbtest2/form01");

//echo $output;

        $this->assertContains('<h2>
form01</h2>', $output);
    }

    public function test_form01_config_two()
    {
        /*
        MonkeyPatch::patchMethod(
            'Mbtest2',
            array('config_one_two' => 2)
        );
        */

        $output = $this->request('GET', "mbtest2/form01");
        $this->assertContains('config_one_two -> 2', $output);
    }

    /*
     * 入力エラーなし
     * 必須エラー
     * 文字長チェック
     * 文字種類チェック
     */
    public function test_form01_confirm_ok01()
    {
        $output = $this->request('POST', 'mbtest2/form01_confirm', ['title' => 'あいうえお', 'text' => 'かきくけこ']);

        $this->assertContains('screen_form01_confirm', $output);
    }
    

    public function test_form01_confirm_e01()
    {
        $output = $this->request('POST', 'mbtest2/form01_confirm', ['title' => '', 'text' => '']);

        $this->assertContains('screen_form01', $output);
        $this->assertContains('タイトル欄は必須フィールドです', $output);
        $this->assertContains('テキスト欄は必須フィールドです', $output);
    }


    public function test_form01_confirm_e02()
    {
        $output = $this->request('POST', 'mbtest2/form01_confirm', ['title' => 'あいうえおぐ',
            'text' => '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６'
        ]);

        $this->assertContains('screen_form01', $output);
        $this->assertContains('タイトル欄は5文字より短くなければなりません', $output);
        $this->assertContains('テキスト欄は75文字より短くなければなりません', $output);
    }


    public function test_form01_confirm_ok02()
    {
        $output = $this->request('POST', 'mbtest2/form01_confirm', ['title' => 'あいうえお',
            'text' => '１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０１２３４５'
        ]);

        $this->assertContains('screen_form01_confirm', $output);
    }
    
}
