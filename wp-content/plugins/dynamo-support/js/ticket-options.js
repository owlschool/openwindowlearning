jQuery(document).ready(function() {
	//KB Tabs Dashboard
	jQuery('#kb-tabs a').click(function(){
		var t = jQuery(this).attr('href');
		jQuery(this).parent().addClass('tabs').siblings('li').removeClass('tabs');
		jQuery('.tabs-panel').hide();
		jQuery(t).show();
		return false;
	});
	
	jQuery('#author-search').after('<div id="auto-complete" style="display:none;"></div>');
	
	jQuery('#author-search').keyup(function() {
		var input = jQuery(this);
		var val = jQuery(this).val();
		var autoComplete = jQuery('#auto-complete');
		if(val.length > 2) {
			autoComplete.css('display','block');
			var data = {
				'action': 'dynamo_support_auto_complete',
				'query': val
			};
			jQuery.post(ajaxurl, data, function(response) {
				autoComplete.html(''+response+'');
				jQuery('.set-author').click(function(e) {
					e.preventDefault();
					var id = jQuery(this).attr('rel');
					jQuery('#post_author_override').val(id);
					jQuery('#author-search').val(jQuery(this).text());
					jQuery('#auto-complete').html('');
					jQuery('#auto-complete').css('display','none');
				});
			});
		} else {
			autoComplete.css('display','none');
		}
	});
	
	
	jQuery('a.ticket-status').click(function() {
		var post_id = jQuery(this).attr('rel');
		var data = {
			'action' : 'dynamo_support_open_close_ticket',
			'id' : post_id
		};
		jQuery.post(ajaxurl, data, function(response) {
			if(response == 'closed') {
				alert('Ticket Closed');
			} else {
				alert('Ticket Opened');
			}
			if(jQuery('#quickview-'+post_id+'').length != 0) {
				jQuery('#quickview-'+post_id+'').animate({
					height: '0px'
				}, 500,function() {
					jQuery('#quickview-'+post_id+'').remove();
					jQuery('tr#post-'+post_id+'').attr('rel','');
				});
			}
			jQuery('tr#post-'+post_id).attr('rel','').fadeOut('slow');
		});
	});
	
	//View Notes Script
	jQuery('.view-ticket-notes').click(function() {
		var url = jQuery(this).attr('href');
		tb_show('', ''+url+'');
		return false;
	});
	jQuery('#add-to-knowledgebase').click(function(e) {
		e.preventDefault();
		var data = {
				'action': 'dynamo_support_add_to_knowledgebase',
				'id': jQuery(this).attr('rel')
			};
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#add-to-know-notice').html(''+response+'');
			});
	});
	
	//Quick View!
	jQuery('a.quick-view').click(function(e) {
		e.preventDefault();
		var id = jQuery(this).attr('rel');
		var row = jQuery(this).parents('tr#post-'+id+'');
		if(row.attr('rel') != 'quick-view') {
			row.attr('rel','quick-view');
			var data = {
				id: id,
				action: 'dynamo_support_quick_view'
			};
			jQuery.post(ajaxurl, data, function(response) {
				//Do stuff
				row.after('<tr id="quickview-'+id+'" rel="visible"><td colspan="9">'+response+'</td></tr>');
				jQuery('#quickview-'+id+'').animate({
					height: '300px'
				}, 500,'');
				
				var data = {
				id: id,
				action: 'sd_expired_check'
				};
				jQuery.post(ajaxurl, data, function(expired) {
					if(expired != '') {
						expired = expired.split('|');

						if(expired[0] == 'all') {
							jQuery('#quickview-'+id+'').animate({
								backgroundColor: '#feb662'
							},500,'');
						} 
						jQuery('#quickview-'+id+'').find('span.user-spending').after(''+expired[1]+'');	
					}
				});
				
				//Datacard
				jQuery('a.user-datacard').hover(function(e) {
					e.preventDefault();
					var card = jQuery(this);
					if(card.children('div.datacard').text() == '') {
						card.append('<div class="datacard"><strong>User DataCard</strong><span class="mini-loading"></span></div>');
						var url = jQuery('#blog-url').val();
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
				
				jQuery('.quick-reply-btn').click(function(e) {
					e.preventDefault();
					var id = jQuery(this).attr('rel');
					var btn = jQuery(this);
					var text = jQuery('.quick-reply[rel="'+id+'"]').val();
					var data = {
						id: id,
						text: text,
						close: jQuery(this).siblings('span').children('input.close-on-reply:checked').val(),
						action: 'dynamo_support_quick_reply'
					};
					btn.attr('disabled','disabled');
					jQuery('.quick-reply[rel="'+id+'"]').attr('diabled','disabled');
					jQuery.post(ajaxurl, data, function(response) {
						jQuery('<div class="reply postbox" style="margin-bottom:5px;">'+response+'</div>').appendTo(btn.parent('div.qv-stats').siblings('div.qv-ticket')).fadeIn(1000);
						btn.removeAttr('disabled');
						setTimeout(function() {
							jQuery('.quick-reply[rel="'+id+'"]').val('');
							jQuery('.quick-reply[rel="'+id+'"]').removeAttr('disabled');
							jQuery('#quickview-'+id+'').animate({
								height: '0px'
							}, 500,function() {
								jQuery('#quickview-'+id+'').remove();
								jQuery('tr#post-'+id+'').attr('rel','').fadeOut('slow');
							});	
						},4000);
					});
				});
				
				jQuery('.close-quickview').click(function() {
					var id  = jQuery(this).attr('rel');
					jQuery('#quickview-'+id+'').animate({
						height: '0px'
					}, 500,function() {
						jQuery('#quickview-'+id+'').remove();
						jQuery('tr#post-'+id+'').attr('rel','');
					});
				});
				
				// Auto Insert Response
				jQuery('.auto-response').change(function() {
					if(jQuery(this).val() == '') return false;
					var pid = jQuery(this).attr('rel');
					var data = {
						'action': 'dynamo_support_insert_auto_response',
						'val': jQuery(this).val()
					};
					jQuery('.quick-reply[rel="'+pid+'"]').val('...');
					jQuery.post(ajaxurl, data, function(response) {
						jQuery('.quick-reply[rel="'+pid+'"]').val(''+response+'');
					});
				});
				
			});
		} else {
			return;
		}
	});
	
	
});
//Topics Creator Functions
jQuery(function() {
        var scntDiv = jQuery('#topicslist');
        var i = jQuery('#topicslist div').size() + 1;

        jQuery('#addScnt').live('click', function() {
                jQuery('<div style="margin-bottom:15px;"><label for="p_scnts" style="margin-right:85px;">Topic:</label><input type="text" size="40" name="topics[' + i +'][name]" value="" placeholder="Name your topic" /> <input type="checkbox" name="topics[' + i +'][hide]" value="true"/> Don\'t Display Topic <input type="hidden" name="topics[' + i + '][source]" value="user"/> <a href="#" class="remScnt" title="Remove Topic"></a></div>').appendTo(scntDiv);
                i++;
                return false;
        });

        jQuery('.remScnt').live('click', function() {
                if( i > 2 ) {
                        jQuery(this).parent('div').remove();
                        i--;
                }
                return false;
        });
});

//Input Creator
jQuery(function() {
        var inpDiv = jQuery('#input-box');
        var i = jQuery('#input-box div').size() + 1;

        jQuery('#addInput').live('click', function() {
                jQuery('<div style="margin-bottom:15px;"><label for="p_scnts" style="margin-right:85px;">Input Name:</label><input type="text" size="40" name="input[' + i +'][label]" value="" placeholder="Label" /> <input type="checkbox" name="input[' + i +'][required]" value="true"/> Required <a href="#" class="remInput" title="Remove Input"></a></div>').appendTo(inpDiv);
                i++;
                return false;
        });

        jQuery('.remInput').live('click', function() {
                if( i > 2 ) {
                        jQuery(this).parent('div').remove();
                        i--;
                }
                return false;
        });
});

//Add e-mail acct
jQuery(function() {
	var actDiv = jQuery('#email-acts');
	var j = jQuery('#email-acts tr').size();
	jQuery('a.add-new-server').live('click', function () {
        
        // Get the value of topic string
        var topicoptionstring = '';
        topicoptionstring += '<select class="mail-act-input" id="email-topic-ticket_'+j+'" disabled="disabled" rel="servers['+j+'][email-topic-ticket]" name="servers['+j+'][email-topic-ticket-dump]"><option value="">-- Select Topic --</option>';
        var topicstring = jQuery("#topicstring").val();
        if(topicstring !=''){
             var topicArr = topicstring.split('|');             
        }
        if(topicArr.length>0){
            for(var i=0; i<topicArr.length; i++){
                 topicoptionstring +='<option value="'+topicArr[i]+'">'+topicArr[i]+'</option>';
            }
        }
        topicoptionstring +='</select>';
		jQuery('<tr rel="'+ j +'" class="server-row">\n\
                            <td>\n\
                                <input class="mail-act-input" disabled="disabled" action-to="servers['+j+'][name]" type="text" value="" name="servers['+j+'][name-dump]"/>\n\
                                <input type="hidden" value="" name="servers['+j+'][name]"/>\n\
                            </td>\n\
                            <td>\n\
                                <input class="mail-act-input" disabled="disabled" action-to="servers['+j+'][server]" type="text" value="" name="servers['+j+'][server-dump]" />\n\
                                <input type="hidden" value="" name="servers['+j+'][server]"/>\n\
                            </td>\n\
                            <td>\n\
                                <input class="mail-act-input" disabled="disabled" action-to="servers['+j+'][port]" type="text" value="" name="servers['+j+'][port-dump]"/>\n\
                                <input type="hidden" value="" name="servers['+j+'][port]"/>\n\
                            </td>\n\
                            <td>\n\
                                <input class="mail-act-input" disabled="disabled" action-to="servers['+j+'][user]" type="text" value="" name="servers['+j+'][user-dump]"/>\n\
                                <input type="hidden" value="" name="servers['+j+'][user]"/>\n\
                            </td>\n\
                            <td>\n\
                                <input class="mail-act-input" disabled="disabled" action-to="servers['+j+'][pass]" type="password" value="" name="servers['+j+'][pass-dump]"/>\n\
                                <input type="hidden" value="" name="servers['+j+'][pass]"/>\n\
                            </td>\n\
                            <td>'+topicoptionstring+'<input type="hidden" name="servers['+j+'][email-topic-ticket]" value="" /></td>\n\
                            <td>\n\
                                <input class="mail-act-input" disabled="disabled" type="radio" value="pop3" action-to="servers['+j+'][protocol]" name="servers['+j+'][protocol-dump]" checked="checked"/>POP3&nbsp;&nbsp;\n\
                                <input class="mail-act-input" disabled="disabled" type="radio" value="imap" action-to="servers['+j+'][protocol]" name="servers['+j+'][protocol-dump]" />IMAP\n\
                                <input type="hidden" name="servers['+j+'][protocol]" value="">\n\
                            </td>\n\
                            <td>\n\
                                <input class="mail-act-input" disabled="disabled" type="checkbox" value="1" action-to="servers['+j+'][ssl]" name="servers['+j+'][ssl-dump]"/> Require SSL\n\
                                <input type="hidden" value="" name="servers['+j+'][ssl]">\n\
                            </td>\n\
                            <td>\n\
                                <button class="row-action" rel="'+j+'">Edit</button><button class="remove-row" rel="'+j+'">Remove</button>\n\
                            </td>\n\
                        </tr>').appendTo(actDiv);
		j++;
		return false;
	});
	
	jQuery('button.remove-row').live('click',function() {
		if(j > 1) {
			jQuery(this).parent('td').parent('tr').remove();
			j;
		}
		return false;
	});
	
	jQuery('button.row-action').live('click',function(e) {
		e.preventDefault();
		var action = jQuery(this).text();
		var row = jQuery(this).attr('rel');
		if(action == 'Edit') {
                    jQuery(this).parent('td').siblings('td').children('input[type="text"]').removeAttr('disabled');
                    jQuery(this).parent('td').siblings('td').children('input[type="password"]').removeAttr('disabled');
                    jQuery(this).parent('td').siblings('td').children('select').removeAttr('disabled');
                    jQuery(this).parent('td').siblings('td').children('input[type="checkbox"]').removeAttr('disabled');
                    jQuery(this).parent('td').siblings('td').children('input[type="radio"]').removeAttr('disabled');
                    jQuery(this).text('Done');
		} else {
                    jQuery(this).parent('td').siblings('td').children('input[type="text"]').attr('disabled','disabled');
                    jQuery(this).parent('td').siblings('td').children('input[type="password"]').attr('disabled','disabled');
                    jQuery(this).parent('td').siblings('td').children('select').attr('disabled','disabled');
                    jQuery(this).parent('td').siblings('td').children('input[type="checkbox"]').attr('disabled','disabled');
                    jQuery(this).parent('td').siblings('td').children('input[type="radio"]').attr('disabled','disabled');
                    
                    // Store our text field in the hidden field
                    jQuery('input[type="text"].mail-act-input').each(function (index) {
                        var $text_rel = 'input[name="'+jQuery(this).attr('action-to')+'"]';
                        var $text_val = jQuery(this).val();
                        jQuery($text_rel).val($text_val);
                    });   
                    
                    // Store our password in the hidden field
                    var $pass_rel = 'input[name="'+jQuery(this).parent('td').siblings('td').children('input[type="password"]').attr('action-to')+'"]';
                    var $pass_val = jQuery(this).parent('td').siblings('td').children('input[type="password"]').val();
                    jQuery($pass_rel).val($pass_val);
                    
                    // Store our selected topic in the hidden field
                    var $select_rel = 'input[name="'+jQuery(this).parent('td').siblings('td').children('select').attr('rel')+'"]';
                    var $select_val = jQuery(this).parent('td').siblings('td').children('select').val();
                    jQuery($select_rel).val($select_val);                    

                    // Store our radio protocol in the hidden field
                    var $radio_rel = 'input[name="'+jQuery(this).parent('td').siblings('td').children('input[type="radio"]:checked').attr('action-to')+'"]';
                    var $radio_val = jQuery(this).parent('td').siblings('td').children('input[type="radio"]:checked').val();
                    jQuery($radio_rel).val($radio_val);                    

                    // Store our SSL checkbox in the hidden field
                    var $check_rel = 'input[name="'+jQuery(this).parent('td').siblings('td').children('input[type="checkbox"]').attr('action-to')+'"]';
                    var $check_val = jQuery(this).parent('td').siblings('td').children('input[type="checkbox"]:checked').val();
                    jQuery($check_rel).val($check_val);
                    
                    jQuery(this).text('Edit');
		}
	});
	jQuery('#ticket-mailserver-submit').live('click',function() {
//            jQuery('input.mail-act-input').removeClass('mail-act-input').removeAttr('disabled'); 
	});
            
        jQuery('form[name="ticket-mailserver"]').submit(function() {
            var $alert = 0;
            jQuery('button.row-action').each(function (index) {
               var $action = jQuery(this).text();
               
               if($action == 'Done')
                   $alert = 1;
            });
            
            if($alert == 1) {
                if(!confirm('One or more of the Server IMAP Settings was not completed. Please click \'Done\' first then proceed to Save the E-mail Settings. Proceed anyway?'))
                    return false;
            }
        });
            
});

jQuery(document).ready(function() {
	//Price Calc
	jQuery('.set-price-settings').click(function(a) {
		a.preventDefault();
		var url = jQuery(this).attr('href');
		var name = jQuery(this).attr('rel');
		var width = '400';
		var height = '280';
		
		var windowWidth = document.documentElement.clientWidth;  
		var windowHeight = document.documentElement.clientHeight;  
		
		var top = windowHeight/2-height/2 +'px';
		var left = windowWidth/2-width/2 +'px';
		jQuery('body').prepend('<div id="pop-overlay"></div><div id="popbox"><h2>'+name+'</h2><iframe src="'+url+'" style="width:100%; height:100%;" frameborder="0"/></div>');
		jQuery('#popbox').css({ width: width+'px', height: height+'px', top: top, left: left  });
		jQuery('#pop-overlay').fadeIn();
		
		jQuery('#pop-overlay').click(function() {
			jQuery('#popbox').fadeOut();
			jQuery(this).fadeOut();
			jQuery('#popbox').remove();
			jQuery(this).remove();
		});
	
		
	});
});