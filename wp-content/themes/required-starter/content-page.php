<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.1.0
 */
?>
    <!-- START: content-page.php -->
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    <header class="entry-header">
            <?php
             $chkbanner = get_post_meta(get_the_ID());
             
             if(isset($chkbanner["wpcf-page-title"][0]) && $chkbanner["wpcf-page-title"][0] != "" ){ ?>
                <h1 class="entry-title"><?php echo $chkbanner["wpcf-page-title"][0]; ?></h1>
            <?php }else{ ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php } 

             if(isset($chkbanner["wpcf-sub-title"][0]) && $chkbanner["wpcf-sub-title"][0] != "" ){ ?>
                <h2 class="entry-title subtitle"><?php echo $chkbanner["wpcf-sub-title"][0]; ?></h2>
            <?php }else{ ?>
                
            <?php } ?>
        </header><!-- .entry-header -->
        <div class="entry-content">
            <?php the_content(); ?>
            <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'requiredfoundation' ) . '</span>', 'after' => '</div>' ) ); ?>
        </div><!-- .entry-content -->
    </article><!-- #post-<?php the_ID(); ?> -->
    <!-- END: content-page.php -->