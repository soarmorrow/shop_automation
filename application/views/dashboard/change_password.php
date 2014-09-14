    <div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url(); ?>">
            Dashboard
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          Change Password
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Change Password
        </h2>
      </div>

      <?php
      //flash messages
      if(isset($flash_message))
      {
        
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> Password changed successfully.';
          echo '</div>';       
        }
        else
        {
          if(isset($err_message))
          {
            echo $err_message;
          }
          else{
            echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
            echo '</div>';  
          }        
        }
      }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');

      //form validation
      echo validation_errors();
      
      echo form_open('change_password', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Current Password</label>
            <div class="controls">
              <input type="password" id="" name="current" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">New Password</label>
            <div class="controls">
              <input type="password" id="" name="password" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Confirm Password</label>
            <div class="controls">
              <input type="password" id="" name="password2"  >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     