<?php
/**
 * Admin Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package Verbalize WP
 * @since 1.0
 */

use falahati\PHPMP3\MpegAudio;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class
 *
 * Manage Admin Panel Class
 *
 * @package Verbalize WP
 * @since 1.0
 */
class Verbalize_WP_Admin {
	/**
	 * The full mp3 file
	 *
	 * @var string
	 */
	private $str;

	/**
	 * The time length of the current file
	 *
	 * @var string
	 */
	private $time;

	/**
	 * The amount of frames in the current file
	 *
	 * @var string
	 */
	private $frames;

	/**
	 * Translate ascii characters to binary
	 *
	 * @var array
	 */
	private $binary_table;

	/**
	 * Construct a new instance
	 *
	 * @param string $path Path to an mp3 file.
	 */
	public function __construct( $path = '' ) {

		$this->binary_table = array();
		for ( $i = 0; $i < 256; $i ++ ) {
			$this->binary_table[ chr( $i ) ] = sprintf( '%08b', $i );
		}

		if ( '' !== $path ) {
			$path         = $this->verbalize_wp_path_to_url( $path );
			$raw_response = wp_remote_get( $path );
			$this->str    = wp_remote_retrieve_body( $raw_response );
		}
	}

	/**
	 * WP Path to URL
	 *
	 * @param string $path path.
	 */
	public function verbalize_wp_path_to_url( $path = '' ) {
		$url = str_replace(
			wp_normalize_path( untrailingslashit( ABSPATH ) ),
			site_url(),
			wp_normalize_path( $path )
		);

		return esc_url_raw( $url );
	}

	/**
	 * Create menu page
	 *
	 * Adding required menu pages and submenu pages
	 * to manage the plugin functionality
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function verbalize_wp_add_menu_page() {

		$verbalize_wp_post_push_notification = add_menu_page( esc_html__( 'Verbalize WP', 'verbalize-wp' ), esc_html__( 'Verbalize WP', 'verbalize-wp' ), 'manage_options', 'audio-text', array( $this, 'verbalize_wp_settings' ) );
	}

	/**
	 * Audio text Setting Page structure in admin
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function verbalize_wp_settings() {
		include_once VERBALIZE_WP_ADMIN_DIR . '/forms/verbalize-wp-settings.php';
	}

	/**
	 * Save shortcode settings in options
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function verbalize_wp_option_settings() {

		register_setting( 'audio-options-group', 'audio_f', array( $this, 'audio_file_save' ) );

		register_setting( 'audio-options-group', 'button_type' );

		register_setting( 'audio-options-group', 'button_color' );

		register_setting( 'audio-options-group', 'width_btn' );

		register_setting( 'audio-options-group', 'height_btn' );

		register_setting( 'audio-options-group', 'second_play' );

		register_setting( 'audio-options-group', 'short_dis', array( $this, 'short_dis_validate' ) );

		register_setting( 'text-options-group', 'text_conv' );

		register_setting( 'text-options-group', 'text_btn_color' );

		register_setting( 'text-options-group', 'text_width_btn' );

		register_setting( 'text-options-group', 'text_height_btn' );

		register_setting( 'text-options-group', 'text_btn_type' );

		register_setting( 'text-options-group', 'short_dis_text', array( $this, 'short_dis_text_validate' ) );
	}

	/**
	 * Audio shortcode validation
	 *
	 * @param array $input validate settings before save to database.
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function short_dis_validate( $input ) {

		$input = array_filter( $input );
		if ( empty( $input ) ) {
			$valid = false;
			add_settings_error( 'audio-text', 'invalid_short_dis', __( 'Audio Short Code is required.', 'verbalize-wp' ), 'error' );
		}
		return $input;
	}

	/**
	 * Audio file save
	 *
	 * @param array $option options of audio.
	 */
	public function audio_file_save( $option ) {
		$option            = get_option( 'audio_f' );
		$option['audio_f'] = array();
		$audio_save = isset( $_POST['audio_save'] ) ? array_map( 'sanitize_text_field', $_POST['audio_save'] ) : array();

		if ( ! empty( $audio_save ) ) {
			foreach ( $audio_save as $save ) {
				$option['audio_f'][] = $save;
			}
		}

		$file_name = isset( $_FILES['audio_f']['name'] ) ? array_map( 'sanitize_text_field', $_FILES['audio_f']['name'] ) : array();

		if ( ! empty( $file_name ) ) {
			foreach ( $file_name as $key => $file ) {
				if ( ! empty( $file ) ) {
					$option['audio_f'][] = $file;
				}
			}
		}
		return $option;
	}

