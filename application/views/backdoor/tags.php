	<style>
	.ui-autocomplete-loading { background: white url('<?=base_url()."adminus/images/ui-anim_basic_16x16.gif"?>') right center no-repeat; }
	</style>
	<script>
	$(function() {
		
		
		$('#tags').tagit({
				fieldName: 'tags',
				removeConfirmation: true,
                tagSource: function(search, showChoices) {
						$.ajax({ url: "<?php echo site_url('backdoor/search_tags'); ?>",
						data: { term: search.term },
						dataType: "json",
						type: "POST",
						success: function(data){
							showChoices(data);
						}
					});
				},
                onTagRemoved: function(evt, tag) {
                    $.ajax({ 
                	url: "<?php echo site_url('backdoor/delete_tag'); ?>",
                	data: { tag: $('#tags').tagit('tagLabel', tag), key: <?=$id?> },
                		key: '14',
						dataType: "json",
						type: "POST",
						success: function(data){
						}
					});
                },
                onTagClicked: function(evt, tag) {
                	//tag clicked on
                }
            }).tagit('option', 'onTagAdded', function(evt, tag) {
                $.ajax({ 
                	url: "<?php echo site_url('backdoor/add_tag'); ?>",
                	data:{ tag: $('#tags').tagit('tagLabel', tag), key: <?=$id?> },
						dataType: "json",
						type: "POST",
						success: function(data){
					}
				});
            });
	});
	</script>

<?php

	function generateList($items, $class)
	{
		$list = "<ul id='".$class."'>";
		
		foreach( $items as $item )
		{
			$system_tag =  ($item['type'] == 1) ? "class='system'" : '';
			$list .= "<li ".$system_tag.">" .$item['name']. "</li>";
		}
		
		$list .= "</ul>";
		return $list;
	}
	
	$data['heading'] = "Tags";
	$data['form_action'] = '#';
	$data['main'] = '<label>A tag is specific per client and can be used to store bits of information</label>';
	$data['main'] .= ($existing_tags > 0) ? generateList($existing_tags, 'tags') : '<input id="tags" />';	

	$this->load->view("backdoor/table", $data);
?>