<?php
/**
 * Theme setup and WordPress integration.
 */

function aqar_theme_setup() {
	load_theme_textdomain( 'aqar', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'قائمة الهيدر', 'aqar' ),
			'footer'  => __( 'قائمة الفوتر', 'aqar' ),
		)
	);
}
add_action( 'after_setup_theme', 'aqar_theme_setup' );

function aqar_allow_webp_uploads( $mimes ) {
	$mimes['webp'] = 'image/webp';

	return $mimes;
}
add_filter( 'upload_mimes', 'aqar_allow_webp_uploads' );

function aqar_check_webp_filetype( $data, $file, $filename, $mimes, $real_mime = '' ) {
	if ( ! is_array( $data ) || ! is_string( $filename ) || 'webp' !== strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
		return $data;
	}

	if ( ! $real_mime && function_exists( 'wp_get_image_mime' ) ) {
		$real_mime = wp_get_image_mime( $file );
	}

	if ( 'image/webp' === $real_mime ) {
		$data['ext']  = 'webp';
		$data['type'] = 'image/webp';
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'aqar_check_webp_filetype', 10, 5 );

function aqar_register_projects() {
	$project_labels = array(
		'name'                  => __( 'المشاريع', 'aqar' ),
		'singular_name'         => __( 'مشروع', 'aqar' ),
		'add_new'               => __( 'أضف مشروعًا', 'aqar' ),
		'add_new_item'          => __( 'إضافة مشروع جديد', 'aqar' ),
		'edit_item'             => __( 'تعديل المشروع', 'aqar' ),
		'new_item'              => __( 'مشروع جديد', 'aqar' ),
		'view_item'             => __( 'عرض المشروع', 'aqar' ),
		'search_items'          => __( 'البحث في المشاريع', 'aqar' ),
		'not_found'             => __( 'لا توجد مشاريع.', 'aqar' ),
		'not_found_in_trash'    => __( 'لا توجد مشاريع في سلة المهملات.', 'aqar' ),
		'all_items'             => __( 'كل المشاريع', 'aqar' ),
		'menu_name'             => __( 'المشاريع', 'aqar' ),
	);

	register_post_type(
		'aqar_project',
		array(
			'labels'        => $project_labels,
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'projects' ),
			'menu_icon'     => 'dashicons-building',
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
		)
	);

	register_post_type(
		'aqar_unit',
		array(
			'labels'        => array(
				'name'                  => __( 'الوحدات العقارية', 'aqar' ),
				'singular_name'         => __( 'وحدة عقارية', 'aqar' ),
				'add_new'               => __( 'أضف وحدة', 'aqar' ),
				'add_new_item'          => __( 'إضافة وحدة عقارية', 'aqar' ),
				'edit_item'             => __( 'تعديل الوحدة العقارية', 'aqar' ),
				'new_item'              => __( 'وحدة عقارية جديدة', 'aqar' ),
				'view_item'             => __( 'عرض الوحدة العقارية', 'aqar' ),
				'search_items'          => __( 'البحث في الوحدات العقارية', 'aqar' ),
				'not_found'             => __( 'لا توجد وحدات عقارية.', 'aqar' ),
				'not_found_in_trash'    => __( 'لا توجد وحدات عقارية في سلة المهملات.', 'aqar' ),
				'all_items'             => __( 'كل الوحدات العقارية', 'aqar' ),
				'menu_name'             => __( 'الوحدات المتاحة', 'aqar' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'units' ),
			'menu_icon'     => 'dashicons-admin-home',
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);

	register_taxonomy(
		'aqar_city',
		array( 'aqar_unit' ),
		array(
			'labels'            => array(
				'name'              => __( 'المدن والمناطق', 'aqar' ),
				'singular_name'     => __( 'مدينة أو منطقة', 'aqar' ),
				'search_items'      => __( 'البحث في المدن والمناطق', 'aqar' ),
				'all_items'         => __( 'كل المدن والمناطق', 'aqar' ),
				'edit_item'         => __( 'تعديل المدينة أو المنطقة', 'aqar' ),
				'update_item'       => __( 'تحديث المدينة أو المنطقة', 'aqar' ),
				'add_new_item'      => __( 'إضافة مدينة أو منطقة', 'aqar' ),
				'new_item_name'     => __( 'اسم المدينة أو المنطقة', 'aqar' ),
				'menu_name'         => __( 'المدن والمناطق', 'aqar' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'cities' ),
		)
	);
}
add_action( 'init', 'aqar_register_projects' );
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

function aqar_add_unit_details_metabox() {
	add_meta_box(
		'aqar_unit_details',
		__( 'بيانات الوحدة العقارية', 'aqar' ),
		'aqar_render_unit_details_metabox',
		'aqar_unit',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_aqar_unit', 'aqar_add_unit_details_metabox' );

function aqar_render_unit_details_metabox( $post ) {
	wp_nonce_field( 'aqar_save_unit_details', 'aqar_unit_details_nonce' );
	$projects = get_posts(
		array(
			'post_type'      => 'aqar_project',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	$project_id = absint( get_post_meta( $post->ID, '_aqar_unit_project', true ) );
	?>
	<p>
		<label for="aqar-unit-project"><strong><?php esc_html_e( 'المشروع التابع له الوحدة', 'aqar' ); ?></strong></label>
		<select id="aqar-unit-project" name="aqar_unit_project" class="widefat" required>
			<option value="0"><?php esc_html_e( 'اختر المشروع', 'aqar' ); ?></option>
			<?php foreach ( $projects as $project ) : ?>
				<option value="<?php echo esc_attr( $project->ID ); ?>" <?php selected( $project_id, $project->ID ); ?>><?php echo esc_html( $project->post_title ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0 16px">
		<?php
		aqar_render_unit_text_field( 'aqar_unit_type', __( 'نوع الوحدة، مثال: شقة أو فيلا', 'aqar' ), $post->ID );
		aqar_render_unit_text_field( 'aqar_unit_status', __( 'حالة الوحدة', 'aqar' ), $post->ID );
		aqar_render_unit_text_field( 'aqar_unit_location', __( 'المنطقة داخل المدينة (اختياري)', 'aqar' ), $post->ID );
		aqar_render_unit_text_field( 'aqar_unit_delivery', __( 'موعد الاستلام (اختياري)', 'aqar' ), $post->ID );
		aqar_render_unit_text_field( 'aqar_unit_bedrooms', __( 'عدد الغرف', 'aqar' ), $post->ID, 'number' );
		aqar_render_unit_text_field( 'aqar_unit_bathrooms', __( 'عدد الحمامات', 'aqar' ), $post->ID, 'number' );
		aqar_render_unit_text_field( 'aqar_unit_area', __( 'المساحة بالمتر المربع', 'aqar' ), $post->ID, 'number' );
		aqar_render_unit_text_field( 'aqar_unit_price', __( 'السعر (رقم)', 'aqar' ), $post->ID, 'number' );
		aqar_render_unit_text_field( 'aqar_unit_price_currency', __( 'عملة السعر، مثال: ج.م', 'aqar' ), $post->ID );
		aqar_render_unit_text_field( 'aqar_unit_installment', __( 'أقل قسط (رقم، اختياري)', 'aqar' ), $post->ID, 'number' );
		aqar_render_unit_text_field( 'aqar_unit_installment_currency', __( 'عملة القسط', 'aqar' ), $post->ID );
		aqar_render_unit_text_field( 'aqar_unit_phone', __( 'رقم التواصل أو واتساب', 'aqar' ), $post->ID, 'tel' );
		?>
	</div>
	<p>
		<label>
			<input name="aqar_unit_featured" type="checkbox" value="1" <?php checked( get_post_meta( $post->ID, '_aqar_unit_featured', true ), '1' ); ?>>
			<span class="dashicons dashicons-star-filled" aria-hidden="true" style="color:#dba617"></span>
			<?php esc_html_e( 'إظهار الوحدة ضمن أفضل الوحدات في صفحة المدينة', 'aqar' ); ?>
		</label>
	</p>
	<p class="description"><?php esc_html_e( 'اختر المدينة أو المنطقة من صندوق التصنيفات، وأضف صورة الوحدة كصورة بارزة، ويمكنك كتابة وصفها في المحرر.', 'aqar' ); ?></p>
	<?php
}

function aqar_render_unit_text_field( $field_name, $label, $post_id, $type = 'text' ) {
	$meta_key = '_aqar_' . substr( $field_name, 5 );
	$value    = get_post_meta( $post_id, $meta_key, true );
	?>
	<p>
		<label for="<?php echo esc_attr( $field_name ); ?>"><?php echo esc_html( $label ); ?></label>
		<input id="<?php echo esc_attr( $field_name ); ?>" name="<?php echo esc_attr( $field_name ); ?>" type="<?php echo esc_attr( $type ); ?>" class="widefat" value="<?php echo esc_attr( $value ); ?>" <?php echo 'number' === $type ? 'min="0" step="any"' : ''; ?>>
	</p>
	<?php
}

function aqar_save_unit_details( $post_id ) {
	if ( ! isset( $_POST['aqar_unit_details_nonce'] ) || ! is_string( $_POST['aqar_unit_details_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['aqar_unit_details_nonce'] ), 'aqar_save_unit_details' ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$project_id = isset( $_POST['aqar_unit_project'] ) && is_scalar( $_POST['aqar_unit_project'] ) ? absint( wp_unslash( $_POST['aqar_unit_project'] ) ) : 0;
	if ( $project_id && 'aqar_project' === get_post_type( $project_id ) ) {
		update_post_meta( $post_id, '_aqar_unit_project', $project_id );
	} else {
		delete_post_meta( $post_id, '_aqar_unit_project' );
	}

	$text_fields = array(
		'aqar_unit_type',
		'aqar_unit_status',
		'aqar_unit_location',
		'aqar_unit_delivery',
		'aqar_unit_price_currency',
		'aqar_unit_installment_currency',
		'aqar_unit_phone',
	);
	foreach ( $text_fields as $field_name ) {
		if ( isset( $_POST[ $field_name ] ) && is_scalar( $_POST[ $field_name ] ) ) {
			update_post_meta( $post_id, '_aqar_' . substr( $field_name, 5 ), sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) ) );
		}
	}

	$number_fields = array(
		'aqar_unit_bedrooms',
		'aqar_unit_bathrooms',
		'aqar_unit_area',
		'aqar_unit_price',
		'aqar_unit_installment',
	);
	foreach ( $number_fields as $field_name ) {
		if ( isset( $_POST[ $field_name ] ) && is_scalar( $_POST[ $field_name ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );
			if ( '' === $value || is_numeric( $value ) ) {
				update_post_meta( $post_id, '_aqar_' . substr( $field_name, 5 ), $value );
			}
		}
	}

	update_post_meta( $post_id, '_aqar_unit_featured', isset( $_POST['aqar_unit_featured'] ) ? '1' : '0' );
}
add_action( 'save_post_aqar_unit', 'aqar_save_unit_details' );

function aqar_unit_admin_columns( $columns ) {
	$unit_columns = array();
	foreach ( $columns as $key => $label ) {
		$unit_columns[ $key ] = $label;
		if ( 'title' === $key ) {
			$unit_columns['aqar_project'] = __( 'المشروع', 'aqar' );
			$unit_columns['aqar_unit_type'] = __( 'نوع الوحدة', 'aqar' );
			$unit_columns['aqar_unit_price'] = __( 'السعر', 'aqar' );
		}
	}

	return $unit_columns;
}
add_filter( 'manage_aqar_unit_posts_columns', 'aqar_unit_admin_columns' );

function aqar_render_unit_admin_column( $column, $post_id ) {
	if ( 'aqar_project' === $column ) {
		$project_id = absint( get_post_meta( $post_id, '_aqar_unit_project', true ) );
		if ( $project_id ) {
			echo esc_html( get_the_title( $project_id ) );
		}
	} elseif ( in_array( $column, array( 'aqar_unit_type', 'aqar_unit_price' ), true ) ) {
		$meta_key = '_aqar_' . $column;
		$value    = get_post_meta( $post_id, $meta_key, true );
		echo esc_html( $value );
		if ( 'aqar_unit_price' === $column && $value ) {
			echo ' ' . esc_html( get_post_meta( $post_id, '_aqar_unit_price_currency', true ) );
		}
	}
}
add_action( 'manage_aqar_unit_posts_custom_column', 'aqar_render_unit_admin_column', 10, 2 );

function aqar_migrate_legacy_project_units() {
	if ( '3' === get_option( 'aqar_legacy_unit_migration_version' ) ) {
		return;
	}

	$projects = get_posts(
		array(
			'post_type'      => 'aqar_project',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => -1,
		)
	);

	foreach ( $projects as $project ) {
		$project_city_ids = wp_get_object_terms( $project->ID, 'aqar_city', array( 'fields' => 'ids' ) );
		if ( is_wp_error( $project_city_ids ) ) {
			$project_city_ids = array();
		}
		$rows = aqar_parse_project_rows( get_post_meta( $project->ID, '_aqar_project_available_units', true ), 12 );
		foreach ( $rows as $index => $row ) {
			$legacy_key = $project->ID . ':' . $index;
			$existing   = get_posts(
				array(
					'post_type'      => 'aqar_unit',
					'post_status'    => 'any',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'meta_key'       => '_aqar_unit_legacy_key',
					'meta_value'     => $legacy_key,
				)
			);
			if ( $existing ) {
				$existing_id = (int) $existing[0];
				if ( '' === get_post_meta( $existing_id, '_aqar_unit_price', true ) ) {
					update_post_meta( $existing_id, '_aqar_unit_price', aqar_legacy_amount_to_number( get_post_meta( $existing_id, '_aqar_unit_price_label', true ) ) );
				}
				if ( '' === get_post_meta( $existing_id, '_aqar_unit_installment', true ) ) {
					update_post_meta( $existing_id, '_aqar_unit_installment', aqar_legacy_amount_to_number( get_post_meta( $existing_id, '_aqar_unit_installment_label', true ) ) );
				}
				if ( ! aqar_assign_legacy_unit_city( $existing_id, $row[2], $project_city_ids ) ) {
					return;
				}
				continue;
			}

			$unit_id = wp_insert_post(
				array(
					'post_type'   => 'aqar_unit',
					'post_status' => $project->post_status,
					'post_title'  => sanitize_text_field( $row[0] ),
					'post_parent' => 0,
					'meta_input'  => array(
						'_aqar_unit_legacy_key'        => $legacy_key,
						'_aqar_unit_project'           => $project->ID,
						'_aqar_unit_type'              => sanitize_text_field( $row[1] ),
						'_aqar_unit_location'          => sanitize_text_field( $row[2] ),
						'_aqar_unit_bedrooms'           => is_numeric( $row[3] ) ? $row[3] : '',
						'_aqar_unit_bathrooms'          => is_numeric( $row[4] ) ? $row[4] : '',
						'_aqar_unit_area'                => is_numeric( $row[5] ) ? $row[5] : '',
						'_aqar_unit_price'               => aqar_legacy_amount_to_number( $row[6] ),
						'_aqar_unit_price_label'         => sanitize_text_field( $row[6] ),
						'_aqar_unit_price_currency'      => sanitize_text_field( $row[7] ),
						'_aqar_unit_installment'         => aqar_legacy_amount_to_number( $row[8] ),
						'_aqar_unit_installment_label'   => sanitize_text_field( $row[8] ),
						'_aqar_unit_installment_currency' => sanitize_text_field( $row[9] ),
						'_aqar_unit_phone'               => sanitize_text_field( $row[11] ),
						'_aqar_unit_legacy_image'        => esc_url_raw( aqar_project_image_url( $row[10] ) ),
					),
				),
				true
			);

			if ( is_wp_error( $unit_id ) || ! $unit_id ) {
				$error_message = is_wp_error( $unit_id ) ? $unit_id->get_error_message() : 'WordPress did not return a unit ID.';
				error_log( 'Aqar legacy unit migration failed: ' . $error_message );
				return;
			}

			if ( ! aqar_assign_legacy_unit_city( $unit_id, $row[2], $project_city_ids ) ) {
				return;
			}
		}

		$project_units = get_posts(
			array(
				'post_type'      => 'aqar_unit',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_aqar_unit_project',
				'meta_value'     => $project->ID,
			)
		);
		if ( $project_city_ids ) {
			foreach ( $project_units as $project_unit_id ) {
				$unit_city_ids = wp_get_object_terms( $project_unit_id, 'aqar_city', array( 'fields' => 'ids' ) );
				if ( ! is_wp_error( $unit_city_ids ) && ! $unit_city_ids ) {
					$set_terms = wp_set_object_terms( $project_unit_id, $project_city_ids, 'aqar_city' );
					if ( is_wp_error( $set_terms ) ) {
						error_log( 'Aqar city migration failed: ' . $set_terms->get_error_message() );
						return;
					}
				}
			}
		}

		$removed_terms = wp_set_object_terms( $project->ID, array(), 'aqar_city' );
		if ( is_wp_error( $removed_terms ) ) {
			error_log( 'Aqar project city cleanup failed: ' . $removed_terms->get_error_message() );
			return;
		}
	}

	update_option( 'aqar_legacy_unit_migration_version', '3' );
}
add_action( 'init', 'aqar_migrate_legacy_project_units', 20 );

function aqar_assign_legacy_unit_city( $unit_id, $location, $fallback_term_ids = array() ) {
	$city = $location ? get_term_by( 'name', sanitize_text_field( $location ), 'aqar_city' ) : false;
	if ( $city && ! is_wp_error( $city ) ) {
		$result = wp_set_object_terms( $unit_id, array( (int) $city->term_id ), 'aqar_city' );
	} elseif ( $fallback_term_ids ) {
		$unit_city_ids = wp_get_object_terms( $unit_id, 'aqar_city', array( 'fields' => 'ids' ) );
		if ( ! is_wp_error( $unit_city_ids ) && ! $unit_city_ids ) {
			$result = wp_set_object_terms( $unit_id, $fallback_term_ids, 'aqar_city' );
		}
	}

	if ( isset( $result ) && is_wp_error( $result ) ) {
		error_log( 'Aqar unit city assignment failed: ' . $result->get_error_message() );
		return false;
	}

	return true;
}

function aqar_register_consultations() {
	register_post_type(
		'aqar_consultation',
		array(
			'labels'              => array(
				'name'                  => __( 'الاستشارات المجانية', 'aqar' ),
				'singular_name'         => __( 'طلب استشارة', 'aqar' ),
				'edit_item'             => __( 'عرض طلب الاستشارة', 'aqar' ),
				'all_items'             => __( 'كل طلبات الاستشارة', 'aqar' ),
				'search_items'          => __( 'البحث في طلبات الاستشارة', 'aqar' ),
				'not_found'             => __( 'لا توجد طلبات استشارة.', 'aqar' ),
				'not_found_in_trash'    => __( 'لا توجد طلبات استشارة في سلة المهملات.', 'aqar' ),
				'menu_name'             => __( 'الاستشارات المجانية', 'aqar' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-format-chat',
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		)
	);
}
add_action( 'init', 'aqar_register_consultations' );

function aqar_add_consultation_details_metabox() {
	add_meta_box(
		'aqar_consultation_details',
		__( 'بيانات طلب الاستشارة', 'aqar' ),
		'aqar_render_consultation_details_metabox',
		'aqar_consultation',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_aqar_consultation', 'aqar_add_consultation_details_metabox' );

function aqar_render_consultation_details_metabox( $post ) {
	$fields = array(
		__( 'رقم الهاتف', 'aqar' )       => get_post_meta( $post->ID, '_aqar_consultation_phone', true ),
		__( 'رقم الواتساب', 'aqar' )     => get_post_meta( $post->ID, '_aqar_consultation_whatsapp', true ),
		__( 'البريد الإلكتروني', 'aqar' ) => get_post_meta( $post->ID, '_aqar_consultation_email', true ),
		__( 'الرسالة', 'aqar' )          => get_post_meta( $post->ID, '_aqar_consultation_message', true ),
	);
	?>
	<table class="form-table">
		<tbody>
			<?php foreach ( $fields as $label => $value ) : ?>
				<tr>
					<th scope="row"><?php echo esc_html( $label ); ?></th>
					<td><?php echo nl2br( esc_html( $value ? $value : '—' ) ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php
}

function aqar_consultation_admin_columns( $columns ) {
	return array(
		'cb'        => isset( $columns['cb'] ) ? $columns['cb'] : '<input type="checkbox">',
		'title'     => __( 'اسم العميل', 'aqar' ),
		'phone'     => __( 'رقم الهاتف', 'aqar' ),
		'whatsapp'  => __( 'رقم الواتساب', 'aqar' ),
		'email'     => __( 'البريد الإلكتروني', 'aqar' ),
		'date'      => isset( $columns['date'] ) ? $columns['date'] : __( 'التاريخ', 'aqar' ),
	);
}
add_filter( 'manage_aqar_consultation_posts_columns', 'aqar_consultation_admin_columns' );

function aqar_render_consultation_admin_column( $column, $post_id ) {
	$meta_keys = array(
		'phone'    => '_aqar_consultation_phone',
		'whatsapp' => '_aqar_consultation_whatsapp',
		'email'    => '_aqar_consultation_email',
	);

	if ( isset( $meta_keys[ $column ] ) ) {
		echo esc_html( get_post_meta( $post_id, $meta_keys[ $column ], true ) );
	}
}
add_action( 'manage_aqar_consultation_posts_custom_column', 'aqar_render_consultation_admin_column', 10, 2 );

function aqar_handle_consultation_submission() {
	if ( ! isset( $_POST['aqar_consultation_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqar_consultation_nonce'] ) ), 'aqar_submit_consultation' ) ) {
		wp_die( esc_html__( 'تعذر التحقق من الطلب. حدّث الصفحة وحاول مرة أخرى.', 'aqar' ) );
	}

	$name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone    = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$whatsapp = isset( $_POST['whatsapp'] ) ? sanitize_text_field( wp_unslash( $_POST['whatsapp'] ) ) : '';
	$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message  = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( '' === $name || '' === $phone || '' === $whatsapp || ( '' !== $email && ! is_email( $email ) ) ) {
		aqar_redirect_consultation_form( 'invalid' );
	}

	$consultation_id = wp_insert_post(
		array(
			'post_type'   => 'aqar_consultation',
			'post_status' => 'private',
			'post_title'  => $name,
			'meta_input'  => array(
				'_aqar_consultation_phone'    => $phone,
				'_aqar_consultation_whatsapp' => $whatsapp,
				'_aqar_consultation_email'    => $email,
				'_aqar_consultation_message'  => $message,
			),
		),
		true
	);

	if ( is_wp_error( $consultation_id ) ) {
		error_log( 'Aqar consultation submission failed: ' . $consultation_id->get_error_message() );
		aqar_redirect_consultation_form( 'error' );
	}

	aqar_redirect_consultation_form( 'success' );
}
add_action( 'admin_post_nopriv_aqar_submit_consultation', 'aqar_handle_consultation_submission' );
add_action( 'admin_post_aqar_submit_consultation', 'aqar_handle_consultation_submission' );

function aqar_redirect_consultation_form( $status ) {
	$url = add_query_arg( 'consultation', sanitize_key( $status ), home_url( '/' ) ) . '#contact';
	wp_safe_redirect( $url );
	exit;
}

function aqar_register_developers() {
	register_post_type(
		'aqar_developer',
		array(
			'labels'        => array(
				'name'               => __( 'المطورون', 'aqar' ),
				'singular_name'      => __( 'مطور عقاري', 'aqar' ),
				'add_new'            => __( 'أضف مطورًا', 'aqar' ),
				'add_new_item'       => __( 'إضافة مطور عقاري', 'aqar' ),
				'edit_item'          => __( 'تعديل بيانات المطور', 'aqar' ),
				'new_item'           => __( 'مطور عقاري جديد', 'aqar' ),
				'view_item'          => __( 'عرض صفحة المطور', 'aqar' ),
				'search_items'       => __( 'البحث في المطورين', 'aqar' ),
				'not_found'          => __( 'لا يوجد مطورون.', 'aqar' ),
				'not_found_in_trash' => __( 'لا يوجد مطورون في سلة المهملات.', 'aqar' ),
				'all_items'          => __( 'كل المطورين', 'aqar' ),
				'menu_name'          => __( 'المطورون', 'aqar' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'developers' ),
			'menu_icon'     => 'dashicons-groups',
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);
}
add_action( 'init', 'aqar_register_developers' );

function aqar_maybe_flush_rewrite_rules() {
	if ( '3' !== get_option( 'aqar_rewrite_rules_version' ) ) {
		flush_rewrite_rules();
		update_option( 'aqar_rewrite_rules_version', '3' );
	}
}
add_action( 'init', 'aqar_maybe_flush_rewrite_rules', 99 );

function aqar_enqueue_assets() {
	$styles = array(
		'aqar-theme'      => '/style.css',
		'aqar-bootstrap'  => '/assets/css/bootstrap.rtl.min.css',
		'aqar-fontawesome'=> '/assets/css/all.min.css',
		'aqar-main'       => '/assets/css/main.css',
		'aqar-responsive' => '/assets/css/responsive.css',
	);

	foreach ( $styles as $handle => $relative_path ) {
		$file_path = get_theme_file_path( $relative_path );
		$version   = file_exists( $file_path ) ? (string) filemtime( $file_path ) : wp_get_theme()->get( 'Version' );
		wp_enqueue_style( $handle, get_theme_file_uri( $relative_path ), array(), $version );
	}

	$scripts = array(
		'aqar-bootstrap' => '/assets/js/bootstrap.bundle.min.js',
		'aqar-main'      => '/assets/js/main.js',
	);

	foreach ( $scripts as $handle => $relative_path ) {
		$file_path = get_theme_file_path( $relative_path );
		$version   = file_exists( $file_path ) ? (string) filemtime( $file_path ) : wp_get_theme()->get( 'Version' );
		wp_enqueue_script( $handle, get_theme_file_uri( $relative_path ), array(), $version, true );
	}
}
add_action( 'wp_enqueue_scripts', 'aqar_enqueue_assets' );

function aqar_search_post_types( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	$query->set( 'post_type', array( 'aqar_project', 'aqar_unit', 'post' ) );
	$query->set( 'post_status', 'publish' );
	$query->set( 'posts_per_page', 12 );
}
add_action( 'pre_get_posts', 'aqar_search_post_types' );

function aqar_add_project_details_metabox() {
	add_meta_box(
		'aqar_project_details',
		__( 'بيانات المشروع', 'aqar' ),
		'aqar_render_project_details_metabox',
		'aqar_project',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes_aqar_project', 'aqar_add_project_details_metabox' );

function aqar_render_project_details_metabox( $post ) {
	wp_nonce_field( 'aqar_save_project_details', 'aqar_project_details_nonce' );
	$description = get_post_meta( $post->ID, '_aqar_project_description', true );
	$location    = get_post_meta( $post->ID, '_aqar_project_location', true );
	$badge       = get_post_meta( $post->ID, '_aqar_project_badge', true );
	$is_featured = get_post_meta( $post->ID, '_aqar_project_featured', true );
	$developers  = get_posts(
		array(
			'post_type'      => 'aqar_developer',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	?>
	<p>
		<label for="aqar-project-description"><?php esc_html_e( 'وصف قصير', 'aqar' ); ?></label>
		<textarea id="aqar-project-description" name="aqar_project_description" rows="3" class="widefat"><?php echo esc_textarea( $description ); ?></textarea>
	</p>
	<p>
		<label for="aqar-project-location"><?php esc_html_e( 'المكان', 'aqar' ); ?></label>
		<input id="aqar-project-location" name="aqar_project_location" type="text" class="widefat" value="<?php echo esc_attr( $location ); ?>">
	</p>
	<p>
		<label for="aqar-project-badge"><?php esc_html_e( 'العلامة على الصورة', 'aqar' ); ?></label>
		<input id="aqar-project-badge" name="aqar_project_badge" type="text" class="widefat" value="<?php echo esc_attr( $badge ); ?>">
	</p>
	<p>
		<label>
			<input name="aqar_project_featured" type="checkbox" value="1" <?php checked( $is_featured, '1' ); ?>>
			<span class="dashicons dashicons-star-filled" aria-hidden="true" style="color: #dba617;"></span>
			<?php esc_html_e( 'إظهار المشروع ضمن أفضل المشاريع', 'aqar' ); ?>
		</label>
	</p>
	<hr>
	<h3><?php esc_html_e( 'بيانات صفحة المشروع', 'aqar' ); ?></h3>
	<p><?php esc_html_e( 'اترك الحقول التي لا تتوفر بياناتها فارغة. الصورة البارزة للمشروع هي الصورة الرئيسية، ومحتوى المحرر هو الوصف الكامل للمشروع.', 'aqar' ); ?></p>
	<?php
	aqar_render_project_text_field( 'aqar_project_tagline', 'عنوان تعريفي صغير', $post->ID );
	aqar_render_project_text_field( 'aqar_project_status', 'حالة المشروع، مثال: متاح للحجز', $post->ID );
	?>
	<p>
		<label for="aqar-project-developer"><?php esc_html_e( 'المطور العقاري', 'aqar' ); ?></label>
		<select id="aqar-project-developer" name="aqar_project_developer" class="widefat">
			<option value="0"><?php esc_html_e( 'اختر مطورًا (اختياري)', 'aqar' ); ?></option>
			<?php foreach ( $developers as $developer ) : ?>
				<option value="<?php echo esc_attr( $developer->ID ); ?>" <?php selected( absint( get_post_meta( $post->ID, '_aqar_project_developer', true ) ), $developer->ID ); ?>>
					<?php echo esc_html( $developer->post_title ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px">
		<?php
		aqar_render_project_text_field( 'aqar_project_start_price', 'السعر يبدأ من', $post->ID );
		aqar_render_project_text_field( 'aqar_project_price_currency', 'عملة السعر، مثال: ج.م', $post->ID );
		aqar_render_project_text_field( 'aqar_project_min_area', 'أقل مساحة', $post->ID );
		aqar_render_project_text_field( 'aqar_project_delivery', 'موعد التسليم', $post->ID );
		aqar_render_project_text_field( 'aqar_project_unit_types_summary', 'أنواع الوحدات', $post->ID );
		aqar_render_project_text_field( 'aqar_project_payment_summary', 'ملخص أنظمة السداد', $post->ID );
		aqar_render_project_text_field( 'aqar_project_contact_phone', 'رقم التواصل', $post->ID );
		aqar_render_project_text_field( 'aqar_project_about_heading', 'عنوان قسم عن المشروع', $post->ID );
		aqar_render_project_text_field( 'aqar_project_about_image', 'رابط صورة قسم عن المشروع', $post->ID );
		aqar_render_project_text_field( 'aqar_project_location_heading', 'عنوان قسم الموقع', $post->ID );
		aqar_render_project_text_field( 'aqar_project_location_subheading', 'عنوان فرعي للموقع', $post->ID );
		aqar_render_project_text_field( 'aqar_project_location_description', 'وصف موقع المشروع', $post->ID );
		aqar_render_project_text_field( 'aqar_project_location_map_url', 'رابط خريطة (اختياري)', $post->ID );
		aqar_render_project_text_field( 'aqar_project_contact_heading', 'عنوان قسم التواصل', $post->ID );
		aqar_render_project_text_field( 'aqar_project_contact_message', 'نص قسم التواصل', $post->ID );
		aqar_render_project_text_field( 'aqar_project_faq_heading', 'عنوان الأسئلة الشائعة', $post->ID );
		?>
	</div>
	<?php
	aqar_render_project_gallery_field( $post->ID );
	aqar_render_project_textarea_field( 'aqar_project_plans', 'مخططات المشروع', 'كل سطر: العنوان | الوصف | رابط الصورة (اختياري) | أسماء الغرف مفصولة بفواصل (اختياري). إذا لم تضف صورة يظهر الرسم التوضيحي.', $post->ID );
	aqar_render_project_textarea_field( 'aqar_project_videos', 'فيديوهات المشروع', 'كل سطر: العنوان | الوصف | رابط فيديو YouTube | رابط صورة الغلاف (اختياري).', $post->ID );
	aqar_render_project_textarea_field( 'aqar_project_unit_types', 'أنواع ومساحات الوحدات', 'كل سطر: النوع | أقل مساحة | عدد الغرف | عدد الحمامات | السعر يبدأ من | العملة.', $post->ID );
	aqar_render_project_textarea_field( 'aqar_project_payment_plans', 'خطط السداد', 'كل سطر: اسم الخطة | المقدم | مدة التقسيط | ميزة أولى | ميزة ثانية. أضف كلمة featured كحقل سادس للخطة المميزة.', $post->ID );
	aqar_render_project_textarea_field( 'aqar_project_location_points', 'مميزات الموقع', 'ميزة واحدة في كل سطر.', $post->ID );
	aqar_render_project_textarea_field( 'aqar_project_faqs', 'الأسئلة الشائعة', 'كل سطر: السؤال | الإجابة.', $post->ID );
}

function aqar_render_project_gallery_field( $post_id ) {
	$field_name = 'aqar_project_gallery';
	$value      = get_post_meta( $post_id, '_aqar_project_gallery', true );
	?>
	<p>
		<label for="<?php echo esc_attr( $field_name ); ?>"><strong><?php esc_html_e( 'صور إضافية للمعرض', 'aqar' ); ?></strong></label>
		<br><span class="description"><?php esc_html_e( 'اختر أو ارفع عدة صور من مكتبة الوسائط. يمكنك أيضًا تعديل القائمة يدويًا، صورة واحدة في كل سطر.', 'aqar' ); ?></span>
	</p>
	<p>
		<button type="button" class="button aqar-project-gallery-select"><?php esc_html_e( 'اختيار / رفع صور المعرض', 'aqar' ); ?></button>
		<button type="button" class="button aqar-project-gallery-clear"><?php esc_html_e( 'مسح الصور', 'aqar' ); ?></button>
	</p>
	<div class="aqar-project-gallery-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px">
		<?php foreach ( preg_split( '/\r\n|\r|\n/', (string) $value ) as $image ) : ?>
			<?php $image_url = aqar_project_image_url( trim( $image ), 'thumbnail' ); ?>
			<?php if ( $image_url ) : ?><img src="<?php echo esc_url( $image_url ); ?>" alt="" style="width:72px;height:60px;object-fit:cover"><?php endif; ?>
		<?php endforeach; ?>
	</div>
	<textarea id="<?php echo esc_attr( $field_name ); ?>" name="<?php echo esc_attr( $field_name ); ?>" rows="4" class="widefat"><?php echo esc_textarea( $value ); ?></textarea>
	<?php
}

function aqar_render_project_text_field( $field_name, $label, $post_id ) {
	$meta_key = '_aqar_' . substr( $field_name, 5 );
	$value    = get_post_meta( $post_id, $meta_key, true );
	?>
	<p>
		<label for="<?php echo esc_attr( $field_name ); ?>"><?php echo esc_html( $label ); ?></label>
		<input id="<?php echo esc_attr( $field_name ); ?>" name="<?php echo esc_attr( $field_name ); ?>" type="text" class="widefat" value="<?php echo esc_attr( $value ); ?>">
	</p>
	<?php
}

function aqar_render_project_textarea_field( $field_name, $label, $description, $post_id ) {
	$meta_key = '_aqar_' . substr( $field_name, 5 );
	$value    = get_post_meta( $post_id, $meta_key, true );
	?>
	<p>
		<label for="<?php echo esc_attr( $field_name ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label>
		<br><span class="description"><?php echo esc_html( $description ); ?></span>
		<textarea id="<?php echo esc_attr( $field_name ); ?>" name="<?php echo esc_attr( $field_name ); ?>" rows="4" class="widefat"><?php echo esc_textarea( $value ); ?></textarea>
	</p>
	<?php
}

function aqar_save_project_details( $post_id ) {
	if ( ! isset( $_POST['aqar_project_details_nonce'] ) || ! is_string( $_POST['aqar_project_details_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['aqar_project_details_nonce'] ), 'aqar_save_project_details' ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'_aqar_project_description' => array( 'aqar_project_description', 'sanitize_textarea_field' ),
		'_aqar_project_location'    => array( 'aqar_project_location', 'sanitize_text_field' ),
		'_aqar_project_badge'       => array( 'aqar_project_badge', 'sanitize_text_field' ),
	);

	foreach ( $fields as $meta_key => $field ) {
		if ( isset( $_POST[ $field[0] ] ) && is_scalar( $_POST[ $field[0] ] ) ) {
			update_post_meta( $post_id, $meta_key, call_user_func( $field[1], wp_unslash( $_POST[ $field[0] ] ) ) );
		}
	}

	$text_fields = array(
		'aqar_project_tagline',
		'aqar_project_status',
		'aqar_project_start_price',
		'aqar_project_price_currency',
		'aqar_project_min_area',
		'aqar_project_delivery',
		'aqar_project_unit_types_summary',
		'aqar_project_payment_summary',
		'aqar_project_contact_phone',
		'aqar_project_about_heading',
		'aqar_project_location_heading',
		'aqar_project_location_subheading',
		'aqar_project_location_description',
		'aqar_project_contact_heading',
		'aqar_project_contact_message',
		'aqar_project_faq_heading',
	);
	foreach ( $text_fields as $field_name ) {
		if ( isset( $_POST[ $field_name ] ) && is_scalar( $_POST[ $field_name ] ) ) {
			update_post_meta( $post_id, '_aqar_' . substr( $field_name, 5 ), sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) ) );
		}
	}

	$url_fields = array(
		'aqar_project_about_image',
		'aqar_project_location_map_url',
	);
	foreach ( $url_fields as $field_name ) {
		if ( isset( $_POST[ $field_name ] ) && is_scalar( $_POST[ $field_name ] ) ) {
			update_post_meta( $post_id, '_aqar_' . substr( $field_name, 5 ), esc_url_raw( wp_unslash( $_POST[ $field_name ] ) ) );
		}
	}

	$textarea_fields = array(
		'aqar_project_gallery',
		'aqar_project_plans',
		'aqar_project_videos',
		'aqar_project_unit_types',
		'aqar_project_payment_plans',
		'aqar_project_location_points',
		'aqar_project_faqs',
	);
	foreach ( $textarea_fields as $field_name ) {
		if ( isset( $_POST[ $field_name ] ) && is_scalar( $_POST[ $field_name ] ) ) {
			update_post_meta( $post_id, '_aqar_' . substr( $field_name, 5 ), sanitize_textarea_field( wp_unslash( $_POST[ $field_name ] ) ) );
		}
	}

	$developer_id = isset( $_POST['aqar_project_developer'] ) && is_scalar( $_POST['aqar_project_developer'] ) ? absint( wp_unslash( $_POST['aqar_project_developer'] ) ) : 0;
	if ( $developer_id && 'aqar_developer' === get_post_type( $developer_id ) ) {
		update_post_meta( $post_id, '_aqar_project_developer', $developer_id );
	} else {
		delete_post_meta( $post_id, '_aqar_project_developer' );
	}

	update_post_meta( $post_id, '_aqar_project_featured', isset( $_POST['aqar_project_featured'] ) ? '1' : '0' );
}
add_action( 'save_post_aqar_project', 'aqar_save_project_details' );

function aqar_parse_project_rows( $value, $expected_columns = 0 ) {
	if ( ! is_string( $value ) || '' === trim( $value ) ) {
		return array();
	}

	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', $value ) as $line ) {
		if ( '' === trim( $line ) ) {
			continue;
		}

		$columns = array_map( 'trim', explode( '|', $line ) );
		if ( ! array_filter( $columns, 'strlen' ) ) {
			continue;
		}
		if ( $expected_columns ) {
			$columns = array_slice( array_pad( $columns, $expected_columns, '' ), 0, $expected_columns );
		}
		$rows[] = $columns;
	}

	return $rows;
}

function aqar_legacy_amount_to_number( $value ) {
	$value = is_scalar( $value ) ? trim( (string) $value ) : '';
	if ( '' === $value ) {
		return '';
	}

	$value = strtr(
		$value,
		array(
			'٠' => '0',
			'١' => '1',
			'٢' => '2',
			'٣' => '3',
			'٤' => '4',
			'٥' => '5',
			'٦' => '6',
			'٧' => '7',
			'٨' => '8',
			'٩' => '9',
		)
	);
	if ( ! preg_match( '/([0-9]+(?:\.[0-9]+)?)\s*(مليون|ملايين|ألف|الف|آلاف)?/u', $value, $matches ) ) {
		return '';
	}

	$amount = (float) $matches[1];
	$scale  = isset( $matches[2] ) ? $matches[2] : '';
	if ( in_array( $scale, array( 'مليون', 'ملايين' ), true ) ) {
		$amount *= 1000000;
	} elseif ( in_array( $scale, array( 'ألف', 'الف', 'آلاف' ), true ) ) {
		$amount *= 1000;
	}

	return (string) $amount;
}

function aqar_get_project_meta( $post_id, $key ) {
	return get_post_meta( $post_id, '_aqar_project_' . $key, true );
}

function aqar_project_image_url( $image, $size = 'large' ) {
	$image = is_scalar( $image ) ? trim( (string) $image ) : '';
	if ( '' === $image ) {
		return '';
	}

	if ( ctype_digit( $image ) ) {
		$image_url = wp_get_attachment_image_url( absint( $image ), $size );
		return $image_url ? $image_url : '';
	}

	return esc_url( $image );
}

function aqar_get_youtube_embed_url( $url ) {
	$url   = is_scalar( $url ) ? trim( (string) $url ) : '';
	$parts = wp_parse_url( $url );
	if ( empty( $parts['host'] ) ) {
		return '';
	}

	$host = strtolower( preg_replace( '/^www\./', '', $parts['host'] ) );
	$id   = '';
	if ( in_array( $host, array( 'youtu.be', 'www.youtu.be' ), true ) ) {
		$id = trim( isset( $parts['path'] ) ? $parts['path'] : '', '/' );
	} elseif ( in_array( $host, array( 'youtube.com', 'm.youtube.com', 'youtube-nocookie.com' ), true ) ) {
		if ( isset( $parts['query'] ) ) {
			parse_str( $parts['query'], $query );
			$id = isset( $query['v'] ) ? $query['v'] : '';
		}
		if ( ! $id && isset( $parts['path'] ) && preg_match( '#^/(?:embed|shorts|live)/([^/]+)#', $parts['path'], $matches ) ) {
			$id = $matches[1];
		}
	} else {
		return '';
	}

	if ( ! preg_match( '/^[A-Za-z0-9_-]{11}$/', $id ) ) {
		return '';
	}

	return 'https://www.youtube-nocookie.com/embed/' . $id;
}

function aqar_add_developer_details_metabox() {
	add_meta_box(
		'aqar_developer_details',
		__( 'بيانات المطور والمشاريع التابعة له', 'aqar' ),
		'aqar_render_developer_details_metabox',
		'aqar_developer',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes_aqar_developer', 'aqar_add_developer_details_metabox' );

function aqar_render_developer_details_metabox( $post ) {
	wp_nonce_field( 'aqar_save_developer_details', 'aqar_developer_details_nonce' );
	$description = get_post_meta( $post->ID, '_aqar_developer_description', true );
	$location    = get_post_meta( $post->ID, '_aqar_developer_location', true );
	$phone       = get_post_meta( $post->ID, '_aqar_developer_phone', true );
	$website     = get_post_meta( $post->ID, '_aqar_developer_website', true );
	$is_featured = get_post_meta( $post->ID, '_aqar_developer_featured', true );
	$selected    = array_map( 'absint', (array) get_post_meta( $post->ID, '_aqar_developer_projects', true ) );
	$projects    = get_posts(
		array(
			'post_type'      => 'aqar_project',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	?>
	<p>
		<label for="aqar-developer-description"><?php esc_html_e( 'نبذة مختصرة', 'aqar' ); ?></label>
		<textarea id="aqar-developer-description" name="aqar_developer_description" rows="3" class="widefat"><?php echo esc_textarea( $description ); ?></textarea>
	</p>
	<p>
		<label for="aqar-developer-location"><?php esc_html_e( 'المقر أو المدينة', 'aqar' ); ?></label>
		<input id="aqar-developer-location" name="aqar_developer_location" type="text" class="widefat" value="<?php echo esc_attr( $location ); ?>">
	</p>
	<p>
		<label for="aqar-developer-phone"><?php esc_html_e( 'رقم الهاتف', 'aqar' ); ?></label>
		<input id="aqar-developer-phone" name="aqar_developer_phone" type="tel" class="widefat" value="<?php echo esc_attr( $phone ); ?>">
	</p>
	<p>
		<label for="aqar-developer-website"><?php esc_html_e( 'الموقع الإلكتروني', 'aqar' ); ?></label>
		<input id="aqar-developer-website" name="aqar_developer_website" type="url" class="widefat" value="<?php echo esc_attr( $website ); ?>">
	</p>
	<p>
		<label>
			<input name="aqar_developer_featured" type="checkbox" value="1" <?php checked( $is_featured, '1' ); ?>>
			<span class="dashicons dashicons-star-filled" aria-hidden="true" style="color: #dba617;"></span>
			<?php esc_html_e( 'إظهار المطور ضمن أفضل المطورين', 'aqar' ); ?>
		</label>
	</p>
	<fieldset>
		<legend><strong><?php esc_html_e( 'المشاريع التي يعمل عليها المطور', 'aqar' ); ?></strong></legend>
		<?php if ( $projects ) : ?>
			<?php foreach ( $projects as $project ) : ?>
				<label style="display: block; margin: 6px 0;">
					<input type="checkbox" name="aqar_developer_projects[]" value="<?php echo esc_attr( $project->ID ); ?>" <?php checked( in_array( $project->ID, $selected, true ) ); ?>>
					<?php echo esc_html( $project->post_title ); ?>
				</label>
			<?php endforeach; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'أضف مشاريع أولًا لتتمكن من ربطها بهذا المطور.', 'aqar' ); ?></p>
		<?php endif; ?>
	</fieldset>
	<p><?php esc_html_e( 'استخدم محرر المحتوى أعلى الصفحة لإضافة تفاصيل المطور الكاملة، والصورة البارزة لإضافة شعاره أو صورته.', 'aqar' ); ?></p>
	<?php
}

function aqar_save_developer_details( $post_id ) {
	if ( ! isset( $_POST['aqar_developer_details_nonce'] ) || ! is_string( $_POST['aqar_developer_details_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['aqar_developer_details_nonce'] ), 'aqar_save_developer_details' ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'_aqar_developer_description' => array( 'aqar_developer_description', 'sanitize_textarea_field' ),
		'_aqar_developer_location'    => array( 'aqar_developer_location', 'sanitize_text_field' ),
		'_aqar_developer_phone'       => array( 'aqar_developer_phone', 'sanitize_text_field' ),
		'_aqar_developer_website'     => array( 'aqar_developer_website', 'esc_url_raw' ),
	);

	foreach ( $fields as $meta_key => $field ) {
		if ( isset( $_POST[ $field[0] ] ) && is_scalar( $_POST[ $field[0] ] ) ) {
			update_post_meta( $post_id, $meta_key, call_user_func( $field[1], wp_unslash( $_POST[ $field[0] ] ) ) );
		}
	}

	update_post_meta( $post_id, '_aqar_developer_featured', isset( $_POST['aqar_developer_featured'] ) ? '1' : '0' );

	$project_ids = isset( $_POST['aqar_developer_projects'] ) && is_array( $_POST['aqar_developer_projects'] )
		? array_values( array_unique( array_filter( array_map( 'absint', wp_unslash( $_POST['aqar_developer_projects'] ) ) ) ) )
		: array();
	$project_ids = array_filter(
		$project_ids,
		function ( $project_id ) {
			return 'aqar_project' === get_post_type( $project_id );
		}
	);

	if ( $project_ids ) {
		update_post_meta( $post_id, '_aqar_developer_projects', $project_ids );
	} else {
		delete_post_meta( $post_id, '_aqar_developer_projects' );
	}
}
add_action( 'save_post_aqar_developer', 'aqar_save_developer_details' );

function aqar_city_image_field( $term = null ) {
	$image_id = $term ? absint( get_term_meta( $term->term_id, '_aqar_city_image', true ) ) : 0;
	$faqs     = $term ? get_term_meta( $term->term_id, '_aqar_city_faqs', true ) : '';
	?>
	<div class="aqar-city-image-field">
		<?php wp_nonce_field( 'aqar_save_city_details', 'aqar_city_details_nonce' ); ?>
		<input type="hidden" name="aqar_city_image" value="<?php echo esc_attr( $image_id ); ?>">
		<button type="button" class="button aqar-city-image-select"><?php esc_html_e( 'اختيار صورة المدينة', 'aqar' ); ?></button>
		<button type="button" class="button aqar-city-image-remove" <?php disabled( ! $image_id ); ?>><?php esc_html_e( 'إزالة الصورة', 'aqar' ); ?></button>
		<div class="aqar-city-image-preview"><?php echo $image_id ? wp_get_attachment_image( $image_id, 'thumbnail' ) : ''; ?></div>
		<p>
			<label>
				<input type="checkbox" name="aqar_city_featured" value="1" <?php checked( $term && get_term_meta( $term->term_id, '_aqar_city_featured', true ), '1' ); ?>>
				<?php esc_html_e( 'إظهار المدينة ضمن المدن المميزة', 'aqar' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" name="aqar_city_best" value="1" <?php checked( $term && get_term_meta( $term->term_id, '_aqar_city_best', true ), '1' ); ?>>
				<?php esc_html_e( 'إظهار المدينة ضمن أفضل المدن', 'aqar' ); ?>
			</label>
		</p>
		<p>
			<label for="aqar-city-faqs"><strong><?php esc_html_e( 'الأسئلة الشائعة في صفحة المدينة', 'aqar' ); ?></strong></label>
			<br><span class="description"><?php esc_html_e( 'سطر لكل سؤال وإجابة بالصيغة: السؤال | الإجابة.', 'aqar' ); ?></span>
			<textarea id="aqar-city-faqs" name="aqar_city_faqs" rows="5" class="widefat"><?php echo esc_textarea( $faqs ); ?></textarea>
		</p>
	</div>
	<?php
}

function aqar_add_city_fields() {
	?>
	<div class="form-field">
		<label><?php esc_html_e( 'صورة المدينة وأقسام الصفحة الرئيسية', 'aqar' ); ?></label>
		<?php aqar_city_image_field(); ?>
	</div>
	<?php
}
add_action( 'aqar_city_add_form_fields', 'aqar_add_city_fields' );

function aqar_edit_city_fields( $term ) {
	?>
	<tr class="form-field">
		<th scope="row"><label><?php esc_html_e( 'صورة المدينة وأقسام الصفحة الرئيسية', 'aqar' ); ?></label></th>
		<td><?php aqar_city_image_field( $term ); ?></td>
	</tr>
	<?php
}
add_action( 'aqar_city_edit_form_fields', 'aqar_edit_city_fields' );

function aqar_save_city_fields( $term_id ) {
	if ( ! isset( $_POST['aqar_city_details_nonce'] ) || ! is_string( $_POST['aqar_city_details_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['aqar_city_details_nonce'] ), 'aqar_save_city_details' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	$image_id = isset( $_POST['aqar_city_image'] ) && is_scalar( $_POST['aqar_city_image'] ) ? absint( wp_unslash( $_POST['aqar_city_image'] ) ) : 0;
	if ( $image_id && ! wp_attachment_is_image( $image_id ) ) {
		$image_id = 0;
	}
	if ( $image_id ) {
		update_term_meta( $term_id, '_aqar_city_image', $image_id );
	} else {
		delete_term_meta( $term_id, '_aqar_city_image' );
	}

	update_term_meta( $term_id, '_aqar_city_featured', isset( $_POST['aqar_city_featured'] ) ? '1' : '0' );
	update_term_meta( $term_id, '_aqar_city_best', isset( $_POST['aqar_city_best'] ) ? '1' : '0' );
	if ( isset( $_POST['aqar_city_faqs'] ) && is_scalar( $_POST['aqar_city_faqs'] ) ) {
		update_term_meta( $term_id, '_aqar_city_faqs', sanitize_textarea_field( wp_unslash( $_POST['aqar_city_faqs'] ) ) );
	}
}
add_action( 'created_aqar_city', 'aqar_save_city_fields' );
add_action( 'edited_aqar_city', 'aqar_save_city_fields' );

function aqar_enqueue_city_admin_assets( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'edit-tags.php', 'term.php' ), true ) || ! isset( $_GET['taxonomy'] ) || ! is_string( $_GET['taxonomy'] ) || 'aqar_city' !== sanitize_key( wp_unslash( $_GET['taxonomy'] ) ) ) {
		return;
	}

	wp_enqueue_media();
	$script_path = get_theme_file_path( '/assets/js/city-term-image.js' );
	wp_enqueue_script(
		'aqar-city-term-image',
		get_theme_file_uri( '/assets/js/city-term-image.js' ),
		array( 'jquery' ),
		file_exists( $script_path ) ? (string) filemtime( $script_path ) : wp_get_theme()->get( 'Version' ),
		true
	);
	wp_localize_script(
		'aqar-city-term-image',
		'aqarCityImagePicker',
		array(
			'title'  => __( 'اختيار صورة المدينة', 'aqar' ),
			'button' => __( 'استخدام الصورة', 'aqar' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'aqar_enqueue_city_admin_assets' );

function aqar_enqueue_project_admin_assets( $hook_suffix ) {
	if ( 'post-new.php' === $hook_suffix && ( ! isset( $_GET['post_type'] ) || ! is_string( $_GET['post_type'] ) || 'aqar_project' !== sanitize_key( wp_unslash( $_GET['post_type'] ) ) ) ) {
		return;
	}
	if ( 'post.php' === $hook_suffix && ( ! isset( $_GET['post'] ) || ! is_scalar( $_GET['post'] ) || 'aqar_project' !== get_post_type( absint( wp_unslash( $_GET['post'] ) ) ) ) ) {
		return;
	}
	if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	wp_enqueue_media();
	$script_path = get_theme_file_path( '/assets/js/project-admin-media.js' );
	wp_enqueue_script(
		'aqar-project-admin-media',
		get_theme_file_uri( '/assets/js/project-admin-media.js' ),
		array( 'jquery' ),
		file_exists( $script_path ) ? (string) filemtime( $script_path ) : wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'aqar_enqueue_project_admin_assets' );

function aqar_render_project_cards( $featured_only = false, $posts_per_page = 6, $paged = 1, $city_term_id = 0 ) {
	$query_args = array(
		'post_type'           => 'aqar_project',
		'post_status'         => 'publish',
		'posts_per_page'      => absint( $posts_per_page ),
		'paged'               => max( 1, absint( $paged ) ),
		'ignore_sticky_posts' => true,
		'orderby'             => array(
			'date' => 'DESC',
			'ID'   => 'DESC',
		),
		'order'               => 'DESC',
	);

	if ( $city_term_id ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'aqar_city',
				'field'    => 'term_id',
				'terms'    => absint( $city_term_id ),
			),
		);
	}

	if ( $featured_only ) {
		$query_args['meta_query'] = array(
			array(
				'key'     => '_aqar_project_featured',
				'value'   => '1',
				'compare' => '=',
			),
		);
	}

	$projects = new WP_Query( $query_args );

	if ( $projects->have_posts() ) :
		while ( $projects->have_posts() ) :
			$projects->the_post();
			$description = get_post_meta( get_the_ID(), '_aqar_project_description', true );
			$location    = get_post_meta( get_the_ID(), '_aqar_project_location', true );
			$badge       = get_post_meta( get_the_ID(), '_aqar_project_badge', true );
			?>
			<article class="project-card">
				<a class="project-card__image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( 'تفاصيل %s', get_the_title() ) ); ?>">
					<div class="thumb">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'project-image' ) ); ?>
						<?php else : ?>
							<img class="project-image" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
						<?php endif; ?>
						<span><?php echo esc_html( $badge ? $badge : ( $featured_only ? 'الأفضل' : 'جديد' ) ); ?></span>
					</div>
				</a>
				<div class="card-copy">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( $description ) : ?>
						<p><?php echo esc_html( wp_trim_words( $description, 18 ) ); ?></p>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<small><?php echo esc_html( $location ); ?></small>
					<?php endif; ?>
				</div>
			</article>
			<?php
		endwhile;
	else :
		echo '<p class="aqar-empty-state">' . esc_html__( 'لا توجد مشاريع لعرضها حاليًا.', 'aqar' ) . '</p>';
	endif;

	wp_reset_postdata();

	return $projects;
}

function aqar_render_city_cards( $section, $posts_per_page = 6 ) {
	$meta_key = array(
		'featured' => '_aqar_city_featured',
		'best'     => '_aqar_city_best',
		'latest'   => '',
		'all'      => '',
	);

	if ( ! isset( $meta_key[ $section ] ) ) {
		return;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'aqar_city',
			'hide_empty' => false,
			'number'     => -1 === $posts_per_page ? 0 : absint( $posts_per_page ),
			'orderby'    => 'term_id',
			'order'      => 'latest' === $section ? 'DESC' : 'ASC',
			'meta_query' => $meta_key[ $section ] ? array(
				array(
					'key'     => $meta_key[ $section ],
					'value'   => '1',
					'compare' => '=',
				),
			) : array(),
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		echo '<p class="aqar-empty-state">' . esc_html__( 'لا توجد مدن لعرضها حاليًا.', 'aqar' ) . '</p>';
		return;
	}

	$card_modifiers = array( '', 'image-card--two', 'image-card--three', 'image-card--four', 'image-card--five', 'image-card--six' );
	foreach ( $terms as $index => $term ) {
		$term_link = get_term_link( $term );
		if ( is_wp_error( $term_link ) ) {
			continue;
		}

		$card_class = 'image-card';
		if ( ! empty( $card_modifiers[ $index ] ) ) {
			$card_class .= ' ' . $card_modifiers[ $index ];
		}

		$image_id  = absint( get_term_meta( $term->term_id, '_aqar_city_image', true ) );
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : false;
		if ( ! $image_url ) {
			$image_url = get_theme_file_uri( '/assets/images/مدينة-التجمع-الخامس-1.webp' );
		}
		?>
		<a class="<?php echo esc_attr( $card_class ); ?>" href="<?php echo esc_url( $term_link ); ?>">
			<img class="city-image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>">
			<div class="city-card__content"><h3><?php echo esc_html( $term->name ); ?></h3></div>
		</a>
		<?php
	}
}

function aqar_render_developer_cards( $featured_only = false, $posts_per_page = 6, $paged = 1 ) {
	$query_args = array(
		'post_type'           => 'aqar_developer',
		'post_status'         => 'publish',
		'posts_per_page'      => -1 === $posts_per_page ? -1 : absint( $posts_per_page ),
		'paged'               => max( 1, absint( $paged ) ),
		'ignore_sticky_posts' => true,
		'orderby'             => array(
			'date' => 'DESC',
			'ID'   => 'DESC',
		),
	);

	if ( $featured_only ) {
		$query_args['meta_query'] = array(
			array(
				'key'     => '_aqar_developer_featured',
				'value'   => '1',
				'compare' => '=',
			),
		);
	}

	$developers = new WP_Query( $query_args );

	if ( $developers->have_posts() ) :
		while ( $developers->have_posts() ) :
			$developers->the_post();
			?>
			<a class="card card--grid flex-col is-stretched developer-card" href="<?php the_permalink(); ?>">
				<div class="card__media">
					<figure class="card__frame aspect-frame">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'card__image' ) ); ?>
						<?php else : ?>
							<img class="card__image" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/شركة-بالم-هيلز-للتطوير-العقاري-1.webp' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
						<?php endif; ?>
					</figure>
				</div>
				<div class="card__content">
					<h3 class="card__title"><?php the_title(); ?></h3>
				</div>
			</a>
			<?php
		endwhile;
	else :
		echo '<p class="aqar-empty-state">' . esc_html__( 'لا يوجد مطورون لعرضهم حاليًا.', 'aqar' ) . '</p>';
	endif;

	wp_reset_postdata();

	return $developers;
}

function aqar_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'aqar_contact_data',
		array(
			'title'    => __( 'بيانات التواصل', 'aqar' ),
			'priority' => 35,
		)
	);

	$settings = array(
		'aqar_contact_logo' => array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		),
		'aqar_phone' => array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'aqar_whatsapp' => array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'aqar_social_youtube' => array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		),
		'aqar_social_instagram' => array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		),
		'aqar_social_linkedin' => array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		),
		'aqar_social_facebook' => array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		),
		'aqar_social_x' => array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		),
		'aqar_footer_description' => array(
			'default'           => 'موقع عقار هو موقع متخصص في بيع الشقق والفيلات والمنازل والمشاريع العقارية في جميع أنحاء مصر. نحن نقدم حلولًا ذكية للعقارات السكنية والتجارية والاستثمارية مع دعم احترافي ومتابعة مستمرة.',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'aqar_footer_cta' => array(
			'default'           => 'هل تبحث عن عقارك القادم؟ فريق نيو عقار جاهز لمساعدتك.',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'aqar_footer_copyright' => array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		),
	);

	foreach ( $settings as $setting_id => $setting_args ) {
		$wp_customize->add_setting(
			$setting_id,
			array_merge(
				array(
					'type'              => 'theme_mod',
					'transport'         => 'refresh',
					'sanitize_callback' => 'sanitize_text_field',
				),
				$setting_args
			)
		);
	}

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aqar_contact_logo',
			array(
				'label'   => __( 'شعار الموقع', 'aqar' ),
				'section' => 'aqar_contact_data',
			)
		)
	);

	$controls = array(
		'aqar_phone' => array(
			'label'   => __( 'رقم الهاتف', 'aqar' ),
			'type'    => 'text',
		),
		'aqar_whatsapp' => array(
			'label'   => __( 'رقم واتساب مع مفتاح الدولة', 'aqar' ),
			'type'    => 'text',
		),
		'aqar_social_youtube' => array(
			'label'   => __( 'رابط يوتيوب', 'aqar' ),
			'type'    => 'url',
		),
		'aqar_social_instagram' => array(
			'label'   => __( 'رابط إنستغرام', 'aqar' ),
			'type'    => 'url',
		),
		'aqar_social_linkedin' => array(
			'label'   => __( 'رابط لينكد إن', 'aqar' ),
			'type'    => 'url',
		),
		'aqar_social_facebook' => array(
			'label'   => __( 'رابط فيسبوك', 'aqar' ),
			'type'    => 'url',
		),
		'aqar_social_x' => array(
			'label'   => __( 'رابط X', 'aqar' ),
			'type'    => 'url',
		),
		'aqar_footer_description' => array(
			'label'   => __( 'النص أسفل الشعار في الفوتر', 'aqar' ),
			'type'    => 'textarea',
		),
		'aqar_footer_cta' => array(
			'label'   => __( 'نص دعوة التواصل في الفوتر', 'aqar' ),
			'type'    => 'text',
		),
		'aqar_footer_copyright' => array(
			'label'   => __( 'نص حقوق النشر', 'aqar' ),
			'type'    => 'text',
		),
	);

	foreach ( $controls as $control_id => $control_args ) {
		$wp_customize->add_control(
			$control_id,
			array_merge(
				$control_args,
				array(
					'section' => 'aqar_contact_data',
				)
			)
		);
	}
}
add_action( 'customize_register', 'aqar_customize_register' );

function aqar_nav_menu_link_attributes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$existing_classes = isset( $atts['class'] ) ? $atts['class'] : '';
		$atts['class']   = trim( $existing_classes . ' nav-link' );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqar_nav_menu_link_attributes', 10, 3 );