	/**
	 * Text shortcode validation
	 *
	 * @param array $input validate field.
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function short_dis_text_validate( $input ) {

		$input = array_filter( $input );
		if ( empty( $input ) ) {
			$valid = false;
			add_settings_error( 'audio-text', 'invalid_short_dis_text', __( 'Text Short Code is required.', 'verbalize-wp' ), 'error' );
		}
		return $input;
	}

	/**
	 * Audio More Section Ajax
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function verbalize_wp_more_audio_section() {

		// Check for nonce security.
		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			die( 'Invalid!' );
		}

		$coutid = isset( $_POST['count'] ) ? sanitize_text_field( wp_unslash( $_POST['count'] ) ) : '';
		$html   = '';
		$html   = '<tr data-id="' . esc_attr( $coutid ) . '">
		       		<td>
		       			<input type="file" data-id="' . esc_attr( $coutid ) . '" accept="' . esc_html__( '.mp3,.m4a,.ogg,.wav', 'verbalize-wp' ) . '" name="audio_f[]" />
		       		</td>
		       		<td>
	       				<label>' . esc_html__( 'Type:', 'verbalize-wp' ) . ' </label>
		       			<select name="button_type[]" class="button-type" data-id="' . esc_attr( $coutid ) . '">
		       				<option value="speaker">' . esc_html__( 'Speaker', 'verbalize-wp' ) . '</option>
							<option value="play">' . esc_html__( 'Play', 'verbalize-wp' ) . '</option>	       								
		       			</select>
		       			<br/><br/>
		       			<label>' . esc_html__( 'Color:', 'verbalize-wp' ) . ' </label>
		       			<select name="button_color[]" class="button-color" data-id="' . esc_attr( $coutid ) . '">
		       				<option value="red">' . esc_html__( 'Red', 'verbalize-wp' ) . '</option>
								<option value="black">' . esc_html__( 'Black', 'verbalize-wp' ) . '</option>
								<option value="blue">' . esc_html__( 'Blue', 'verbalize-wp' ) . '</option>
								<option value="white">' . esc_html__( 'White', 'verbalize-wp' ) . '</option>
								<option value="pink">' . esc_html__( 'Pink', 'verbalize-wp' ) . '</option>
								<option value="yellow">' . esc_html__( 'Yellow', 'verbalize-wp' ) . '</option>
								<option value="orange">' . esc_html__( 'Orange', 'verbalize-wp' ) . '</option>
		       			</select>
		       		</td>
		       		<td>
		       			<label>' . esc_html__( 'Width :(in px)', 'verbalize-wp' ) . ' </label>
		       			<input type="text" class="width_btn" name="width_btn[]" value="" placeholder="20" data-id="' . esc_attr( $coutid ) . '">
		       			<br/><br/>
		       			<label>' . esc_html__( 'Height :(in px)', 'verbalize-wp' ) . ' </label>
		       			<input type="text" class="height_btn" name="height_btn[]" value="" placeholder="20" data-id="' . esc_attr( $coutid ) . '">
		       		</td>
		       		<td>
		       			<input type="number" name="second_play[]" data-id="' . esc_attr( $coutid ) . '">
		       		</td>		       		
		       		<td>
		       			<button type="button" class="gen-short button" data-id="' . esc_attr( $coutid ) . '" value="save_audio">' . esc_html__( 'Generate Shortcode', 'verbalize-wp' ) . '</button>
		       			
		       		</td>
		       		<td  data-id="' . esc_attr( $coutid ) . '" class="short-code">
		       			<input type="text" name="short_dis[]" id="short_dis' . esc_attr( $coutid ) . '" value="" readonly  data-id="' . esc_attr( $coutid ) . '">
		       			<button type="button" data-id="' . esc_attr( $coutid ) . '" title="' . esc_html__( 'Remove', 'verbalize-wp' ) . '" value="remove" class="remove_row btn button btn-close">X</button>
		       			<button type="button" data-toggle="tooltip" data-placement="bottom" title="' . esc_html__( 'Copied', 'verbalize-wp' ) . '" class="button copy-short" data-id="' . esc_attr( $coutid ) . '">' . esc_html__( 'Copy', 'verbalize-wp' ) . '</button>
		       			
		       		</td>
		       	</tr>';

		wp_send_json_success( $html );
		die();
	}


	/**
	 * Text More Section Ajax
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function text_load_more_section() {

		// Check for nonce security.
		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			die( 'Invalid!' );
		}

		$coutid = isset( $_POST['count'] ) ? sanitize_text_field( wp_unslash( $_POST['count'] ) ) : '';
		$html   = '<tr data-id="' . esc_attr( $coutid ) . '">
		       		<td>
		       			<textarea name="text_conv[]" data-id="' . esc_attr( $coutid ) . '" rows="5" cols="50" maxlength="150"></textarea>
		       		</td>
		       		<td>
		       			<label>' . esc_html__( 'Type:', 'verbalize-wp' ) . ' </label>
		       			<select name="text_btn_type[]" class="button-text-type" data-id="' . esc_attr( $coutid ) . '">
		       				<option value="speaker">' . esc_html__( 'Speaker', 'verbalize-wp' ) . '</option>
							<option value="play">' . esc_html__( 'Play', 'verbalize-wp' ) . '</option>
		       			</select>
		       			<br/><br/>
		       			<label>' . esc_html__( 'Color:', 'verbalize-wp' ) . ' </label>
		       			<select name="text_btn_color[]" class="button-text-color" data-id="' . esc_attr( $coutid ) . '">
		       					<option value="red">' . esc_html__( 'Red', 'verbalize-wp' ) . '</option>
								<option value="black">' . esc_html__( 'Black', 'verbalize-wp' ) . '</option>
								<option value="blue">' . esc_html__( 'Blue', 'verbalize-wp' ) . '</option>
								<option value="white">' . esc_html__( 'White', 'verbalize-wp' ) . '</option>
								<option value="pink">' . esc_html__( 'Pink', 'verbalize-wp' ) . '</option>
								<option value="yellow">' . esc_html__( 'Yellow', 'verbalize-wp' ) . '</option>
								<option value="orange">' . esc_html__( 'Orange', 'verbalize-wp' ) . '</option>
		       			</select>
		       		</td>	
		       		<td>
		       			<label>' . esc_html__( 'Width :(in px)', 'verbalize-wp' ) . '</label>
		       			<input type="text" class="text_width_btn" name="text_width_btn[]" value="" placeholder="20" data-id="' . esc_attr( $coutid ) . '">
		       			<br/><br/>
		       			<label>' . esc_html__( 'Height :(in px)', 'verbalize-wp' ) . '</label>
		       			<input type="text" class="text_height_btn" name="text_height_btn[]" value="" placeholder="20" data-id="' . esc_attr( $coutid ) . '">
		       		</td>	       		
		       		<td>
		       			<button type="button" class="gen-short-text button"  data-id="' . esc_attr( $coutid ) . '" id="gen-short-text" value="save_text">' . esc_html__( 'Generate Shortcode', 'verbalize-wp' ) . '</button>
		       			
		       		</td>
		       		<td data-id="' . esc_attr( $coutid ) . '" class="short-code">
		       			<input type="text" name="short_dis_text[]" id="short_text' . esc_attr( $coutid ) . '" value="" readonly data-id="' . esc_attr( $coutid ) . '">
		       			<button type="button" data-id="' . esc_attr( $coutid ) . '" title="' . esc_html__( 'Remove', 'verbalize-wp' ) . '" value="remove" class="remove_row btn button btn-close">X</button>
		       			<button type="button" data-toggle="tooltip" data-placement="bottom" title="' . esc_html__( 'Copied', 'verbalize-wp' ) . '" class="button copy-short-text" data-id="' . esc_attr( $coutid ) . '">' . esc_html__( 'Copy', 'verbalize-wp' ) . '</button>
		       			
		       		</td>
		       	</tr>';
		wp_send_json_success( $html );
		die();
	}


	/**
	 * Audio Shorcode generate
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function verbalize_wp_generate_code() {

		// Check for nonce security.
		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			die( 'Invalid!' );
		}

		$button_type  = isset( $_POST['btn_val'] ) ? sanitize_text_field( wp_unslash( $_POST['btn_val'] ) ) : '';
		$button_color = isset( $_POST['btn_color'] ) ? sanitize_text_field( wp_unslash( $_POST['btn_color'] ) ) : '';
		$seconds      = isset( $_POST['num_val'] ) ? sanitize_text_field( wp_unslash( $_POST['num_val'] ) ) : '';
		$width        = isset( $_POST['width'] ) ? sanitize_text_field( wp_unslash( $_POST['width'] ) ) : '';
		$height       = isset( $_POST['height'] ) ? sanitize_text_field( wp_unslash( $_POST['height'] ) ) : '';

		if ( empty( $seconds ) ) {

			$result['status'] = 'error';
			$result['msg']    = esc_html__( 'Please enter seconds to play', 'verbalize-wp' );
			echo wp_json_encode( $result );
			exit;
		}

		if ( empty( $width ) ) {

			$result['status'] = 'error';
			$result['msg']    = esc_html__( 'Please enter width', 'verbalize-wp' );
			echo wp_json_encode( $result );
			exit;
		}
		if ( empty( $height ) ) {

			$result['status'] = 'error';
			$result['msg']    = esc_html__( 'Please enter height', 'verbalize-wp' );
			echo wp_json_encode( $result );
			exit;
		}

		if ( isset( $_FILES['theFile']['size'] ) && $_FILES['theFile']['size'] > 0 ) {
			$aud_file_temp = isset( $_FILES['theFile']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['theFile']['tmp_name'] ) ) : '';
			$aud_file_name = isset( $_FILES['theFile']['name'] ) ? basename( sanitize_text_field( wp_unslash( $_FILES['theFile']['name'] ) ) ) : '';
			$aud_file_ext  = end( explode( '.', $aud_file_name ) );
			$filename      = 'audio-demo.' . $aud_file_ext;
			$random        = wp_rand( 1000, 9999 );
			$aud_dir       = trailingslashit( wp_upload_dir()['basedir'] ) . 'audio-text/';
			$randm_dir     = $aud_dir . $random . '/';
			$uploads_dir   = $randm_dir . $filename;

			if ( ! is_dir( $aud_dir ) ) {
				wp_mkdir_p( $aud_dir );
				if ( ! is_dir( $randm_dir ) ) {
					wp_mkdir_p( $randm_dir );
					if ( false === @move_uploaded_file( $aud_file_temp, $uploads_dir ) ) { // phpcs:ignore

						$result['status'] = 'error';
						$result['msg']    = esc_html__( 'Something goes wrong please try again', 'verbalize-wp' );
					} else {

						$this->audio_cut_seconds( $uploads_dir, $seconds );
						$audio_id         = $random;
						$shortcode        = '[audio_play id="' . $audio_id . '" audio="' . $filename . '" type="' . $button_type . '" color="' . $button_color . '" seconds="' . $seconds . '" width="' . $width . 'px" height="' . $height . 'px"]';
						$result['status'] = 'success';
						$result['html']   = $shortcode;

					}
				} else {
					$result['status'] = 'error';
					$result['msg']    = esc_html__( 'Something goes wrong please try again', 'verbalize-wp' );
				}
			} else {
				if ( ! is_dir( $randm_dir ) ) {
					wp_mkdir_p( $randm_dir );
					if ( false === @move_uploaded_file( $aud_file_temp, $uploads_dir ) ) { // phpcs:ignore

						$result['status'] = 'error';
						$result['msg']    = esc_html__( 'Something goes wrong please try again', 'verbalize-wp' );
					} else {

						$this->audio_cut_seconds( $uploads_dir, $seconds );

						$audio_id         = $random;
						$shortcode        = '[audio_play id="' . $audio_id . '" audio="' . $filename . '" type="' . $button_type . '" color="' . $button_color . '" seconds="' . $seconds . '" width="' . $width . 'px" height="' . $height . 'px"]';
						$result['status'] = 'success';
						$result['html']   = $shortcode;
					}
				} else {
					$result['status'] = 'error';
					$result['msg']    = esc_html__( 'Something goes wrong please try again', 'verbalize-wp' );
				}
			}
		} else {
			$result['status'] = 'error';
			$result['msg']    = esc_html__( 'Please Select Audio file', 'verbalize-wp' );
		}

		echo wp_json_encode( $result );
		exit;

	}

	/**
	 * Text Shorcode generate
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function text_generate_code() {

		// Check for nonce security.
		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			die( 'Invalid!' );
		}

		$button_type  = isset( $_POST['btn_val'] ) ? sanitize_text_field( wp_unslash( $_POST['btn_val'] ) ) : '';
		$button_color = isset( $_POST['btn_color'] ) ? sanitize_text_field( wp_unslash( $_POST['btn_color'] ) ) : '';
		$text_val     = isset( $_POST['text_val'] ) ? sanitize_textarea_field( wp_unslash( $_POST['text_val'] ) ) : '';
		$width        = isset( $_POST['width'] ) ? sanitize_text_field( wp_unslash( $_POST['width'] ) ) : '';
		$height       = isset( $_POST['height'] ) ? sanitize_text_field( wp_unslash( $_POST['height'] ) ) : '';

		if ( empty( $button_color ) ) {

			$result['status'] = 'error';
			$result['msg']    = esc_html__( 'Please select button color', 'verbalize-wp' );
			echo wp_json_encode( $result );
			exit;
		}
		if ( empty( $width ) ) {

			$result['status'] = 'error';
			$result['msg']    = esc_html__( 'Please enter width', 'verbalize-wp' );
			echo wp_json_encode( $result );
			exit;
		}
		if ( empty( $height ) ) {

			$result['status'] = 'error';
			$result['msg']    = esc_html__( 'Please enter height', 'verbalize-wp' );
			echo wp_json_encode( $result );
			exit;
		}

		if ( isset( $text_val ) && ! empty( trim( $text_val ) ) ) {
			$aud_file_name = 'speech.txt';
			$aud_file_nm     = current( explode( '.', $aud_file_name ) );
			$random        = wp_rand( 1000, 9999 );
			$aud_dir       = trailingslashit( wp_upload_dir()['basedir'] ) . 'audio-text/';
			$randm_dir     = $aud_dir . $random . '/';
			$uploads_dir   = $randm_dir . $aud_file_name;

			if ( ! is_dir( $aud_dir ) ) {
				wp_mkdir_p( $aud_dir );
				if ( ! is_dir( $randm_dir ) ) {
					wp_mkdir_p( $randm_dir );
					$file = fopen( $uploads_dir, 'w' );
					if ( fwrite( $file, $text_val ) ) {
						$text_id          = $random;
						$shortcode        = '[text_audio id="' . $text_id . '" type="' . $button_type . '"  color="' . $button_color . '" width="' . $width . 'px" height="' . $height . 'px"]';
						$result['status'] = 'success';
						$result['html']   = $shortcode;

					} else {
						$result['status'] = 'error';
						$result['msg']    = esc_html__( 'Something goes wrong please try again', 'verbalize-wp' );
					}
					fclose( $file );
				} else {
					$result['status'] = 'error';
					$result['msg']    = esc_html__( 'Something goes wrong please try again', 'verbalize-wp' );
				}
			} else {
				if ( ! is_dir( $randm_dir ) ) {
					wp_mkdir_p( $randm_dir );
					$file = fopen( $uploads_dir, 'w' );
					if ( fwrite( $file, $text_val ) ) {
						$text_id          = $random;
						$shortcode        = '[text_audio id="' . $text_id . '" type="' . $button_type . '" color="' . $button_color . '" width="' . $width . 'px" height="' . $height . 'px"]';
						$result['status'] = 'success';
						$result['html']   = $shortcode;

					} else {
						$result['status'] = 'error';
						$result['msg']    = esc_html__( 'Something goes wrong please try again', 'verbalize-wp' );
					}
					fclose( $file );
				} else {
					$result['status'] = 'error';
					$result['msg']    = esc_html__( 'Something goes wrong please try again', 'verbalize-wp' );
				}
			}
		} else {
			$result['status'] = 'error';
			$result['msg']    = esc_html__( 'Please enter Text for shortcode', 'verbalize-wp' );
		}

		echo wp_json_encode( $result );
		exit;

	}

	/**
	 * Cut the audio to the seconds
	 *
	 * @param string $uploads_dir upload dir path.
	 * @param string $seconds seconds.
	 */
	public function audio_cut_seconds( $uploads_dir, $seconds ) {
		$path = $uploads_dir;
		$mp3  = new Verbalize_WP_Admin( $path );

		$mp3_1 = $mp3->extract( 0, $seconds );

		$mp3_1->save( $uploads_dir );

	}

