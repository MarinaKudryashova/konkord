<?php 
  $page_id = $args["page_id"];
  $sec_name = $args["name"]["value"];

  get_template_part( "template-parts/section/sec-slider", null, array('page_id' => $page_id, 'name'  => $sec_name, 'lastblock' => null));
?>