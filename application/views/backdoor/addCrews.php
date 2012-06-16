<p class="breadcrumb"><?=anchor('/backdoor/overview', 'Overview')?> &raquo; <?=anchor('/backdoor/viewcrews', 'View All Crews')?> &raquo; Add Crew</p>
    
<?php
    $data['heading'] = "Add Crew";
    $data['form_action'] = '/backdoor/addcrews';
    $data['submit_text'] = "Add Crew";
    $data['submit_name'] = "addCrew";
    
    $data['main'] = validation_errors();
     
    $data['main'] .= '<p><label>Manager Name</label><br>'. form_input('manager', set_value('manager'), "class='text small'" );
    $data['main'] .= '<p><label>Vehicle License Number</label><br>'. form_input('license', set_value('license'), "class='text small'" );

    $this->load->view( "backdoor/table", $data );