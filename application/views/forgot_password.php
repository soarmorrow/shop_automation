<!DOCTYPE html> 
<html lang="en-US">
  <head>
    <title>Backend Administration</title>
    <meta charset="utf-8">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/css/admin.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container login">
      <?php 
      $attributes = array('class' => 'form-signin');
      echo form_open('forgot_password', $attributes);
      echo '<h2 class="form-signin-heading">Forgot password</h2>';

      if(isset($flash_message))
      {
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
          echo '<a class="close" data-dismiss="alert">×</a>';
          echo '<strong>Well done!</strong> New password is send to your email, Check inbox or spam.';
          echo '</div>';  
        }
        else
        {
          echo '<div class="alert alert-error">';
          echo '<a class="close" data-dismiss="alert">×</a>';
          echo '<strong>Oh snap!</strong> Your email or username is incorrect.';
          echo '</div>';
        }

      }

      echo validation_errors();

      echo form_input('user_name', '', 'placeholder="Username or Email" style="width:200px"');
      echo "<br />";
      echo anchor('login', 'Back to login');
      echo "<br />";
      echo "<br />";

      echo form_submit('submit', 'Submit', 'class="btn btn-large btn-primary"');
      echo form_close();
      ?>      
    </div><!--container-->
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
  </body>
</html>    
    
