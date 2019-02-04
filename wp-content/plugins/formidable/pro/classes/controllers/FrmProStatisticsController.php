<?php

class FrmProStatisticsController {

	/**
	 * Returns stats requested through the [frm-stats] shortcode
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function stats_shortcode( $atts ) {
		$defaults = array(
			'id' => false, //the ID of the field to show stats for
			'type' => 'total', //total, count, average, median, deviation, star, minimum, maximum, unique
			'user_id' => false, //limit the stat to a specific user id or "current"
			'value' => false, //only count entries with a specific value
			'round' => 100, //how many digits to round to
			'limit' => '', //limit the number of entries used in this calculation
			'drafts' => false, //don't include drafts by default
			//any other field ID in the form => the value it should be equal to
			//'entry_id' => show only for a specific entry ie if you want to show a star rating for a single entry
			//'thousands_sep' => set thousands separator

		);

		$sc_atts = shortcode_atts( $defaults, $atts );
		// Combine arrays - DO NOT use array_merge here because numeric keys are renumbered
		$atts = (array)$atts + (array)$sc_atts;

		if ( ! $atts['id'] ) {
			return '';
		}

		$atts['user_id'] = FrmAppHelper::get_user_id_param( $atts['user_id'] );

		$new_atts = $atts;
		foreach ( $defaults as $unset => $val ) {
			unset( $new_atts[ $unset ] );
		}

		return self::get_field_stats(
			$atts['id'], $atts['type'], $atts['user_id'], $atts['value'],
			$atts['round'], $atts['limit'], $new_atts, $atts['drafts']
		);
	}

	private static function get_field_stats( $id, $type = 'total', $user_id = false, $value = false, $round = 100, $limit = '', $atts = array(), $drafts = false ) {
		global $wpdb, $frm_post_ids;

		$field = FrmField::getOne( $id );

		if ( ! $field ) {
			return 0;
		}

		$id = $field->id;

		if ( isset( $atts['thousands_sep'] ) && $atts['thousands_sep'] ) {
			$thousands_sep = $atts['thousands_sep'];
			unset( $atts['thousands_sep'] );
			$round = ( $round == 100 ? 2 : $round );
		}

		$where = array();
		if ( $value ) {
			$slash_val = ( strpos( $value, '\\' ) === false ) ? addslashes( $value ) : $value;
			if ( FrmField::is_field_with_multiple_values( $field ) ) {
				$where[] = array( 'or' => 1, 'meta_value like' => $value, 'meta_value like ' => $slash_val );
				//add extra slashes to match values that are escaped in the database
			} else {
				//$where_value = $wpdb->prepare(" meta_value = %s", addcslashes( $slash_val, '_%' ) );
				$where[] = array( 'or' => 1, 'meta_value' => $value, 'meta_value ' => addcslashes( $slash_val, '_%' ) );
			}
			unset( $slash_val );
		}

		//if(!$frm_post_ids)
		$frm_post_ids = array();

		$post_ids = array();

		if ( isset( $frm_post_ids[ $id ] ) ) {
			$form_posts = $frm_post_ids[ $id ];
		} else {
			$where_post = array( 'form_id' => $field->form_id, 'post_id >' => 1 );
			if ( $drafts != 'both' ) {
				$where_post['is_draft'] = $drafts;
			}
			if ( $user_id ) {
				$where_post['user_id'] = $user_id;
			}

			$form_posts = FrmDb::get_results( 'frm_items', $where_post, 'id,post_id' );

			$frm_post_ids[ $id ] = $form_posts;
		}

		foreach ( (array)$form_posts as $form_post ) {
			$post_ids[ $form_post->id ] = $form_post->post_id;
		}

		if ( $value ) {
			$atts[ $id ] = $value;
		}

		if ( ! empty( $atts ) ) {
			$entry_ids = array();

			if ( isset( $atts['entry_id'] ) && $atts['entry_id'] && is_numeric( $atts['entry_id'] ) ) {
				$entry_ids[] = $atts['entry_id'];
			}

			$after_where = false;

			foreach ( $atts as $orig_f => $val ) {
				// Accommodate for times when users are in Visual tab
				$val = str_replace( array( '&gt;', '&lt;' ), array( '>', '<' ), $val );

				// If first character is a quote, but the last character is not a quote
				if ( ( strpos( $val, '"' ) === 0 && substr( $val, -1 ) != '"' ) || ( strpos( $val, "'" ) === 0 && substr( $val, -1 ) != "'" ) ) {
					//parse atts back together if they were broken at spaces
					$next_val = array( 'char' => substr( $val, 0, 1 ), 'val' => $val );
					continue;
					// If we don't have a previous value that needs to be parsed back together
				} else if ( ! isset( $next_val ) ) {
					$temp = FrmAppHelper::replace_quotes( $val );
					foreach ( array( '"', "'" ) as $q ) {
						// Check if <" or >" exists in string and string does not end with ".
						if ( substr( $temp, -1 ) != $q && ( strpos( $temp, '<' . $q ) || strpos( $temp, '>' . $q ) ) ) {
							$next_val = array( 'char' => $q, 'val' => $val );
							$cont = true;
						}
						unset( $q );
					}
					unset( $temp );

					if ( isset( $cont ) ) {
						unset( $cont );
						continue;
					}
				}

				// If we have a previous value saved that needs to be parsed back together (due to WordPress pullling it apart)
				if ( isset( $next_val ) ) {
					if ( substr( FrmAppHelper::replace_quotes( $val ), -1 ) == $next_val['char'] ) {
						$val = $next_val['val'] . ' ' . $val;
						unset( $next_val );
					} else {
						$next_val['val'] .= ' ' . $val;
						continue;
					}
				}

				$form_id = $field->form_id;
				$entry_ids = self::get_field_matches( compact( 'entry_ids', 'orig_f', 'val', 'form_id', 'form_posts', 'after_where', 'drafts' ) );
				$after_where = true;
			}

			if ( empty( $entry_ids ) ) {
				if ( $type == 'star' ) {
					$stat = '';
					ob_start();
					include( FrmAppHelper::plugin_path() . '/pro/classes/views/frmpro-fields/star_disabled.php' );
					$contents = ob_get_contents();
					ob_end_clean();
					return $contents;
				} else {
					return 0;
				}
			}

			foreach ( $post_ids as $entry_id => $post_id ) {
				if ( ! in_array( $entry_id, $entry_ids ) ) {
					unset( $post_ids[ $entry_id ] );
				}
			}

			$where['it.item_id'] = $entry_ids;
		}

		$join = '';

		if ( is_numeric( $id ) ) {
			$where['field_id'] = $id;
		} else {
			$join .= ' LEFT OUTER JOIN ' . $wpdb->prefix . 'frm_fields fi ON it.field_id=fi.id';
			$where['fi.field_key'] = $id;
		}

		if ( $user_id ) {
			$where['en.user_id'] = $user_id;
		}

		$join .= ' LEFT OUTER JOIN ' . $wpdb->prefix . 'frm_items en ON en.id=it.item_id';
		if ( $drafts != 'both' ) {
			$where['en.is_draft'] = $drafts;
		}

		$field_metas = FrmDb::get_col( $wpdb->prefix . 'frm_item_metas it ' . $join, $where, 'meta_value', array( 'order_by' => 'it.created_at DESC', 'limit' => $limit ) );

		if ( ! empty( $post_ids ) ) {
			if ( FrmField::is_option_true( $field, 'post_field' ) ) {
				if ( $field->field_options['post_field'] == 'post_custom' ) { //get custom post field value
					$post_values = FrmDb::get_col( $wpdb->postmeta, array( 'meta_key' => $field->field_options['custom_field'], 'post_id' => $post_ids ), 'meta_value' );
				} else if ( $field->field_options['post_field'] == 'post_category' ) {
					$post_query = array( 'tt.taxonomy' => $field->field_options['taxonomy'], 'tr.object_id' => $post_ids );

					if ( $value ) {
						$post_query[] = array( 'or' => 1, 't.term_id' => $value, 't.slug' => $value, 't.name' => $value );
					}

					$post_values = FrmDb::get_col( $wpdb->terms . ' AS t INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt ON tt.term_id = t.term_id INNER JOIN ' . $wpdb->term_relationships . ' AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id', $post_query, 'tr.object_id' );
					$post_values = array_unique( $post_values );
				} else {
					$post_values = FrmDb::get_results( $wpdb->posts, array( 'ID' => $post_ids ), $field->field_options['post_field'] );
				}

				$field_metas = array_merge( $post_values, $field_metas );
			}
		}

		if ( $type != 'star' ) {
			unset( $field );
		}

		if ( empty( $field_metas ) ) {
			if ( $type == 'star' ) {
				$stat = '';
				ob_start();
				include( FrmAppHelper::plugin_path() . '/pro/classes/views/frmpro-fields/star_disabled.php' );
				$contents = ob_get_contents();
				ob_end_clean();
				return $contents;
			} else {
				return 0;
			}
		}

		$count = count( $field_metas );
		$total = array_sum( $field_metas );

		switch ( $type ) {
			case 'average':
			case 'mean':
			case 'star':
				$stat = ( $total / $count );
				break;
			case 'median':
				rsort( $field_metas );
				$n = ceil( $count / 2 ); // Middle of the array
				if ( $count % 2 ) {
					$stat = $field_metas[ $n - 1 ]; // If number is odd
				} else {
					$n2 = floor( $count / 2 ); // Other middle of the array
					$stat = ( $field_metas[ $n - 1 ] + $field_metas[ $n2 - 1 ] ) / 2;
				}
				$stat = maybe_unserialize( $stat );
				if ( is_array( $stat ) )
					$stat = 0;
				break;
			case 'deviation':
				$mean = ( $total / $count );
				$stat = 0.0;
				foreach ( $field_metas as $i ) {
					$stat += pow( $i - $mean, 2 );
				}

				if ( $count > 1 ) {
					$stat /= ( $count - 1 );

					$stat = sqrt( $stat );
				} else {
					$stat = 0;
				}
				break;
			case 'minimum':
				$stat = min( $field_metas );
				break;
			case 'maximum':
				$stat = max( $field_metas );
				break;
			case 'count':
				$stat = $count;
				break;
			case 'unique':
				$stat = array_unique( $field_metas );
				$stat = count( $stat );
				break;
			case 'total':
			default:
				$stat = $total;
		}

		$stat = round( $stat, $round );
		if ( $type == 'star' ) {
			ob_start();
			include( FrmAppHelper::plugin_path() . '/pro/classes/views/frmpro-fields/star_disabled.php' );
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		if ( ( $round && $round < 5 ) || isset( $thousands_sep ) ) {
			$thousands_sep = ( isset( $thousands_sep ) ? $thousands_sep : ',' );
			$stat = number_format( $stat, $round, '.', $thousands_sep );
		}

		return $stat;
	}

	/**
	 * Get the entry IDs for a field, operator, and value combination
	 *
	 * @param array $args
	 * @return array
	 */
	public static function get_field_matches( $args ) {
		$filter_args = self::get_filter_args( $args );

		if ( ! $filter_args['field'] || ( $filter_args['after_where'] && ! $filter_args['entry_ids'] ) ) {
			return array();
		}

		return self::get_entry_ids_for_field_filter( $filter_args );
	}

