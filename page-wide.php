<?php
/*
Template Name: Wide
*/
get_header(); ?>
<?php wpbp_content_before(); ?>
<section id="content">
    <div class="container <?php wpbp_option('container_class'); ?>">
        <?php wpbp_main_before(); ?>
        <section id="main" role="main">
            <?php wpbp_main_inside_before(); ?>
            <div class="container">
                <?php wpbp_loop_before(); ?>
                <?php get_template_part('loop', 'page'); ?>
                <?php wpbp_loop_after(); ?>
            </div>
            <?php wpbp_main_inside_after(); ?>
        </section>
        <?php wpbp_main_after(); ?>
    </div>
</section>
<?php wpbp_content_after(); ?>
<?php get_footer(); ?>