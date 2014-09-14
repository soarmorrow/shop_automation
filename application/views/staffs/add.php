    <div class="container top">

      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url($this->uri->segment(1)); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
         Add Staff
       </li>
     </ul>

     <div class="page-header">
      <h2>
        Adding Users
      </h2>
    </div>

    <div id="formalert"></div>

    <?php
      //form data
    $attributes = array('class' => 'form-horizontal ajax-form', 'id' => '');
    $user_level = array(1 => 'Admin' , 2 => 'Coffee Maker');
    echo form_open('staffs/add', $attributes);
    ?>
    <fieldset>
      <div class="control-group">
        <label for="inputError" class="control-label">First Name *</label>
        <div class="controls">
          <input required type="text" id="" name="first_name" value="<?php echo set_value('first_name'); ?>" >
          <!--<span class="help-inline">Woohoo!</span>-->
        </div>
      </div>
      <div class="control-group">
        <label for="inputError" class="control-label">Last Name *</label>
        <div class="controls">
          <input type="text" required id="" name="last_name" value="<?php echo set_value('last_name'); ?>" >
          <!--<span class="help-inline">Woohoo!</span>-->
        </div>
      </div>
      <div class="control-group">
        <label for="inputError" class="control-label">Email Address *</label>
        <div class="controls">
          <input type="email" required id="" name="email_address" value="<?php echo set_value('email_addres'); ?>" >
          <!--<span class="help-inline">Woohoo!</span>-->
        </div>
      </div>
      <div class="control-group">
        <label for="inputError" class="control-label">User name *</label>
        <div class="controls">
          <input type="text" id="" required name="user_name" value="<?php echo set_value('user_name'); ?>" >
          <!--<span class="help-inline">Woohoo!</span>-->
        </div>
      </div>
      <div class="control-group">
        <label for="status" class="control-label">User type *</label>
        <div class="controls">   
          <?php echo form_dropdown('user_level', $user_level, 1, 'class="span2"'); ?>
          <!--<span class="help-inline">Woohoo!</span>-->
        </div>
      </div>
      <div class="control-group">
        <label for="inputError" class="control-label">Password *</label>
        <div class="controls">
          <input type="password" required id="" name="pass_word"  >
          <!--<span class="help-inline">Woohoo!</span>-->
        </div>
      </div>
      <div class="control-group">
        <label for="inputError" class="control-label">Password Confirm*</label>
        <div class="controls">
          <input type="password"  required id="" name="pass_word_confirm"  />
          <!--<span class="help-inline">Woohoo!</span>-->
        </div>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save</button>
        <button class="btn" type="reset"><i class="fa fa-refresh"></i> Reset</button>
      </div>
    </fieldset>

    <?php echo form_close(); ?>

  </div>
