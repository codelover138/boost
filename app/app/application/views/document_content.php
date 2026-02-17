<?php $this->load->view('includes/html_pre_content'); //start HTML and include css,js,metatags etc ?>

<?php $this->load->view('includes/modal'); //include modal HTML ?>

<?php $this->load->view('includes/offline_error_modal'); //include offline error modal HTML ?>

<nav class="navbar navbar-default navbar-static" id="navbar-example">
  <div class="max-width-1200">
    <div class="navbar-header">
      <a href="http://boostaccounting.com" class="navbar-brand logo_container"><img src="<?php echo base_url('images/boost_logo.png'); ?>" alt="BOOST" /></a>
    </div>  
    <div class="navbar-collapse">
		<ul class="nav navbar-nav ">
			<?php if($request["type"] != "statements" && isset($request['statement_url'])){ ?>
			<li><a href="#" onclick="window.location='<?php echo $request['statement_url']; ?>'" class="" >View Statement</a></li>
			<?php } ?>
		</ul>
	</div>   
  </div>
</nav>

<!-- START content area -->
<main>
    <div class="outerContainer_table">
        <div class="tableRow">
			<?php $this->load->view($page['main_view']); ?>
        </div>
    </div>
</main>
<!-- END content area -->

<?php $this->load->view('includes/footer'); //include footer ?>

<?php $this->load->view('includes/html_post_content'); //End HTML ?>