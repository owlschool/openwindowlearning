(function() {
    tinymce.create('tinymce.plugins.NewaveShortcodes', {
        init : function(ed, url) {
            ed.addButton('newave_shortcode_button', {
				type: 'menubutton',
				text: 'N',
				title: 'Insert Newave Shortcode',
                icon: false,
				
				menu: [
				
					{
						text: 'Accordions',
                        onclick: function () {
                                tb_show("Add Accordion", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=accordion&width=700&height=600');
                        }
					},
					
					{
						text: 'Buttons',
                        onclick: function () {
							tb_show("Add Button", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=button&width=700&height=600');
                        }
					},
					
					{
						text: 'Boxes',
                        menu: [
					
							{
								text: 'Alert Box',
								onclick: function () {
									tb_show("Add Alert Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=alert&width=400&height=500');
								}
							},
							{
								text: 'Call To Action Box',
								onclick: function () {
									tb_show("Add Call To Action Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=tagline_box&width=400&height=500');
								}
							},
							{
								text: 'Client Slider Box',
								onclick: function () {
									tb_show("Add Client Slider Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=testimonials&width=400&height=500');
								}
							},
							{
								text: 'Contact Box',
								onclick: function () {
									tb_show("Add Contact Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=contact_details&width=400&height=500');
								}
							},
							{
								text: 'Contact Details Slider',
								onclick: function () {
									tb_show("Add Contact Details Slider", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=contact_details_slider&width=400&height=500');
								}
							},
							{
								text: 'Counter Box',
								onclick: function () {
									tb_show("Add Counter Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=counter&width=400&height=500');
								}
							},
							{
								text: 'Big Counter Box',
								onclick: function () {
									tb_show("Add Big Counter Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=big_counter&width=400&height=500');
								}
							},
							{
								text: 'Radial Counter Box',
								onclick: function () {
									tb_show("Add Radial Counter Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=radial_counter&width=400&height=500');
								}
							},
							{
								text: 'Parallax Link',
								onclick: function () {
									tb_show("Add Parallax Link", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=parallax_link&width=400&height=500');
								}
							},
							{
								text: 'Parallax Quote',
								onclick: function () {
									tb_show("Add Parallax Quote", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=parallax_quote&width=400&height=500');
								}
							},
							{
								text: 'Recent Posts',
								onclick: function () {
									tb_show("Add Recent Posts", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=recent_posts&width=400&height=500');
								}
							},
							{
								text: 'Show Three Posts',
								onclick: function () {
									tb_show("Show Three Blog Posts", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=show_three_blog_posts&width=400&height=500');
								}
							},
							{
								text: 'Service Box',
								onclick: function () {
									tb_show("Add Service Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=service&width=400&height=500');
								}
							},
							{
								text: 'Service Box -  Carousel',
								onclick: function () {
									tb_show("Add Service Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=services_box_carousel&width=400&height=500');
								}
							},
							{
								text: 'Technology Box',
								onclick: function () {
									tb_show("Add Technology Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=technologies&width=400&height=500');
								}
							},
							{
								text: 'Tweet Box',
								onclick: function () {
									tb_show("Add Tweets Box", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=parallax_twitter&width=400&height=500');
								}
							}
						]
					},
					
					{
						text: 'Columns',
                        
						menu: [
						
							{
								text: 'One Half',
								onclick: function () {
									tb_show("Add One Half Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=one_half&width=400&height=500');
								}
							},
							
							{
								text: 'One Third',
								onclick: function () {
									tb_show("Add One Third Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=one_third&width=400&height=500');
								}
							},
							
							{
								text: 'One Fourth',
								onclick: function () {
									tb_show("Add One Fourth Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=one_fourth&width=400&height=500');
								}
							},
							
							{
								text: 'One Fifth',
								onclick: function () {
									tb_show("Add One Fifth Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=one_fifth&width=400&height=500');
								}
							},
							
							{
								text: 'One Sixth',
								onclick: function () {
									tb_show("Add One Sixth Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=one_sixth&width=400&height=500');
								}
							},

							{
								text: 'Two Third',
								onclick: function () {
									tb_show("Add Two Third Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=two_third&width=400&height=500');
								}
							},

							{
								text: 'Two Fifth',
								onclick: function () {
									tb_show("Add Two Fifth Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=two_fifth&width=400&height=500');
								}
							},

							{
								text: 'Three Fourth',
								onclick: function () {
									tb_show("Add Three Fourth Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=three_fourth&width=400&height=500');
								}
							},

							{
								text: 'Three Fifth',
								onclick: function () {
									tb_show("Add Three Fifth Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=three_fifth&width=400&height=500');
								}
							},

							{
								text: 'Four Fifth',
								onclick: function () {
									tb_show("Add Four Fifth Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=four_fifth&width=400&height=500');
								}
							},

							{
								text: 'Five Sixth',
								onclick: function () {
									tb_show("Add Five Sixth Column", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=five_sixth&width=400&height=500');
								}
							}
							
						]
					},
					
					{
						text: 'FontAwesome Icon',
                        onclick: function () {
							tb_show("Add FontAwesome Icon", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=fontawesome_icon&width=400&height=500');
                        }
                    },
					
					{
						text: 'Helpers',
                        menu: [
						
							{
								text: 'Text Primary Color',
								onclick: function () {
									ed.insertContent('[text_color]ADD TEXT HERE[/text_color]');
								}
							},

							{
								text: 'Title Divider',
								onclick: function () {
									ed.insertContent('[title_divider]ADD TITLE HERE[/title_divider]');
								}
							}
						]
                    },
					
					{
                        text: 'Image Group',
                        onclick: function () {
                            tb_show("Add Image Group", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=image_group&width=400&height=500');
                        }
                    },

                    {
                        text: 'List Styles',
                        onclick: function () {
                            tb_show("Add List Style", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=list_styles&width=400&height=500');
                        }
                    },
					
					{
						text: 'Media',
						menu: [
							
							{
								text: 'Image',
								onclick: function () {
									tb_show("Add Image", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=single_image&width=700&height=600');
								}
							},

							{
								text: 'Youtube Video',
								onclick: function () {
									tb_show("Add Youtube Video", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=youtube&width=700&height=600');
								}
							},
							
							{
								text: 'Vimeo Video',
								onclick: function () {
									tb_show("Add Vimeo Video", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=vimeo&width=700&height=600');
								}
							}
						
						]
					},
					
					{
						text: 'Pricing Table',
						onclick: function () {
							ed.insertContent('[pricing_table][pricing_column icon=\"fa fa-dashboard\" title=\"Standard\"][pricing_price price=\"$19.99\" time=\"Per Month\"][/pricing_price][pricing_row]5 Gb Storage[/pricing_row][pricing_row]Free Live Support[/pricing_row][pricing_row]Unlimited Users[/pricing_row][pricing_footer url=\"\"]Sign Up[/pricing_footer][/pricing_column][/pricing_table]');
						}
					},
					
					{
                        text: 'Progress Bar',
                        onclick: function () {
                            tb_show("Add Progress Bar", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=progress&width=700&height=600');
                        }
                    },

                    {
                        text: 'Social Sharing Links',
                        onclick: function () {
                            tb_show("Add Social Sharing Link", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=social_icons&width=700&height=600');
                        }
                    },

                    {
                        text: 'Tabs',
                        onclick: function () {
                            tb_show("Add Tab", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=tabs&width=700&height=600');
                        }
                    },

                    {
                        text: 'Team Member',
                        onclick: function () {
                            tb_show("Add Team Member", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=team&width=700&height=600');
                        }
                    },

                    {
                        text: 'Team Member - Carousel',
                        onclick: function () {
                            tb_show("Add Team Member", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=team_carousel&width=700&height=600');
                        }
                    },

                    {
                        text: 'Toggles',
                        onclick: function () {
                            tb_show("Add Toggle", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=toggle&width=700&height=600');
                        }
                    },
					
					{
						text: 'Typo Elements',
						menu: [
						
							{
								text: 'Breaking Line',
								onclick: function () {
									ed.insertContent('[br]');
								}
							},
							
							{
								text: 'Dropcap',
								onclick: function () {
									tb_show("Add Dropcap", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=dropcap&width=700&height=600');
								}
							},

							{
								text: 'Title',
								onclick: function () {
									tb_show("Add Title", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=title&width=700&height=600');
								}
							},

							{
								text: 'Small Title',
								onclick: function () {
									tb_show("Add Small Title", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=small_title&width=700&height=600');
								}
							}
							
						]
					},
					
					{
						text: 'Portfolio Shortcodes',
						menu: [
						
							{
								text: 'Project Section',
								onclick: function () {
									tb_show("Add Project Section", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=project_section&width=700&height=600');
								}
							},

							{
								text: 'Project Slider',
								onclick: function () {
									tb_show("Add Project Slider", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=project_slider&width=700&height=600');
								}
							},

							{
								text: 'Project Title',
								onclick: function () {
									tb_show("Add Project Title", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=project_title&width=700&height=600');
								}
							},

							{
								text: 'Project URL',
								onclick: function () {
									tb_show("Add Project URL", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=project_url&width=700&height=600');
								}
							},

							{
								text: 'Visit Project',
								onclick: function () {
									tb_show("Add Visit Project", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=visit_project&width=700&height=600');
								}
							}
						]
					},
					
					{
                        text: 'Testimonials Carousel',
                        onclick: function () {
                            tb_show("Add Testimonials Carousel", ShortcodeAttributes.shortcode_folder + '/shortcodes_popup/shortcodes_popup.php?&sc=carousel_testimonials&width=400&height=500');
                        }
                    }
				]
            });
             
        },
        
		getInfo: function () {
            return {
        
				longname: 'Newave Shortcodes',
                author: 'Clapat Studio',
                authorurl: 'http://themeforest.net/user/clapat/',
                infourl: 'http://clapat.ro/themes/newave-wp/',
                version: "1.3"
            }
        }
    });
    // Register plugin
    tinymce.PluginManager.add( 'NewaveShortcodes', tinymce.plugins.NewaveShortcodes );
})();