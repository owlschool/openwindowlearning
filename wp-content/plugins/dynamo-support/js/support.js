jQuery(document).ready(function() {
	//KB Rate
	jQuery('input.rate-kb').click(function() {
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var val = jQuery(this).val();
		var data = {
			'postid': jQuery('#post-id').val(),
			'vote': val,
			'action' : 'dynamo_support_kb_vote'
		};
		jQuery('#kb-rate-votes').fadeOut('fast');
		jQuery('#kb-loading').fadeIn('slow');
		
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#kb-rate-votes p.notice').remove();
			jQuery('#kb-rate-votes input.rate-kb').attr('disabled','disabled');
			jQuery('#kb-rate-votes').prepend('<p class="notice"><strong>Thank you for your feedback, your response has been counted.</strong></p>');
			if(val == 1) {
				jQuery('#kb-rate-votes span.yes').html(response);
			} else {
				jQuery('#kb-rate-votes span.no').html(response);
			}
			jQuery('#kb-loading').fadeOut('fast');
			jQuery('#kb-rate-votes').fadeIn('slow');
		});
	});
	//Report Bug
	jQuery('#ds-report-bug').click(function(e) {
		e.preventDefault();
		//Add Overlay
		jQuery('<div id="ds-overlay"></div>').insertBefore('body').fadeIn('slow');
		//Close Function
		jQuery('#ds-overlay').click(function() {
			jQuery(this, '.ds-pop').fadeOut('slow', function() { jQuery('#ds-overlay, .ds-pop').remove(); });
		});
		
		//Make Pop Pop
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'post' : jQuery(this).attr('rel'),
			'bug-report': '1',
			'action' : 'dynamo_support_new_ticket'
		};

		jQuery('<div class="ds-pop loading-small" style="top: 50%; left:50%; margin-top:-50px; margin-left:-50px;"></div>').insertAfter('#ds-overlay').fadeIn('slow');
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('.ds-pop').removeClass('loading-small').css({ 'width': 'auto', 'height': 'auto', 'marginTop': '0', 'marginLeft': '0'});
			jQuery('.ds-pop').html(response).center();
			jQuery('#submit-bug').click(function(e) { 
				e.preventDefault(); 
				var visname = jQuery('input[name="visitorname"]').val();
				var visemail = jQuery('input[name="visitoremail"]').val();
				var emailadd = jQuery('input[name="email-address"]').val();
				var suppmsg = jQuery('textarea[name="support-message"]').val();
				var ticktitle = jQuery('input[name="ticket-title"]').val();
				var content = jQuery('textarea[name="ticket-content"]').val();
				var author = jQuery('input[name="ticket-author"]').val();
				var data = {
					'post' : jQuery('#ds-report-bug').attr('rel'),
					'bug-report': '1',
					'visitorname': visname,
					'visitoremail': visemail,
					'email-address': emailadd,
					'support-message': suppmsg,
					'ticket-topic': 'Bug Report',
					'ticket-title': ticktitle,
					'ticket-content': content,
					'ticket-author': author,
					'ticket-submit': '1',
					'pageurl': jQuery('#page-url').val(),
					'action' : 'dynamo_support_new_ticket'
				};
				processBugReport( data, ajaxurl ); 
			});
		});
	});
	
	function processBugReport( data, ajaxurl ) {
				
				jQuery('.ds-pop').html('').addClass('loading-small').css({'width':'100px', 'height':'100px', 'top':'50%', 'left':'50%', 'marginLeft':'-50px', 'marginTop': '-50px'}).center();
				
				
				jQuery.post(ajaxurl, data, function(response) {
					jQuery('.ds-pop').removeClass('loading-small').css({ 'width': 'auto', 'height': 'auto', 'marginTop': 0, 'marginLeft': 0}).html(response).center();
					
					if( jQuery('.support-success-notice').is('*') ) {
						jQuery('<p>Click anywhere on the overlay to close this window</p>').insertAfter('.support-success-notice');
						jQuery('#create_new_ticket').remove();
						jQuery('.new-ticket-head').remove();
						jQuery('.ds-pop').center();
					}
					
					jQuery('#submit-bug').click(function(e) { 
						e.preventDefault(); 
						var visname = jQuery('input[name="visitorname"]').val();
						var visemail = jQuery('input[name="visitoremail"]').val();
						var emailadd = jQuery('input[name="email-address"]').val();
						var suppmsg = jQuery('textarea[name="support-message"]').val();
						var ticktitle = jQuery('input[name="ticket-title"]').val();
						var content = jQuery('textarea[name="ticket-content"]').val();
						var author = jQuery('input[name="ticket-author"]').val();
						var data = {
							'post' : jQuery('#ds-report-bug').attr('rel'),
							'bug-report': '1',
							'visitorname': visname,
							'visitoremail': visemail,
							'email-address': emailadd,
							'support-message': suppmsg,
							'ticket-topic': 'Bug Report',
							'ticket-title': ticktitle,
							'ticket-content': content,
							'ticket-author': author,
							'ticket-submit': '1',
							'pageurl': jQuery('#page-url').val(),
							'action' : 'dynamo_support_new_ticket'
						};
						processBugReport( data ); 
					});
				});
	}
	//Feedback Form
	//Rater
		jQuery('#ratelinks li a').click(function(){
			var num = jQuery(this).text();
			var perc = num*20;
			jQuery('#current-rating').css('display','inline');
			jQuery('#current-rating').css('width',''+perc+'%');
			jQuery('#new-review-rating').val(''+num+'');
		});
		//Submit Feedback
		jQuery('#submit-feedback').click(function(e) {
			e.preventDefault();
			jQuery('div.feedback-error').fadeOut('slow');
			jQuery('#review-form').prepend('<div id="review-loading">Processing Feedback...<div  class="loading-small"></div></div>');
			var data = {
				'action': 'dynamo_support_submit_feedback',
				'name': jQuery('#review-form').find('input[name="review-name"]').val(),
				'email': jQuery('#review-form').find('input[name="review-email"]').val(),
				'domain': jQuery('#review-form').find('input[name="review-domain"]').val(),
				'subject': jQuery('#review-form').find('input[name="review-subject"]').val(),
				'rating': jQuery('#review-form').find('input[name="rating"]').val(),
				'feedback': jQuery('#review-form').find('textarea[name="review-feedback"]').val()
			};
			jQuery.post(ajaxurl, data, function(response) {
				if(response != '1') {
					jQuery('div.feedback-error').remove();
					jQuery('#review-loading').remove();
					jQuery('#review-form').prepend('<div class="feedback-error">'+response+'</div>');
				} else {
					jQuery('div.feedback-error').remove();
					jQuery('#review-loading').remove();
					jQuery('#review-form').prepend('<div class="feedback-success">Thank You For Your Feedback</div>');
					jQuery('input[name="review-name"]').val('');
					jQuery('input[name="review-email"]').val('');
					jQuery('input[name="review-domain"]').val('');
					jQuery('input[name="review-subject"]').val('');
					jQuery('input[name="rating"]').val('');
					jQuery('textarea[name="review-feedback"]').val('');
					jQuery('#current-rating').css('width','0%');
					setTimeout(
						function(){
							jQuery('#review-form').fadeOut('slow');
						},3000);
				}
			});
		});
	
	
	//Close / Leave Open Links
	jQuery('#ticket-close-request a.tick-close-action').click(function(e) {
		e.preventDefault();
		var val = jQuery(this).attr('rel');
		var post_id = jQuery(this).attr('title');
		if(val == 'close') {
			jQuery('#ticket-close-request').html('&nbsp;').addClass('loading-small');
			setTimeout(
				function(){
					var url = jQuery('#ajax-url').val();
					var ajaxurl = url+'/wp-admin/admin-ajax.php';
					
					var data = {
						'action': 'dynamo_support_close_feedback',
						'status': 'close',
						'post_id': post_id
					};
					jQuery.post(ajaxurl, data, function(response) {
						jQuery('div#respond').fadeOut('slow');
						jQuery('form#commentform').fadeOut('slow');
						jQuery('#ticket-current-status').text('Closed');
						jQuery('#ticket-close-request').removeClass('loading-small').html(''+response+'');
						//Rater
						jQuery('#ratelinks li a').click(function(){
							var num = jQuery(this).text();
							var perc = num*20;
							jQuery('#current-rating').css('display','inline');
							jQuery('#current-rating').css('width',''+perc+'%');
							jQuery('#new-review-rating').val(''+num+'');
						});
						//Submit Feedback
						jQuery('#submit-feedback').click(function(e) {
							e.preventDefault();
							jQuery('div.feedback-error').fadeOut('slow');
							jQuery('#review-form').prepend('<div id="review-loading">Processing Feedback...<div  class="loading-small"></div></div>');
							var data = {
								'action': 'dynamo_support_submit_feedback',
								'name': jQuery('#review-form').find('input[name="review-name"]').val(),
								'email': jQuery('#review-form').find('input[name="review-email"]').val(),
								'domain': jQuery('#review-form').find('input[name="review-domain"]').val(),
								'subject': jQuery('#review-form').find('input[name="review-subject"]').val(),
								'rating': jQuery('#review-form').find('input[name="rating"]').val(),
								'feedback': jQuery('#review-form').find('textarea[name="review-feedback"]').val()
							};
							jQuery.post(ajaxurl, data, function(response) {
								if(response != '1') {
									jQuery('div.feedback-error').remove();
									jQuery('#review-loading').remove();
									jQuery('#review-form').prepend('<div class="feedback-error">'+response+'</div>');
								} else {
									jQuery('div.feedback-error').remove();
									jQuery('#review-loading').remove();
									jQuery('#review-form').prepend('<div class="feedback-success">Thank You For Your Feedback</div>');
									jQuery('input[name="review-name"]').val('');
									jQuery('input[name="review-email"]').val('');
									jQuery('input[name="review-domain"]').val('');
									jQuery('input[name="review-subject"]').val('');
									jQuery('input[name="rating"]').val('');
									jQuery('textarea[name="review-feedback"]').val('');
									jQuery('#current-rating').css('width','0%');
									setTimeout(
										function(){
											jQuery('#review-form').fadeOut('slow');
										},3000);
								}
							});
						});
					});
					
					
				},1000);
		} else {
			jQuery('#ticket-close-request').html('&nbsp;').addClass('loading-small');
			setTimeout(
				function(){
					jQuery('#ticket-close-request').removeClass('loading-small').html('<strong>This question will remain open.</strong>');
					},1000);
				jQuery('#ticket-close-request').delay(5000).fadeOut('slow');
		}
	});
	
	//Default Open If No Knowledge Base
	if(jQuery('#sd-recent-articles').length > 0) {
		if(jQuery('#kb-active').val() != '1') {
			jQuery('#sd-recent-articles').removeClass('hidden');
			jQuery('#sd-recent-articles').html('');
			jQuery('#sd-recent-articles').addClass('loading');
			
			var url = jQuery('#ajax-url').val();
			var ajaxurl = url+'/wp-admin/admin-ajax.php';
			if(typeof($_POST) != 'undefined' && $_POST !== null && $_POST !== false) {
				var data = {
					'action' : 'dynamo_support_new_ticket',
					'postdata': $_POST,
					'submitted': '1'
				};								jQuery('.open-ticket').show();
			} else {
				var data = {
					'action' : 'dynamo_support_all_tickets'
				};
			}
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#sd-recent-articles').removeClass('loading');
				jQuery('#sd-recent-articles').html(''+response+'');
			});
		}
	}


	//New Ticket
	jQuery('.new-ticket').click(function(e) {				jQuery('#sd-recent-articles').removeClass('hidden');		jQuery('#sd-recent-articles').html('');		jQuery('#sd-recent-articles').addClass('loading');		jQuery('.open-ticket').show();		
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'post' : jQuery(this).attr('rel'),			
			'action' : 'dynamo_support_new_ticket'
		};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#sd-recent-articles').removeClass('loading');
			jQuery('#sd-recent-articles').html(''+response+'');
			var browserWidth = jQuery(window).width();
			var browserHeight = jQuery(window).height();
			jQuery('#browserwidth').val(browserWidth);
			jQuery('#browserheight').val(browserHeight);
		});
	});

	jQuery('.open-ticket').click(function(e) {
		jQuery('#sd-recent-articles').removeClass('hidden');
		jQuery('#sd-recent-articles').html('');
		jQuery('#sd-recent-articles').addClass('loading');
		jQuery('.open-ticket').hide();
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'action' : 'dynamo_support_all_tickets'
		};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#sd-recent-articles').removeClass('loading');
			jQuery('#sd-recent-articles').html(''+response+'');
		});
	});
