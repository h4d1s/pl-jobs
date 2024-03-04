<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (class_exists('PL_Jobs_Post_Shortcode')) {
  return;
}

class PL_Jobs_Post_Shortcode
{
  public static function init() {
    if(!shortcode_exists("pl_jobs")) {
      add_shortcode("pl_jobs", array(__CLASS__, "shortcode"));
    }
  }

  public static function shortcode($atts = array()) {
    $wp_query = new WP_Query();
    $args = PL_Jobs_Post_Query::jobs_query($wp_query);
    $wp_query->query($args);
    
    ob_start();
    ?>
      <main id="main" class="site-main" role="main">
        <div class="pl-row">
          <?php
            pl_display_total_count();

            $count_posts = wp_count_posts( 'pl_job_post' );
            $published_posts = $count_posts->publish;
            if($published_posts > 3) {
              pl_search_form();
              pl_display_filters();
            }
          ?>
          <?php
            if( $wp_query->have_posts() ) :
              while ( $wp_query->have_posts() ) : $wp_query->the_post();
                pl_get_template_part( 'content', 'archive' );
              endwhile;
              
              $current_page = max(1, get_query_var( 'pl_page' ));
              $total = $wp_query->max_num_pages;

              if($total > 1):
              ?>
              <nav class="pl-pagination">
                <?php
                echo paginate_links(
                  apply_filters(
                    'pl_jobs_pagination_args',
                    array(
                      'format'    => '?pl_page=%#%',
                      'current'   => max( 1, $current_page ),
                      'total'     => $total,
                      'prev_text' => '&larr;',
                      'next_text' => '&rarr;',
                      'type'      => 'list',
                      'end_size'  => 3,
                      'mid_size'  => 3,
                    )
                  )
                );
                ?>
              </nav>
              <?php
              endif;
            else:
              pl_get_template_part( 'content', 'none' );
            endif;
            wp_reset_query();
          ?>
        </div>
      </main><!-- #main -->
    <?php
    return ob_get_clean();
  }
}
