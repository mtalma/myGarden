<form class="form-horizontal">
<fieldset>
<div class="control-group">
    <label for="client" class="control-label">Client</label>
    <div class="controls">
      <select id="client">
        <? foreach( $clients as $client ): ?>
        	<option value="<?= $client->user_id ?>"><?= $client->user->first_name . " " . $client->user->last_name ?></option>
        <? endforeach ?>
      </select>
    </div>
  </div>
  
  
  	<div class="control-group form-inline">
    	<label for="time_in" class="control-label">Arrival:</label>
    	<div class="controls">
      		<input type="text" id="time_in" class="input-small">
      	</div>
     </div>
	<div class="control-group form-inline">
	<label for="time_out" class="control-label">Departure:</label>
      	<div class="controls">
      		<input type="text" id="time_out" class="input-small">
    </div>
  </div>
  
  <div class="control-group">
    <label for="send_mail" class="control-label">Send Service Report</label>
    <div class="controls">
      <label class="checkbox">
        <input type="checkbox" checked="checked" value="send_email" id="send_email">
        Sends an email to the client with service details <br>(only uncheck if this service report is over 2 days old)
      </label>
    </div>
  </div>

  <div class="control-group">
    <label for="multiSelect" class="control-label">Services Completed:</label>
    <div class="controls">
        <select id="client" multiple="multiple">
        <? foreach( $clients as $client ): ?>
        	<option value="<?= $client->user_id ?>"><?= $client->user->first_name . " " . $client->user->last_name ?></option>
        <? endforeach ?>
      </select>
    </div>
  </div>

  <div class="control-group">
    <label for="textarea" class="control-label">Manager Notes</label>
    <div class="controls">
      <textarea rows="3" id="textarea" class="input-xlarge"></textarea>
    </div>
  </div>
  
  <div class="control-group">
    <label for="textarea" class="control-label">Client Notes</label>
    <div class="controls">
      <textarea rows="3" id="textarea" class="input-xlarge"></textarea>
    </div>
  </div>
  
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Save changes</button>
    <a href="<?= base_url('admin/maintenance_records') ?>" class="btn">Cancel</a>
  </div>
</fieldset>
</form>