<div class="row-fluid">
	<div class="well">
		<div class="row-fluid">
			<div class="span6">
				 <h2>Maintenance Plans</h2>
				 <small>$2665.03</small>
			</div>
			<div class="span2">
				<h2>8</h2>
				<small>Active</small>
			</div>
			<div class="span2">
				<h2>14</h2>
				<small>Pending</small>
			</div>
			<div class="span2">
				<h2>27</h2>
				<small>On Hold</small>
			</div>
		</div>	
	</div>
</div>

<form>
<div class="row-fluid">
	<div class="span4">
		<div class="control-group">
            <div class="controls">
                <input type="text" size="16" id="appendedInputButtons" placeholder="Search Clients" class="span8">
            </div>
          </div>
	</div>
	<div class="span4">
	    <div class="btn-group" data-toggle="buttons-radio">
		    <button class="btn btn-primary">Active</button>
		    <button class="btn btn-primary">Pending</button>
		    <button class="btn btn-primary">On Hold</button>
	    </div>
	</div>
	<div class="span4">
		<button class="btn btn-success pull-right"><i class="icon-plus icon-white"></i> Add Maintenance Plan</button>
	</div>
</div>
</form>

<div class="row-fluid">
<? $this->table->set_heading('Client', 'Region', 'Cycle', 'Status') ?>
<? foreach( $records as $record ): ?>
	<?
		$options = "<div class='btn-group'>
			<a href=" . base_url('admin/maintenance_plans/view/'.$record->id) . " class='btn'><i class='icon-search'></i></a>
			<a href=" . base_url('admin/maintenance_plans/edit/'.$record->id) . " class='btn'><i class='icon-pencil'></i></a>
			<a href=" . base_url('admin/maintenance_plans/delete/'.$record->id) . " class='btn'><i class='icon-trash'></i> </a>
		</div>";
		
		$this->table->add_row( $record->user->first_name . " " . $record->user->last_name,
								$record->region->name,
								$record->service_cycle->cycle,
								$record->status->text,
								$options );

	?>
<? endforeach ?>

<?= $this->table->generate() ?>
</div>