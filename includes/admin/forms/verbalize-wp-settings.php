<?php
/**
 * Settings
 *
 * @package Verbalize WP
 * @since 1.0
 */

settings_errors();

$count = 2;
$count_text = 2;

// Audio options.
$audios           = get_option( 'audio_f' );
$btn_type_audios  = get_option( 'button_type' );
$btn_color_audios = get_option( 'button_color' );
$width            = get_option( 'width_btn' );
$height           = get_option( 'height_btn' );
$second_play      = get_option( 'second_play' );
$shortcodes       = ( get_option( 'short_dis' ) ) ? get_option( 'short_dis' ) : array();


$display1 = "style='display:none;'";
$display2 = "style='display:none;'";
if ( ! empty( $shortcodes ) ) {
	$display1 = "style='display:block;'";
	$count    = count( $shortcodes ) + 1;
}

// Text options.
$texts          = get_option( 'text_conv' );
$text_btn       = get_option( 'text_btn_type' );
$text_btn_color = get_option( 'text_btn_color' );
$text_width     = get_option( 'text_width_btn' );
$text_height    = get_option( 'text_height_btn' );
$shortcodes_text = ( get_option( 'short_dis_text' ) ) ? get_option( 'short_dis_text' ) : array();
if ( ! empty( $shortcodes_text ) ) {
	$display2   = "style='display:block;'";
	$count_text = count( $shortcodes_text ) + 1;
}
?>
<div class="wrap">
	<h2><?php echo esc_html__( 'Verbalize WP Settings', 'verbalize-wp' ); ?></h2>
	<div class="meta-box-sortables ui-sortable aud-text-wrap">
		<div class="postbox" id="p1">
			<div class="container">
				<form action="options.php" name="audio-form" method="post" id="audio-form" enctype="multipart/form-data">

					<?php
					settings_fields( 'audio-options-group' );
					do_settings_sections( 'audio-options-group' );
					?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php echo esc_html__( 'Audio File*', 'verbalize-wp' ); ?></th>
							<th scope="row"> <?php echo esc_html__( 'Button* ', 'verbalize-wp' ); ?> <br/><span><?php echo esc_html__( '(Please select speaker/play button to display in shortcode)', 'verbalize-wp' ); ?></span></th>
							<th scope="row"><?php echo esc_html__( 'Button Type*', 'verbalize-wp' ); ?></th>
							<th scope="row"><?php echo esc_html__( 'Seconds*', 'verbalize-wp' ); ?></th>
							<th scope="row"><?php echo esc_html__( 'Action', 'verbalize-wp' ); ?></th>
							<th scope="row"><?php echo esc_html__( 'Shortcode', 'verbalize-wp' ); ?></th>
						</tr>
						<?php
						if ( ! empty( $shortcodes ) ) {
							foreach ( $shortcodes as $key2 => $value ) {
								$key = $key2 + 1;
								?>
								<tr data-id="<?php echo esc_attr( $key ); ?>">
									<td>
										<input type="file" id="audio-file" accept=".mp3,.m4a,.ogg,.wav" data-id="<?php echo esc_attr( $key ); ?>" name="audio_f[]" value="<?php echo esc_attr( $audios['audio_f'][ $key2 ] ); ?>">
										<span><?php echo esc_attr( $audios['audio_f'][ $key2 ] ); ?></span>
										<input type="hidden" value="<?php echo esc_attr( $audios['audio_f'][ $key2 ] ); ?>" name="audio_save[]">
									</td>
									<td>						      
										<label><?php echo esc_html__( 'Type:', 'verbalize-wp' ); ?></label>
										<select name="button_type[]" class="button-type" data-id="<?php echo esc_attr( $key ); ?>">
											<option value="speaker" <?php if ( $btn_type_audios[ $key2 ] === 'speaker' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Speaker', 'verbalize-wp' ); ?>
											</option>
											<option value="play" <?php if ( $btn_type_audios[ $key2 ] === 'play' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Play', 'verbalize-wp' ); ?>
											</option>
										</select>
										<br/><br/>
										<label><?php echo esc_html__( 'Color:', 'verbalize-wp' ); ?></label>

										<select name="button_color[]" class="button-color" data-id="<?php echo esc_attr( $key ); ?>">
											<option value="red" <?php if ( $btn_color_audios[ $key2 ] === 'red' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Red', 'verbalize-wp' ); ?>
											</option>
											<option value="black" <?php if ( $btn_color_audios[ $key2 ] === 'black' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Black', 'verbalize-wp' ); ?>
											</option>
											<option value="blue" <?php if ( $btn_color_audios[ $key2 ] === 'blue' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Blue', 'verbalize-wp' ); ?>
											</option>
											<option value="white" <?php if ( $btn_color_audios[ $key2 ] === 'white' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'White', 'verbalize-wp' ); ?>
											</option>
											<option value="pink" <?php if ( $btn_color_audios[ $key2 ] === 'pink' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Pink', 'verbalize-wp' ); ?>
											</option>
											<option value="yellow" <?php if ( $btn_color_audios[ $key2 ] === 'yellow' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Yellow', 'verbalize-wp' ); ?>
											</option>
											<option value="orange" <?php if ( $btn_color_audios[ $key2 ] === 'orange' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Orange', 'verbalize-wp' ); ?>
											</option>
										</select>
									</td>
									<td>
										<label><?php echo esc_html__( 'Width : (in px)', 'verbalize-wp' ); ?></label>
										<input type="text" class="width_btn" class="width_btn" name="width_btn[]" data-id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $width[ $key2 ] ); ?>" placeholder="20">
										<br/><br/>
										<label><?php echo esc_html__( 'Height : (in px)', 'verbalize-wp' ); ?></label>
										<input type="text" class="height_btn" name="height_btn[]" data-id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $height[ $key2 ] ); ?>" placeholder="20">
									</td>
									<td>
										<input type="number" min="1"  data-id="<?php echo esc_attr( $key ); ?>" name="second_play[]" value="<?php echo esc_attr( $second_play[ $key2 ] ); ?>">
									</td>				       		
									<td>
										<button type="button" class="gen-short button"  data-id="<?php echo esc_attr( $key ); ?>" id="gen-short" value="save_audio"><?php echo esc_html__( 'Generate Shortcode', 'verbalize-wp' ); ?></button>
									</td>
									<td data-id="<?php echo esc_attr( $key ); ?>" class="short-code">
										<input type="text" name="short_dis[]" id="short_dis<?php echo esc_attr( $key ); ?>" value='<?php echo esc_attr( $value ); ?>' readonly data-id="<?php echo esc_attr( $key ); ?>">
									<?php if ( $key > 1 ) { ?>
											<button type="button" data-id="<?php echo esc_attr( $key ); ?>" title="<?php echo esc_html__( 'Remove', 'verbalize-wp' ); ?>" value="remove" class="remove_row btn button btn-close"><?php echo esc_html__( 'X', 'verbalize-wp' ); ?></button>
									<?php } ?>
										<button type="button" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_html__( 'Copied', 'verbalize-wp' ); ?>" class="button copy-short" data-id="<?php echo esc_attr( $key ); ?>"><?php echo esc_html__( 'Copy', 'verbalize-wp' ); ?></button>
									</td>
								</tr>								
								<?php
							}
						} else {
							?>
							<tr data-id="1">
								<td>
									<input type="file" id="audio-file" accept=".mp3,.m4a,.ogg,.wav" data-id="1" name="audio_f[]" >
								</td>
								<td>
									<label><?php echo esc_html__( 'Type:', 'verbalize-wp' ); ?></label>
									<select name="button_type[]" class="button-type" data-id="1">
										<option value="speaker"><?php echo esc_html__( 'Speaker', 'verbalize-wp' ); ?></option>
										<option value="play"><?php echo esc_html__( 'Play', 'verbalize-wp' ); ?></option>	       								
									</select>
									<br/><br/>
									<label><?php echo esc_html__( 'Color:', 'verbalize-wp' ); ?></label>
									<select name="button_color[]" class="button-color" data-id="1">
										<option value="red"><?php echo esc_html__( 'Red', 'verbalize-wp' ); ?></option>
										<option value="black"><?php echo esc_html__( 'Black', 'verbalize-wp' ); ?></option>
										<option value="blue"><?php echo esc_html__( 'Blue', 'verbalize-wp' ); ?></option>
										<option value="white"><?php echo esc_html__( 'White', 'verbalize-wp' ); ?></option> 
									<option value="pink"><?php echo esc_html__( 'Pink', 'verbalize-wp' ); ?></option>
										<option value="yellow"><?php echo esc_html__( 'Yellow', 'verbalize-wp' ); ?></option>
									<option value="orange"><?php echo esc_html__( 'Orange', 'verbalize-wp' ); ?></option>
									</select>
								</td>
								<td>
									<label><?php echo esc_html__( 'Width : (in px)', 'verbalize-wp' ); ?></label>
									<input type="text" class="width_btn" name="width_btn[]" data-id="1" value="" placeholder="20">
									<br/><br/>
									<label><?php echo esc_html__( 'Height : (in px)', 'verbalize-wp' ); ?></label>
									<input type="text" class="height_btn" name="height_btn[]" data-id="1" value="" placeholder="20">
								</td>
								<td>
									<input type="number" min="1"  data-id="1" name="second_play[]">
								</td>				       		
								<td>
									<button type="button" class="gen-short button"  data-id="1" id="gen-short" value="save_audio"><?php echo esc_html__( 'Generate Shortcode', 'verbalize-wp' ); ?></button>
								</td>
								<td data-id="1" class="short-code">
									<input type="text" name="short_dis[]" id="short_dis1" value="" readonly data-id="1">
									<button type="button" title="Remove" value="remove" class="remove_row btn button btn-close" style="display:none;"><?php echo esc_html__( 'X', 'verbalize-wp' ); ?></button>
									<button type="button" data-toggle="tooltip" data-placement="bottom" title="Copied" class="button copy-short"  data-id="1"><?php echo esc_html__( 'Copy', 'verbalize-wp' ); ?></button>
								</td>
							</tr>
							<?php
						}
						?>
						<tr class="new-row"></tr>
						<tr>
							<td colspan="4">
								<button class="button button-primary" type="submit"><?php echo esc_html__( 'Save Changes', 'verbalize-wp' ); ?></button>				       		
							</td>
							<td>
								<button class="button add-more" type="button" id="add-more-aud" <?php echo esc_attr( $display1 ); ?> ><?php echo esc_html__( 'Add More', 'verbalize-wp' ); ?></button>
							</td>
						</tr>
					</table>
				</form>
				<input type="hidden" value="<?php echo esc_attr( $count ); ?>" id="aud-count" />
			</div>
		</div>
		<div class="postbox" id="p2">
			<div class="container">
				<form action="options.php" method="post" name="text-form" id="text-form">

					<?php
					settings_fields( 'text-options-group' );
					do_settings_sections( 'text-options-group' );
					?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php echo esc_html__( 'Text*', 'verbalize-wp' ); ?></th>
							<th scope="row"><?php echo esc_html__( 'Button*', 'verbalize-wp' ); ?><br/><span><?php echo esc_html__( '(Please select speaker/play button to display in shortcode)', 'verbalize-wp' ); ?></span></th>
							<th scope="row"><?php echo esc_html__( 'Button Sizes*', 'verbalize-wp' ); ?> </th>
							<th scope="row"><?php echo esc_html__( 'Action', 'verbalize-wp' ); ?></th>
							<th scope="row"><?php echo esc_html__( 'Shortcode', 'verbalize-wp' ); ?></th>
						</tr>
						<?php
						if ( ! empty( $shortcodes_text ) ) {
							foreach ( $shortcodes_text as $key2 => $value ) {
								$key = $key2 + 1;
								?>
								<tr data-id="<?php echo esc_attr( $key ); ?>">
									<td>
										<textarea name="text_conv[]" data-id="<?php echo esc_attr( $key ); ?>" rows="5" cols="50" maxlength="150"><?php echo esc_attr( $texts[ $key2 ] ); ?></textarea>
									</td>
									<td>
										<label><?php echo esc_html__( 'Type:', 'verbalize-wp' ); ?> </label>
										<select name="text_btn_type[]" class="button-text-type" data-id="<?php echo esc_attr( $key ); ?>">
											<option value="speaker" <?php if ( $text_btn[ $key2 ] === 'speaker' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Speaker', 'verbalize-wp' ); ?>
											</option>
											<option value="play"  <?php if ( $text_btn[ $key2 ] === 'play' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Play', 'verbalize-wp' ); ?>
											</option>
										</select>
										<br/><br/>
										<label><?php echo esc_html__( 'Color:', 'verbalize-wp' ); ?></label>
										<select name="text_btn_color[]" class="button-text-color" data-id="<?php echo esc_attr( $key ); ?>">
											<option value="red" <?php if ( $text_btn_color[ $key2 ] === 'red' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Red', 'verbalize-wp' ); ?>
											</option>
											<option value="black" <?php if ( $text_btn_color[ $key2 ] === 'black' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Black', 'verbalize-wp' ); ?>
											</option>
											<option value="blue" <?php if ( $text_btn_color[ $key2 ] === 'blue' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Blue', 'verbalize-wp' ); ?>
											</option>
											<option value="white" <?php if ( $text_btn_color[ $key2 ] === 'white' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'White', 'verbalize-wp' ); ?>
											</option>
											<option value="pink" <?php if ( $text_btn_color[ $key2 ] === 'pink' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Pink', 'verbalize-wp' ); ?>
											</option>
											<option value="yellow" <?php if ( $btn_color_audios[ $key2 ] === 'yellow' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Yellow', 'verbalize-wp' ); ?>
											</option>
											<option value="orange" <?php if ( $text_btn_color[ $key2 ] === 'orange' ) { echo esc_attr( 'selected' ); } ?>>
												<?php echo esc_html__( 'Orange', 'verbalize-wp' ); ?>
											</option>
										</select>
									</td>
									<td>
										<label><?php echo esc_html__( 'Width : (in px)', 'verbalize-wp' ); ?></label>
										<input type="text" class="text_width_btn" name="text_width_btn[]" data-id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $text_width[ $key2 ] ); ?>" placeholder="20">
										<br/><br/>
										<label><?php echo esc_html__( 'Height : (in px)', 'verbalize-wp' ); ?></label>
										<input type="text" class="text_height_btn" name="text_height_btn[]" data-id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $text_height[ $key2 ] ); ?>" placeholder="20">
									</td>
									<td>
										<button type="button" class="gen-short-text button"  data-id="<?php echo esc_attr( $key ); ?>" id="gen-short-text" value="save_text"><?php echo esc_html__( 'Generate Shortcode', 'verbalize-wp' ); ?></button>
									</td>
									<td data-id="1" class="short-code">
										<input type="text" name="short_dis_text[]" id="short_text<?php echo esc_attr( $key ); ?>" value='<?php echo esc_attr( $value ); ?>' readonly data-id="<?php echo esc_attr( $key ); ?>">
										<?php if ( $key > 1 ) { ?>
											<button type="button" data-id="<?php echo esc_attr( $key ); ?>" title="<?php echo esc_html__( 'Remove', 'verbalize-wp' ); ?>" value="remove" class="remove_row btn button btn-close"><?php echo esc_html__( 'X', 'verbalize-wp' ); ?></button>
										<?php } ?>
										<button type="button" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_html__( 'Copied', 'verbalize-wp' ); ?>" class="button copy-short-text" data-id="<?php echo esc_attr( $key ); ?>"><?php echo esc_html__( 'Copy', 'verbalize-wp' ); ?></button>									
									</td>
								</tr>
								<?php
							}
						} else {
							?>
							<tr data-id="1">
								<td>
									<textarea name="text_conv[]" data-id="1"  rows="5" cols="50" maxlength="150"></textarea>
								</td>
								<td>
									<label><?php echo esc_html__( 'Type:', 'verbalize-wp' ); ?> </label>
									<select name="text_btn_type[]" class="button-type" data-id="1">
										<option value="speaker"><?php echo esc_html__( 'Speaker', 'verbalize-wp' ); ?></option>
										<option value="play"><?php echo esc_html__( 'Play', 'verbalize-wp' ); ?></option>	       								
									</select>
									<br/><br/>
									<label><?php echo esc_html__( 'Color:', 'verbalize-wp' ); ?></label>
									<select name="text_btn_color[]" class="button-type" data-id="1">
										<option value="red"><?php echo esc_html__( 'Red', 'verbalize-wp' ); ?></option>
										<option value="black"><?php echo esc_html__( 'Black', 'verbalize-wp' ); ?></option>
										<option value="blue"><?php echo esc_html__( 'Blue', 'verbalize-wp' ); ?></option>
										<option value="white"><?php echo esc_html__( 'White', 'verbalize-wp' ); ?></option>
										<option value="pink"><?php echo esc_html__( 'Pink', 'verbalize-wp' ); ?></option>
										<option value="yellow"><?php echo esc_html__( 'Yellow', 'verbalize-wp' ); ?></option>
										<option value="orange"><?php echo esc_html__( 'Orange', 'verbalize-wp' ); ?></option>
									</select>
								</td>
								<td>
									<label><?php echo esc_html__( 'Width : (in px)', 'verbalize-wp' ); ?></label>
									<input type="text" class="text_width_btn" name="text_width_btn[]" data-id="1" value="" placeholder="20">
									<br/><br/>
									<label><?php echo esc_html__( 'Height : (in px)', 'verbalize-wp' ); ?></label>
									<input type="text" class="text_height_btn" name="text_height_btn[]" data-id="1" value="" placeholder="20">
								</td>			       		
								<td>
									<button type="button" class="gen-short-text button"  data-id="1" id="gen-short-text" value="save_text"><?php echo esc_html__( 'Generate Shortcode', 'verbalize-wp' ); ?></button>	       			

								</td>
								<td data-id="1" class="short-code-text">
									<input type="text" name="short_dis_text[]" id="short_text1" value="" readonly data-id="1">
									<button type="button" title="<?php echo esc_html__( 'Remove', 'verbalize-wp' ); ?>" value="remove" class="remove_row btn button btn-close" style="display:none;"><?php echo esc_html__( 'X', 'verbalize-wp' ); ?></button>
									<button type="button" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_html__( 'Copied', 'verbalize-wp' ); ?>" class="button copy-short-text" data-id="1"><?php echo esc_html__( 'Copy', 'verbalize-wp' ); ?></button>
								</td>
							</tr>
						<?php } ?>
							<tr class="new-row-text"></tr>
							<tr>
								<td colspan="3">
									<button class="button button-primary" type="submit"><?php echo esc_html__( 'Save Changes', 'verbalize-wp' ); ?></button>				       		
								</td>
								<td>
									<button class="button add-more" type="button" id="add-more-text" <?php echo esc_html( $display2 ); ?>> <?php echo esc_html__( 'Add More', 'verbalize-wp' ); ?></button>
								</td>
						</tr>
					</table>
				</form>
				<input type="hidden" value="<?php echo esc_attr( $count_text ); ?>" id="text-count" />
			</div>
		</div>
	</div>
