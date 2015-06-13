<?php
/**
 * Template Name: Single State
 * Description: A Page Template to display page content without the sidebar.
 *
 * @package  WellThemes
 * @file     single-state.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
<?php get_header(); ?>

<div id="content" class="full-content">
		<header class="entry-header">
                        <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                        <h1>Información para <?php the_title(); ?></h1>
                        <?php } else { ?>
			<h1>Information For <?php the_title(); ?></h1>
                        <?php } ?>
		</header><!-- /entry-header -->
    <?php
        $state_info_link = get_cfc_field('test-information', 'state-link');
        $state_info_link_2 = get_cfc_field('test-information', 'state-link-2');
        $transcript_link = get_cfc_field('test-information', 'transcript-request');
        $transcript_link_2 = get_cfc_field('test-information', 'transcript-request-old-link');

        $ged = array();
        $hiset = array();
        $tasc = array();

        $statefinaid = array();
        $scholarships = array();

        foreach( get_cfc_meta( 'available-test' ) as $key => $value ){
            $name = get_cfc_field( 'available-test','test-type', false, $key );
            $link = get_cfc_field( 'available-test','info-link', false, $key );

            if ($name === 'ged') {
                if ($link === 'Default') {
                    $link = 'http://www.ged.com/';
                }
                array_push($ged, $link);
            } else if ($name === 'hiset') {
                if ($link === 'Default') {
                    $link = 'http://hiset.ets.org/';
                }
                array_push($hiset, $link);
            } else {
                if ($link === 'Default') {
                    $link = 'http://www.tasctest.com/';
                }
                array_push($tasc, $link);
            }
        }

        $one_stop_app_link = get_cfc_field('social-services', 'one-stop-app-link');
        $one_stop_app_link_2 = get_cfc_field('social-services', 'one-stop-app-link-2');
        $non_profit_link = get_cfc_field('social-services', 'non-profit-agency-link');

        $financial_aid_available = false;
        foreach( get_cfc_meta( 'financial-aid' ) as $key => $value ){
            $financial_aid_available = true;
            $type = get_cfc_field( 'financial-aid', 'type-of-aid', false, $key );
            $desc = get_cfc_field( 'financial-aid', 'description', false, $key );
            $name = get_cfc_field( 'financial-aid', 'name', false, $key );

            if ($type === 'statefinaid') {
                array_push($statefinaid, $desc);
            } else if ($type === 'scholarships') {
                array_push($scholarships, $desc);
            }
        }

        if (has_post_thumbnail( $post->ID ) ) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
        }
     ?>

    <a style="font-size:17px;" name="top"></a>
    <?php if (isset($image)) { ?>
        <img class="alignright wp-image-5821 " src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title($post->ID); ?>" width="400" height="470" />
    <?php } ?>
<!--    <h3>Navigation:</h3>
    <ol>
        <li><a style="font-size:17px;" href="#tests">High School Equivalency Exams</a></li>
        <li><a style="font-size:17px;" href="#programs">Youth Programs And Adult Education</a></li>
        <?php if ($one_stop_app_link || $one_stop_app_link_2 || $non_profit_link) { ?><li><a style="font-size:17px;" href="#social">Social Services</a></li><?php } ?>
    </ol>-->
    <?php $test_name_string = '';
          $tests = array();
        if (count($ged) > 0) {
            array_push($tests, 'GED');
        }
        if (count($hiset) > 0) {
            array_push($tests, 'HiSET');
        }
        if (count($tasc) > 0) {
            array_push($tests, 'TASC');
        } ?>

    <?php $test_name_string = '';
          $tests = array();
        if (count($ged) > 0) {
            array_push($tests, 'GED');
        }
        if (count($hiset) > 0) {
            array_push($tests, 'HiSET');
        }
        if (count($tasc) > 0) {
            array_push($tests, 'TASC');
        } 
 
        foreach($tests as $curr_test) {
            $test_name_string = (strlen($test_name_string) > 0 ? $test_name_string . ', ' . $curr_test : $curr_test);
        }

       ?>
<?php if (ICL_LANGUAGE_CODE == 'es') { ?>
<p>Casi 800.000 personas presentan cada año el examen de equivalencia a la preparatoria colocándolo como la segunda manera más popular de obtener la credencial de la preparatoria después de la obtención de un diploma tradicional de preparatoria. En <?php single_post_title(); ?>, puedes tomar el <?php echo $test_name_string; ?>, uno de los exámenes equivalentes de la preparatoria. Para ello, chequea primero el sitio web del Departamento de Educación de <?php single_post_title(); ?> para informarte de sus políticas en torno al asunto. Entonces, inscríbete en la web del <?php echo $test_name_string; ?> para tomar dicho examen.</p>
<p>También hay otros programas que permiten a los estudiantes conseguir los diplomas de la preparatoria. Muchos de esos programas, incluso las escuelas en línea, están manejados por los distritos escolares o otras agencias estatales. Es importante que te pongas en contacto con la agencia correcta para evitar ser estafado.</p>
<p>Los institutos de formación superior locales (los community college), las oficinas de empleo, y las agencias sin ánimo de lucro proporcionan clases particulares gratis, becas universitarias, capacitación gratis para el trabajo, además de asesoramiento universitario y otros servicios para los aspirantes al <?php echo $test_name_string; ?> y los jóvenes de bajos recursos. Ver los enlaces a  continuación para aprovechar de estos recursos.</p>
<?php } else { ?>
    <p>Nearly 800,000 people take the high school equivalency exam each year making it the second most popular way of earning a high school credential after the traditional high school diploma. In <?php single_post_title(); ?>, you can take the <?php echo $test_name_string; ?> as a high school equivalency exam. To take the test, first, check the <?php single_post_title(); ?> State office website to learn about the policies. After that, register to take your <?php echo $test_name_string; ?> on the test website.</p>
<p>There are other programs that allow students get high school diplomas too. Many of these programs, including online schools are run by the school districts or other state agencies. It’s important you contact the right agency to avoid getting scammed.</p>
<p>Free tutoring, college scholarships, free job training, free counseling and other resources are available for <?php echo $test_name_string; ?> seekers and low-income young adults through the local community colleges, the employment offices, and non-profit agencies.  See the links below to take advantage of these resources.</p>
<?php } ?>
    <p>&nbsp;</p>

    <a style="font-size:17px;" name="tests"></a>
    <!--<p><a style="font-size:17px;" href="#top">Go to top</a></p>-->
<?php if (ICL_LANGUAGE_CODE == 'es') { ?>
    <h2>Examen de Equivalencia a la Preparatoria  <a style="font-size:12px;" href="http://www.gedboard.com/es/como-conseguir-el-ged-tasc-hiset/"><span class="fa-stack fa-lg" style="margin-bottom:8px;">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-question fa-stack-1x fa-inverse"></i>
</span></a></h2>
<?php } else { ?>
    <h2>High School Equivalency Exam  <a style="font-size:12px;" href="http://www.gedboard.com/get-ged-tasc-hiset/"><span class="fa-stack fa-lg" style="margin-bottom:8px;">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-question fa-stack-1x fa-inverse"></i>
</span></a></h2>
<?php } ?>
<p>
    <?php if ($state_info_link || $state_info_link_2) { ?>
        <?php if ($state_info_link) { ?>
            <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
	            <a style="font-size:17px;" href="<?php echo $state_info_link; ?>" target="_blank">Pulsar acá para el Sitio Web del Departamento de Educación del Estado de  <?php single_post_title(); ?></a><br/>
	    <?php } else { ?>
            <a style="font-size:17px;" href="<?php echo $state_info_link; ?>" target="_blank">Click here to go to the <?php single_post_title(); ?> State Office Website</a><br/>
            <?php } ?>
        <?php } ?>
        <?php if ($state_info_link_2) { ?>
            <a style="font-size:17px;" href="<?php echo $state_info_link_2; ?>" target="_blank">Click here for additional information</a><br/>
        <?php } ?>
    <?php } ?>

    <?php if ($transcript_link || $transcript_link_2) { ?>
        <?php if ($transcript_link) { ?>
            <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
	            <a style="font-size:17px;" href="<?php echo $transcript_link; ?>" target="_blank">Pulsar acá para el Sitio Web de Solicitud de Transcripción</a><br/>
	    <?php } else { ?>
            <a style="font-size:17px;" href="<?php echo $transcript_link; ?>" target="_blank">Click here to go to the Transcript Request Website</a><br/>
            <?php } ?>
        <?php } ?>
        <?php if ($transcript_link_2) { ?>
          <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
	            <a style="font-size:17px;" href="<?php echo $transcript_link_2; ?>" target="_blank">Pulsar acá para las Transcripciones Viejas</a><br/>
	    <?php } else { ?>
            <a style="font-size:17px;" href="<?php echo $transcript_link_2; ?>" target="_blank">Click here for older transcripts</a><br/>
            <?php } ?>
        <?php } ?>
    <?php } ?>

    <?php if (count($ged) > 0 || count($hiset) > 0 || count($tasc) > 0) { ?>
        <?php if (count($ged) > 0) { ?>
            <?php foreach($ged as $info_link) { ?>
                <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                <a style="font-size:17px;" href="<?php echo $info_link; ?>" target="_blank">Pulsar acá para Inscribirte en GED</a><br/>
		<?php } else { ?>
                <a style="font-size:17px;" href="<?php echo $info_link; ?>" target="_blank">Click here for GED Registration</a><br/>
                <?php } ?>
            <?php } ?>

        <?php } ?>
        <?php if (count($hiset) > 0) { ?>
            <?php foreach($hiset as $info_link) { ?>
                <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                <a style="font-size:17px;" href="<?php echo $info_link; ?>" target="_blank">Pulsar acá para Inscribirte en HiSET</a><br/>
		<?php } else { ?>
                <a style="font-size:17px;" href="<?php echo $info_link; ?>" target="_blank">Click here for HiSET Registration</a><br/>
                <?php } ?>
            <?php } ?>

        <?php } ?>
        <?php if (count($tasc) > 0) { ?>
            <?php foreach($tasc as $info_link) { ?>
                <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                <a style="font-size:17px;" href="<?php echo $info_link; ?>" target="_blank">Pulsar acá para Inscribirte en TASC</a><br/>
		<?php } else { ?>
                <a style="font-size:17px;" href="<?php echo $info_link; ?>" target="_blank">Click here for TASC Registration</a><br/>
                <?php } ?>

            <?php } ?>
        <?php } ?>
    <?php } ?>
    </p>

    <p>&nbsp;</p>

    <p>&nbsp;</p>

    <a style="font-size:17px;" name="programs"></a>
    <!--<p><a style="font-size:17px;" href="#top">Go to top</a></p>-->
    <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
        <h2>Realizar Preparatoria y Otros Programas de Educación</h2>
    <?php } else { ?>
        <h2>High School Completion And Other Education Programs</h2>
    <?php } ?>
    <?php
    foreach( get_cfc_meta( 'education-programs' ) as $key => $value ){
        $type = get_cfc_field( 'education-programs','program-type', false, $key );
        $name = get_cfc_field( 'education-programs','name-overwrite', false, $key );
        $desc = get_cfc_field( 'education-programs','description-overwrite', false, $key );
        $link1 = get_cfc_field( 'education-programs','link-1-if-any', false, $key );
        $link2 = get_cfc_field( 'education-programs','link-2-if-any', false, $key );
        $link3 = get_cfc_field( 'education-programs','link-3-if-any', false, $key );

        if (!$name) {
            if ($type === 'alternative-learning') {
                $name = (ICL_LANGUAGE_CODE == 'es') ? 'Programas Alternativos de Educación' : 'Alternative Learning Programs';
            } else if ($type === 'school-districts') {
                $name = (ICL_LANGUAGE_CODE == 'es') ? 'Distritos Escolares' : 'School Districts';
            } else if ($type === 'community-colleges') {
                $name = (ICL_LANGUAGE_CODE == 'es') ? 'Institutos de Formación Profesional (los Community Colleges)' : 'Community Colleges';
            } else if ($type === 'unemployment-offices') {
                $name = (ICL_LANGUAGE_CODE == 'es') ? 'Oficinas de Empleo' : 'Employment Offices';
            }
        }

        if (!$desc) {
            if ($type === 'alternative-learning') {
                $desc = '';
            } else if ($type === 'school-districts') {
                $desc = (ICL_LANGUAGE_CODE =='es') ? 'Los distritos escolares ofrecen varios programas de realización de la preparatoria para estudiantes de hasta 21 años de edad.' :'School districts offer various high school completion programs for students up to 21 years old.';
            } else if ($type === 'community-colleges') {
                $desc = '';
            } else if ($type === 'unemployment-offices') {
                $desc = (ICL_LANGUAGE_CODE == 'es') ? 'Las oficinas de empleo locales brindan varios programas dirigidos a los jóvenes de bajos recursos (de 14 a 21 años) que necesitan auxilio adicional para completar un programa de educación, o para conseguir y conservar un trabajo. Estos servicios para los jóvenes incluyen clases de apoyo escolar, escuela alternativa, trabajos de verano, oportunidades de prácticas profesionales, formación laboral, orientación escolar, y más.' : 'Local employment offices provide various youth programs to low-income youth (age 14-21) who require additional assistance to complete an educational program, or to secure and hold employment. Youth services include tutoring, alternative school, summer employment, internships, job training, counseling and more.';
            }
        }

        $clickText = (ICL_LANGUAGE_CODE == 'es') ? 'Pulsar acá para ' : 'Click here for ';

        if ($link1 || $link2 || $link3) { ?>
            <h3><?php echo $name; ?></h3>
            <p style="font-size:17px;"><?php if ($desc !== '') { ?> <?php echo $desc; ?><br/> <?php } ?>
            <?php if ($link1) { ?>
                <a style="font-size:17px;" href="<?php echo $link1; ?>" target="_blank"><?php echo $clickText; echo $name; ?></a><br/>
            <?php } if ($link2) { ?>
                <a style="font-size:17px;" href="<?php echo $link2; ?>" target="_blank"><?php echo $clickText; echo $name; ?></a><br/>
            <?php } if ($link3) { ?>
                <a style="font-size:17px;" href="<?php echo $link3; ?>" target="_blank"><?php echo $clickText; echo $name; ?></a><br/>
            <?php } ?>
            </p>
        <?php }
    } ?>

    <p>&nbsp;</p>

    <p>&nbsp;</p>

	<?php if ($financial_aid_available) { ?>
            <a style="font-size:17px;" name="finaid"></a>
            <!--<p><a style="font-size:17px;" href="#top">Go to top</a></p>-->
<?php if (ICL_LANGUAGE_CODE == 'es') { ?>
	    <h2>Becas  <a style="font-size:12px;" href="http://www.gedboard.com/es/consejos-sobre-becas-para-titulares-del-ged/"><span class="fa-stack fa-lg" style="margin-bottom:8px;">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-question fa-stack-1x fa-inverse"></i>
</span></a></h2>
<?php } else { ?>
	    <h2>Scholarships  <a style="font-size:12px;" href="http://www.gedboard.com/scholarship-tips-ged-graduates/"><span class="fa-stack fa-lg" style="margin-bottom:8px;">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-question fa-stack-1x fa-inverse"></i>
</span></a></h2>
<?php } ?>
            <?php if (count($scholarships) > 0) { ?>
                <h3>Merit-Based Scholarships for GED Graduates</h3>
                <?php foreach ($scholarships as $finaid) { ?>
                    <div style="font-size:17px;">
                    <?php echo $finaid; ?>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if (count($statefinaid) > 0) { ?>
                <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                <h3>Becas por Necesidad Financiera o por Mérito</h3>
                <?php } else { ?>
                <h3>Need-Based and Merit-Based State Scholarships</h3>
                <?php } ?>
                <?php foreach ($statefinaid as $finaid) { ?>
                    <div style="font-size:17px;">
                    <?php echo $finaid; ?>
                    </div>
                <?php } ?>
            <?php } ?>
                
            <p>&nbsp;</p>

            <p>&nbsp;</p>
        <?php } ?> 

    <?php if ($one_stop_app_link || $one_stop_app_link_2 || $non_profit_link) { ?>
        <a style="font-size:17px;" name="social"></a>
        <!--<p><a style="font-size:17px;" href="#top">Go to top</a></p>-->
<?php if (ICL_LANGUAGE_CODE == 'es') { ?>
        <h2>Servicios Sociales  <a style="font-size:12px;" href="http://www.gedboard.com/es/te-niegues-una-mano-de-ayuda/"><span class="fa-stack fa-lg" style="margin-bottom:8px;">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-question fa-stack-1x fa-inverse"></i>
</span></a></h2>
<?php } else { ?>
        <h2>Social Services  <a style="font-size:12px;" href="http://www.gedboard.com/dont-refuse-helping-hand/"><span class="fa-stack fa-lg" style="margin-bottom:8px;">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-question fa-stack-1x fa-inverse"></i>
</span></a></h2>
<?php } ?>
        <p>
        <?php if ($one_stop_app_link) { ?>
         <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
            <a style="font-size:17px;" href="<?php echo $one_stop_app_link; ?>" target="_blank">Pulsar acá para Portal en línea de Aplicaciones Directas</a><br/>
         <?php } else { ?>
            <a style="font-size:17px;" href="<?php echo $one_stop_app_link; ?>" target="_blank">Click here for the One stop Application</a><br/>
         <?php } ?>
        <?php } if ($one_stop_app_link_2) { ?>
            <a style="font-size:17px;" href="<?php echo $one_stop_app_link_2; ?>" target="_blank">Click here for Additional Services</a><br/>
        <?php } if ($non_profit_link) {
            if ($non_profit_link === 'Default') {
                $non_profit_link = 'http://www.communityactionpartnership.com/index.php?option=com_spreadsheets&view=search&spreadsheet=cap&Itemid=188';
            } ?>
            <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
            <a style="font-size:17px;" style="font-size:17px;" href="<?php echo $non_profit_link; ?>" target="_blank">Pulsar acá para Agencias sin ánimo de Lucro</a><br/>
            <?php } else { ?>
            <a style="font-size:17px;" style="font-size:17px;" href="<?php echo $non_profit_link; ?>" target="_blank">Click here for Non Profit Agencies</a><br/>
            <?php } ?>
        <?php } ?>
        </p>

        <p>&nbsp;</p>

        <p>&nbsp;</p>

        <?php } ?>

</div><!-- /content -->
	
<?php get_footer(); ?>