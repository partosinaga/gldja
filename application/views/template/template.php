<?php
	$this->load->view('template/header');
?>
<div class="content-wrapper">
	<?php
    	$this->load->view('alert/alert');
  	?>
	<?php $this->load->view($page); ?>

</div>

<?php
	$this->load->view('template/footer');
?>