<?php
/**
 * GEO-утилиты для работы с городами BelingoGeo
 * 
 * @package konkord
 */

/**
 * Получить текущий город из BelingoGeo
 * 
 * @return string|null Слаг города или null
 */
function get_current_geo_city() {
    if(function_exists('belingo_get_current_city')) {
        $city = belingo_get_current_city();
        if(!empty($city['slug'])) {
            return $city['slug'];
        }
    }
    
    return null;
}

/**
 * Получить текущий город из query vars или из BelingoGeo
 * 
 * @return string|null Слаг города или null
 */
function get_geo_city_from_query() {
    $city = get_query_var('geo_city');
    
    if(empty($city)) {
        $city = get_current_geo_city();
    }
    
    return $city;
}

/**
 * Построить URL с городом
 * 
 * @param string $path Путь без ведущего слеша
 * @param string|null $city Слаг города (если null — используется текущий)
 * @return string URL
 */
function build_geo_url($path = '', $city = null) {
    $path = ltrim($path, '/');
    
    if(empty($city)) {
        $city = get_geo_city_from_query();
    }
    
    if(!empty($city)) {
        return home_url('/' . $city . '/' . $path);
    }
    
    return home_url('/' . $path);
}

/**
 * Получить паттерн для городов (для rewrite правил)
 * 
 * @return string|null Регулярное выражение для городов или null
 */
function get_geo_city_pattern() {
    $cities = get_option('belingo_geo_cities', array());
    $city_slugs = array();

    foreach ($cities as $city) {
        if (!empty($city['slug'])) {
            $city_slugs[] = preg_quote($city['slug'], '/');
        }
    }

    if (empty($city_slugs)) {
        return null;
    }

    return '(' . implode('|', $city_slugs) . ')';
}