	/**
	 * Package the arguments needed for a field filter
	 *
	 * @since 2.02.05
	 * @param array $args
	 * @return array
	 */
	private static function get_filter_args( $args ) {
		$filter_args = array(
			'field' => '',
			'operator' => '=',
			'value' => $args['val'],
			'form_id' => $args['form_id'],
			'entry_ids' => $args['entry_ids'],
			'after_where' => $args['after_where'],
			'drafts' => $args['drafts'],
			'form_posts' => $args['form_posts'],
		);

		$f = $args['orig_f'];

		if ( strpos( $f, '_not_equal' ) !== false ) {
			self::get_not_equal_filter_args( $f, $filter_args );

		} else if ( strpos( $f, '_less_than' ) !== false ) {
			self::get_less_than_filter_args( $f, $filter_args );

		} else if ( strpos( $f, '_greater_than' ) !== false ) {
			self::get_greater_than_filter_args( $f, $filter_args );

		} else if ( strpos( $f, '_contains' ) !== false ) {
			self::get_contains_filter_args( $f, $filter_args );

		} else if ( is_numeric( $f ) && $f <= 10 ) {
			// If using <, >, <=, >=, !=. $f will count up for certain atts
			self::get_filter_args_for_deprecated_field_filters( $filter_args );

		} else {
			// $f is field ID, key, updated_at, or created_at
			self::get_equal_to_filter_args( $f, $filter_args );
		}

		self::convert_filter_field_key_to_id( $filter_args );

		self::prepare_filter_value( $filter_args );

		return $filter_args;
	}

