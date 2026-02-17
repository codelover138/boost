<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php if(isset($page['html_class'])){ echo 'class="'.$page['html_class'].'"'; } ?> xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $page['title']; ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/bootstrap.css');?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/theme.css');?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/bootstrap-datepicker3.css');?>"/>

<script type="text/javascript">
	var api_base_url = '<?php echo base_api_url(); ?>';
	var base_url = '<?php echo base_url();?>';
	var domain = '<?php echo $_SERVER['HTTP_HOST'];?>';
	var domain_name_string = '<?php echo $this->config->item('domain_name_string');?>';
	var main_domain = '<?php echo $this->config->item('main_domain');?>';
	document.domain = '<?php echo $this->config->item('main_domain');?>';
</script>
<script type="text/javascript" src="<?php echo base_url('js/scripts.semimini.js');?>"></script>
<script type="text/javascript">	
	
	$(document).ready(function(){

	<?php
		if(isset($_GET['openModal'])){
		
			foreach($_GET['openModal'] as $modalElement => $elsementData){
				if(is_numeric($elsementData)){
					$jsObjArray[] = $modalElement.' : '.$elsementData;	
				}else{
					$jsObjArray[] = $modalElement.' : "'.$elsementData.'"';	
				}
			}	
	
			echo "var tempModalData = {".implode(",",$jsObjArray)."} \r\n";
			
			echo 'openModal(document, tempModalData);';
	
			//print_r($_GET['openModal']);
		}
	?>
	
	});
</script>

</head>
<body <?php if(isset($page['body_class'])){ echo 'class="'.$page['body_class'].'"'; } ?>>
