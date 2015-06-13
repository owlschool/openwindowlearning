<?php 
    /*  Create a ticket widget
    *   Show last 10 tickets
    */
    class Dynamo_Ticket_Widget extends WP_Widget{

        function Dynamo_Ticket_Widget() {
            $widget_ops = array( 'classname' => 'ticketwidget', 'description' => __('Lists a users open tickets if logged in, if not logged in shows a login form.', 'dynamo') );
            $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'dynamo-open-ticket' );
            $this->WP_Widget( 'dynamo-open-ticket', __('Support Dynamo Tickets', 'dynamo'), $widget_ops, $control_ops );
        }

        public function widget( $args, $instance ) {
            $format = $instance['format'];
           




            $tmp_content_width = $GLOBALS['content_width'];
            $GLOBALS['content_width'] = 250;

            echo $args['before_widget'];
        
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
		
            $view = 'open';
            $ticketlist = '';

            // Check whether the user is logged in or not;
            if(is_user_logged_in()){
                // Get the user ticket
                $tickets = dynamo_get_user_tickets($view);

                if($view === '' || $view === 'open') {
                    $e = 'You currently have no open tickets';
                } else if ($view === 'closed') {
                        $e = 'You currently have no closed tickets';
                    } else {
                        $e = 'You currently have no tickets';
                }
                if(!empty($tickets) && is_array($tickets)) {
                    $ticketlist .= '<ul class="widgetticketlist">'; 
                    foreach($tickets as $t) {
                        if($t->meta_value == '0') {
                            $status = 'Closed';
                        } else if($t->meta_value == '1') {
                                $status = 'Open';
                            } else if ($t->meta_value == '2') {
                                    $status = 'Waiting For Your Reply...';
                                } else {
                                    $stat = get_post_meta($t->id,'ticket_status',true); if($stat == 0) { $status = 'Closed'; } else if($stat == 1) { $status = 'Open'; } else if($stat == 2) { $status = 'Waiting For Your Reply...'; }
                        }
                        // Show only open tickets
                        if($t->meta_value == '1'):
                            $ticketlist .='<li id="ticket-'.$t->id.'" class="ticket-row">';                    
                            $ticketlist .='<a href="'.get_permalink($t->id).'" title="'.$t->post_title.'">'.$t->post_title.'</a> ('.$t->comment_count.')';                   
                            $ticketlist .='</li>';
                            endif;
                        unset($status); unset($stat);
                    }
                    $ticketlist .='</ul>';
                } else {
                    $ticketlist .='<p>'.$e.'</p>';
                }

                echo $ticketlist;
            }else{

                // Login form argument
                $loginargs = array(
                'echo'           => false,
                'redirect'       => $_SERVER['REQUEST_URI'], 
                'form_id'        => 'loginform',
                'label_username' => __( 'Username' ),
                'label_password' => __( 'Password' ),
                'label_remember' => __( 'Remember Me' ),
                'label_log_in'   => __( 'Log In' ),
                'id_username'    => 'user_login',
                'id_password'    => 'user_pass',
                'id_remember'    => 'rememberme',
                'id_submit'      => 'wp-submit',
                'remember'       => true,
                'value_username' => NULL,
                'value_remember' => false
                );
                // login form
				if(!empty($instance['content_before'])) {
					 echo '<p>'. esc_attr(stripslashes($instance['content_before']) ).'</p>';
				}
				echo '<div class="ticketlogin">'.wp_login_form( $loginargs ).'</div>';
            }



            echo $args['after_widget'];

            // Reset the post globals as this query will have stomped on it.


            $GLOBALS['content_width'] = $tmp_content_width;


        }

        function update( $new_instance, $instance ) {
            $instance['title']  = strip_tags( $new_instance['title'] );
            $instance['number'] = empty( $new_instance['number'] ) ? 2 : absint( $new_instance['number'] );  
			$instance['content_before'] = strip_tags($new_instance['content_before']);
            return $instance;
        } 

        function form( $instance ) {
            $title  = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
			$contentBefore = esc_attr($instance['content_before']);
            
        ?>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dynamo' ); ?></label></p>
        <p><input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>
		
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'content_before' ) ); ?>"><?php _e( 'Content Before Login Form:', 'dynamo' ); ?></label></p>
		<p><textarea id="content_before" style="width:100%;" rows="4" name="<?php echo esc_attr( $this->get_field_name( 'content_before' ) ); ?>"><?php echo $contentBefore; ?></textarea></p>
    


        <?php
        }

    }

    add_action( 'widgets_init', 'register_dynamo_ticket_widget' ); // function to load my widget  

    function register_dynamo_ticket_widget() {         
        register_widget( 'Dynamo_Ticket_Widget' );
    } 
?>
