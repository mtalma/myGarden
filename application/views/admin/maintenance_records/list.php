<div class="row-fluid">
	<div class="well">
		<div class="row-fluid">
			<div class="span6">
				 <h2>Maintenance Records</h2>
				 <small>$2665.03</small>
			</div>
			<div class="span2">
				<h2>12</h2>
				<small>This Month</small>
				<br><small>$2665.03</small>
			</div>
			<div class="span2">
				<h2>354</h2>
				<small>This Year</small>
				<br><small>$2665.03</small>
			</div>
			<div class="span2">
				<h2>447</h2>
				<small>Total</small>
				<br><small>$2665.03</small>
			</div>
		</div>	
	</div>
</div>

<form>
<div class="row-fluid">
	<div class="span4">
		<div class="control-group">
            <div class="controls">
                <input type="text" size="16" id="" placeholder="Search Clients" class="span8">
            </div>
          </div>
	</div>
	<div class="span4">
	    <div class="btn-group" data-toggle="buttons-radio">
		    <button class="btn btn-primary">Week</button>
		    <button class="btn btn-primary">Month</button>
		    <button class="btn btn-primary">Select Month</button>
	    </div>
	</div>
	<div class="span4">
		<a href="<?= base_url('admin/maintenance_records/add') ?>" class="btn btn-success pull-right"><i class="icon-plus icon-white"></i> Add Maintenance Record</a>
	</div>
</div>
</form>

<div class="row-fluid">
<? $this->table->set_heading('Client', 'Time In', 'Time Out', 'Date', 'Cost', 'Rating', 'Crew') ?>
<? foreach( $records as $record ): ?>
	<?
		$options = "<div class='btn-group'>
			<a href=" . base_url('admin/maintenance_records/view/'.$record->id) . " class='btn'><i class='icon-search'></i></a>
			<a href=" . base_url('admin/maintenance_records/edit/'.$record->id) . " class='btn'><i class='icon-pencil'></i></a>
			<a href=" . base_url('admin/maintenance_records/delete/'.$record->id) . " class='btn'><i class='icon-trash'></i> </a>
		</div>";
		
		$david = "<span class='label label-success'>David</span>";
		$this->table->add_row(  $record->maintenance_plan->user->first_name . " " . $record->maintenance_plan->user->last_name,
								date( 'g:i A', strtotime($record->time_in) ),
								date( 'g:i A', strtotime($record->time_out) ),
								date( "l jS F Y", strtotime($record->date) ),
								"$". number_format( $record->service_cost + $record->transport_cost, 2, '.', ',' ),
								$record->rating,
								$david,
								$options );
	?>
<? endforeach ?>

<?= $this->table->generate() ?>
</div>