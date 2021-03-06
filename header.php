<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

?><!DOCTYPE html>
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
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentysixteen' ); ?></a>

		<header id="masthead" class="site-header" role="banner">


<div class="site-slider">
				<div class="site-branding">
					<?php twentysixteen_the_custom_logo(); ?>

					<?php if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php endif;

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo $description; ?></p>
					<?php endif; ?>

				</div><!-- .site-branding -->

			<div class="site-header-main">

				<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'social' ) ) : ?>
					<button id="menu-toggle" class="menu-toggle"><?php _e( 'Menu', 'twentysixteen' ); ?></button>

					<div id="site-header-menu" class="site-header-menu">
						<?php if ( has_nav_menu( 'primary' ) ) : ?>
							<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'primary',
										'menu_class'     => 'primary-menu',
									 ) );
								?>
							</nav><!-- .main-navigation -->
						<?php endif; ?>

						<?php if ( has_nav_menu( 'social' ) ) : ?>
							<nav id="social-navigation" class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Social Links Menu', 'twentysixteen' ); ?>">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'social',
										'menu_class'     => 'social-links-menu',
										'depth'          => 1,
										'link_before'    => '<span class="screen-reader-text">',
										'link_after'     => '</span>',
									) );
								?>
							</nav><!-- .social-navigation -->
						<?php endif; ?>
					</div><!-- .site-header-menu -->
				<?php endif; ?>
			</div><!-- .site-header-main -->

			

			</div>


			<?php if ( get_header_image() ) : ?>
				<?php
					/**
					 * Filter the default twentysixteen custom header sizes attribute.
					 *
					 * @since Twenty Sixteen 1.0
					 *
					 * @param string $custom_header_sizes sizes attribute
					 * for Custom Header. Default '(max-width: 709px) 85vw,
					 * (max-width: 909px) 81vw, (max-width: 1362px) 88vw, 1200px'.
					 */
					$custom_header_sizes = apply_filters( 'twentysixteen_custom_header_sizes', '(max-width: 709px) 85vw, (max-width: 909px) 81vw, (max-width: 1362px) 88vw, 1200px' );
				?>
				<div class="header-image">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php header_image(); ?>" srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_custom_header()->attachment_id ) ); ?>" sizes="<?php echo esc_attr( $custom_header_sizes ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
					</a>
				</div><!-- .header-image -->
			<?php endif; // End header image check. ?>
</div>
		</header><!-- .site-header -->

		<div id="content" class="site-content">