	/**
	 * Write an mp3 file
	 *
	 * @param string $path Path to write file to.
	 * @return bool
	 */
	public function save( $path ) {
		$fp           = fopen( $path, 'w' );
		$bytes_written = fwrite( $fp, $this->str );
		fclose( $fp );
		return $bytes_written == strlen( $this->str );
	}

	/**
	 * Extract a portion of an mp3
	 *
	 * @param int $start Time in seconds to extract from.
	 * @param int $length Time in seconds to extract.
	 * @return static
	 */
	public function extract( $start, $length ) {
		$max_str_len     = strlen( $this->str );
		$current_str_pos = $this->get_start();
		$frames_count    = 0;
		$time            = 0;
		$start_count     = - 1;
		$end_count       = - 1;
		while ( $current_str_pos < $max_str_len ) {
			if ( $start_count == - 1 && $time >= $start ) {
				$start_count = $current_str_pos;
			}
			if ( $end_count == - 1 && $time >= ( $start + $length ) ) {
				$end_count = $current_str_pos - $start_count;
			}
			$str    = substr( $this->str, $current_str_pos, 4 );
			$strlen = strlen( $str );
			$parts  = array();
			for ( $i = 0; $i < $strlen; $i ++ ) {
				$parts[] = $this->binary_table[ $str[ $i ] ];
			}
			if ( '11111111' == $parts[0] ) {
				$a                = $this->do_frame_stuff( $parts );
				$current_str_pos += $a[0];
				$time            += $a[1];
				$frames_count ++;
			} else {
				break;
			}
		}
		$mp3 = new static();
		if ( $end_count == - 1 ) {
			$end_count = $max_str_len - $start_count;
		}
		if ( $start_count != - 1 && $end_count != - 1 ) {
			$mp3->set_str( substr( $this->str, $start_count, $end_count ) );
		}
		return $mp3;
	}



