<?php
	if ( have_posts() ) {
		
		the_post();

        if( get_post_meta(get_the_ID(), 'newave_portfolio_item_layout', true) == "external" ){

            get_template_part('sections/portfolio_item_external');
        }
        else if( get_post_meta(get_the_ID(), 'newave_portfolio_item_layout', true) == "external_slider" ){

            get_template_part('sections/portfolio_item_external_slider');
        }
        else{
?>
<div class="item-data project-page clearfix element_fade_in">
<?php
            if( get_post_meta(get_the_ID(), 'newave_portfolio_item_layout', true) == "slider" ){

                get_template_part('sections/portfolio_item_slider');
            }
            else if( get_post_meta(get_the_ID(), 'newave_portfolio_item_layout', true) == "full_screen" ){

                get_template_part('sections/portfolio_item_full_screen');
            }
            else {

                get_template_part('sections/portfolio_item_normal');
            }
?>
</div>
<?php
        }

	}
?>
