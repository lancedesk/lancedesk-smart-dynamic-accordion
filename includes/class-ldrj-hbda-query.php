<?php
/**
 * Dynamic accordion post query helpers.
 *
 * @package LanceDeskSmartDynamicAccordion
 */

namespace LanceDesk\HBDA;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared query and item normalization for dynamic accordion sources.
 */
final class Query {

	/**
	 * Build WP_Query arguments from widget settings.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param int                 $posts_per_page Posts per page.
	 * @param int                 $offset Query offset.
	 *
	 * @return array<string,mixed>
	 */
	public static function ldrj_hbda_build_query_args( array $settings, int $posts_per_page, int $offset = 0 ): array {
		$post_type = isset( $settings['ldrj_hbda_post_type'] ) ? sanitize_key( (string) $settings['ldrj_hbda_post_type'] ) : 'post';
		$order     = isset( $settings['ldrj_hbda_order'] ) && in_array( $settings['ldrj_hbda_order'], array( 'ASC', 'DESC' ), true ) ? $settings['ldrj_hbda_order'] : 'DESC';
		$orderby   = isset( $settings['ldrj_hbda_orderby'] ) ? sanitize_key( (string) $settings['ldrj_hbda_orderby'] ) : 'date';

		$allowed_orderby = array( 'date', 'title', 'menu_order', 'rand' );
		if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
			$orderby = 'date';
		}

		if ( $posts_per_page < 1 ) {
			$posts_per_page = 6;
		}

		$include_ids = isset( $settings['ldrj_hbda_include_ids'] ) ? self::ldrj_hbda_parse_csv_ids( (string) $settings['ldrj_hbda_include_ids'] ) : array();
		$exclude_ids = isset( $settings['ldrj_hbda_exclude_ids'] ) ? self::ldrj_hbda_parse_csv_ids( (string) $settings['ldrj_hbda_exclude_ids'] ) : array();

		$query_args = array(
			'post_type'              => $post_type,
			'post_status'            => 'publish',
			'posts_per_page'         => $posts_per_page,
			'orderby'                => $orderby,
			'order'                  => $order,
			'offset'                 => max( 0, $offset ),
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		if ( ! empty( $include_ids ) ) {
			$query_args['post__in'] = $include_ids;
		}

		if ( ! empty( $exclude_ids ) ) {
			$query_args['post__not_in'] = $exclude_ids;
		}

		$tax_query = self::ldrj_hbda_build_tax_query( $settings );
		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query;
		}

