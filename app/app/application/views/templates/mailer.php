<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $subject; ?> | Boost Accounting</title>

    <style type="text/css">
        <!--
		body{
			margin:0;
		}
		
        table th
        {
            padding:15px;
        }
		
		table
		{
			font-family:Arial, Helvetica, sans-serif;
			font-size:14px;
			line-height:1.5em;
			color:#6d90a9;
		}
        -->
    </style>
</head>

<body style="background-color:#f6f7fa;">
<table style="background-color:#f6f7fa;" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td height="30"></td>
    </tr>
    <tr>
        <td align="center">
            <table style="border:1px solid #dddddd; background-color:#fff;" width="723" border="0"  cellspacing="15" cellpadding="0">
                <tr>
                    <td height="10"></td>
                </tr>
                <?php
					if(isset($theme_data->image_string) && $theme_data->image_string != ''){
				?>
                <tr>
                    <td style="font-size: 23px;">
                       <?php echo  '<img style="max-height:150px; max-width:300px; " src="'.$theme_data->image_string.'" alt="'.$organisation->company_name.'" title="'.$organisation->company_name.'" />'; ?>                           
                    </td>
                </tr>                
                <tr>
                	<td height="1" style="background-color:#dddddd;"></td>
                </tr>
                <?php } ?>
                <?php
					if(isset($heading)){
				?>
                 <tr>
                	<td style="font-size: 23px;"><?php echo $heading; ?></td>
                </tr>
                 <?php } ?>
                <tr>
                    <td><?php
                        $message = str_replace(array("\r\n", "\n\r"), '<br>', $message);
                        $message = str_replace(array("\r", "\n"), '<br>', $message);
                        echo $message;

                        ?>
                    </td>
                </tr>

                <?php if(isset($link) && $link != '') : ?>
                <tr>
                    <td><a href="<?php echo $link;?>" style="color: #35a2ef; text-decoration: none;"><?php if(@$link_pretext != false){ echo 'View your '; } ?><?php echo ucwords(str_replace('_',' ',$link_entity_text)); ?></a>.</td>
                </tr>
                
                <?php endif; ?>

                <?php if(isset($email_signature) && $email_signature != '') : ?>
                <tr>
                    <td><?php echo $email_signature; ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td height="30"></td>
    </tr>
    <tr>
    	<td align="center">
        	<table width="725">
            	 <tr style="background-color: #1c2c36; border:1px solid #1c2c36;">
                    <td>
                    	<table width="100%" border="0" cellspacing="15" cellpadding="0">
                          <tr>
                            <td align="left" style="color:#fff; font-size:11px; line-height:1.1em;">
                            	This has been sent using <a href="http://boostaccounting.com/" style="color:#fff; text-decoration:none;"><strong>Boost Cloud Accounting.</strong></a><br />
								The easiest way to invoice clients. <a href="http://boostaccounting.com/" style="color:#fff; text-decoration:none;"><strong>Try it free</strong></a>.
							</td>
                            <td align="right"><?php echo img('images/boost_small_logo.png');?></td>
                          </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>