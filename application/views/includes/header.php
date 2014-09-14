<?php
 	if($this->session->userdata('admin'))
        $admin = true;
    else
    	$admin = false;
?>
<!DOCTYPE html> 
<html lang="en-US">
<head>
  <title>Coffeeshop Administration</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
	    <div class="container">
	      <a class="brand"><i class="fa fa-coffee"></i> Coffeeshop</a>
	      <ul class="nav">
	      	<li <?php if($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == ''){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>
	        </li>
	        <?php if($admin) { ?>
	        <li class="dropdown <?php if($this->uri->segment(1) == 'staffs' || $this->uri->segment(1) == 'customer_groups'){echo 'active';}?>">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-group"></i> Staffs <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	          	<li>
	              <a href="<?php echo base_url(); ?>staffs"><i class="fa fa-list"></i> View Staffs</a>
	            </li>
	            <li>
	              <a href="<?php echo base_url(); ?>staffs/add"><i class="fa fa-user"></i> &nbsp;Add New Staff</a>
	            </li>
	          </ul>
	        </li>
	        <?php } ?>
	        <li class="dropdown <?php if($this->uri->segment(1) == 'orders'){echo 'active';}?>">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-coffee"></i> Orders <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	          	<li>
	              <a href="<?php echo base_url(); ?>orders/"><i class="fa fa-list"></i> Orders</a>
	            </li>
	            <li>
	              <a href="<?php echo base_url(); ?>orders/add"><i class="fa fa-money"></i> Place an order</a>
	            </li>
	          </ul>
	        </li>
	        <li class="dropdown <?php if($this->uri->segment(1) == 'items' || $this->uri->segment(1) == 'flavours' || $this->uri->segment(1) == 'addons'){echo 'active';}?>">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fire"></i> Manage Kitchen <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	          	<li>
	              <a href="<?php echo base_url(); ?>items/"><i class="fa fa-list"></i> View items</a>
	            </li>
	            <li>
	              <a href="<?php echo base_url(); ?>items/add"><i class="fa fa-cutlery"></i> &nbsp;Add item</a>
	            </li>
	            <li class="divider">
	            </li>
	          	<li>
	              <a href="<?php echo base_url(); ?>flavours/"><i class="fa fa-list"></i> View flavours</a>
	            </li>
	            <li>
	              <a href="<?php echo base_url(); ?>flavours/add"><i class="fa fa-spoon"></i>&nbsp;&nbsp; Add flavour</a>
	            </li>
	            <li class="divider">
	            </li>
	          	<li>
	              <a href="<?php echo base_url(); ?>addons/"><i class="fa fa-list"></i> View add-ons</a>
	            </li>
	            <li>
	              <a href="<?php echo base_url(); ?>addons/add"><i class="fa fa-spoon"></i>&nbsp;&nbsp; Add add-on</a>
	            </li>
	          </ul>
	        </li>
	        <li class="dropdown <?php if($this->uri->segment(1) == 'settings' || $this->uri->segment(1) == 'change_password'){echo 'active';}?>">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i> System <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	          	<li>
	              <a href="<?php echo base_url(); ?>settings"><i class="fa fa-wrench"></i> Settings</a>
	            </li>
	            <li>
	              <a href="<?php echo base_url(); ?>change_password"><i class="fa fa-unlock-alt"></i> Change Password</a>
	            </li>
	          </ul>
	        </li>
	        <li>
	        	<a href="<?php echo base_url(); ?>logout"><i class="fa fa-sign-out"></i> Logout</a>
	        </li>
	      </ul>
	    </div>
	  </div>
	</div>	
