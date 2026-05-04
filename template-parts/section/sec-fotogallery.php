<?php 
  $page_id = $args["id"];
  $sec_name = $args["name"]["value"];
  $sec_bg = 'light';

  get_template_part( "template-parts/section/sec-slider", null, array('id' => $page_id, 'name'  => $sec_name, 'bg'  => $sec_bg));
?>