<?php 
  $page_id = $args["page_id"];
  $sec_name = $args["name"]["value"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  $sec_class = '';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }
?>