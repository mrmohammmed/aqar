<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <header class="topbar">
    <div class="topbar__inner container page-width content-width navbar navbar-expand-lg">
      <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' - الرئيسية' ); ?>">
        <img src="<?php echo esc_url( get_theme_mod( 'aqar_contact_logo', get_theme_file_uri( '/assets/images/logo.png' ) ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
      </a>
      <nav class="topbar__nav navbar-collapse" id="mainNavigation" aria-label="القائمة الرئيسية">
        <?php
        wp_nav_menu(
          array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'navbar-nav d-flex flex-row gap-3',
            'fallback_cb'    => false,
            'depth'          => 1,
          )
        );
        ?>
      </nav>
      <div class="topbar__actions">
        <a class="add-button" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">استشاره مجانيه</a>
      </div>
    </div>
































  </header>