	/**
	 * Set the mp3 data
	 *
	 * @param string $str Mp3 file.
	 * @return void
	 */
	public function set_str( $str ) {
		$this->str = $str;
	}

	/**
	 * Get the start of audio data
	 *
	 * @return bool|int|void
	 */
	public function get_start() {
		$current_str_pos = - 1;
		while ( true ) {
			$current_str_pos = strpos( $this->str, chr( 255 ), $current_str_pos + 1 );
			if ( false === $current_str_pos ) {
				return 0;
			}

			$str    = substr( $this->str, $current_str_pos, 4 );
			$strlen = strlen( $str );
			$parts  = array();
			for ( $i = 0; $i < $strlen; $i ++ ) {
				$parts[] = $this->binary_table[ $str[ $i ] ];
			}

			if ( $this->do_frame_stuff( $parts ) === false ) {
				continue;
			}

			return $current_str_pos;
		}
	}

	/**
	 * Get the length of a frame in bytes and seconds
	 *
	 * @param string[] $parts A frame with bytes converted to binary.
	 * @return array|bool
	 */
	public function do_frame_stuff( $parts ) {
		// Get Audio Version.
		$seconds = 0;
		$errors  = array();
		switch ( substr( $parts[1], 3, 2 ) ) {
			case '01':
				$errors[] = esc_html__( 'Reserved audio version', 'verbalize-wp' );
				break;
			case '00':
				$audio = 2.5;
				break;
			case '10':
				$audio = 2;
				break;
			case '11':
				$audio = 1;
				break;
		}
		// Get Layer.
		switch ( substr( $parts[1], 5, 2 ) ) {
			case '01':
				$layer = 3;
				break;
			case '00':
				$errors[] = esc_html__( 'Reserved layer', 'verbalize-wp' );
				break;
			case '10':
				$layer = 2;
				break;
			case '11':
				$layer = 1;
				break;
		}
		// Get Bitrate.
		$bit_flag         = substr( $parts[2], 0, 4 );
		$bit_array        = array(
			'0000' => array( 0, 0, 0, 0, 0 ),
			'0001' => array( 32, 32, 32, 32, 8 ),
			'0010' => array( 64, 48, 40, 48, 16 ),
			'0011' => array( 96, 56, 48, 56, 24 ),
			'0100' => array( 128, 64, 56, 64, 32 ),
			'0101' => array( 160, 80, 64, 80, 40 ),
			'0110' => array( 192, 96, 80, 96, 48 ),
			'0111' => array( 224, 112, 96, 112, 56 ),
			'1000' => array( 256, 128, 112, 128, 64 ),
			'1001' => array( 288, 160, 128, 144, 80 ),
			'1010' => array( 320, 192, 160, 160, 96 ),
			'1011' => array( 352, 224, 192, 176, 112 ),
			'1100' => array( 384, 256, 224, 192, 128 ),
			'1101' => array( 416, 320, 256, 224, 144 ),
			'1110' => array( 448, 384, 320, 256, 160 ),
			'1111' => array( - 1, - 1, - 1, - 1, - 1 ),
		);
		$bit_part         = $bit_array[ $bit_flag ];
		$bit_array_number = null;
		if ( 1 == $audio ) {
			switch ( $layer ) {
				case 1:
					$bit_array_number = 0;
					break;
				case 2:
					$bit_array_number = 1;
					break;
				case 3:
					$bit_array_number = 2;
					break;
			}
		} else {
			switch ( $layer ) {
				case 1:
					$bit_array_number = 3;
					break;
				case 2:
					$bit_array_number = 4;
					break;
				case 3:
					$bit_array_number = 4;
					break;
			}
		}
		$bit_rate = $bit_part[ $bit_array_number ];
		if ( $bit_rate <= 0 ) {
			return false;
		}
		// Get Frequency.
		$frequencies  = array(
			1   => array(
				'00' => 44100,
				'01' => 48000,
				'10' => 32000,
				'11' => 'reserved',
			),
			2   => array(
				'00' => 44100,
				'01' => 48000,
				'10' => 32000,
				'11' => 'reserved',
			),
			2.5 => array(
				'00' => 44100,
				'01' => 48000,
				'10' => 32000,
				'11' => 'reserved',
			),
		);
		$freq         = $frequencies[ $audio ][ substr( $parts[2], 4, 2 ) ];
		$frame_length = 0;
		// IsPadded?
		$padding = substr( $parts[2], 6, 1 );
		if ( 3 == $layer || 2 == $layer ) {
			$frame_length = 144 * $bit_rate * 1000 / $freq + $padding;
		}
		$frame_length = floor( $frame_length );
		if ( 0 == $frame_length ) {
			return false;
		}
		$seconds += $frame_length * 8 / ( $bit_rate * 1000 );
		return array( $frame_length, $seconds );
	}

