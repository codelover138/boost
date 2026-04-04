<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php if(isset($page['html_class'])){ echo 'class="'.$page['html_class'].'"'; } ?> xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TPWXZ4ZH');</script>
<!-- End Google Tag Manager -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $page['title']; ?></title>

<link rel="icon" type="image/svg+xml" href="<?php echo base_url('images/boost_icon.svg'); ?>">
<link rel="apple-touch-icon" href="<?php echo base_url('images/boost_icon.svg'); ?>">

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
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TPWXZ4ZH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
