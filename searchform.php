<?php
/**
 * Кастомная поисковая форма
 */
?>

<form class="header__search search" id="searchform" role="search" action="<?php echo home_url('/'); ?>" method="get" aria-label="форма поиска по сайту">
  <div class="search__inner">
    <!-- Кнопка закрытия поиска -->
    <button aria-label="Поиск" class="search__close-btn ui-btn ui-btn--icon" type="button">
      <svg class="search__icon">
        <use xlink:href="<?php echo get_template_directory_uri(); ?>/img/sprite.svg#arrow-left"></use>
      </svg>
    </button>
  
    <input 
      class="search__field" 
      type="search" 
      name="s" 
      value="<?php echo get_search_query(); ?>" 
      placeholder="Поиск" 
      aria-label="поиск по сайту"
      data-search-input
    >

    <button aria-label="Поиск" class="search__btn" type="submit">
      <svg class="search__icon">
        <use xlink:href="<?php echo get_template_directory_uri(); ?>/img/sprite.svg#search"></use>
      </svg>
    </button>
  </div>
</form>