<?php get_header(); ?>

  <main>
    <section class="hero">
      <div class="hero__copy">
        <h1>نيو عقار .. دليلك للعقارات في مصر</h1>
        <p>اكتشف أفضل العقارات في مصر، واختر وحدتك المناسبة بكل سهولة.</p>
        <form class="search-box" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
          <input class="form-control" type="search" name="s" placeholder="ابحث عن مدينة، مشروع أو وحدة..." aria-label="ابحث عن مدينة، مشروع أو وحدة" required>
          <button class="btn" type="submit" aria-label="بحث"><i class="fas fa-search" aria-hidden="true"></i></button>
        </form>
      </div>
    </section>

    <div class="container page-width content-width">
      <section class="content-section">
        <div class="section-heading">
          <h2>المدن المميزه</h2>
          <a href="<?php echo esc_url( get_permalink( 14 ) ); ?>">عرض الكل</a>
        </div>
        <div class="card-grid image-grid">
          <?php aqar_render_city_cards( 'featured' ); ?>
        </div>
      </section>
    

      <section class="content-section projects-section">
        <div class="section-heading">
          <h2>أفضل المشاريع</h2>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'aqar_project' ) ); ?>">عرض الكل</a>
        </div>
        <div class="card-grid">
          <?php aqar_render_project_cards( true ); ?>
        </div>
      </section>

      <section class="content-section projects-section">
        <div class="section-heading">
          <h2>أحدث المشاريع</h2>
          <a href="<?php echo esc_url( get_permalink( 12 ) ); ?>">عرض الكل</a>
        </div>
        <div class="card-grid">
          <?php aqar_render_project_cards(); ?>
        </div>
      </section>

      <section class="request-section" id="contact">
        <h2>احصل على استشارة مجانية</h2>
        <?php
        $consultation_status = isset( $_GET['consultation'] ) ? sanitize_key( wp_unslash( $_GET['consultation'] ) ) : '';
        if ( 'success' === $consultation_status ) :
          ?>
          <p class="aqar-form-notice aqar-form-notice--success" role="status"><?php esc_html_e( 'تم تسجيل طلبك بنجاح، وسنتواصل معك قريبًا.', 'aqar' ); ?></p>
        <?php elseif ( 'invalid' === $consultation_status ) : ?>
          <p class="aqar-form-notice aqar-form-notice--error" role="alert"><?php esc_html_e( 'تأكد من إدخال الاسم ورقم الهاتف والواتساب، ومن صحة البريد الإلكتروني.', 'aqar' ); ?></p>
        <?php elseif ( 'error' === $consultation_status ) : ?>
          <p class="aqar-form-notice aqar-form-notice--error" role="alert"><?php esc_html_e( 'حدث خطأ أثناء تسجيل الطلب. حاول مرة أخرى لاحقًا.', 'aqar' ); ?></p>
        <?php endif; ?>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
          <input type="hidden" name="action" value="aqar_submit_consultation">
          <?php wp_nonce_field( 'aqar_submit_consultation', 'aqar_consultation_nonce' ); ?>
        <div class="request-panel">
          <div class="request-form">
            <div class="request-row">
              <label for="request-name">الاسم الكامل <span>*</span></label>
              <input id="request-name" type="text" name="name" aria-label="الاسم الكامل" autocomplete="name" required>
            </div>
            <div class="request-row">
              <label for="request-phone">رقم الهاتف <span>*</span></label>
              <input id="request-phone" type="tel" name="phone" aria-label="رقم الهاتف" autocomplete="tel" required>
            </div>
            <div class="request-row">
              <label for="request-whatsapp">رقم الواتساب <span>*</span></label>
              <input id="request-whatsapp" type="tel" name="whatsapp" aria-label="رقم الواتساب" required>
            </div>
            <div class="request-row">
              <label for="request-email">عنوان البريد الإلكتروني</label>
              <input id="request-email" type="email" name="email" aria-label="عنوان البريد الإلكتروني" autocomplete="email">
            </div>
          </div>
             <div class="request-panel__left">
              <textarea id="request-message" name="message" rows="4" placeholder="اكتب رسالتك هنا..." aria-label="اكتب رسالتك هنا"></textarea>
            <button type="submit" class="request-submit">تسجيل</button>
          </div>
        </div>
        </form>
      </section>

    

      <section class="content-section">
        <div class="section-heading"><h2>أفضل المدن</h2><a href="<?php echo esc_url( get_permalink( 14 ) ); ?>">عرض الكل</a></div>
        <div class="card-grid image-grid">
          <?php aqar_render_city_cards( 'best' ); ?>
        </div>
      </section>

      <section class="content-section">
        <div class="section-heading"><h2>أحدث المدن</h2><a href="<?php echo esc_url( get_permalink( 14 ) ); ?>">عرض الكل</a></div>
        <div class="card-grid image-grid">
          <?php aqar_render_city_cards( 'latest' ); ?>
        </div>
      </section>

      <section class="content-section">
        <div class="section-heading">
          <h2>أفضل المطورين</h2>
          <a href="<?php echo esc_url( get_permalink( 16 ) ); ?>">عرض الكل</a>
        </div>
        <div class="grid grid--3">
          <?php aqar_render_developer_cards( true ); ?>
        </div>
      </section>

      <section class="content-section">
        <div class="section-heading">
          <h2>أحدث المطورين</h2>
          <a href="<?php echo esc_url( get_permalink( 16 ) ); ?>">عرض الكل</a>
        </div>
        <div class="grid grid--3">
          <?php aqar_render_developer_cards(); ?>
        </div>
      </section>

  <section class="content-section featured-section">
      <div class="section-heading">
          <h2>أحدث المقالات</h2>
          <?php if ( get_option( 'page_for_posts' ) ) : ?>
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">عرض الكل</a>
          <?php endif; ?>
        </div>
    <div class="featured-grid container page-width content-width">
      <?php
      $aqar_latest_posts = new WP_Query(
        array(
          'post_type'           => 'post',
          'post_status'         => 'publish',
          'category_name'       => 'real-estate-consulting',
          'posts_per_page'      => 3,
          'ignore_sticky_posts' => true,
          'orderby'             => array(
            'date' => 'DESC',
            'ID'   => 'DESC',
          ),
        )
      );

      if ( $aqar_latest_posts->have_posts() ) :
        while ( $aqar_latest_posts->have_posts() ) :
          $aqar_latest_posts->the_post();
          $aqar_post_categories = get_the_category();
          ?>
          <a class="featured-card" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
            <div class="featured-card__media">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?>
              <?php else : ?>
                <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
              <?php endif; ?>
              <span class="featured-tag"><?php echo esc_html( ! empty( $aqar_post_categories ) ? $aqar_post_categories[0]->name : 'مقال' ); ?></span>
              <span class="featured-badge"><?php echo esc_html( get_the_date( 'j F' ) ); ?></span>
            </div>
            <div class="featured-card__content">
              <h3><?php the_title(); ?></h3>
            </div>
          </a>
          <?php
        endwhile;
      else :
        echo '<p class="aqar-empty-state">' . esc_html__( 'لا توجد مقالات منشورة لعرضها حاليًا.', 'aqar' ) . '</p>';
      endif;

      wp_reset_postdata();
      ?>
    </div>
  </section>

     
    </div>
  </main>

<?php get_footer(); ?>