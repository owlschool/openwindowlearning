<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{
		//Access the WordPress Categories via an Array
		$of_categories = array();  
		$of_categories_obj = get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp = array_unshift($of_categories, "Select a category:");    
	       
		//Access the WordPress Pages via an Array
		$of_pages = array();
		$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp = array_unshift($of_pages, "Select a page:");       
	
		//Testing 
		$of_options_select = array("one","two","three","four","five"); 
		$of_options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
		
		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array
		( 
			"disabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_one"		=> "Block One",
				"block_two"		=> "Block Two",
				"block_three"	=> "Block Three",
			), 
			"enabled" => array (
				"placebo" => "placebo", //REQUIRED!
				"block_four"	=> "Block Four",
			),
		);


		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();
		
		if ( is_dir($alt_stylesheet_path) ) 
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) 
		    { 
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) 
		        {
		            if(stristr($alt_stylesheet_file, ".css") !== false)
		            {
		                $alt_stylesheets[] = $alt_stylesheet_file;
		            }
		        }    
		    }
		}


		//Background Images Reader
		$bg_images_path = get_stylesheet_directory(). '/images/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri(). '/images/bg/'; // change this to where you store your bg images
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) { 
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }    
		    }
		}
		

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr = wp_upload_dir();
		$all_uploads_path = $uploads_arr['path'];
		$all_uploads = get_option('of_uploads');
		$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
		
		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 
		
		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 

                $google_fonts = array(
							"0" => "Select Font",
                            "ABeeZee" => "ABeeZee",
                            "Abel" => "Abel",
                            "Abril Fatface" => "Abril Fatface",
                            "Aclonica" => "Aclonica",
                            "Acme" => "Acme",
                            "Actor" => "Actor",
                            "Adamina" => "Adamina",
                            "Advent Pro" => "Advent Pro",
                            "Aguafina Script" => "Aguafina Script",
                            "Akronim" => "Akronim",
                            "Aladin" => "Aladin",
                            "Aldrich" => "Aldrich",
                            "Alegreya" => "Alegreya",
                            "Alegreya SC" => "Alegreya SC",
                            "Alex Brush" => "Alex Brush",
                            "Alfa Slab One" => "Alfa Slab One",
                            "Alice" => "Alice",
                            "Alike" => "Alike",
                            "Alike Angular" => "Alike Angular",
                            "Allan" => "Allan",
                            "Allerta" => "Allerta",
                            "Allerta Stencil" => "Allerta Stencil",
                            "Allura" => "Allura",
                            "Almendra" => "Almendra",
                            "Almendra Display" => "Almendra Display",
                            "Almendra SC" => "Almendra SC",
                            "Amarante" => "Amarante",
                            "Amaranth" => "Amaranth",
                            "Amatic SC" => "Amatic SC",
                            "Amethysta" => "Amethysta",
                            "Anaheim" => "Anaheim",
                            "Andada" => "Andada",
                            "Andika" => "Andika",
                            "Angkor" => "Angkor",
                            "Annie Use Your Telescope" => "Annie Use Your Telescope",
                            "Anonymous Pro" => "Anonymous Pro",
                            "Antic" => "Antic",
                            "Antic Didone" => "Antic Didone",
                            "Antic Slab" => "Antic Slab",
                            "Anton" => "Anton",
                            "Arapey" => "Arapey",
                            "Arbutus" => "Arbutus",
                            "Arbutus Slab" => "Arbutus Slab",
                            "Architects Daughter" => "Architects Daughter",
                            "Archivo Black" => "Archivo Black",
                            "Archivo Narrow" => "Archivo Narrow",
                            "Arimo" => "Arimo",
                            "Arizonia" => "Arizonia",
                            "Armata" => "Armata",
                            "Artifika" => "Artifika",
                            "Arvo" => "Arvo",
                            "Asap" => "Asap",
                            "Asset" => "Asset",
                            "Astloch" => "Astloch",
                            "Asul" => "Asul",
                            "Atomic Age" => "Atomic Age",
                            "Aubrey" => "Aubrey",
                            "Audiowide" => "Audiowide",
                            "Autour One" => "Autour One",
                            "Average" => "Average",
                            "Average Sans" => "Average Sans",
                            "Averia Gruesa Libre" => "Averia Gruesa Libre",
                            "Averia Libre" => "Averia Libre",
                            "Averia Sans Libre" => "Averia Sans Libre",
                            "Averia Serif Libre" => "Averia Serif Libre",
                            "Bad Script" => "Bad Script",
                            "Balthazar" => "Balthazar",
                            "Bangers" => "Bangers",
                            "Basic" => "Basic",
                            "Battambang" => "Battambang",
                            "Baumans" => "Baumans",
                            "Bayon" => "Bayon",
                            "Belgrano" => "Belgrano",
                            "Belleza" => "Belleza",
                            "BenchNine" => "BenchNine",
                            "Bentham" => "Bentham",
                            "Berkshire Swash" => "Berkshire Swash",
                            "Bevan" => "Bevan",
                            "Bigelow Rules" => "Bigelow Rules",
                            "Bigshot One" => "Bigshot One",
                            "Bilbo" => "Bilbo",
                            "Bilbo Swash Caps" => "Bilbo Swash Caps",
                            "Bitter" => "Bitter",
                            "Black Ops One" => "Black Ops One",
                            "Bokor" => "Bokor",
                            "Bonbon" => "Bonbon",
                            "Boogaloo" => "Boogaloo",
                            "Bowlby One" => "Bowlby One",
                            "Bowlby One SC" => "Bowlby One SC",
                            "Brawler" => "Brawler",
                            "Bree Serif" => "Bree Serif",
                            "Bubblegum Sans" => "Bubblegum Sans",
                            "Bubbler One" => "Bubbler One",
                            "Buda" => "Buda",
                            "Buenard" => "Buenard",
                            "Butcherman" => "Butcherman",
                            "Butterfly Kids" => "Butterfly Kids",
                            "Cabin" => "Cabin",
                            "Cabin Condensed" => "Cabin Condensed",
                            "Cabin Sketch" => "Cabin Sketch",
                            "Caesar Dressing" => "Caesar Dressing",
                            "Cagliostro" => "Cagliostro",
                            "Calligraffitti" => "Calligraffitti",
                            "Cambo" => "Cambo",
                            "Candal" => "Candal",
                            "Cantarell" => "Cantarell",
                            "Cantata One" => "Cantata One",
                            "Cantora One" => "Cantora One",
                            "Capriola" => "Capriola",
                            "Cardo" => "Cardo",
                            "Carme" => "Carme",
                            "Carrois Gothic" => "Carrois Gothic",
                            "Carrois Gothic SC" => "Carrois Gothic SC",
                            "Carter One" => "Carter One",
                            "Caudex" => "Caudex",
                            "Cedarville Cursive" => "Cedarville Cursive",
                            "Ceviche One" => "Ceviche One",
                            "Changa One" => "Changa One",
                            "Chango" => "Chango",
                            "Chau Philomene One" => "Chau Philomene One",
                            "Chela One" => "Chela One",
                            "Chelsea Market" => "Chelsea Market",
                            "Chenla" => "Chenla",
                            "Cherry Cream Soda" => "Cherry Cream Soda",
                            "Cherry Swash" => "Cherry Swash",
                            "Chewy" => "Chewy",
                            "Chicle" => "Chicle",
                            "Chivo" => "Chivo",
                            "Cinzel" => "Cinzel",
                            "Cinzel Decorative" => "Cinzel Decorative",
                            "Clicker Script" => "Clicker Script",
                            "Coda" => "Coda",
                            "Coda Caption" => "Coda Caption",
                            "Codystar" => "Codystar",
                            "Combo" => "Combo",
                            "Comfortaa" => "Comfortaa",
                            "Coming Soon" => "Coming Soon",
                            "Concert One" => "Concert One",
                            "Condiment" => "Condiment",
                            "Content" => "Content",
                            "Contrail One" => "Contrail One",
                            "Convergence" => "Convergence",
                            "Cookie" => "Cookie",
                            "Copse" => "Copse",
                            "Corben" => "Corben",
                            "Courgette" => "Courgette",
                            "Cousine" => "Cousine",
                            "Coustard" => "Coustard",
                            "Covered By Your Grace" => "Covered By Your Grace",
                            "Crafty Girls" => "Crafty Girls",
                            "Creepster" => "Creepster",
                            "Crete Round" => "Crete Round",
                            "Crimson Text" => "Crimson Text",
                            "Croissant One" => "Croissant One",
                            "Crushed" => "Crushed",
                            "Cuprum" => "Cuprum",
                            "Cutive" => "Cutive",
                            "Cutive Mono" => "Cutive Mono",
                            "Damion" => "Damion",
                            "Dancing Script" => "Dancing Script",
                            "Dangrek" => "Dangrek",
                            "Dawning of a New Day" => "Dawning of a New Day",
                            "Days One" => "Days One",
                            "Delius" => "Delius",
                            "Delius Swash Caps" => "Delius Swash Caps",
                            "Delius Unicase" => "Delius Unicase",
                            "Della Respira" => "Della Respira",
                            "Denk One" => "Denk One",
                            "Devonshire" => "Devonshire",
                            "Didact Gothic" => "Didact Gothic",
                            "Diplomata" => "Diplomata",
                            "Diplomata SC" => "Diplomata SC",
                            "Domine" => "Domine",
                            "Donegal One" => "Donegal One",
                            "Doppio One" => "Doppio One",
                            "Dorsa" => "Dorsa",
                            "Dosis" => "Dosis",
                            "Dr Sugiyama" => "Dr Sugiyama",
                            "Droid Sans" => "Droid Sans",
                            "Droid Sans Mono" => "Droid Sans Mono",
                            "Droid Serif" => "Droid Serif",
                            "Duru Sans" => "Duru Sans",
                            "Dynalight" => "Dynalight",
                            "EB Garamond" => "EB Garamond",
                            "Eagle Lake" => "Eagle Lake",
                            "Eater" => "Eater",
                            "Economica" => "Economica",
                            "Electrolize" => "Electrolize",
                            "Elsie" => "Elsie",
                            "Elsie Swash Caps" => "Elsie Swash Caps",
                            "Emblema One" => "Emblema One",
                            "Emilys Candy" => "Emilys Candy",
                            "Engagement" => "Engagement",
                            "Englebert" => "Englebert",
                            "Enriqueta" => "Enriqueta",
                            "Erica One" => "Erica One",
                            "Esteban" => "Esteban",
                            "Euphoria Script" => "Euphoria Script",
                            "Ewert" => "Ewert",
                            "Exo" => "Exo",
                            "Expletus Sans" => "Expletus Sans",
                            "Fanwood Text" => "Fanwood Text",
                            "Fascinate" => "Fascinate",
                            "Fascinate Inline" => "Fascinate Inline",
                            "Faster One" => "Faster One",
                            "Fasthand" => "Fasthand",
                            "Federant" => "Federant",
                            "Federo" => "Federo",
                            "Felipa" => "Felipa",
                            "Fenix" => "Fenix",
                            "Finger Paint" => "Finger Paint",
                            "Fjalla One" => "Fjalla One",
                            "Fjord One" => "Fjord One",
                            "Flamenco" => "Flamenco",
                            "Flavors" => "Flavors",
                            "Fondamento" => "Fondamento",
                            "Fontdiner Swanky" => "Fontdiner Swanky",
                            "Forum" => "Forum",
                            "Francois One" => "Francois One",
                            "Freckle Face" => "Freckle Face",
                            "Fredericka the Great" => "Fredericka the Great",
                            "Fredoka One" => "Fredoka One",
                            "Freehand" => "Freehand",
                            "Fresca" => "Fresca",
                            "Frijole" => "Frijole",
                            "Fruktur" => "Fruktur",
                            "Fugaz One" => "Fugaz One",
                            "GFS Didot" => "GFS Didot",
                            "GFS Neohellenic" => "GFS Neohellenic",
                            "Gabriela" => "Gabriela",
                            "Gafata" => "Gafata",
                            "Galdeano" => "Galdeano",
                            "Galindo" => "Galindo",
                            "Gentium Basic" => "Gentium Basic",
                            "Gentium Book Basic" => "Gentium Book Basic",
                            "Geo" => "Geo",
                            "Geostar" => "Geostar",
                            "Geostar Fill" => "Geostar Fill",
                            "Germania One" => "Germania One",
                            "Gilda Display" => "Gilda Display",
                            "Give You Glory" => "Give You Glory",
                            "Glass Antiqua" => "Glass Antiqua",
                            "Glegoo" => "Glegoo",
                            "Gloria Hallelujah" => "Gloria Hallelujah",
                            "Goblin One" => "Goblin One",
                            "Gochi Hand" => "Gochi Hand",
                            "Gorditas" => "Gorditas",
                            "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
                            "Graduate" => "Graduate",
                            "Grand Hotel" => "Grand Hotel",
                            "Gravitas One" => "Gravitas One",
                            "Great Vibes" => "Great Vibes",
                            "Griffy" => "Griffy",
                            "Gruppo" => "Gruppo",
                            "Gudea" => "Gudea",
                            "Habibi" => "Habibi",
                            "Hammersmith One" => "Hammersmith One",
                            "Hanalei" => "Hanalei",
                            "Hanalei Fill" => "Hanalei Fill",
                            "Handlee" => "Handlee",
                            "Hanuman" => "Hanuman",
                            "Happy Monkey" => "Happy Monkey",
                            "Headland One" => "Headland One",
                            "Henny Penny" => "Henny Penny",
                            "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
                            "Holtwood One SC" => "Holtwood One SC",
                            "Homemade Apple" => "Homemade Apple",
                            "Homenaje" => "Homenaje",
                            "IM Fell DW Pica" => "IM Fell DW Pica",
                            "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
                            "IM Fell Double Pica" => "IM Fell Double Pica",
                            "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
                            "IM Fell English" => "IM Fell English",
                            "IM Fell English SC" => "IM Fell English SC",
                            "IM Fell French Canon" => "IM Fell French Canon",
                            "IM Fell French Canon SC" => "IM Fell French Canon SC",
                            "IM Fell Great Primer" => "IM Fell Great Primer",
                            "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
                            "Iceberg" => "Iceberg",
                            "Iceland" => "Iceland",
                            "Imprima" => "Imprima",
                            "Inconsolata" => "Inconsolata",
                            "Inder" => "Inder",
                            "Indie Flower" => "Indie Flower",
                            "Inika" => "Inika",
                            "Irish Grover" => "Irish Grover",
                            "Istok Web" => "Istok Web",
                            "Italiana" => "Italiana",
                            "Italianno" => "Italianno",
                            "Jacques Francois" => "Jacques Francois",
                            "Jacques Francois Shadow" => "Jacques Francois Shadow",
                            "Jim Nightshade" => "Jim Nightshade",
                            "Jockey One" => "Jockey One",
                            "Jolly Lodger" => "Jolly Lodger",
                            "Josefin Sans" => "Josefin Sans",
                            "Josefin Slab" => "Josefin Slab",
                            "Joti One" => "Joti One",
                            "Judson" => "Judson",
                            "Julee" => "Julee",
                            "Julius Sans One" => "Julius Sans One",
                            "Junge" => "Junge",
                            "Jura" => "Jura",
                            "Just Another Hand" => "Just Another Hand",
                            "Just Me Again Down Here" => "Just Me Again Down Here",
                            "Kameron" => "Kameron",
                            "Karla" => "Karla",
                            "Kaushan Script" => "Kaushan Script",
                            "Kavoon" => "Kavoon",
                            "Keania One" => "Keania One",
                            "Kelly Slab" => "Kelly Slab",
                            "Kenia" => "Kenia",
                            "Khmer" => "Khmer",
                            "Kite One" => "Kite One",
                            "Knewave" => "Knewave",
                            "Kotta One" => "Kotta One",
                            "Koulen" => "Koulen",
                            "Kranky" => "Kranky",
                            "Kreon" => "Kreon",
                            "Kristi" => "Kristi",
                            "Krona One" => "Krona One",
                            "La Belle Aurore" => "La Belle Aurore",
                            "Lancelot" => "Lancelot",
                            "Lato" => "Lato",
                            "League Script" => "League Script",
                            "Leckerli One" => "Leckerli One",
                            "Ledger" => "Ledger",
                            "Lekton" => "Lekton",
                            "Lemon" => "Lemon",
                            "Libre Baskerville" => "Libre Baskerville",
                            "Life Savers" => "Life Savers",
                            "Lilita One" => "Lilita One",
                            "Limelight" => "Limelight",
                            "Linden Hill" => "Linden Hill",
                            "Lobster" => "Lobster",
                            "Lobster Two" => "Lobster Two",
                            "Londrina Outline" => "Londrina Outline",
                            "Londrina Shadow" => "Londrina Shadow",
                            "Londrina Sketch" => "Londrina Sketch",
                            "Londrina Solid" => "Londrina Solid",
                            "Lora" => "Lora",
                            "Love Ya Like A Sister" => "Love Ya Like A Sister",
                            "Loved by the King" => "Loved by the King",
                            "Lovers Quarrel" => "Lovers Quarrel",
                            "Luckiest Guy" => "Luckiest Guy",
                            "Lusitana" => "Lusitana",
                            "Lustria" => "Lustria",
                            "Macondo" => "Macondo",
                            "Macondo Swash Caps" => "Macondo Swash Caps",
                            "Magra" => "Magra",
                            "Maiden Orange" => "Maiden Orange",
                            "Mako" => "Mako",
                            "Marcellus" => "Marcellus",
                            "Marcellus SC" => "Marcellus SC",
                            "Marck Script" => "Marck Script",
                            "Margarine" => "Margarine",
                            "Marko One" => "Marko One",
                            "Marmelad" => "Marmelad",
                            "Marvel" => "Marvel",
                            "Mate" => "Mate",
                            "Mate SC" => "Mate SC",
                            "Maven Pro" => "Maven Pro",
                            "McLaren" => "McLaren",
                            "Meddon" => "Meddon",
                            "MedievalSharp" => "MedievalSharp",
                            "Medula One" => "Medula One",
                            "Megrim" => "Megrim",
                            "Meie Script" => "Meie Script",
                            "Merienda" => "Merienda",
                            "Merienda One" => "Merienda One",
                            "Merriweather" => "Merriweather",
                            "Merriweather Sans" => "Merriweather Sans",
                            "Metal" => "Metal",
                            "Metal Mania" => "Metal Mania",
                            "Metamorphous" => "Metamorphous",
                            "Metrophobic" => "Metrophobic",
                            "Michroma" => "Michroma",
                            "Milonga" => "Milonga",
                            "Miltonian" => "Miltonian",
                            "Miltonian Tattoo" => "Miltonian Tattoo",
                            "Miniver" => "Miniver",
                            "Miss Fajardose" => "Miss Fajardose",
                            "Modern Antiqua" => "Modern Antiqua",
                            "Molengo" => "Molengo",
                            "Molle" => "Molle",
                            "Monda" => "Monda",
                            "Monofett" => "Monofett",
                            "Monoton" => "Monoton",
                            "Monsieur La Doulaise" => "Monsieur La Doulaise",
                            "Montaga" => "Montaga",
                            "Montez" => "Montez",
                            "Montserrat" => "Montserrat",
                            "Montserrat Alternates" => "Montserrat Alternates",
                            "Montserrat Subrayada" => "Montserrat Subrayada",
                            "Moul" => "Moul",
                            "Moulpali" => "Moulpali",
                            "Mountains of Christmas" => "Mountains of Christmas",
                            "Mouse Memoirs" => "Mouse Memoirs",
                            "Mr Bedfort" => "Mr Bedfort",
                            "Mr Dafoe" => "Mr Dafoe",
                            "Mr De Haviland" => "Mr De Haviland",
                            "Mrs Saint Delafield" => "Mrs Saint Delafield",
                            "Mrs Sheppards" => "Mrs Sheppards",
                            "Muli" => "Muli",
                            "Mystery Quest" => "Mystery Quest",
                            "Neucha" => "Neucha",
                            "Neuton" => "Neuton",
                            "New Rocker" => "New Rocker",
                            "News Cycle" => "News Cycle",
                            "Niconne" => "Niconne",
                            "Nixie One" => "Nixie One",
                            "Nobile" => "Nobile",
                            "Nokora" => "Nokora",
                            "Norican" => "Norican",
                            "Nosifer" => "Nosifer",
                            "Nothing You Could Do" => "Nothing You Could Do",
                            "Noticia Text" => "Noticia Text",
                            "Nova Cut" => "Nova Cut",
                            "Nova Flat" => "Nova Flat",
                            "Nova Mono" => "Nova Mono",
                            "Nova Oval" => "Nova Oval",
                            "Nova Round" => "Nova Round",
                            "Nova Script" => "Nova Script",
                            "Nova Slim" => "Nova Slim",
                            "Nova Square" => "Nova Square",
                            "Numans" => "Numans",
                            "Nunito" => "Nunito",
                            "Odor Mean Chey" => "Odor Mean Chey",
                            "Offside" => "Offside",
                            "Old Standard TT" => "Old Standard TT",
                            "Oldenburg" => "Oldenburg",
                            "Oleo Script" => "Oleo Script",
                            "Oleo Script Swash Caps" => "Oleo Script Swash Caps",
                            "Open Sans" => "Open Sans",
                            "Open Sans Condensed" => "Open Sans Condensed",
                            "Oranienbaum" => "Oranienbaum",
                            "Orbitron" => "Orbitron",
                            "Oregano" => "Oregano",
                            "Orienta" => "Orienta",
                            "Original Surfer" => "Original Surfer",
                            "Oswald" => "Oswald",
                            "Over the Rainbow" => "Over the Rainbow",
                            "Overlock" => "Overlock",
                            "Overlock SC" => "Overlock SC",
                            "Ovo" => "Ovo",
                            "Oxygen" => "Oxygen",
                            "Oxygen Mono" => "Oxygen Mono",
                            "PT Mono" => "PT Mono",
                            "PT Sans" => "PT Sans",
                            "PT Sans Caption" => "PT Sans Caption",
                            "PT Sans Narrow" => "PT Sans Narrow",
                            "PT Serif" => "PT Serif",
                            "PT Serif Caption" => "PT Serif Caption",
                            "Pacifico" => "Pacifico",
                            "Paprika" => "Paprika",
                            "Parisienne" => "Parisienne",
                            "Passero One" => "Passero One",
                            "Passion One" => "Passion One",
                            "Patrick Hand" => "Patrick Hand",
                            "Patrick Hand SC" => "Patrick Hand SC",
                            "Patua One" => "Patua One",
                            "Paytone One" => "Paytone One",
                            "Peralta" => "Peralta",
                            "Permanent Marker" => "Permanent Marker",
                            "Petit Formal Script" => "Petit Formal Script",
                            "Petrona" => "Petrona",
                            "Philosopher" => "Philosopher",
                            "Piedra" => "Piedra",
                            "Pinyon Script" => "Pinyon Script",
                            "Pirata One" => "Pirata One",
                            "Plaster" => "Plaster",
                            "Play" => "Play",
                            "Playball" => "Playball",
                            "Playfair Display" => "Playfair Display",
                            "Playfair Display SC" => "Playfair Display SC",
                            "Podkova" => "Podkova",
                            "Poiret One" => "Poiret One",
                            "Poller One" => "Poller One",
                            "Poly" => "Poly",
                            "Pompiere" => "Pompiere",
                            "Pontano Sans" => "Pontano Sans",
                            "Port Lligat Sans" => "Port Lligat Sans",
                            "Port Lligat Slab" => "Port Lligat Slab",
                            "Prata" => "Prata",
                            "Preahvihear" => "Preahvihear",
                            "Press Start 2P" => "Press Start 2P",
                            "Princess Sofia" => "Princess Sofia",
                            "Prociono" => "Prociono",
                            "Prosto One" => "Prosto One",
                            "Puritan" => "Puritan",
                            "Purple Purse" => "Purple Purse",
                            "Quando" => "Quando",
                            "Quantico" => "Quantico",
                            "Quattrocento" => "Quattrocento",
                            "Quattrocento Sans" => "Quattrocento Sans",
                            "Questrial" => "Questrial",
                            "Quicksand" => "Quicksand",
                            "Quintessential" => "Quintessential",
                            "Qwigley" => "Qwigley",
                            "Racing Sans One" => "Racing Sans One",
                            "Radley" => "Radley",
                            "Raleway" => "Raleway",
                            "Raleway Dots" => "Raleway Dots",
                            "Rambla" => "Rambla",
                            "Rammetto One" => "Rammetto One",
                            "Ranchers" => "Ranchers",
                            "Rancho" => "Rancho",
                            "Rationale" => "Rationale",
                            "Redressed" => "Redressed",
                            "Reenie Beanie" => "Reenie Beanie",
                            "Revalia" => "Revalia",
                            "Ribeye" => "Ribeye",
                            "Ribeye Marrow" => "Ribeye Marrow",
                            "Righteous" => "Righteous",
                            "Risque" => "Risque",
                            "Roboto" => "Roboto",
                            "Roboto Condensed" => "Roboto Condensed",
                            "Rochester" => "Rochester",
                            "Rock Salt" => "Rock Salt",
                            "Rokkitt" => "Rokkitt",
                            "Romanesco" => "Romanesco",
                            "Ropa Sans" => "Ropa Sans",
                            "Rosario" => "Rosario",
                            "Rosarivo" => "Rosarivo",
                            "Rouge Script" => "Rouge Script",
                            "Ruda" => "Ruda",
                            "Rufina" => "Rufina",
                            "Ruge Boogie" => "Ruge Boogie",
                            "Ruluko" => "Ruluko",
                            "Rum Raisin" => "Rum Raisin",
                            "Ruslan Display" => "Ruslan Display",
                            "Russo One" => "Russo One",
                            "Ruthie" => "Ruthie",
                            "Rye" => "Rye",
                            "Sacramento" => "Sacramento",
                            "Sail" => "Sail",
                            "Salsa" => "Salsa",
                            "Sanchez" => "Sanchez",
                            "Sancreek" => "Sancreek",
                            "Sansita One" => "Sansita One",
                            "Sarina" => "Sarina",
                            "Satisfy" => "Satisfy",
                            "Scada" => "Scada",
                            "Schoolbell" => "Schoolbell",
                            "Seaweed Script" => "Seaweed Script",
                            "Sevillana" => "Sevillana",
                            "Seymour One" => "Seymour One",
                            "Shadows Into Light" => "Shadows Into Light",
                            "Shadows Into Light Two" => "Shadows Into Light Two",
                            "Shanti" => "Shanti",
                            "Share" => "Share",
                            "Share Tech" => "Share Tech",
                            "Share Tech Mono" => "Share Tech Mono",
                            "Shojumaru" => "Shojumaru",
                            "Short Stack" => "Short Stack",
                            "Siemreap" => "Siemreap",
                            "Sigmar One" => "Sigmar One",
                            "Signika" => "Signika",
                            "Signika Negative" => "Signika Negative",
                            "Simonetta" => "Simonetta",
                            "Sintony" => "Sintony",
                            "Sirin Stencil" => "Sirin Stencil",
                            "Six Caps" => "Six Caps",
                            "Skranji" => "Skranji",
                            "Slackey" => "Slackey",
                            "Smokum" => "Smokum",
                            "Smythe" => "Smythe",
                            "Sniglet" => "Sniglet",
                            "Snippet" => "Snippet",
                            "Snowburst One" => "Snowburst One",
                            "Sofadi One" => "Sofadi One",
                            "Sofia" => "Sofia",
                            "Sonsie One" => "Sonsie One",
                            "Sorts Mill Goudy" => "Sorts Mill Goudy",
                            "Source Code Pro" => "Source Code Pro",
                            "Source Sans Pro" => "Source Sans Pro",
                            "Special Elite" => "Special Elite",
                            "Spicy Rice" => "Spicy Rice",
                            "Spinnaker" => "Spinnaker",
                            "Spirax" => "Spirax",
                            "Squada One" => "Squada One",
                            "Stalemate" => "Stalemate",
                            "Stalinist One" => "Stalinist One",
                            "Stardos Stencil" => "Stardos Stencil",
                            "Stint Ultra Condensed" => "Stint Ultra Condensed",
                            "Stint Ultra Expanded" => "Stint Ultra Expanded",
                            "Stoke" => "Stoke",
                            "Strait" => "Strait",
                            "Sue Ellen Francisco" => "Sue Ellen Francisco",
                            "Sunshiney" => "Sunshiney",
                            "Supermercado One" => "Supermercado One",
                            "Suwannaphum" => "Suwannaphum",
                            "Swanky and Moo Moo" => "Swanky and Moo Moo",
                            "Syncopate" => "Syncopate",
                            "Tangerine" => "Tangerine",
                            "Taprom" => "Taprom",
                            "Tauri" => "Tauri",
                            "Telex" => "Telex",
                            "Tenor Sans" => "Tenor Sans",
                            "Text Me One" => "Text Me One",
                            "The Girl Next Door" => "The Girl Next Door",
                            "Tienne" => "Tienne",
                            "Tinos" => "Tinos",
                            "Titan One" => "Titan One",
                            "Titillium Web" => "Titillium Web",
                            "Trade Winds" => "Trade Winds",
                            "Trocchi" => "Trocchi",
                            "Trochut" => "Trochut",
                            "Trykker" => "Trykker",
                            "Tulpen One" => "Tulpen One",
                            "Ubuntu" => "Ubuntu",
                            "Ubuntu Condensed" => "Ubuntu Condensed",
                            "Ubuntu Mono" => "Ubuntu Mono",
                            "Ultra" => "Ultra",
                            "Uncial Antiqua" => "Uncial Antiqua",
                            "Underdog" => "Underdog",
                            "Unica One" => "Unica One",
                            "UnifrakturCook" => "UnifrakturCook",
                            "UnifrakturMaguntia" => "UnifrakturMaguntia",
                            "Unkempt" => "Unkempt",
                            "Unlock" => "Unlock",
                            "Unna" => "Unna",
                            "VT323" => "VT323",
                            "Vampiro One" => "Vampiro One",
                            "Varela" => "Varela",
                            "Varela Round" => "Varela Round",
                            "Vast Shadow" => "Vast Shadow",
                            "Vibur" => "Vibur",
                            "Vidaloka" => "Vidaloka",
                            "Viga" => "Viga",
                            "Voces" => "Voces",
                            "Volkhov" => "Volkhov",
                            "Vollkorn" => "Vollkorn",
                            "Voltaire" => "Voltaire",
                            "Waiting for the Sunrise" => "Waiting for the Sunrise",
                            "Wallpoet" => "Wallpoet",
                            "Walter Turncoat" => "Walter Turncoat",
                            "Warnes" => "Warnes",
                            "Wellfleet" => "Wellfleet",
                            "Wendy One" => "Wendy One",
                            "Wire One" => "Wire One",
                            "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
                            "Yellowtail" => "Yellowtail",
                            "Yeseva One" => "Yeseva One",
                            "Yesteryear" => "Yesteryear",
                            "Zeyada" => "Zeyada",
						);
