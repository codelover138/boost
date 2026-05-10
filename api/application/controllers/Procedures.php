<?php
class Procedures extends CI_Controller
{
   public function index()
	{
		echo 'Please check the procedures controller for use.';
	}
   
    public function add_expenses_module()
    {
       /* 
	   //commented out for now as it was only supposed to be used once
	   // this has been left here as an example
	    		
		$create_expenses_query = "
		CREATE TABLE IF NOT EXISTS `boost_expenses` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `usr_id` int(11) NOT NULL,
		  `contact_id` int(11) DEFAULT NULL,
		  `date` timestamp NULL DEFAULT NULL,
		  `currency_id` int(11) NOT NULL DEFAULT '1',
		  `notes` varchar(255) DEFAULT NULL,
		  `status` varchar(15) DEFAULT 'draft',
		  `content_status` varchar(15) NOT NULL DEFAULT 'active',
		  `supplier_id` int(11) DEFAULT NULL,
		  `tax_1` int(11) DEFAULT NULL,
		  `tax_2` int(11) DEFAULT NULL,
		  `category_id` int(11) NOT NULL,
		  `file_name` varchar(255) DEFAULT NULL,
		  `sub_total` double DEFAULT NULL,
		  `tax_amount` double DEFAULT NULL,
		  `total_amount` double DEFAULT NULL,
		  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;
		";
		
		$create_expenses_cat_query = "
		CREATE TABLE IF NOT EXISTS `boost_expenses_categories` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `category_name` varchar(255) NOT NULL,
		  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
		";
		
		$activities_table_drop ="DROP TABLE IF EXISTS `boost_activities`;";
		
		$activities_table_update ="
			CREATE TABLE `boost_activities` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `category` varchar(20) NOT NULL,
			  `item_id` int(11) NOT NULL,
			  `type` varchar(20) NOT NULL DEFAULT 'standard',
			  `short_message` varchar(100) NOT NULL,
			  `label` varchar(50) DEFAULT NULL,
			  `link` varchar(200) DEFAULT NULL,
			  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;
		";
		
		$this->load->model('procedures_model');
		$db_list = $this->procedures_model->list_account_dbs();
		
		foreach($db_list as $database){
			
			if(!$this->procedures_model->db_table_exists($database,'boost_expenses')){
				$result = $this->procedures_model->db_run_query($database,$create_expenses_query);
				echo '"boost_expenses" table created in "'.$database.'"<br />';
			}
			if(!$this->procedures_model->db_table_exists($database,'boost_expenses_categories')){
				$result = $this->procedures_model->db_run_query($database,$create_expenses_cat_query);
				echo '"boost_expenses_categories" table created in "'.$database.'"<br />';
			}			

		}
		*/	
    }

}