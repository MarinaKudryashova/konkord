<?php 
  $page_id = $args["id"];
  $sec_name = $args["name"]["value"];

  get_template_part( "template-parts/section/sec-slider", null, array('id' => $page_id, 'name'  => $sec_name));
?>