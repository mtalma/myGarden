<div class="page-header">
    	<h1>Database Migration</h1>
  	</div>
<?= form_open('admin/migrations', array('class'=>'form-horizontal')) ?>
<fieldset>
	<? if( isset($msg) && $msg != '' ): ?>
		<div class="alert alert-success"><?= $msg ?>. Databased backed up to <?= $path ?></div>
	<? endif ?>


	<div class="well">
		<h3><?= $count ?> Tables</h3>
		<p><?= implode(", ", $tables ) ?></p>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="version">Database Version</label>
		<div class="controls">
			<input type="text" name="version" class="span1" id="version">
		</div>
	</div>
	
	<div class="form-actions">
		<button class="btn btn-primary" name="run" value="run" type="submit">Run Migrate</button>
		<button class="btn">Cancel</button>
	</div>
          
</fieldset>
</form>
	
