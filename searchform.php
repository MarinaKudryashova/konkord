<?php
/**
 * Кастомная поисковая форма
 */
?>

<form class="header__search search"  id="searchform" role="search" data-search action="<?php echo home_url( '/' ) ?>" method="get" aria-label="форма поиска по сайту">
  <div class="search__inner">
    <input class="search__field" data-search-input type="text" value="<?php echo get_search_query() ?>" name="s" id="s" name="search" placeholder="Поиск" aria-label="поиск по сайту">
    <button aria-label="Поиск" class="search__btn" type="submit">
      <svg class="search__icon">
        <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#search"></use>
      </svg>
    </button>
  </div>
</form>