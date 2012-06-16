<? $record = $records[0] ?>

<h1></h1>

<div class="page-header">
    <h1><?= $record->user->first_name . " " . $record->user->last_name ?> 
    <br><small><?= $record->region->name ?></small></h1>
</div>