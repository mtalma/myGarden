<table width="100%" cellspacing="0" cellpadding="0" class="today_stats">
<tbody>
	<tr>
		<td><strong><?=date( 'g:i A', $record->get_start() )?></strong> Time In</td>
		<td><strong><?=date( 'g:i A', $record->get_end() )?></strong> Time get_crewOut</td>
		<td><strong><?=time_diff( $record->get_time() )?></strong> Maintenance Time</td>
		<td class="last"><strong><?="$". number_format( $record->get_cost(), 2, '.', ',')?></strong> Cost</td>
	</tr>
</tbody>
</table>


<?php
	$data['heading'] = "Maintenance Record";

	$this->table->add_row( 'Time In:', date( 'g:i A', $record->get_start() ) );
	$this->table->add_row( 'Time Out:', date( 'g:i A', $record->get_end() ) );
	$this->table->add_row( 'Maintenance Time:', time_diff( $record->get_time() ) );
	$this->table->add_row( 'Service Date:', date( "l jS F Y", $record->get_service_date()) );
	$this->table->add_row( 'Rating:', get_rating( $record->get_rating() ) );
	$this->table->add_row( 'Crew:', isset( $crew ) ? $crew->get_manager() . ' (' . $crew->get_license() . ')' : 'N/A' );
	$this->table->add_row( 'Client\'s Notes:', $record->get_client_notes() );
	$this->table->add_row( 'Manager\'s Notes:', $record->get_manager_notes() );
	$this->table->add_row( 'Service Cost:', "$". number_format( $record->get_service_cost(), 2, '.', ',') );
	$this->table->add_row( 'Transport Cost:', "$". number_format( $record->get_transport_cost(), 2, '.', ',') );

	$desc = $record->get_discount_percentage() == 0 ? "" : " - " . $record->get_discount_percentage() . "% of cost";
	$this->table->add_row( 'Discount:', "$". number_format( $record->get_discount_amount(), 2, '.', ',') . $desc );

	$total = $record->get_cost();
	$this->table->add_row( 'Total (VAT Exclusive):', "$". number_format( $total, 2, '.', ',') );

	$vat = VAT * $total;
	$this->table->add_row( 'VAT:', "$". number_format( $vat, 2, '.', ',') );

	$data['main'] = $this->table->generate();
	$this->load->view( "backdoor/table", $data );