	/**
	 * Get the filter arguments for a not_equal filter
	 *
	 * @since 2.02.05
	 * @param string $f
	 * @param array $filter_args
	 */
	private static function get_not_equal_filter_args( $f, &$filter_args ) {
		$filter_args['field'] = str_replace( '_not_equal', '', $f );
		$filter_args['operator'] = '!=';
		self::maybe_get_all_entry_ids_for_form( $filter_args );
	}

	/**
	 * Get the filter arguments for a less_than filter
	 *
	 * @since 2.02.05
	 * @param string $f
	 * @param array $filter_args
	 */
	private static function get_less_than_filter_args( $f, &$filter_args ) {
		$filter_args['field'] = str_replace( '_less_than', '', $f );
		$filter_args['operator'] = '<';
	}

	/**
	 * Get the filter arguments for a greater_than filter
	 *
	 * @since 2.02.05
	 * @param string $f
	 * @param array $filter_args
	 */
	private static function get_greater_than_filter_args( $f, &$filter_args ) {
		$filter_args['field'] = str_replace( '_greater_than', '', $f );
		$filter_args['operator'] = '>';
	}

	/**
	 * Get the filter arguments for a like filter
	 *
	 * @since 2.02.05
	 * @param string $f
	 * @param array $filter_args
	 */
	private static function get_contains_filter_args( $f, &$filter_args ) {
		$filter_args['field'] = str_replace( '_contains', '', $f );
		$filter_args['operator'] = 'LIKE';
	}

