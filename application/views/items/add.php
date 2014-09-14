    <div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url($this->uri->segment(1)); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
            Add
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Adding items
        </h2>
      </div>

      <div id="formalert"></div>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal ajax-form', 'id' => '');
      
      echo form_open('items/add', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Item name *</label>
            <div class="controls">
              <input type="text" id="" name="name" value="<?php echo set_value('item_name'); ?>" >
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
     