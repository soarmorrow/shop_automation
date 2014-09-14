    <div class="container top">

      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url($this->uri->segment(1)); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          Update
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Updating items
        </h2>
      </div>

      <div id="formalert"></div>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal ajax-form', 'id' => '');

      $status = array(0 => 'Unavailable' , 1 => 'Available');

      echo form_open('items/update/'.$this->uri->segment(3).'', $attributes);
      ?>
      <fieldset>
        <div class="control-group">
          <label for="inputError" class="control-label">Item name</label>
          <div class="controls">
            <input type="text" id="" name="name" value="<?php echo $item[0]['item_name']; ?>" >
          </div>
        </div>
        <?php
        if($this->session->userdata('admin')){
          echo '<div class="control-group">';
          echo '<label for="status" class="control-label">Status</label>';
          echo '<div class="controls">';   
          echo form_dropdown('status', $status, $item[0]['status'], 'class="span2"');
          echo '</div>';
          echo '</div">';
        }
        ?>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save changes</button>
          <button class="btn" type="reset"><i class="fa fa-refresh"></i> Reset</button>
        </div>
      </fieldset>

      <?php echo form_close(); ?>

    </div>
