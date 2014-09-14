    <div class="container top">

      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url($this->uri->segment(1)); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          All Staffs
        </li>
      </ul>

      <div class="page-header users-header">
        <h2>
          All Staffs
          <a href="<?php echo site_url($this->uri->segment(1)); ?>/add" class="btn btn-success"><i class="fa fa-plus"></i> Add a new user</a>        
        </h2>
      </div>
      
      <div class="row">
        <div class="span12 columns">
          <div class="well">
           
            <?php

            $user_level = array(1 => 'Admin' , 2 => 'Staff');
            $status = array(0 => 'Suspended' , 1 => 'Active');
           
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');
           
            $hide = array('id','pass_word' );        
            $options_users = array();    
            foreach ($users as $array) {
              foreach ($array as $key => $value) {
                if(in_array($key, $hide))
                  continue;
                $options_users[$key] = ucfirst(str_replace('_', ' ', $key));
              }
              break;
            }

            echo form_open('staffs', $attributes);
     
              echo form_label('search:', 'search_string');
              echo form_input('search_string', $search_string_selected,'class="search-query input-small" placeholder="search users"');

              echo form_label('search field:', 'search_field');
              echo form_dropdown('search_field', $options_users, $search_field, 'class="span2 from-control"');

              echo form_label('order by:', 'order');
              echo form_dropdown('order', $options_users, $order, 'class="span2 from-control"');

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go', 'style'=>'border-radius: 0px');

              $options_order_type = array('Asc' => 'Ascending', 'Desc' => 'Descending');
              echo form_dropdown('order_type', $options_order_type, $order_type_selected, 'class="span2 form-control"');

              echo form_submit($data_submit);

            echo form_close();
            ?>

          </div>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header table-checkbox"><input type="checkbox" id="select_all" value="select_all"/></th>
                <th class="yellow header headerSortDown">Username</th>
                <th class="yellow header headerSortDown">First Name</th>
                <th class="yellow header headerSortDown">Last Name</th>
                <th class="yellow header headerSortDown">Email</th>
                <th class="yellow header headerSortDown">User level</th>
                <th class="yellow header headerSortDown">Status</th>
                <th class="yellow header headerSortDown">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($users as $row)
              {
                echo '<tr>';
                echo '<td><input type="checkbox" class="select_one" value="'.$row['id'].'"/></td>';
                echo '<td>'.$row['user_name'].'</td>';
                echo '<td>'.$row['first_name'].'</td>';
                echo '<td>'.$row['last_name'].'</td>';
                echo '<td>'.$row['email_address'].'</td>';
                echo '<td>'.$user_level[$row['user_level']].'</td>';
                echo '<td>'.$status[$row['status']].'</td>';
                echo '<td class="crud-actions" style="width:250px;">';
                  echo '<a href="'.site_url().'staffs/change/'.$row['id'].'"';
                  if($row['status'] == 1)
                    echo 'class="btn"><i class="fa fa-ban"></i> Suspend</a> ';  
                  else 
                    echo 'class="btn  btn-success"><i class="fa fa-key"></i> Activate</a> '; 
                echo '<a href="'.site_url().'staffs/update/'.$row['id'].'" class="btn btn-info"><i class="fa fa-edit"></i> edit</a>  
                  <a href="'.site_url().'staffs/delete" data-id="'.$row['id'].'" class="btn btn-danger delete"><i class="fa fa-trash-o"></i> trash</a>
                </td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>
          <a href="<?php echo site_url(); ?>users/delete" id="delete-selected" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete selected</a>

          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>