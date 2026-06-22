<?php
/**
 * Кастомная поисковая форма
 */
?>

<form class="header__search searchform" id="searchform" role="search" action="<?php echo home_url('/'); ?>" method="get" aria-label="форма поиска по сайту">
  <div class="searchform__inner">
    <!-- Кнопка закрытия поиска -->
    <button aria-label="Поиск" class="searchform__close-btn ui-btn ui-btn--icon" type="button">
      <svg class="searchform__icon">
        <use xlink:href="<?php echo get_template_directory_uri(); ?>/img/sprite.svg#arrow-left"></use>
      </svg>
    </button>
  
    <input 
      class="searchform__field" 
      type="search" 
      name="s" 
      value="<?php echo get_search_query(); ?>" 
      placeholder="Поиск" 
      aria-label="поиск по сайту"
      data-search-input
    >

    <button aria-label="Поиск" class="searchform__btn" type="submit">
      <svg class="searchform__icon">
        <use xlink:href="<?php echo get_template_directory_uri(); ?>/img/sprite.svg#search"></use>
      </svg>
    </button>
  </div>
</form>