<h2>
<?php echo $page_title; ?>
</h2>

 <?php echo 'screen_form01'; ?>
<?php echo validation_errors(); ?>

<?php echo form_open('mbtest2/form01_confirm'); ?>

 <?php echo form_label('タイトル', 'title'); ?>
 <?php echo form_input('title', $title); ?> <br />

 <?php echo form_label('テキスト', 'text'); ?>
 
 <?php echo form_textarea('text', $text); ?> <br />

 
 <?php echo form_submit('button_confirm', '確認'); ?>

</form>
