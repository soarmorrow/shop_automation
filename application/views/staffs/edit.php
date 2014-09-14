    <div class="container top">

      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url($this->uri->segment(1)); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          Update Staff
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Updating Staff
        </h2>
      </div>

      <div id="formalert"></div>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal ajax-form', 'id' => '');

      $user_level = array(1 => 'Admin' , 2 => 'Coffee Maker');

      echo form_open('staffs/update/'.$this->uri->segment(3).'', $attributes);
      ?>
      <fieldset>
        <div class="control-group">
            <label for="inputError" class="control-label">First Name *</label>
            <div class="controls">
              <input type="text" id="" name="first_name" value="<?php echo $user['first_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Last Name *</label>
            <div class="controls">
              <input type="text" id="" name="last_name" value="<?php echo $user['last_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Email Address *</label>
            <div class="controls">
              <input type="text" id="" name="email_address" value="<?php echo $user['email_address']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">User name *</label>
            <div class="controls">
              <input type="text" id="" name="user_name" value="<?php echo $user['user_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
        <div class="control-group">
          <label for="status" class="control-label">User type *</label>
          <div class="controls">   
            <?php echo form_dropdown('user_level', $user_level, $user['user_level'], 'class="span2 form-control"'); ?>
          <!--<span class="help-inline">Woohoo!</span>-->
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Save changes</button>
          <button class="btn" type="reset"><i class="fa fa-refresh"></i> Reset</button>
        </div>
      </fieldset>

      <?php echo form_close(); ?>

    </div>