	/**
	 * Get the filter arguments for an x=value filter
	 *
	 * @since 2.02.05
	 * @param string $f
	 * @param array $filter_args
	 */
	private static function get_equal_to_filter_args( $f, &$filter_args ) {
		$filter_args['field'] = $f;

		if ( $filter_args['value'] === '' ) {
			self::maybe_get_all_entry_ids_for_form( $filter_args );
		}
	}

	/**
	 * Convert a filter field key to an ID
	 *
	 * @since 2.02.05
	 * @param array $filter_args
	 */
	private static function convert_filter_field_key_to_id( &$filter_args ) {
		if ( ! is_numeric( $filter_args['field'] ) && ! in_array( $filter_args['field'], array ( 'created_at', 'updated_at' ) ) ) {
			$filter_args['field'] = FrmField::get_id_by_key( $filter_args['field'] );
		}
	}

	/**
	 * Prepare a filter value
	 *
	 * @since 2.02.05
	 * @param array $filter_args
	 */
	private static function prepare_filter_value( &$filter_args ) {
		$filter_args['value'] = FrmAppHelper::replace_quotes( $filter_args['value'] );

		if ( in_array( $filter_args['field'], array( 'created_at', 'updated_at' ) ) ) {
			$filter_args['value'] = str_replace( array( '"', "'" ), "", $filter_args['value'] );
			$filter_args['value'] = date( 'Y-m-d', strtotime( $filter_args['value'] ) );
		} else {
			$filter_args['value'] = trim( trim( $filter_args['value'], "'" ), '"' );
		}
	}

