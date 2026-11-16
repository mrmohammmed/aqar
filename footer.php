  <div class="floating-tools" aria-label="تواصل سريع">
    <?php
    $aqar_phone    = preg_replace( '/[^0-9+]/', '', get_theme_mod( 'aqar_phone', '' ) );
    $aqar_whatsapp = preg_replace( '/\D+/', '', get_theme_mod( 'aqar_whatsapp', '' ) );
    ?>
    <?php if ( $aqar_phone ) : ?>
      <a class="floating-tool floating-tool--dark" href="tel:<?php echo esc_attr( $aqar_phone ); ?>" aria-label="اتصال"><i class="fas fa-phone"></i></a>
    <?php endif; ?>
    <?php if ( $aqar_whatsapp ) : ?>
      <a class="floating-tool floating-tool--green" href="<?php echo esc_url( 'https://wa.me/' . $aqar_whatsapp ); ?>" target="_blank" rel="noopener noreferrer" aria-label="واتساب"><i class="fab fa-whatsapp"></i></a>
    <?php endif; ?>
  </div>

  <section class="footer__cta">
    <div class="footer__cta-inner container page-width content-width">
       <p><?php echo esc_html( get_theme_mod( 'aqar_footer_cta', 'هل تبحث عن عقارك القادم؟ فريق نيو عقار جاهز لمساعدتك.' ) ); ?></p>
      <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="footer__cta-btn">اتصل بنا</a>
    </div>
  </section>

  <footer class="footer">
    <div class="container page-width content-width footer__inner">
      <div class="footer__left">
 
             <div class="footer__brand-wrap">
          <span class="footer__brand-line" aria-hidden="true"></span>
          <div class="footer__brand">
           <img src="<?php echo esc_url( get_theme_mod( 'aqar_contact_logo', get_theme_file_uri( '/assets/images/logo.png' ) ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
          </div>
          <div class="footer__logo-mark" aria-hidden="true">
            <span></span>
          </div>
        </div>

        <div class="footer__about">
          <p>
            <?php echo esc_html( get_theme_mod( 'aqar_footer_description', 'موقع عقار هو موقع متخصص في بيع الشقق والفيلات والمنازل والمشاريع العقارية في جميع أنحاء مصر. نحن نقدم حلولًا ذكية للعقارات السكنية والتجارية والاستثمارية مع دعم احترافي ومتابعة مستمرة.' ) ); ?>
          </p>
        </div>
        <?php
        $aqar_social_links = array(
          'youtube'   => array( 'label' => 'يوتيوب', 'icon' => 'fab fa-youtube' ),
          'instagram' => array( 'label' => 'إنستغرام', 'icon' => 'fab fa-instagram' ),
          'linkedin'  => array( 'label' => 'لينكد إن', 'icon' => 'fab fa-linkedin-in' ),
          'facebook'  => array( 'label' => 'فيسبوك', 'icon' => 'fab fa-facebook-f' ),
          'x'         => array( 'label' => 'X', 'icon' => 'fab fa-x-twitter' ),
        );
        ?>
        <div class="footer__socials">
          <?php foreach ( $aqar_social_links as $network => $social ) : ?>
            <?php $social_url = get_theme_mod( 'aqar_social_' . $network, '' ); ?>
            <?php if ( $social_url ) : ?>
              <a href="<?php echo esc_url( $social_url ); ?>" aria-label="<?php echo esc_attr( $social['label'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="<?php echo esc_attr( $social['icon'] ); ?>"></i></a>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

     

      <div class="footer__right">
        <?php
        wp_nav_menu(
          array(
            'theme_location' => 'footer',
            'container'      => false,
            'menu_class'     => 'footer__list',
            'fallback_cb'    => false,
            'depth'          => 1,
          )
        );
        ?>
      </div>
    </div>
    <div class="footer__bottom"><?php echo esc_html( get_theme_mod( 'aqar_footer_copyright', '' ) ?: 'جميع الحقوق محفوظة © ' . get_bloginfo( 'name' ) . ' ' . wp_date( 'Y' ) ); ?></div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>