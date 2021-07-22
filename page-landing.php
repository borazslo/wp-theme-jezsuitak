<?php
/**


* Template Name: Landing Page


*/

 ?>
 <!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>

<?php if ( $fb = get_theme_mod('facebookpixel_id') ): ?>
<!-- Facebook Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window,document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '<?php echo $fb ?>'); 
		fbq('track', 'PageView');
	</script>
	<noscript>
		<img height="1" width="1" 
			src="https://www.facebook.com/tr?id=<?php echo $fb ?>&ev=PageView&noscript=1"/>
	</noscript>
<!-- End Facebook Pixel Code -->
<?php endif; ?>


<link rel="apple-touch-icon" sizes="57x57" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/favicon-16x16.png">
<link rel="manifest" href="//jezsuita.hu/wp-content/themes/jezsuitak/images/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

</head>
<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/hu_HU/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="page" class="site">
	<div class="site-inner">

		<div id="content" class="site-content">


<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->


<?php get_footer(); ?>