/*	
	jQuery('.closed-ticket a').click(function(e) {
		jQuery('#sd-recent-articles').removeClass('hidden');
		jQuery('#sd-recent-articles').html('');
		jQuery('#sd-recent-articles').addClass('loading');				jQuery('.closed-ticket a').hide();		jQuery('.open-ticket').show();
		
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'action' : 'dynamo_support_closed_tickets'
		};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#sd-recent-articles').removeClass('loading');
			jQuery('#sd-recent-articles').html(''+response+'');
		});
	});*/
	
	jQuery('.knowledgebase a').click(function(e) {
		jQuery('#sd-recent-articles').removeClass('hidden');
		jQuery('#sd-recent-articles').html('');
		jQuery('#sd-recent-articles').addClass('loading');
		
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'action' : 'dynamo_support_browse_knowledgebase'
		};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#sd-recent-articles').removeClass('loading');
			jQuery('#sd-recent-articles').html(''+response+'');
		});
	});
	
	jQuery('#search-submit').click(function(e) {
		e.preventDefault();
		jQuery('#sd-recent-articles').removeClass('hidden');
		jQuery('#sd-recent-articles').html('');
		jQuery('#sd-recent-articles').addClass('loading');
		
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'action' : 'dynamo_support_knowledgebase_search',
			'search' : jQuery('#search').val()
		};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#sd-recent-articles').removeClass('loading');
			jQuery('#sd-recent-articles').html(''+response+'');
		});
	});
	jQuery('#search-knowledgebase').submit(function(e) {
		e.preventDefault();
		jQuery('#sd-recent-articles').removeClass('hidden');
		jQuery('#sd-recent-articles').html('');
		jQuery('#sd-recent-articles').addClass('loading');
		
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'action' : 'dynamo_support_knowledgebase_search',
			'search' : jQuery('#search').val()
		};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#sd-recent-articles').removeClass('loading');
			jQuery('#sd-recent-articles').html(''+response+'');
		});
	});
	/*
	//Ticket Nav Link Click
	jQuery('.tick-nav').click(function(e) {
		e.preventDefault();
		var view = jQuery(this).attr('rel');
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var h2 = jQuery('#ticket-view');
		var data = {
				'action': 'dynamo_support_ajax_tickets',
				'view': view
		};
		
		jQuery.post(ajaxurl, data, function(response) {
				jQuery('#ticket-table-content').html(''+response+'');
				if(view === 'all') {
					h2.text('Currently Viewing All Your Tickets');
				}
				if(view === 'open') {
					h2.text('Currently Viewing All Your Open Tickets');
				}
				if(view === 'closed') {
					h2.text('Currently Viewing All Your Closed Tickets');
				}
		});
		
	});
	*/
	
	
	//Admin ticket status
	jQuery('a.ticket-status').click(function(e) {
		e.preventDefault();
		var status = jQuery('#ticket-status').val();
		var post_id = jQuery(this).attr('rel');
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		
		var data = {
			'action': 'dynamo_support_edit_status',
			'status': status,
			'post_id': post_id
		};
		
		if(confirm('Are you sure you want to '+status+' this question?')) {
			jQuery.post(ajaxurl, data, function(response) {
				if(status === 'close') {
					jQuery('a.ticket-status').text('Open');
					
					jQuery('a.ticket-status').attr('href','#open-ticket');
					jQuery('a.ticket-status').attr('title','Open this question');
					jQuery('#ticket-status').val('open');
					jQuery('#ticket-current-status').text('Closed');
					window.location = window.location+'?fdsjlkf=1';
					window.location.reload(true);
				} else {
					jQuery('a.ticket-status').text('Close');
					
					jQuery('a.ticket-status').attr('href','#close-ticket');
					jQuery('a.ticket-status').attr('title','Close this question');
					jQuery('#ticket-status').val('close');
					jQuery('#ticket-current-status').text('Open');
					window.location.reload(true);
				}
			});
		}	
	});
	// Admin Trash Ticket
	jQuery('a.ticket-trash').click(function(e) {
		e.preventDefault();
		var post_id = jQuery(this).attr('rel');
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		
		var data = {
			'action': 'dynamo_support_trash_ticket',
			'post_id': post_id
		};
		
		if(confirm('Are you sure you want to trash this question?')) {
			jQuery.post(ajaxurl, data, function(response) {
				window.location = url+response;
			});
		}	
	});

	jQuery('#upload_image_attachment').click(function() {
		var url = jQuery('#blog-url').val();
		formfield = jQuery('#attahment_url').attr('name');
		tb_show('', ''+url+'/wp-admin/media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		//Check if is image
		var source = html.match(/src=\".*\" alt/);
		//If not Image is other type of media
		if(source == null) {
		var	source = html.match(/href=\'.*'>/);
			//if not image get source from href
			source = source[0].replace(/^href=\'/, "").replace(/'>/, "");
		} else {
			//if is image get source from src
			source = source[0].replace(/^src=\"/, "").replace(/" alt$/, "");
		}
		
		var comment = jQuery('#comment').val();
		jQuery('#comment').val(''+comment+'<!-- ATTACHMENT SEPERATOR --><br/>'+html+'<br/><!-- ATTACHMENT SEPERATOR -->');
		
		var url = jQuery('#blog-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'action': 'dynamo_support_update_attachment_meta',
			'imgurl' : source
		};
		jQuery.post(ajaxurl, data, function(response) {
			tb_remove();
		});
	}
	
	//View Notes Script
	jQuery('.view-ticket-notes').click(function() {
		var url = jQuery(this).attr('href');
		tb_show('', ''+url+'');
		return false;
	});
	// Admin Email Ticket
	jQuery('a.email-ticket').click(function() {
		var url = jQuery(this).attr('href');
		tb_show('E-Mail Ticket', ''+url+'');
		return false;	
	});
	
	// Auto Insert Response
	jQuery('#auto-response').change(function() {
		if(jQuery(this).val() == '') return false;
		var url = jQuery('#ajax-url').val();
		var ajaxurl = url+'/wp-admin/admin-ajax.php';
		var data = {
			'action': 'dynamo_support_insert_auto_response',
			'val': jQuery(this).val()
		};
		jQuery('#comment').text('...');
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#comment').text(''+response+'');
		});
	});
	
	//Datacard
	jQuery('a.user-datacard').hover(function(e) {
		e.preventDefault();
		var card = jQuery(this);
		if(card.children('div.datacard').text() == '') {
			card.append('<div class="datacard"><strong>User DataCard</strong><span class="mini-loading"></span></div>');
			var url = jQuery('#blog-url').val();
			var ajaxurl = url+'/wp-admin/admin-ajax.php';
			var data = {
				'action': 'dynamo_support_user_datacard',
				'val': jQuery(this).attr('rel')
			};
			jQuery.post(ajaxurl, data, function(response) {
				card.find('span.mini-loading').remove();
				card.find('div.datacard').html('<strong>User DataCard</strong><br/>'+response+'<div class="clear"></div>');
			});
		}
	},
	function () {
		jQuery('div.datacard').remove();
	});
	
	//Total Spending
				jQuery('span.user-spending').hover(function(e) {
					
					var spend = jQuery(this);
					if(spend.children('div.spendcard').text() == '') {
						spend.append('<div class="spendcard"><strong>User Spending</strong><span class="mini-loading"></span></div>');
						var data = {
							'action': 'dynamo_support_user_spendcard',
							'val': jQuery(this).attr('rel')
						};
						jQuery.post(ajaxurl, data, function(response) {
							spend.find('span.mini-loading').remove();
							spend.find('div.spendcard').html('<strong>User Spending</strong><br/>'+response+'<div class="clear"></div>');
						});
					}
				},
				function () {
					jQuery('div.spendcard').remove();
				});
	
});

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", ((jQuery(window).height() - this.outerHeight()) / 2) + jQuery(window).scrollTop() + "px");
    this.css("left", ((jQuery(window).width() - this.outerWidth()) / 2) + jQuery(window).scrollLeft() + "px");
    return this;
}

jQuery(function($) {
	
	$(window).hashchange( function(){
		var hash = location.hash;
		if($_POST == false) {
			jQuery('#ticket-bar ul li a').each(function() {
					var that = jQuery(this);
					if(that.attr('href') === hash) {
						that.trigger('click');
						return;
					} else if(hash == '') {
						jQuery('#sd-recent-articles').removeClass('hidden');
						jQuery('#sd-recent-articles').html('');
						jQuery('#sd-recent-articles').addClass('loading');
						var url = jQuery('#ajax-url').val();
						var ajaxurl = url+'/wp-admin/admin-ajax.php';
						var data = {
							'action' : 'sd_page_content',
							'ajax': 1
						};
						jQuery.post(ajaxurl, data, function(response) {
							jQuery('#sd-recent-articles').removeClass('loading');
							jQuery('#sd-recent-articles').html(''+response+'');
						});
						return;
					}
			});
		}
	});
	$(window).hashchange();
});