/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();

$of_options[] = array( "name" => __("General Settings", THEME_LANGUAGE_DOMAIN),
                    "type" => "heading");
					
					
$of_options[] = array( "name" => __("Custom Favicon", THEME_LANGUAGE_DOMAIN),
					"desc" => __("You can put url of an ico image that will represent your website's favicon (16px x 16px)", THEME_LANGUAGE_DOMAIN),
					"id" => "favicon",
					"std" => get_template_directory_uri()."/images/favicon.ico",
					"type" => "upload");

$of_options[] = array( "name" => __("Custom Loading Icon", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("You can put url of an animated image that will be displayed while the website is loading", THEME_LANGUAGE_DOMAIN),
                    "id" => "loading_img",
                    "std" => get_template_directory_uri()."/images/newave-loading.gif",
                    "type" => "upload");

$of_options[] = array(  "name" => __("Custom Loading Icon Background Color", THEME_LANGUAGE_DOMAIN),
                    "desc" => "",
                    "id" => "loading_img_bknd_color",
                    "std" => "#ffffff",
                    "type" => "color");

$of_options[] = array(  "name" => __("Enable Smooth Scrolling", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Use smooth or standard scrolling.", THEME_LANGUAGE_DOMAIN),
                        "id" => "enable_smooth_scrolling",
                        "std" => 1,
                        "type" => "switch" );

$of_options[] = array( "name" => __("Tracking Code", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.", THEME_LANGUAGE_DOMAIN),
					"id" => "google_analytics",
					"std" => "",
					"type" => "textarea");        

$of_options[] = array( "name" => __("Space before &lt;/head&gt;", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Add code before the &lt;/head&gt; tag.", THEME_LANGUAGE_DOMAIN),
					"id" => "space_head",
					"std" => "",
					"type" => "textarea");

// Header options
$of_options[] = array( "name" => __("Home Options", THEME_LANGUAGE_DOMAIN),
                    "type" => "heading");

$of_options[] = array( "name" => "",
                    "desc" => "",
                    "id" => "home_heading",
                    "std" => __("Home Section Type", THEME_LANGUAGE_DOMAIN),
                    "icon" => false,
                    "type" => "info");

$of_options[] = array( "name" 		=> __("Home Layout Type", THEME_LANGUAGE_DOMAIN),
                    "desc" 		=> __("Select your home type.", THEME_LANGUAGE_DOMAIN),
                    "id" 		=> "home_layout_type",
                    "std"       => "Image Pattern",
                    "type" 		=> "select",
                    "options" 	=> array("Image Pattern", "Full Screen Slider", "Full Width Parallax Slider", "Full Screen Image Parallax", "Full Screen Video Background", "Full Screen Moving Background", "Full Screen Overlay Slider"));

$of_options[] = array( "name" => "",
                    "desc" => "",
                    "id" => "home_image_pattern",
                    "std" => __("Image Pattern Layout Options", THEME_LANGUAGE_DOMAIN),
                    "icon" => false,
                    "type" => "info");

$of_options[] = array( "name" => __("Image Pattern Background", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Upload image pattern background", THEME_LANGUAGE_DOMAIN),
                    "id" => "home_image_pattern_background",
                    "std" => get_template_directory_uri()."/images/home_pattern.png",
                    "type" => "upload");

$of_options[] = array( "name" => __("Show Html Page Content", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Show the content of the home page.", THEME_LANGUAGE_DOMAIN),
                    "id" => "home_image_pattern_content",
                    "std" => 1,
                    "type" => "checkbox");

$of_options[] = array( 	"name" 		=> __("Text Slider", THEME_LANGUAGE_DOMAIN),
                    "desc" 		=> "",
                    "id" 		=> "home_image_pattern_text_slider",
                    "std" 		=> "",
                    "type" 		=> "text_slider"
               );

$of_options[] = array(  "name"      => __("Text Slider Transition Type", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the transition type.", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_image_pattern_text_slider_transition",
                        "std"       => "fade",
                        "type" 		=> "select",
                        "options" 	=> array("fade" => "Fade", "vertical" => "Vertical"));

$of_options[] = array( 	"name" 		=> __("Text Slider Speed", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the slider speed in milliseconds", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_image_pattern_text_slider_speed",
                        "std" 		=> "5000",
                        "min" 		=> "1000",
                        "step"		=> "100",
                        "max" 		=> "10000",
                        "type" 		=> "sliderui" );

$of_options[] = array( 	"name" 		=> __("Bullet Words", THEME_LANGUAGE_DOMAIN),
                    "desc" 		=> "",
                    "id" 		=> "home_image_pattern_bullet_words",
                    "std" 		=> "",
                    "type" 		=> "text_slider"
                );

$of_options[] = array(  "name" 		=> __("Text Color", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_image_pattern_text_color",
                        "std"       => "Light",
                        "type" 		=> "select",
                        "options" 	=> array("Light", "Dark"));

$of_options[] = array( "name" => __("Button Name", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "home_image_pattern_button_name",
                        "std" => "",
                        "type" => "text"
                    );

$of_options[] = array( "name" => __("Button URL", THEME_LANGUAGE_DOMAIN),
                       "desc" => "",
                       "id" => "home_image_pattern_button_url",
                       "std" => "#",
                       "type" => "text"
                    );

$of_options[] = array(  "name" => "",
                        "desc" => "",
                        "id" => "home_full_screen_slider",
                        "std" => __("Full Screen Slider Layout Options", THEME_LANGUAGE_DOMAIN),
                        "icon" => false,
                        "type" => "info");

$of_options[] = array( 	"name" 		=> __("Full Screen Slider", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_full_screen_slider_slider",
                        "std" 		=> "",
                        "type" 		=> "extended_slider"
                    );

$of_options[] = array(  "name"      => __("Full Screen Slider Transition Type", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the transition type.", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_full_screen_slider_transition",
                        "std"       => "fade",
                        "type" 		=> "select",
                        "options" 	=> array("fade" => "Fade", "scrollHorz" => "Horizontal"));

$of_options[] = array( 	"name" 		=> __("Full Screen Slider Speed", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the slider speed in milliseconds", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_full_screen_slider_speed",
                        "std" 		=> "5000",
                        "min" 		=> "1000",
                        "step"		=> "100",
                        "max" 		=> "10000",
                        "type" 		=> "sliderui" );

$of_options[] = array(  "name" => "",
                        "desc" => "",
                        "id" => "home_parallax_slider",
                        "std" => __("Parallax Slider Layout Options", THEME_LANGUAGE_DOMAIN),
                        "icon" => false,
                        "type" => "info");

$of_options[] = array( 	"name" 		=> __("Parallax Slider", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_parallax_slider_slider",
                        "std" 		=> "",
                        "type" 		=> "extended_slider"
                    );

$of_options[] = array(  "name"      => __("Parallax Slider Auto Play", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_parallax_slider_auto",
                        "std"       => "1",
                        "type" 		=> "switch" );

$of_options[] = array( 	"name" 		=> __("Parallax Slider Speed", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the slider speed in milliseconds", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_parallax_slider_speed",
                        "std" 		=> "5000",
                        "min" 		=> "1000",
                        "step"		=> "100",
                        "max" 		=> "10000",
                        "type" 		=> "sliderui" );

$of_options[] = array( "name" => "",
                        "desc" => "",
                        "id" => "fullscreen_image_parallax",
                        "std" => __("Full Screen Image Parallax Layout Options", THEME_LANGUAGE_DOMAIN),
                        "icon" => false,
                        "type" => "info");

$of_options[] = array( "name" => __("Image Background", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Upload image background", THEME_LANGUAGE_DOMAIN),
                        "id" => "fullscreen_image_parallax_background",
                        "std" => "",
                        "type" => "upload");

$of_options[] = array( "name" => __("Show Html Page Content", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Show the content of the home page.", THEME_LANGUAGE_DOMAIN),
                        "id" => "fullscreen_image_parallax_content",
                        "std" => 1,
                        "type" => "checkbox");

$of_options[] = array( 	"name" 		=> __("Text Slider", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "fullscreen_image_parallax_text_slider",
                        "std" 		=> "",
                        "type" 		=> "text_slider"
                    );

$of_options[] = array(  "name"      => __("Text Slider Transition Type", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the transition type.", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "fullscreen_image_parallax_text_slider_transition",
                        "std"       => "fade",
                        "type" 		=> "select",
                        "options" 	=> array("fade" => "Fade", "vertical" => "Vertical"));

$of_options[] = array( 	"name" 		=> __("Text Slider Speed", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the slider speed in milliseconds", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "fullscreen_image_parallax_text_slider_speed",
                        "std" 		=> "5000",
                        "min" 		=> "1000",
                        "step"		=> "100",
                        "max" 		=> "10000",
                        "type" 		=> "sliderui" );


$of_options[] = array( 	"name" 		=> __("Bullet Words", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "fullscreen_image_parallax_bullet_words",
                        "std" 		=> "",
                        "type" 		=> "text_slider"
                    );

$of_options[] = array(  "name" 		=> __("Text Color", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "fullscreen_image_parallax_text_color",
                        "std"       => "Light",
                        "type" 		=> "select",
                        "options" 	=> array("Light", "Dark"));

$of_options[] = array( "name" => __("Button Name", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "fullscreen_image_parallax_button_name",
                        "std" => "",
                        "type" => "text"
                    );

$of_options[] = array( "name" => __("Button URL", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "fullscreen_image_parallax_button_url",
                        "std" => "#",
                        "type" => "text"
                     );

$of_options[] = array( "name" => "",
                        "desc" => "",
                        "id" => "home_video",
                        "std" => __("Full Screen Video Layout Options", THEME_LANGUAGE_DOMAIN),
                        "icon" => false,
                        "type" => "info");

$of_options[] = array( "name" => __("Video Background URL", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Enter video background URL", THEME_LANGUAGE_DOMAIN),
                        "id" => "home_video_background",
                        "std" => "",
                        "type" => "text");

$of_options[] = array( "name" => __("Show Html Page Content", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Show the content of the home page.", THEME_LANGUAGE_DOMAIN),
                        "id" => "home_video_content",
                        "std" => 1,
                        "type" => "checkbox");

$of_options[] = array( 	"name" 		=> __("Text Slider", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_video_text_slider",
                        "std" 		=> "",
                        "type" 		=> "text_slider"
                    );

$of_options[] = array(  "name"      => __("Text Slider Transition Type", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the transition type.", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_video_text_slider_transition",
                        "std"       => "fade",
                        "type" 		=> "select",
                        "options" 	=> array("fade" => "Fade", "vertical" => "Vertical"));

$of_options[] = array( 	"name" 		=> __("Text Slider Speed", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the slider speed in milliseconds", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_video_text_slider_speed",
                        "std" 		=> "5000",
                        "min" 		=> "1000",
                        "step"		=> "100",
                        "max" 		=> "10000",
                        "type" 		=> "sliderui" );

$of_options[] = array( 	"name" 		=> __("Bullet Words", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_video_bullet_words",
                        "std" 		=> "",
                        "type" 		=> "text_slider"
                    );

$of_options[] = array(  "name" 		=> __("Text Color", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_video_text_color",
                        "std"       => "Light",
                        "type" 		=> "select",
                        "options" 	=> array("Light", "Dark"));

$of_options[] = array( "name" => __("Button Name", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "home_video_button_name",
                        "std" => "",
                        "type" => "text"
                     );

$of_options[] = array( "name" => __("Button URL", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "home_video_button_url",
                        "std" => "#",
                        "type" => "text"
                    );

$of_options[] = array(  "name" => "",
                        "desc" => "",
                        "id" => "home_moving_bknd",
                        "std" => __("Full Screen Moving Background Options", THEME_LANGUAGE_DOMAIN),
                        "icon" => false,
                        "type" => "info");

$of_options[] = array(  "name" => __("Image Background", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Upload image background", THEME_LANGUAGE_DOMAIN),
                        "id" => "home_moving_bknd_image",
                        "std" => get_template_directory_uri()."/images/collage.jpg",
                        "type" => "upload");

$of_options[] = array( "name" => __("Show Html Page Content", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Show the content of the home page.", THEME_LANGUAGE_DOMAIN),
                        "id" => "home_moving_bknd_content",
                        "std" => 1,
                        "type" => "checkbox");

$of_options[] = array( 	"name" 		=> __("Text Slider", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_moving_bknd_text_slider",
                        "std" 		=> "",
                        "type" 		=> "text_slider");

$of_options[] = array( 	"name" 		=> __("Bullet Words", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_moving_bknd_bullet_words",
                        "std" 		=> "",
                        "type" 		=> "text_slider");

$of_options[] = array(  "name" 		=> __("Moving Direction", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_moving_bknd_direction",
                        "std"       => "horizontal",
                        "type" 		=> "select",
                        "options" 	=> array("horizontal", "vertical", "diagonal"));

$of_options[] = array(  "name" 		=> __("Scroll Speed", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_moving_bknd_scroll_speed",
                        "std"       => "normal",
                        "type" 		=> "select",
                        "options" 	=> array("normal", "slow"));

$of_options[] = array(  "name" 		=> __("Background Repeat", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Use yes when using background image as a pattern", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_moving_bknd_repeat",
                        "std"       => "no",
                        "type" 		=> "select",
                        "options" 	=> array("yes", "no"));

$of_options[] = array(  "name" 		=> __("Text Color", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_moving_bknd_text_color",
                        "std"       => "Light",
                        "type" 		=> "select",
                        "options" 	=> array("Light", "Dark"));

$of_options[] = array(  "name" => __("Bouncing arrow URL", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "home_moving_bknd_arrow_url",
                        "std" => "#",
                        "type" => "text" );

$of_options[] = array(  "name" => __("Image Overlay Color", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "home_moving_bknd_color",
                        "std" => "",
                        "type" => "color");

$of_options[] = array(  "name" 		=> __("Image Overlay Opacity", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_moving_bknd_opacity",
                        "std"       => "0.8",
                        "type" 		=> "select",
                        "options" 	=> array("0.0", "0.1", "0.2", "0.3", "0.4", "0.5", "0.6", "0.7", "0.8", "0.9"));


$of_options[] = array(  "name" => "",
                        "desc" => "",
                        "id" => "home_overlay_slider",
                        "std" => __("Fullscreen Overlay Slider Layout Options", THEME_LANGUAGE_DOMAIN),
                        "icon" => false,
                        "type" => "info");

$of_options[] = array( "name" => __("Show Html Page Content", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Show the content of the home page.", THEME_LANGUAGE_DOMAIN),
                        "id" => "home_overlay_slider_content",
                        "std" => 1,
                        "type" => "checkbox");

$of_options[] = array( 	"name" 		=> __("Images Included In Slider", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> "",
                        "id" 		=> "home_overlay_slider_slider",
                        "std" 		=> "",
                        "type" 		=> "slider");

$of_options[] = array(  "name"      => __("Full Screen Overlay Slider Transition Type", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the transition type.", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_overlay_slider_transition",
                        "std"       => "fade",
                        "type" 		=> "select",
                        "options" 	=> array("fade" => "Fade", "scrollHorz" => "Horizontal"));

$of_options[] = array( 	"name" 		=> __("Full Screen Overlay Slider Speed", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the slider speed in milliseconds", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "home_overlay_slider_speed",
                        "std" 		=> "5000",
                        "min" 		=> "1000",
                        "step"		=> "100",
                        "max" 		=> "10000",
                        "type" 		=> "sliderui" );


// Header options
$of_options[] = array( "name" => __("Header Options", THEME_LANGUAGE_DOMAIN),
                    "type" => "heading");


$of_options[] = array( "name" => __("Select a Header Layout", THEME_LANGUAGE_DOMAIN),
					"desc" => "",
					"id" => "header_layout",
					"std" => "v3",
					"type" => "images",
					"options" => array(
						"v1" => get_template_directory_uri() ."/images/headers/header1.jpg",
						"v2" => get_template_directory_uri() ."/images/headers/header2.jpg",
						"v3" => get_template_directory_uri() ."/images/headers/header3.jpg",
						"v4" => get_template_directory_uri() ."/images/headers/header4.jpg"
					));

$of_options[] = array(  "name" => __("Logo - first version of the header layout", THEME_LANGUAGE_DOMAIN),
					    "desc" => __("Please choose an image file for your logo.", THEME_LANGUAGE_DOMAIN),
					    "id" => "logo_v1",
					    "std" => get_template_directory_uri()."/images/logo.png",
					    "mod" => "min",
					    "type" => "media" );

$of_options[] = array(  "name" => __("Logo - second version of the header layout", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Please choose an image file for your logo.", THEME_LANGUAGE_DOMAIN),
                        "id" => "logo_v2",
                        "std" => get_template_directory_uri()."/images/logo1.png",
                        "mod" => "min",
                        "type" => "media" );

$of_options[] = array(  "name" => __("Logo - third version of the header layout", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Please choose an image file for your logo.", THEME_LANGUAGE_DOMAIN),
                        "id" => "logo_v3",
                        "std" => get_template_directory_uri()."/images/logo2.png",
                        "mod" => "min",
                        "type" => "media" );

$of_options[] = array(  "name" => __("Logo - fourth version of the header layout", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Please choose an image file for your logo.", THEME_LANGUAGE_DOMAIN),
                        "id" => "logo_v4",
                        "std" => get_template_directory_uri()."/images/logo1.png",
                        "mod" => "min",
                        "type" => "media" );




$of_options[] = array(  "name" => __("Footer Options", THEME_LANGUAGE_DOMAIN),
					    "type" => "heading");


$of_options[] = array(  "name" => __("Footer Social Links", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Show social links in footer.", THEME_LANGUAGE_DOMAIN),
                        "id" => "footer_social_links",
                        "std" => 1,
                        "type" => "checkbox" );

$of_options[] = array(  "name"      => __("Footer Position", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Specify the position of the footer.", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "footer_position",
                        "std"       => "normal",
                        "type" 		=> "select",
                        "options" 	=> array("normal" => "Normal", "hidden" => "Hidden"));

$of_options[] = array( "name" => __("Copyright Text", THEME_LANGUAGE_DOMAIN),
                       "desc" => "",
                       "id" => "footer_text",
                       "std" => __("2014 &copy; newave. All rights reserved.", THEME_LANGUAGE_DOMAIN),
                       "type" => "textarea" );



// Social Sharing Links
$of_options[] = array( "name" => __("Social Links", THEME_LANGUAGE_DOMAIN),
					"type" => "heading");


$social_links = array(  "Facebook"      => "facebook",
                        "Twitter"       => "twitter",
                        "Linkedin"      => "linkedin",
                        "Behance"       => "behance",
                        "Deviantart"    => "deviantart",
                        "Dribble"       => "dribble",
                        "Flickr"        => "flickr",
                        "Google+"       => "google",
                        "Lastfm"        => "lastfm",
                        "Picasa"        => "picasa",
                        "Pinterest"     => "pinterest",
                        "RSS"           => "rss",
                        "Skype"         => "skype",
                        "Stumble"       => "stumble",
                        "Vimeo"         => "vimeo",
                        "Youtube"       => "youtube",
                        "Instagram"     => "instagram"
                     );

foreach( $social_links as $key => $value ){

    $of_options[] = array(  "name" => $key,
                            "desc" => "",
                            "id" => "social_link_" . $value,
                            "std" => "",
                            "type" => "text" );
}

// Portfolio options
$of_options[] = array(  "name" => __("Portfolio Options", THEME_LANGUAGE_DOMAIN),
                        "type" => "heading");

$of_options[] = array(  "name" => __("Number Of Portfolio Columns", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("How many columns of portfolio items", THEME_LANGUAGE_DOMAIN),
                        "id" => "portfolio_columns",
                        "std" => "4",
                        "type" => "select",
                        "options" => array('3' => '3', '4' => '4', '5' => '5') );

$of_options[] = array( "name" => __("Show Portfolio Filters", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Show portfolio filter in portfolio section(s).", THEME_LANGUAGE_DOMAIN),
                        "id" => "show_portfolio_filter",
                        "std" => 1,
                        "type" => "switch");

$of_options[] = array(  "name" => __("Number Of Featured Images For Portfolio Items", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Enter a value between 1 and 50", THEME_LANGUAGE_DOMAIN),
                        "id" => "portfolio_featured_images",
                        "std" => "5",
                        "type" => "text" );

$of_options[] = array(  "name" => __("Maximum Number Of Portfolio Items", THEME_LANGUAGE_DOMAIN),
                        "desc" => "Maximum number of portfolio items to show in the portfolio section(s). Leave empty for no limit.",
                        "id" => "max_portfolio_items",
                        "std" => "",
                        "type" => "text" );

$of_options[] = array(  "name"      => __("Hover Effect", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Hover effect for portfolio items in portfolio section.", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "portfolio_hover_effect",
                        "std"       => "overlay",
                        "type" 		=> "select",
                        "options" 	=> array("overlay" => "Overlay", "slide" => "Slide"));

$of_options[] = array( "name" => __("Social Sharing Box", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Enable social sharing portfolio posts.", THEME_LANGUAGE_DOMAIN),
                        "id" => "portfolio_social",
                        "std" => 1,
                        "type" => "checkbox");

$of_options[] = array( "name" => __("Share on Facebook", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Share portfolio item on Facebook.", THEME_LANGUAGE_DOMAIN),
                        "id" => "portfolio-facebook-sharing",
                        "std" => 1,
                        "type" => "checkbox");

$of_options[] = array( "name" => __("Share on Twitter", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Share portfolio item on Twitter.", THEME_LANGUAGE_DOMAIN),
                        "id" => "portfolio-twitter-sharing",
                        "std" => 1,
                        "type" => "checkbox");

$of_options[] = array( "name" => __("Share on Pinterest", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("Share portfolio item on Pinterest.", THEME_LANGUAGE_DOMAIN),
                        "id" => "portfolio-pinterest-sharing",
                        "std" => 1,
                        "type" => "checkbox");

$of_options[] = array(  "name" => __("Portfolio Sharing text", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "portfolio_sharing_text",
                        "std" => __("Did you love this project? Please share it with the world and we will love you forever!", THEME_LANGUAGE_DOMAIN),
                        "type" => "text");

$of_options[] = array(  "name" => __("Custom Slug", THEME_LANGUAGE_DOMAIN),
                        "desc" => __("If you want your portfolio post type to have a custom slug in the url, please enter it here.  You will still have to refresh your permalinks after saving this! This is done by going to Settings > Permalinks and clicking save.", THEME_LANGUAGE_DOMAIN),
                        "id" => "portfolio_rewrite_slug",
                        "std" => "",
                        "type" => "text");

// Blog options
$of_options[] = array( "name" => __("Blog Options", THEME_LANGUAGE_DOMAIN),
					"type" => "heading");

$of_options[] = array( "name" => __("Post Title", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Show the post title.", THEME_LANGUAGE_DOMAIN),
					"id" => "blog_post_title",
					"std" => 1,
					"type" => "checkbox");

$of_options[] = array( "name" => __("Author Info Box", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Show the author info box below posts.", THEME_LANGUAGE_DOMAIN),
					"id" => "author_info",
					"std" => 1,
					"type" => "checkbox");

$of_options[] = array( "name" => __("Comments", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Show link to comments.", THEME_LANGUAGE_DOMAIN),
					"id" => "blog_comments",
					"std" => 1,
					"type" => "checkbox");

$of_options[] = array( "name" => __("Excerpt or Full Blog Content", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Show excerpt or full blog content on archive / blog pages", THEME_LANGUAGE_DOMAIN),
					"id" => "content_length",
					"std" => "Full Content",
					"type" => "select",
					"options" => array('excerpt' => 'Excerpt', 'full' => 'Full Content') );

$of_options[] = array( "name" => __("Excerpt Length", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Input the number of words you want to cut from the content to be the excerpt of search and archive page.", THEME_LANGUAGE_DOMAIN),
					"id" => "excerpt_length_blog",
					"std" => "55",
					"type" => "text");

$of_options[] = array(  "name" => __("Number Of Featured Images For Blog Posts", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Enter a value between 1 and 50", THEME_LANGUAGE_DOMAIN),
                    "id" => "blog_featured_images",
                    "std" => "5",
                    "type" => "text" );
					
$of_options[] = array(  "name" => __("Number Of Posts Displayed In Blog Sections", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Enter a numeric value greater than zero", THEME_LANGUAGE_DOMAIN),
                    "id" => "blog_section_posts",
                    "std" => "6",
                    "type" => "text" );					

					
// Contact options					
$of_options[] = array( "name" => __("Contact Options", THEME_LANGUAGE_DOMAIN),
					"type" => "heading");

$of_options[] = array( "name" =>__( "Company Name", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Your company name", THEME_LANGUAGE_DOMAIN),
					"id" => "gmap_company_name",
					"std" => "NEWAVE",
					"type" => "text");

$of_options[] = array( "name" => __("Company Info", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Your company info", THEME_LANGUAGE_DOMAIN),
					"id" => "gmap_company_info",
					"std" => __("Here we are. Come to drink a coffee!", THEME_LANGUAGE_DOMAIN),
					"type" => "text");

$of_options[] = array( 	"name" 		=> __("Show map", THEME_LANGUAGE_DOMAIN),
                        "desc" 		=> __("Show map in contact section.", THEME_LANGUAGE_DOMAIN),
                        "id" 		=> "contact_show_map",
                        "std" 		=> 1,
                        "type" 		=> "switch" );

$of_options[] = array( "name" => __("Google Map Address", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Example: 775 New York Ave, Brooklyn, Kings, New York 11203", THEME_LANGUAGE_DOMAIN),
					"id" => "gmap_address",
					"std" => __("775 New York Ave, Brooklyn, Kings, New York 11203", THEME_LANGUAGE_DOMAIN),
					"type" => "text");

$of_options[] = array( "name" => __("Email Address", THEME_LANGUAGE_DOMAIN),
					"desc" => "",
					"id" => "email_address",
					"std" => "",
					"type" => "text");

$of_options[] = array( "name" => __("Google Map Width", THEME_LANGUAGE_DOMAIN),
					"desc" => __("(in pixels or percentage, e.g.:100% or 100px)", THEME_LANGUAGE_DOMAIN),
					"id" => "gmap_width",
					"std" => "100%",
					"type" => "text");

$of_options[] = array( "name" => __("Google Map Height", THEME_LANGUAGE_DOMAIN),
					"desc" => __("(in pixels, e.g.: 100px)", THEME_LANGUAGE_DOMAIN),
					"id" => "gmap_height",
					"std" => "500px",
					"type" => "text");

$of_options[] = array( "name" => __("Map Marker", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Please choose an image file for the marker.", THEME_LANGUAGE_DOMAIN),
					"id" => "gmap_marker_icon",
					"std" => get_template_directory_uri()."/images/marker.png",
					"mod" => "min",
					"type" => "media");
					
$of_options[] = array( "name" => __("Map Zoom Level", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Higher number will be more zoomed in", THEME_LANGUAGE_DOMAIN),
					"id" => "map_zoom_level",
					"std" => "8",
					"type" => "text");


// Typography options
$of_options[] = array( "name" => __("Typography", THEME_LANGUAGE_DOMAIN),
				"type" => "heading");

$of_options[] = array( "name" => __("Select Body Font Family", THEME_LANGUAGE_DOMAIN),
					"desc" => __("Select a font family for body text. Leave empty to use default.", THEME_LANGUAGE_DOMAIN),
					"id" => "google_font_body",
                    "preview" => array(
                        "text" => __("This is my preview text!", THEME_LANGUAGE_DOMAIN), //this is the text from preview box
                        "size" => "28px" //this is the text size from preview box
                    ),
					"std" => "Select a font",
					"type" => "select_google_font",
					"options" => $google_fonts);

$of_options[] = array( "name" => __("Enter Body Font Family if it's not in the list", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Cannot find the font in the list? You can enter it here!", THEME_LANGUAGE_DOMAIN),
                    "id" => "custom_google_font_body",
                    "std" => "",
                    "type" => "text");

$of_options[] = array( "name" => __("Select Htag Font Family", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Select a font family for htag text. Leave empty to use default.", THEME_LANGUAGE_DOMAIN),
					"id" => "google_font_htag",
                    "preview" => array(
                                       "text" => __("This is my preview text!", THEME_LANGUAGE_DOMAIN), //this is the text from preview box
                                       "size" => "28px" //this is the text size from preview box
                                 ),
					"std" => "Select a font",
					"type" => "select_google_font",
					"options" => $google_fonts);

$of_options[] = array( "name" => __("Enter Htag Font Family if it's not in the list", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Cannot find the font in the list? You can enter it here!", THEME_LANGUAGE_DOMAIN),
                    "id" => "custom_google_font_htag",
                    "std" => "",
                    "type" => "text");


// Styling options    
$of_options[] = array( "name" => __("Styling Options", THEME_LANGUAGE_DOMAIN),
					"type" => "heading");

$of_options[] = array(  "name" => __("Select Color Skin", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "color_skin",
                        "std" => "black",
                        "type" => "select",
                        "options" => array(
                            'black'     => 'Black',
                            'green'     => 'Green',
                            'red'       => 'Red',
                            'blue'      => 'Blue',
                            'yellow'    => 'Yellow',
                            'custom'    => 'Custom Color'
                        ));

$of_options[] = array(  "name" => __("Custom Color", THEME_LANGUAGE_DOMAIN),
                        "desc" => "",
                        "id" => "custom_color",
                        "std" => "",
                        "type" => "color");

$of_options[] = array( "name" => __("Custom CSS", THEME_LANGUAGE_DOMAIN),
                    "desc" => __("Paste your css code. Any custom CSS from the user should go in this field, it will override the theme CSS. Do not include tags or any html tag in this field.", THEME_LANGUAGE_DOMAIN),
                    "id" => "custom_css",
                    "std" => "",
                    "type" => "textarea");

// 404 page options
$of_options[] = array( "name" => __("Error Page Options", THEME_LANGUAGE_DOMAIN),
			"type" => "heading");

$of_options[] = array( "name" => __("Title", THEME_LANGUAGE_DOMAIN),
			"desc" => __("404 page title", THEME_LANGUAGE_DOMAIN),
			"id" => "404_title",
			"std" => "404",
			"type" => "text");

$of_options[] = array( "name" => __("Subtitle", THEME_LANGUAGE_DOMAIN),
			"desc" => __("404 page subtitle", THEME_LANGUAGE_DOMAIN),
			"id" => "404_subtitle",
			"std" => __("The page that you requested doesn't exist. You may want to return home and start again. Sorry :(", THEME_LANGUAGE_DOMAIN),
			"type" => "text");

$of_options[] = array( "name" => __("Escape message", THEME_LANGUAGE_DOMAIN),
            "desc" => "",
            "id" => "404_escape",
            "std" => __("Back to home", THEME_LANGUAGE_DOMAIN),
            "type" => "text");


// Backup Options
$of_options[] = array( "name" => __("Backup Options", THEME_LANGUAGE_DOMAIN),
					"type" => "heading");
					
$of_options[] = array( "name" => __("Backup and Restore Options", THEME_LANGUAGE_DOMAIN),
                    "id" => "of_backup",
                    "std" => "",
                    "type" => "backup",
					"desc" => __("You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.", THEME_LANGUAGE_DOMAIN),
					);

$of_options[] = array( "name" => __("Transfer Theme Options Data", THEME_LANGUAGE_DOMAIN),
                        "id" => "of_transfer",
                        "std" => "",
                        "type" => "transfer",
                        "desc" => __("You can transfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click Import Options", THEME_LANGUAGE_DOMAIN)
                    );

    }
}

?>