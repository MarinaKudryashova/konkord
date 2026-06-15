<?php 
  $page_id = $args["page_id"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  $sec_class = 'news';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }

  $news_title = get_the_title($page_id);
?>

<section class="<?php echo esc_attr($sec_class); ?>">
  <div class="news__container container">
    <h1 class="news__title sec-title"><?php echo $news_title; ?></h1>
    <?php if ( have_posts() ) : ?>
      <ul class="news__list">
        <?php while ( have_posts() ) : the_post(); ?>
        <li class="news__item">
          <?php get_template_part('template-parts/components/card-news', null, ['page_id' => $page_id]); ?>
        </li>
        <?php endwhile; ?>
      </ul>

      <?php /* == Пагинация по страницам == */ ?>
      <?php get_template_part('template-parts/components/pagination'); ?>

    <?php else : 
      get_template_part( 'template-parts/content', 'none' ); ?>
    <?php endif; ?>
  </div>

</section>

<?php 
// Сбрасываем запрос
wp_reset_postdata(); 
?>
