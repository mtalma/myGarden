
<table width="100%" cellspacing="0" cellpadding="0" class="today_stats">
<tbody>
	<tr>
		<td><strong>2481</strong> page views <span class="goup">+53%</span></td>
		<td><strong>1781</strong> unique visitors <span class="goup">+53%</span></td>
		<td><strong>583</strong> conversions <span class="godown">-12%</span></td>
		<td class="last"><strong>12</strong> support requests</td>
	</tr>
</tbody>
</table>

<div class="block">
			
				<div class="block_head">
					<div class="bheadl"></div>
					<div class="bheadr"></div>
					
					<h2>Web stats</h2>
					
					<ul class="tabs">
						<li><a href="#days">Per days</a></li>

						<li><a href="#months">Per months</a></li>
					</ul>
				</div>		<!-- .block_head ends -->
				
				
				
				<div class="block_content tab_content" id="days">
					
					<table class="stats" rel="line" cellpadding="0" cellspacing="0" width="100%">
					
						<thead>
							<tr>
								<td>&nbsp;</td>

								<th scope="col">01.03</th>
								<th scope="col">02.03</th>
								<th scope="col">03.03</th>
								<th scope="col">04.03</th>
								<th scope="col">05.03</th>
								<th scope="col">06.03</th>

								<th scope="col">07.03</th>
								<th scope="col">08.03</th>
								<th scope="col">09.03</th>
								<th scope="col">10.03</th>
								<th scope="col">11.03</th>
								<th scope="col">12.03</th>

								<th scope="col">13.03</th>
								<th scope="col">14.03</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<th>Page views</th>

								<td>50</td>
								<td>90</td>
								<td>40</td>
								<td>120</td>
								<td>180</td>
								<td>280</td>

								<td>320</td>
								<td>220</td>
								<td>100</td>
								<td>120</td>
								<td>40</td>
								<td>70</td>

								<td>20</td>
								<td>60</td>
							</tr>
							
							<tr>
								<th>Unique visitors</th>								
								<td>30</td>
								<td>50</td>

								<td>15</td>
								<td>90</td>
								<td>140</td>
								<td>160</td>
								<td>230</td>
								<td>170</td>

								<td>50</td>
								<td>90</td>
								<td>30</td>
								<td>50</td>
								<td>10</td>
								<td>40</td>

							</tr>
						</tbody>
					</table>
					
				</div>		<!-- .block_content ends -->
				
				
				
				
				
				<div class="block_content tab_content" id="months">
					
					<table class="stats" rel="bar" cellpadding="0" cellspacing="0" width="100%">
					
						<thead>
							<tr>

								<td>&nbsp;</td>
								<th scope="col">JAN</th>
								<th scope="col">FEB</th>
								<th scope="col">MAR</th>
								<th scope="col">APR</th>
								<th scope="col">MAY</th>

								<th scope="col">JUN</th>
								<th scope="col">JUL</th>
								<th scope="col">AUG</th>
								<th scope="col">SEP</th>
								<th scope="col">OCT</th>
								<th scope="col">NOV</th>

								<th scope="col">DEC</th>
								<th scope="col">JAN</th>
								<th scope="col">FEB</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>

								<th scope="row">Page views</th>
								<td>1800</td>
								<td>900</td>
								<td>700</td>
								<td>1200</td>
								<td>600</td>

								<td>2800</td>
								<td>3200</td>
								<td>500</td>
								<td>2200</td>
								<td>1000</td>
								<td>1200</td>

								<td>700</td>
								<td>650</td>
								<td>800</td>
							</tr>
							
							<tr>
								<th scope="row">Unique visitors</th>								
								<td>1600</td>

								<td>650</td>
								<td>550</td>
								<td>900</td>
								<td>500</td>
								<td>2300</td>
								<td>2700</td>

								<td>350</td>
								<td>1700</td>
								<td>600</td>
								<td>1000</td>
								<td>500</td>
								<td>400</td>

								<td>700</td>
							</tr>
						</tbody>
					</table>	
				
				</div>		<!-- .block_content ends -->
				
				<div class="bendl"></div>
				<div class="bendr"></div>
			</div>		<!-- .block ends -->


<?php

	$data['heading'] = "Marketing Statistics";
	
	$this->table->set_heading( 'Browser &amp; Browser Version', 'Pageviews', 'Visits', 'Paths', 'Location');	

	foreach( $results as $result )
		$this->table->add_row( 	$result->getbrowser() . " " . $result->getbrowserVersion(), 
								$result->getpageviews(), 
								$result->getVisits(), 
								$result->getreferralPath(), 
								$result->getcity() . ", " . $result->getcountry() . ": " . $result->getlatitude() . " " . $result->getlongitude() );


	$data['main'] = $this->table->generate();
	$this->load->view( "backdoor/table", $data );