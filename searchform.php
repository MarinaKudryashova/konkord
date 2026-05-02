<?php
/**
 * Кастомная поисковая форма
 */
?>

<form action="<?php echo home_url( '/' ) ?>" class="searchbar__form search" aria-label="форма поиска по сайту" method="get" id="searchform">
  <input type="hidden" name="post_type" value="Поиск">
  <button type="button" class="searchbar__btn searchbar__btn--close ui-btn ui-btn--icon" aria-label="закрыть форму поиска">
    <svg class="searchbar__icon-close ui-btn__icon">
      <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#icon-chevron-down"></use>
    </svg>
  </button>
  <input type="search" id="search-input" class="search__input input-reset" placeholder="Поиск" value="<?php echo get_search_query(); ?>" name="s" aria-label="Введите поисковый запрос">
  <svg class="search__icon">
    <use xlink:href="<?php echo get_template_directory_uri();?>/img/sprite.svg#icon-search"></use>
  </svg>
  <!-- <button type="button" id="search-clear" class="search__btn-clear btn-icon" aria-label="очистить строку поиска">
    <svg class="btn-icon__icon">
      <use xlink:href="<?php // echo get_template_directory_uri();?>/img/sprite.svg#icon-clear"></use>
    </svg>
  </button> -->
</form>