	/**
	 * Adding Hooks
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function add_hooks() {

		// Plugin Menu.
		add_action( 'admin_menu', array( $this, 'verbalize_wp_add_menu_page' ) );

		// Option Settings.
		add_action( 'admin_init', array( $this, 'verbalize_wp_option_settings' ) );

		// Generate Audio Shortcode.
		add_action( 'wp_ajax_generate_code', array( $this, 'verbalize_wp_generate_code' ) );
		add_action( 'wp_ajax_nopriv_generate_code', array( $this, 'verbalize_wp_generate_code' ) );

		// Generate Text Shortcode.
		add_action( 'wp_ajax_text_generate_code', array( $this, 'text_generate_code' ) );
		add_action( 'wp_ajax_nopriv_text_generate_code', array( $this, 'text_generate_code' ) );

		// Load more audio.
		add_action( 'wp_ajax_verbalize_wp_more', array( $this, 'verbalize_wp_more_audio_section' ) );
		add_action( 'wp_ajax_nopriv_verbalize_wp_more', array( $this, 'verbalize_wp_more_audio_section' ) );

		// More audio.
		add_action( 'wp_ajax_text_load_more', array( $this, 'text_load_more_section' ) );
		add_action( 'wp_ajax_nopriv_text_load_more', array( $this, 'text_load_more_section' ) );
	}
}