	/**
	 * Get the filter arguments for deprecated stats parameters
	 *
	 * @since 2.02.05
	 * @param array $filter_args
	 */
	private static function get_filter_args_for_deprecated_field_filters( &$filter_args ) {
		$lpos = strpos( $filter_args['value'], '<' );
		$gpos = strpos( $filter_args['value'], '>' );
		$not_pos = strpos( $filter_args['value'], '!=' );
		$dash_pos = strpos( $filter_args['value'], '-' );

		if ( $not_pos !== false || $filter_args['value'] === '' ) {
			self::maybe_get_all_entry_ids_for_form( $filter_args );
		}

		if ( $not_pos !== false ) {
			// Not equal
			$filter_args['operator'] = '!=';

			$str = explode( $filter_args['operator'], $filter_args['value'] );

			$filter_args['field'] = $str[ 0 ];
			$filter_args['value'] = $str[ 1 ];

		} else if ( $lpos !== false || $gpos !== false ) {
			// Greater than or less than
			$filter_args['operator'] = ( ( $gpos !== false && $lpos !== false && $lpos > $gpos ) || $lpos === false ) ? '>' : '<';
			$str = explode( $filter_args['operator'], $filter_args['value'] );

			if ( count( $str ) == 2 ) {
				$filter_args['field'] = $str[ 0 ];
				$filter_args['value'] = $str[ 1 ];
			} else if ( count( $str ) == 3 ) {
				//3 parts assumes a structure like '-1 month'<255<'1 month'
				$pass_args = $filter_args;
				$pass_args['val'] = str_replace( $str[ 0 ] . $filter_args['operator'], '', $filter_args['value'] );

				$filter_args['entry_ids'] = self::get_field_matches( $pass_args );
				$filter_args['after_where'] = true;

				$filter_args['field'] = $str[ 1 ];
				$filter_args['value'] = $str[ 0 ];
				$filter_args['operator'] = ( $filter_args['operator'] == '<' ) ? '>' : '<';
			}

			if ( strpos( $filter_args['value'], '=' ) === 0 ) {
				$filter_args['operator'] .= '=';
				$filter_args['value'] = substr( $filter_args['value'], 1 );
			}

		} else if ( $dash_pos !== false && strpos( $filter_args['value'], '=' ) !== false ) {
			// Field key contains dash
			// If field key contains a dash, then it won't be put in as $f automatically (WordPress quirk maybe?)

			$str = explode( '=', $filter_args['value'] );
			$filter_args['field'] = $str[ 0 ];
			$filter_args['value'] = $str[ 1 ];
		}
	}

	/**
	 * Get all the entry IDs for a form if entry IDs is empty and after_where is false
	 *
	 * @since 2.02.05
	 * @param array $args
	 */
	private static function maybe_get_all_entry_ids_for_form( &$args ) {
		if ( empty( $args['entry_ids'] ) && $args['after_where'] == 0 ) {

			$query = array( 'form_id' => $args['form_id'] );
			if ( $args['drafts'] != 'both' ) {
				$query['is_draft'] = $args['drafts'];
			}

			$args['entry_ids'] = FrmDb::get_col( 'frm_items', $query );
		}
	}

	/**
	 * Get the entry IDs for a field/column filter
	 *
	 * @since 2.02.05
	 * @param array $filter_args
	 * @return array
	 */
	private static function get_entry_ids_for_field_filter( $filter_args ) {
		if ( in_array( $filter_args['field'], array( 'created_at', 'updated_at' ) ) ) {
			$where = array(
				'form_id' => $filter_args['form_id'],
				$filter_args['field'] . FrmDb::append_where_is( $filter_args['operator'] ) => $filter_args['value'],
			);

			if ( $filter_args['entry_ids'] ) {
				$where['id'] = $filter_args['entry_ids'];
			}

			$entry_ids = FrmDb::get_col( 'frm_items', $where );
		} else {
			$where_atts = apply_filters( 'frm_stats_where', array( 'where_is' => $filter_args['operator'], 'where_val' => $filter_args['value'] ), $filter_args );

			$pass_args = array(
				'where_opt' => $filter_args['field'],
				'where_is' => $where_atts['where_is'],
				'where_val' => $where_atts['where_val'],
				'form_id' => $filter_args['form_id'],
				'form_posts' => $filter_args['form_posts'],
				'after_where' => $filter_args['after_where'],
				'drafts' => $filter_args['drafts'],
			);

			$entry_ids = FrmProAppHelper::filter_where( $filter_args['entry_ids'], $pass_args );
		}

		return $entry_ids;
	}

	public static function show() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::show_reports' );
		FrmProGraphsController::show_reports();
	}

	public static function get_daily_entries() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::show_reports' );
		return '';
	}

	public static function graph_shortcode( $atts ) {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return FrmProGraphsController::graph_shortcode( $atts );
	}

	public static function get_google_graph() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_graph_values() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function convert_to_google() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_fields() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_form_posts() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_entry_ids() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_x_field() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_x_axis_inputs() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_graph_cols() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_graph_options() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function clean_inputs() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function mod_post_inputs() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function mod_x_inputs() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function format_f_inputs() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_user_id_values() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_final_x_axis_values() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function combine_dates() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function graph_by_period() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_multiple_id_values() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_x_axis_values() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_count_values() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_generic_inputs() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function field_opt_order_vals() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}

	public static function get_displayed_values() {
		_deprecated_function( __FUNCTION__, '2.01.02', 'FrmProGraphsController::graph_shortcode' );
		return '';
	}
}
