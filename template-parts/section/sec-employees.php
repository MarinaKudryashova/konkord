<?php 
  $page_id = $args["id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = $args["lastblock"];

  get_template_part( "template-parts/section/sec-slider", null, array('id' => $page_id, 'name'  => $sec_name, 'lastblock' => $sec_is_last));
?>