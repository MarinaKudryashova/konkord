<?php 
  $page_id = $args["page_id"];

  $sec_services_cat_title = get_field('services-cat_title', $page_id);
  $sec_services_cat_list  = get_field('services-cat_list', $page_id);
?>
<section class="sec-services-cat sec-offset">
  <div class="container">
    <?php if($sec_services_cat_title) : ?>
      <h2 class="sec-title sec-title--center" data-aos="fade-up"><?php echo $sec_services_cat_title; ?></h2>
    <?php endif; ?>
    <?php if(!empty($sec_services_cat_list) && is_array($sec_services_cat_list)) : ?>
    <ul class="sec-services-cat__list">
      <?php foreach($sec_services_cat_list as $index => $services_cat) : ?>
        <li class="sec-services-cat__item" data-aos="fade-up" data-aos-anchor=".sec-title" data-aos-delay="<?php echo $index*100+50; ?>">
          <?php get_template_part('template-parts/components/card-services-cat', null, ['page_id' => $page_id, 'services-cat-id' => $services_cat, 'services-cat-index' => $index]); ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>