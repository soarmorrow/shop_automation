<!DOCTYPE html> 
<html lang="en-US">
<head>
  <title>Coffeshop Login</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/css/admin.css" rel="stylesheet" type="text/css">
  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
  <div class="container login">
    <?php 
    $attributes = array('class' => 'form-signin');
    echo form_open('validate_credentials', $attributes);
    echo '<h2 class="form-signin-heading">Login</h2>';
    echo form_input('user_name', '', 'placeholder="Username"');
    echo form_password('password', '', 'placeholder="Password"');
    if(isset($message_error) && $message_error){
      echo '<div class="alert alert-error">';
      echo '<a class="close" data-dismiss="alert">×</a>';
      echo '<strong>Oh snap!</strong> Change a few things up and try submitting again.';
      echo '</div>';             
    }
    echo "<br />";
    $data = array(
      'name' => 'submit',
      'class' => 'btn btn-primary',
      'type' => 'submit',
      'content' => '<i class="fa fa-sign-in"></i> Login'
      );
    printf("<div class='btn-group'>%s %s</div>",form_button($data),anchor('forgot_password', '<i class="fa fa-key"></i> Forgot password?', 'class="btn btn-danger"'));

    echo '<br /><br /><a href="'.site_url().'guests" class="btn btn-primary">Place order as a guest</a>';
    echo form_close();
    ?>     
  </div><!--container-->
  <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</body>
</html>    
