<?php $path = base_url() . "adminus"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?=$title?></title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="<?=$path?>/css/report.css" />


<body id="invoice-show">

<div class="invoice-action">
	  		<div class="invoice-action-buttons">
	    		<a class="ir print-button" href="#" onclick="window.print();; return false;" title="Print Service Record(s)">Print Service Record(s)</a>        
			</div>
	
	  		<div style='clear:both;'></div>
		</div>

<?php foreach( $plans as $plan ) { ?>
	<div id="new_client_view_shell" class="client-shell-web"> 
	 
	    <div class="client-document-container">
			<div id="client-document" class="">
				<div class="client-doc-header status_draft">
					<div class="client-doc-name">
						<img alt="Logo" id="client-document-logo" src="<?=$path?>/images/logohorizontalbw-screen.jpg" height="100" width="200"/>
					</div>
					
					<div class="client-doc-doc-type">myGarden Service Record</div>
    
					<div style='clear:right;'></div>
					
					<div class="client-doc-from client-doc-address">
						<h3>Service Date</h3>
						<div><strong><?=date('D jS M Y', $date)?></strong></div>
					</div>
				
					<div style='clear:both;'></div>
	
					<div class="client-doc-for client-doc-address">
						<h3>Client</h3>
					<div>
						<strong><?=$plan['user']['fname']." ".$plan['user']['lname']?></strong>
				        <?php
				        	echo ucwords($plan['user']['address']) . "<br>";
				        	echo !empty($plan['user']['address2']) ? ucwords($plan['user']['address2']) . "<br>" : "";
				        	echo $plan['user']['region'];
				        ?>
					</div>
				</div>
			
			    <div class="client-doc-details">
					<table cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<tr>
						<td class="label">Home Phone</td>
						<td class="definition"><?=format_phone_number($plan['user']['hphone'])?></td>
						</tr>
						
						<tr>
						<td class="label">Cell Phone</td>
						<td class="definition"><?=format_phone_number($plan['user']['cphone'])?></td>
						</tr>
						
						<tr>
						<td class="label">Last Service</td>
						<td class="definition">
							<?=empty($plan['last_record']) ? '-' : date('D jS F Y', $plan['last_record']['date'])?>
						</td>
						</tr>
						
						
						<tr>
						<td class="label">Cycle</td>
						<td class="definition">
							<?=$plan['service_cycle']?>
						</td>
						</tr>
						
					</tbody>
					</table>
			    </div>
				<div style='clear:both;'></div>
			</div>
		
			<table class="client-doc-items" cellspacing="0" cellpadding="0" border="0">
			<thead>
			  <tr>
			    <th class="item-type first">Completed </th>
			    <th class="item-description">Service </th>
			    <th class="item-amount last">Notes </th>
			  </tr>
			</thead>
			<tbody class="client-doc-rows">
			
				<?php
					$row = 0;
					foreach( $plan['services'] as $service )
					{
						$class = $row % 2 == 0 ? "row-even" : "row-odd";
						$row++;
						
						echo "<tr class=".$class.">";
					    echo "<td class='item-type first'></td>";
					    echo "<td class='item-description'>".$service['name']."</td>";
					    echo "<td class='item-amount last'></td>";
					    echo "</tr>";
					}
				?>
			  
			</tbody>
			</table>

			<div class="client-doc-notes left">
				<h3>Manager Notes</h3>
				<p class="notes"><?=$plan['last_record']['manager_notes']?></p>
			</div>
		
			<div class="client-doc-notes right">
				<h3>Client Notes</h3>
				<p class="notes"><?=$plan['last_record']['client_notes']?></p>
			</div>
			
			<div style="clear:both;"></div>
			
			<div class="footer">
				
				<div class="client-doc-sig left">
					<h3>Arrival Time</h3>
				</div>
				<div class="client-doc-sig right">
					<h3>Departure Time</h3>
				</div>
				
			  	<div style='clear:both;'></div>
				<div class="client-doc-sig left">
					<h3>Maintenance Manager</h3>
				</div>
				<div class="client-doc-sig right">
					<h3><?=$plan['user']['fname']." ".$plan['user']['lname']?></h3>
				</div>
				<div style='clear:both;'></div>
			</div>

		</div>
	</div>
	
	<div class="page-break"></div>
	<?php } ?>
</div>



</body>
</html>

