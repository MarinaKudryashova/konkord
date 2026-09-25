<?php 
  $page_id = $args["page_id"];
  $sec_is_last = (int) $args["lastblock"] ?? 0;

  $sec_class = 'news';
  if($sec_is_last != 1) {
    $sec_class .= ' sec-offset';
  }

  $news_title = get_the_title($page_id);

  $primary_cat = function_exists( 'konkord_get_primary_news_category' )
    ? konkord_get_primary_news_category()
    : null;
  $primary_cat_id = ( $primary_cat && ! is_wp_error( $primary_cat ) ) ? (int) $primary_cat->term_id : 0;

  $current_cat = is_category() ? get_queried_object() : null;
  $current_cat_id = ( $current_cat && ! is_wp_error( $current_cat ) ) ? (int) $current_cat->term_id : 0;
  // Страница /novosti-i-akczii/ = рубрика «Новости».
  if ( ! $current_cat_id && function_exists( 'konkord_is_news_posts_page' ) && konkord_is_news_posts_page() ) {
    $current_cat_id = $primary_cat_id;
  }

  $news_categories = function_exists( 'konkord_get_categories_by_priority' )
    ? konkord_get_categories_by_priority( array( 'hide_empty' => true ) )
    : get_terms( array(
        'taxonomy'   => 'category',
        'hide_empty' => true,
      ) );
  if ( is_wp_error( $news_categories ) ) {
    $news_categories = array();
  }
  // Навигация только если есть ещё рубрики с записями.
  $show_news_nav = count( $news_categories ) > 1;
?>

<section class="<?php echo esc_attr($sec_class); ?>">
  <div class="news__container container">
    <h1 class="news__title sec-title" data-aos="fade-up"><?php echo $news_title; ?></h1>

    <?php if ( $show_news_nav ) : ?>
    <div class="news__nav categories-nav" data-aos="fade-up" data-aos-delay="200">
      <ul class="categories-nav__list">
        <?php foreach ( $news_categories as $cat ) :
          $active = ( $current_cat_id === (int) $cat->term_id ) ? 'is-active' : '';
          $cat_url = get_term_link( $cat );
          if ( is_wp_error( $cat_url ) ) {
            continue;
          }
        ?>
        <li class="categories-nav__item">
          <a href="<?php echo esc_url( $cat_url ); ?>" class="categories-nav__link <?php echo esc_attr( $active ); ?>">
            <?php echo esc_html( $cat->name ); ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    <?php if ( have_posts() ) : ?>
      <ul class="news__list">
        <?php $index = 0; while ( have_posts() ) : the_post(); ?>
        <li class="news__item" data-aos="fade-up" data-aos-once="false" data-aos-duration="600" data-aos-delay="<?php echo $index++*100 + 50; ?>">
          <?php get_template_part('template-parts/components/card-news', null, ['page_id' => $page_id]); ?>
        </li>
        <?php endwhile; ?>
      </ul>

      <?php /* == Пагинация по страницам == */ ?>
      <?php get_template_part('template-parts/components/pagination'); ?>

    <?php else : ?>
      <p class="not-found">Новостей не найдено</p>
    <?php endif; ?>
  </div>

</section>

<?php 
// Сбрасываем запрос
wp_reset_postdata(); 
?>