		return $query_args;
	}

	/**
	 * Build tax_query clauses from category, tag, and advanced taxonomy settings.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 *
	 * @return array<int|string,mixed>
	 */
	public static function ldrj_hbda_build_tax_query( array $settings ): array {
		$clauses  = array();
		$relation = isset( $settings['ldrj_hbda_taxonomy_relation'] ) && 'OR' === $settings['ldrj_hbda_taxonomy_relation'] ? 'OR' : 'AND';

		$category_terms = self::ldrj_hbda_parse_compound_terms( $settings['ldrj_hbda_category_terms'] ?? array() );
		$tag_terms      = self::ldrj_hbda_parse_compound_terms( $settings['ldrj_hbda_tag_terms'] ?? array() );

		foreach ( array( $category_terms, $tag_terms ) as $grouped_terms ) {
			foreach ( $grouped_terms as $taxonomy => $term_ids ) {
				if ( '' === $taxonomy || ! taxonomy_exists( $taxonomy ) || empty( $term_ids ) ) {
					continue;
				}

				$clauses[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_ids,
				);
			}
		}

		$taxonomy      = isset( $settings['ldrj_hbda_taxonomy'] ) ? sanitize_key( (string) $settings['ldrj_hbda_taxonomy'] ) : '';
		$tax_terms_raw = isset( $settings['ldrj_hbda_tax_terms'] ) ? (string) $settings['ldrj_hbda_tax_terms'] : '';
		$tax_field     = isset( $settings['ldrj_hbda_tax_field'] ) && in_array( $settings['ldrj_hbda_tax_field'], array( 'slug', 'term_id' ), true ) ? $settings['ldrj_hbda_tax_field'] : 'slug';

		if ( '' !== $taxonomy && taxonomy_exists( $taxonomy ) && '' !== trim( $tax_terms_raw ) ) {
			if ( 'term_id' === $tax_field ) {
				$terms = self::ldrj_hbda_parse_csv_ids( $tax_terms_raw );
			} else {
				$terms = self::ldrj_hbda_parse_csv_slugs( $tax_terms_raw );
			}

			if ( ! empty( $terms ) ) {
				$clauses[] = array(
					'taxonomy' => $taxonomy,
					'field'    => $tax_field,
					'terms'    => $terms,
				);
			}
		}

		if ( empty( $clauses ) ) {
			return array();
		}

		if ( count( $clauses ) > 1 ) {
			$clauses['relation'] = $relation;
		}

		return $clauses;
	}

	/**
	 * Count matching posts for load-more totals.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 *
	 * @return int
	 */
	public static function ldrj_hbda_count_posts( array $settings ): int {
		$query_args                   = self::ldrj_hbda_build_query_args( $settings, 1, 0 );
		$query_args['posts_per_page'] = 1;
		$query_args['fields']         = 'ids';

		$query = new \WP_Query( $query_args );

		return (int) $query->found_posts;
	}

	/**
	 * Fetch normalized accordion items from dynamic query settings.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param int                 $posts_per_page Posts per page.
	 * @param int                 $offset Query offset.
	 *
	 * @return array<int,array<string,string>>
	 */
	public static function ldrj_hbda_get_dynamic_items( array $settings, int $posts_per_page, int $offset = 0 ): array {
		$query_args = self::ldrj_hbda_build_query_args( $settings, $posts_per_page, $offset );
		$posts      = get_posts( $query_args );
		$items      = array();

		$title_source       = isset( $settings['ldrj_hbda_title_source'] ) ? sanitize_key( (string) $settings['ldrj_hbda_title_source'] ) : 'title';
		$title_meta_key     = isset( $settings['ldrj_hbda_title_meta_key'] ) ? sanitize_key( (string) $settings['ldrj_hbda_title_meta_key'] ) : '';
		$content_source     = isset( $settings['ldrj_hbda_content_source'] ) ? sanitize_key( (string) $settings['ldrj_hbda_content_source'] ) : 'content';
		$meta_key           = isset( $settings['ldrj_hbda_meta_key'] ) ? sanitize_key( (string) $settings['ldrj_hbda_meta_key'] ) : '';
		$meta_format        = isset( $settings['ldrj_hbda_meta_value_format'] ) ? sanitize_key( (string) $settings['ldrj_hbda_meta_value_format'] ) : 'auto';
		$show_read_more     = isset( $settings['ldrj_hbda_show_read_more'] ) && 'yes' === $settings['ldrj_hbda_show_read_more'];
		$read_more_text     = isset( $settings['ldrj_hbda_read_more_text'] ) ? sanitize_text_field( (string) $settings['ldrj_hbda_read_more_text'] ) : esc_html__( 'Read more', 'lancedesk-smart-dynamic-accordion' );
		$read_more_new_tab  = isset( $settings['ldrj_hbda_read_more_new_tab'] ) && 'yes' === $settings['ldrj_hbda_read_more_new_tab'];
		$read_more_nofollow = isset( $settings['ldrj_hbda_read_more_nofollow'] ) && 'yes' === $settings['ldrj_hbda_read_more_nofollow'];

		global $post;
		$original_post = $post;

		foreach ( $posts as $query_post ) {
			$post = $query_post;
			setup_postdata( $post );

			$title   = sanitize_text_field( get_the_title( $post ) );
			$content = '';

			if ( 'meta' === $title_source && '' !== $title_meta_key ) {
				$raw_title = get_post_meta( $post->ID, $title_meta_key, true );
				if ( is_scalar( $raw_title ) ) {
					$title = sanitize_text_field( (string) $raw_title );
				}
			}

			if ( 'excerpt' === $content_source ) {
				$raw_excerpt = has_excerpt( $post ) ? $post->post_excerpt : wp_trim_words( wp_strip_all_tags( (string) $post->post_content ), 35 );
				$content     = wpautop( esc_html( $raw_excerpt ) );
			} elseif ( 'meta' === $content_source && '' !== $meta_key ) {
				$raw_meta = get_post_meta( $post->ID, $meta_key, true );
				if ( is_scalar( $raw_meta ) ) {
					$content = self::ldrj_hbda_format_meta_content( (string) $raw_meta, $meta_format );
				}
			} else {
				$content = apply_filters( 'the_content', get_the_content( null, false, $post ) );
			}

			if ( '' === $content && has_post_thumbnail( $post ) ) {
				$content = wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), 'large' );
			}

			if ( '' === $title && '' === trim( wp_strip_all_tags( $content ) ) ) {
				continue;
			}

			$items[] = array(
				'title'              => $title,
				'content'            => $content,
				'url'                => $show_read_more ? (string) get_permalink( $post ) : '',
				'read_more_label'    => $read_more_text,
				'read_more_new_tab'  => $read_more_new_tab ? 'yes' : 'no',
				'read_more_nofollow' => $read_more_nofollow ? 'yes' : 'no',
			);
		}

		wp_reset_postdata();
		$post = $original_post;

		return $items;
	}

	/**
	 * Build SELECT2 options for hierarchical or flat taxonomies of a post type.
	 *
	 * @param string $post_type Post type slug.
	 * @param bool   $hierarchical True for category-like taxonomies.
	 *
	 * @return array<string,string>
	 */
	public static function ldrj_hbda_get_taxonomy_term_options( string $post_type, bool $hierarchical ): array {
		$post_type = sanitize_key( $post_type );
		if ( '' === $post_type ) {
			$post_type = 'post';
		}

		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$options    = array();

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! $taxonomy instanceof \WP_Taxonomy ) {
				continue;
			}

			if ( ! $taxonomy->public && ! $taxonomy->publicly_queryable ) {
				continue;
			}

			if ( (bool) $taxonomy->hierarchical !== $hierarchical ) {
				continue;
			}

			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy->name,
					'hide_empty' => false,
				)
			);

			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				continue;
			}

			foreach ( $terms as $term ) {
				if ( ! $term instanceof \WP_Term ) {
					continue;
				}

				$key             = $taxonomy->name . ':' . (int) $term->term_id;
				$options[ $key ] = sprintf(
					/* translators: 1: taxonomy singular label, 2: term name. */
					__( '%1$s: %2$s', 'lancedesk-smart-dynamic-accordion' ),
					$taxonomy->labels->singular_name,
					$term->name
				);
			}
		}

		asort( $options );

		return $options;
	}

	/**
	 * Format dynamic meta value based on selected type.
	 *
	 * @param string $raw_value Raw meta value.
	 * @param string $meta_format Selected format.
	 *
	 * @return string
	 */
	public static function ldrj_hbda_format_meta_content( string $raw_value, string $meta_format ): string {
		$value = trim( $raw_value );

		if ( '' === $value ) {
			return '';
		}

		if ( 'image_url' === $meta_format ) {
			$url = esc_url( $value );
			if ( '' !== $url ) {
				return '<img src="' . $url . '" alt="" loading="lazy" />';
			}
			return '';
		}

		if ( 'text' === $meta_format ) {
			return wpautop( esc_html( $value ) );
		}

		if ( 'html' === $meta_format ) {
			return wp_kses_post( $value );
		}

		if ( wp_http_validate_url( $value ) && preg_match( '/\.(jpg|jpeg|png|gif|webp|avif|svg)(\?.*)?$/i', $value ) ) {
			$url = esc_url( $value );
			if ( '' !== $url ) {
				return '<img src="' . $url . '" alt="" loading="lazy" />';
			}
		}

		if ( wp_strip_all_tags( $value ) !== $value ) {
			return wp_kses_post( $value );
		}

		return wpautop( esc_html( $value ) );
	}

	/**
	 * Parse compound taxonomy:term_id keys into grouped term IDs.
	 *
	 * @param array<int,string>|string $raw_terms Selected control values.
	 *
	 * @return array<string,array<int,int>>
	 */
	public static function ldrj_hbda_parse_compound_terms( $raw_terms ): array {
		if ( ! is_array( $raw_terms ) ) {
			return array();
		}

		$grouped = array();

		foreach ( $raw_terms as $compound ) {
			$compound = sanitize_text_field( (string) $compound );
			if ( false === strpos( $compound, ':' ) ) {
				continue;
			}

			list( $taxonomy, $term_id ) = array_pad( explode( ':', $compound, 2 ), 2, '' );
			$taxonomy                   = sanitize_key( $taxonomy );
			$term_id                    = absint( $term_id );

			if ( '' === $taxonomy || $term_id < 1 || ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			if ( ! isset( $grouped[ $taxonomy ] ) ) {
				$grouped[ $taxonomy ] = array();
			}

			$grouped[ $taxonomy ][] = $term_id;
		}

		foreach ( $grouped as $taxonomy => $term_ids ) {
			$grouped[ $taxonomy ] = array_values( array_unique( array_map( 'absint', $term_ids ) ) );
		}

		return $grouped;
	}

	/**
	 * Parse comma-separated IDs safely.
	 *
	 * @param string $value Raw csv value.
	 *
	 * @return array<int,int>
	 */
	public static function ldrj_hbda_parse_csv_ids( string $value ): array {
		$parts = explode( ',', $value );
		$ids   = array();

		foreach ( $parts as $part ) {
			$id = absint( trim( $part ) );
			if ( $id > 0 ) {
				$ids[] = $id;
			}
		}

		return array_values( array_unique( $ids ) );
	}

	/**
	 * Parse comma-separated slugs safely.
	 *
	 * @param string $value Raw csv value.
	 *
	 * @return array<int,string>
	 */
	public static function ldrj_hbda_parse_csv_slugs( string $value ): array {
		$parts = explode( ',', $value );
		$out   = array();

		foreach ( $parts as $part ) {
			$slug = sanitize_title( trim( $part ) );
			if ( '' !== $slug ) {
				$out[] = $slug;
			}
		}

		return array_values( array_unique( $out ) );
	}
}
