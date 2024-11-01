<?php
/**
 * Front Class
 *
 * @package Verbalize WP
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class
 *
 * Manage Front Panel Class
 *
 * @package Verbalize WP
 * @since 1.0
 */
class Verbalize_WP_Front {

	/**
	 * Audio Shortcode structure
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function audio_play_shortcode_display( $atts ) {

		$atts = shortcode_atts(
			array(
				'id'      => '',
				'audio'   => '',
				'seconds' => '',
				'type'    => 'speaker',
				'color'   => 'red',
				'width'   => '32px',
				'height'  => '32px',
			),
			$atts,
			'audio_play'
		);

		$upload_dir = wp_upload_dir();

		$folder_id = $atts['id'];
		$file_name = $atts['audio'];
		$btn_type  = $atts['type'];
		$btn_color = $atts['color'];
		$width     = $atts['width'];
		$height    = $atts['height'];
		$seconds   = $atts['seconds'];

		$file_path             = $upload_dir['baseurl'] . '/audio-text/' . $folder_id . '/' . $file_name;

		$speaker_default       = VERBALIZE_WP_INC_URL . '/images/speaker-default.png';
		$speaker_img           = VERBALIZE_WP_INC_URL . '/images/speaker-' . $btn_color . '.png';
		$speaker_mute_img      = VERBALIZE_WP_INC_URL . '/images/mute-' . $btn_color . '.png';
		$play_img              = VERBALIZE_WP_INC_URL . '/images/play-' . $btn_color . '.png';
		$pause_img             = VERBALIZE_WP_INC_URL . '/images/pause-' . $btn_color . '.png';
		$speaker_default_style = 'style="width:' . esc_attr( $width ) . '; height:' . esc_attr( $height ) . '"';

		ob_start();
		?>

		<div class="audio-short-wrap" id="audio-wrap-<?php echo esc_attr( $folder_id ); ?>">

			<h4><?php echo esc_html__( 'Audio', 'verbalize-wp' ); ?></h4>

			<p id="status"></p>

			<?php if ( 'speaker' === $btn_type ) { ?>

				<a href="javascript:void(0);"  class="speak-aud-default" data-src="<?php echo esc_url( $file_path ); ?>" id="speak-default<?php echo esc_attr( $folder_id ); ?>" data-id="<?php echo esc_attr( $folder_id ); ?>" data-bs-target="#text-short<?php echo esc_attr( $folder_id ); ?>">
					<img src="<?php echo esc_url( $speaker_default ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />
				</a>

				<a href="javascript:void(0);" class="speak-aud" data-src="<?php echo esc_url( $file_path ); ?>" id="speak-<?php echo esc_attr( $folder_id ); ?>" data-id="<?php echo esc_attr( $folder_id ); ?>" data-bs-target="#text-short<?php echo esc_attr( $folder_id ); ?>">

					<img src="<?php echo esc_url( $speaker_img ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />

				</a>

				<span id="audio-set-<?php echo esc_attr( $folder_id ); ?>"></span>

				<a href="javascript:void(0);" title="<?php echo esc_html__( 'Pause Audio', 'verbalize-wp' ); ?>" class="mute-aud" id="mute-<?php echo esc_attr( $folder_id ); ?>" data-id="<?php echo esc_attr( $folder_id ); ?>" >
					<img src="<?php echo esc_url( $speaker_mute_img ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />
				</a>

			<?php } else { ?>

			<a href="javascript:void(0);" title="<?php echo esc_html__( 'Play Audio', 'verbalize-wp' ); ?>" class="play-aud" data-src="<?php echo esc_url( $file_path ); ?>" id="play-<?php echo esc_attr( $folder_id ); ?>" data-id="<?php echo esc_attr( $folder_id ); ?>" data-bs-target="#text-short<?php echo esc_attr( $folder_id ); ?>">

				<img src="<?php echo esc_url( $play_img ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />

			</a>

			<span id="audio-set-<?php echo esc_attr( $folder_id ); ?>"></span>

			<a href="javascript:void(0);" title="<?php echo esc_html__( 'Pause Audio', 'verbalize-wp' ); ?>" class="pause-aud" id="pause-<?php echo esc_attr( $folder_id ); ?>" data-id="<?php echo esc_attr( $folder_id ); ?>" data-bs-target="#text-short<?php echo esc_attr( $folder_id ); ?>" >
				<img src="<?php echo esc_url( $pause_img ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />
			</a>

			<?php } ?>

		</div>
		<?php
		return ob_get_clean();
	}



	/**
	 * Text Shortcode structure
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function text_audio_shortcode_display( $atts ) {

		global $wp_filesystem;

		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();

		$atts = shortcode_atts(
			array(
				'id'     => '',
				'width'  => '32px',
				'type'   => 'speaker',
				'color'  => 'red',
				'height' => '32px',
			),
			$atts,
			'text_audio'
		);

		$upload_dir = wp_upload_dir();

		$folder_id = $atts['id'];

		$btn_type = $atts['type'];

		$btn_color = $atts['color'];

		$width = $atts['width'];

		$height = $atts['height'];

		$file_path = $upload_dir['basedir'] . '/audio-text/' . $folder_id . '/speech.txt';

		if ( $wp_filesystem->exists( $file_path ) ) {
			$text = $wp_filesystem->get_contents( $file_path );
		}

		$txt = htmlspecialchars( $text );

		$txt = rawurlencode( $txt );

		$audio = wp_remote_get( 'https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=en-IN' );
		$audio = wp_remote_retrieve_body( $audio );

		$speaker_default = VERBALIZE_WP_INC_URL . '/images/speaker-default.png';

		$speaker_img = VERBALIZE_WP_INC_URL . '/images/speaker-' . $btn_color . '.png';

		$speaker_mute_img = VERBALIZE_WP_INC_URL . '/images/mute-' . $btn_color . '.png';

		$play_img = VERBALIZE_WP_INC_URL . '/images/play-' . $btn_color . '.png';

		$pause_img = VERBALIZE_WP_INC_URL . '/images/pause-' . $btn_color . '.png';

		$speaker_default_style = 'style="width:' . esc_attr( $width ) . '; height:' . esc_attr( $height ) . '"';

		ob_start();

		?>

		<div class="audio-short-wrap">

			<h4><?php echo esc_html__( 'Text To Speech', 'verbalize-wp' ); ?></h4>

			<audio controls='controls' id='text-audio-<?php echo esc_attr( $folder_id ); ?>' style="display:none;" autoplay><source src='data:audio/mpeg;base64, <?php echo base64_encode( $audio ); ?>'></audio>

			<?php if ( 'speaker' === $btn_type ) { ?>

				<a href="javascript:void(0);"  class="speak-text-default"  id="speak-text-default-<?php echo esc_attr( $folder_id ); ?>"  data-id="<?php echo esc_attr( $folder_id ); ?>" data-bs-target="#text-short<?php echo esc_attr( $folder_id ); ?>" >
					<img src="<?php echo esc_url( $speaker_default ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />
				</a>

				<a href="javascript:void(0);"  class="speak-text" data-src="<?php echo esc_url( $file_path ); ?>" id="speak-text-<?php echo esc_attr( $folder_id ); ?>" data-id="<?php echo esc_attr( $folder_id ); ?>" data-bs-target="#text-short<?php echo esc_attr( $folder_id ); ?>">
					<img src="<?php echo esc_url( $speaker_img ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />
				</a>

				<span id="audio-set-<?php echo esc_attr( $folder_id ); ?>"></span>

				<a href="javascript:void(0);" title="<?php echo esc_html__( 'Pause Text', 'verbalize-wp' ); ?>" class="mute-text" id="mute-text-<?php echo esc_attr( $folder_id ); ?>" data-id="<?php echo esc_attr( $folder_id ); ?>" >
					<img src="<?php echo esc_url( $speaker_mute_img ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />
				</a>

			<?php } else { ?>

				<a href="javascript:void(0);" title="<?php echo esc_html__( 'Play Audio', 'verbalize-wp' ); ?>" class="play-text" data-id="<?php echo esc_attr( $folder_id ); ?>"  data-bs-toggle="modal" data-bs-target="#text-short<?php echo esc_attr( $folder_id ); ?>" id="play-text-<?php echo esc_attr( $folder_id ); ?>" >
					<img src="<?php echo esc_url( $play_img ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />
				</a>

				<a href="javascript:void(0);" title="<?php echo esc_html__( 'Pause Audio', 'verbalize-wp' ); ?>" id="pause-text-<?php echo esc_attr( $folder_id ); ?>" class="pause-text" data-id="<?php echo esc_attr( $folder_id ); ?>"  data-bs-toggle="modal" data-bs-target="#text-short<?php echo esc_attr( $folder_id ); ?>" >
					<img src="<?php echo esc_url( $pause_img ); ?>" <?php echo esc_attr( $speaker_default_style ); ?> />
				</a>
			<?php } ?>

			<!-- Modal -->
			<div class="modal fade text-convert-modal" id="text-short<?php echo esc_attr( $folder_id ); ?>" tabindex="-1" role="dialog" aria-labelledby="modalLongTitle<?php echo esc_attr( $folder_id ); ?>" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modalLongTitle<?php echo esc_attr( $folder_id ); ?>"><?php echo esc_html__( 'Text To Speech', 'verbalize-wp' ); ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
						<p><?php echo esc_html( $text ); ?></p>
					</div>
				</div>
			</div>
		</div>
		</div>

		<?php

		return ob_get_clean();
	}

	/**
	 * Adding Hooks
	 *
	 * @package Verbalize WP
	 * @since 1.0
	 */
	public function add_hooks() {

		// Audio Shortcode.
		add_shortcode( 'audio_play', array( $this, 'audio_play_shortcode_display' ) );

		// Text Shortcode.
		add_shortcode( 'text_audio', array( $this, 'text_audio_shortcode_display' ) );
	}
}
