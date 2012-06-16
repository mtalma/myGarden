<p class="breadcrumb"><?=anchor('/backdoor/overview', 'Overview')?> » <?=anchor('/backdoor/details/'.$plan['id'], 'Maintenance Plan')?> » Edit Services</p>

<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		
		<h2>All Services</h2>
		<ul>
			<li>Total: <?='$'.number_format($hourly_rate,2)?>/hour</li>
		</ul>
		</div>		<!-- .block_head ends -->

		<div class="block_content">
		<p>To remove services from the maintenance plan uncheck the services on the right. To add services to the plan, check the services on the right.</p>

<?php
	echo form_open('/backdoor/editServices/' . $plan['id'] );

	$data['class'] = "small left";
	$data['heading'] = "Current Services";
	
	//$this->table->set_heading('','Services In Plan', 'Quoted Price');
	$services = $plan['services'];
	foreach( $services as $service => $desc )
		$this->table->add_row( form_checkbox( $desc['short_name'], $desc['id'], TRUE), $desc['name'], '$'.number_format($desc['quoted_price'],2) );

	$data['main'] = $this->table->generate();
	$this->table->clear();

	$this->load->view("backdoor/table", $data);
	 
	table_data_clear($data);
	$data['class'] = "small right";
	$data['heading'] = "Other Services";

	//$this->table->set_heading('','Excluded Services', 'Price');
	if( $excluded )
	{
		foreach( $excluded as $service => $desc )
			$this->table->add_row( form_checkbox( $desc['short_name'], $desc['id'], FALSE), $desc['name'], '$'.number_format($desc['price'],2) );
	}
	$data['main'] =  $this->table->generate();
	$this->load->view("backdoor/table", $data);
	 	
	echo "<p>".form_submit( 'serviceEditSubmit', 'Save', "class='submit mid'")."</p>";
	echo form_close();
?>

	</div>		<!-- .block_content ends -->

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>
