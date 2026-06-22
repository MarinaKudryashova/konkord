<?php 
  $page_id = $args["page_id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  get_template_part( "template-parts/section/sec-slider", null, array('page_id' => $page_id, 'name'  => $sec_name, 'lastblock' => $sec_is_last));
?>