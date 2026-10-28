<?php
/**
 * Template for displaying search forms
 *
 * @package Kickmaker
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(
    home_url("/"),
); ?>" style="position: relative; display: flex; align-items: center;">
    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x(
        "Search Term Here",
        "placeholder",
        "kickmaker",
    ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    
    <!-- Search Icon (Submit) -->
    <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x(
        "Submit Search",
        "submit button",
        "kickmaker",
    ); ?>" style="position: absolute; right: 15px; background: none; border: none; padding: 0; cursor: pointer; color: #1C151A; display: flex;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </button>
</form>
