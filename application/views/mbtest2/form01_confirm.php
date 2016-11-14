
<h2>
<?php echo $page_title; ?>
</h2>


 <?php echo validation_errors(); ?>

<?php echo form_open('mbtest2/form01_commit', null, $posts); ?>

<?php echo form_transaction_token(); ?>

 <?php echo form_label('タイトル', 'title'); ?>
 <?php echo form_prep($title); ?> <br />

 <?php echo form_label('テキスト', 'text'); ?>
 
 <?php //echo form_prep($text); ?> <br />
 <?php echo $text; ?> <br />

 
 <?php echo form_submit('button_confirm', '確認'); ?>
 <?php echo form_submit_action_replace('form01_commit', 'form01', 'button_return', '戻る'); ?>
 
</form>