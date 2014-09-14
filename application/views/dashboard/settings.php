    <div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url(); ?>">
            Dashboard
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          Settings
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Settings
        </h2>
      </div>

      <div id="formalert"></div>
      
      <?php

      $attributes = array('class' => 'form-horizontal ajax-form', 'id' => '');
      
      echo form_open('dashboard/settings', $attributes);
      ?>
        <fieldset>

          <?php 
          foreach ($options as $option) {

            echo   '<div class="control-group">
                      <label for="inputError" class="control-label">'.ucfirst(str_replace('_', ' ',$option['name'])).'</label>
                      <div class="controls">
                        <input type="text" id="" name="'.$option['name'].'" value="'.$option['value'].'" >
                      </div>
                    </div>';
          }

          ?>

          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     