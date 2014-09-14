    <div class="container top">

      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url($this->uri->segment(1)); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          View
        </li>
      </ul>

      <div class="page-header users-header">
        <h2>
          All items
          <a href="<?php echo site_url("").'items/add';?>" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add a new item</a>       
        </h2>
      </div>
      
      <div class="row">
        <div class="span12 columns">
          <div class="well">
           
            <?php
            $status = array(0 => 'Unavailable' , 1 => 'Available');
           
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');

            $options_items = array(0 => "all");
            foreach ($items as $row)
            {
              $options_item[$row['id']] = $row['item_name'];
            }
           
            $hide = array();        
            $options_items = array();    
            foreach ($items as $array) {
              foreach ($array as $key => $value) {
                if(in_array($key, $hide))
                  continue;
                $options_items[$key] = ucfirst($key);
              }
              break;
            }

            echo form_open('items', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected);



              echo form_label('Order by:', 'order');
              echo form_dropdown('order', $options_items, $order, 'class="span2"');

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

              $options_order_type = array('Asc' => 'Ascending', 'Desc' => 'Descending');
              echo form_dropdown('order_type', $options_order_type, $order_type_selected, 'class="span2"');

              echo form_submit($data_submit);

            echo form_close();
            ?>

          </div>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header table-checkbox"><input type="checkbox" id="select_all" value="select_all"/></th>
                <th class="header">#</th>
                <th class="yellow header headerSortDown">Name</th>
                <th class="yellow header headerSortDown">Status</th>
                <th class="yellow header headerSortDown">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($items as $row)
              {
                echo '<tr>';
                echo '<td><input type="checkbox" class="select_one" value="'.$row['id'].'"/></td>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['item_name'].'</td>';
                echo '<td>'.$status[$row['status']].'</td>';
                echo '<td class="crud-actions">
                  <a href="'.site_url('items/update/'.$row['id']).'" class="btn btn-info"><i class="fa fa-edit"></i> edit</a>  
                  <a href="'.site_url('items/delete').'" data-id="'.$row['id'].'" class="btn btn-danger delete"><i class="fa fa-trash-o"></i> delete</a>
                </td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>
          <a href="<?php echo site_url(); ?>items/delete" id="delete-selected" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete selected</a>

          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>