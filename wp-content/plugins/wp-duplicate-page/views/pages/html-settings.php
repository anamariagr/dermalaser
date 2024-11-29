<?php
defined( 'ABSPATH' ) || exit;
use NjtDuplicate\Helper\Utils;
?>
<div class="wrap">
	<div id="njt-duplicate-root">
		<div class="njt-duplicate-layout">
			<div class="njt-duplicate-layout-primary">
				<div class="njt-duplicate-layout-main">
					<div class="njt-duplicate-settings">
						<form method="post" id="njt_duplicate_setting_form">
							<div class="njt-duplicate-card">
								<div class="njt-duplicate-card-header">
									<div class="njt-duplicate-card-title-wrapper">
										<h3 class="njt-duplicate-card-title njt-duplicate-card-header-item">
											<?php echo esc_html( __( 'Duplicate Page Settings', 'wp-duplicate-page' ) ); ?>
										</h3>
									</div>
								</div>
								<div class="njt-duplicate-card-body"> 
									<div class="njt-duplicate-control">
										<label class="njt-duplicate-base-control-label" for="inspector-select-control-2"><?php echo esc_html( __( 'Allowed User Roles', 'wp-duplicate-page' ) ); ?></label>
										<div>
											<?php
											global $wp_roles;
											$roles            = $wp_roles->get_names();
											$editCapabilities = array( 'edit_posts' => true );
											foreach ( $roles as $roleName => $displayName ) :
												$role = get_role( $roleName );
												if ( count( array_intersect_key( $role->capabilities, $editCapabilities ) ) > 0 ) :
													?>
													<div class="njt-duplicate-base-control">
														<div class="njt-duplicate-base-control-field">
															<span class="njt-duplicate-checkbox-control-input-container">
																<input
																	type="checkbox"
																	id="njt-duplicate-<?php echo esc_attr( $roleName ); ?>" 
																	name="njt_duplicate_roles[]" 
																	class="njt-duplicate-checkbox-control-input" 
																	value="<?php echo esc_attr( $roleName ); ?>" 
																	<?php
																	if ( $role->has_cap( 'njt_duplicate_page' ) ) {
																		echo 'checked="checked"';
																	}
																	?>
																	 
																/>
																<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" role="img" class="njt-duplicate-checkbox-control-checked" aria-hidden="true" focusable="false">
																	<path d="M18.3 5.6L9.9 16.9l-4.6-3.4-.9 1.2 5.8 4.3 9.3-12.6z"></path>
																</svg>
															</span>
															<label class="njt-duplicate-checkbox-control-label" for="njt-duplicate-<?php echo esc_attr( $roleName ); ?>"><?php echo esc_html( translate_user_role( $displayName ) ); ?></label><br />
														</div>
													</div>
													<?php
												endif;
											endforeach;
											?>
										</div>
									</div>
									<div class="njt-duplicate-control">
										<label class="njt-duplicate-base-control-label" for="inspector-select-control-2"><?php echo esc_html( __( 'Allowed Post Types', 'wp-duplicate-page' ) ); ?></label>
										<div>
											<?php
											$postTypes = get_post_types( array( 'show_ui' => true ), 'objects' );
											foreach ( $postTypes as $postType ) :
												if ( 'attachment' === $postType->name ) {
													continue;
												}
												?>
													<div class="njt-duplicate-base-control">
														<div class="njt-duplicate-base-control-field">
															<span class="njt-duplicate-checkbox-control-input-container">
																<input
																	type="checkbox"
																	id="njt-duplicate-<?php echo esc_attr( $postType->name ); ?>" 
																	name="njt_duplicate_post_types[]" 
																	class="njt-duplicate-checkbox-control-input" 
																	value="<?php echo esc_attr( $postType->name ); ?>" 
																	<?php
																	if ( Utils::checkPostTypeDuplicate( $postType->name ) ) {
																		echo 'checked="checked"';
																	}
																	?>
																	 
																/>
																<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" role="img" class="njt-duplicate-checkbox-control-checked" aria-hidden="true" focusable="false">
																	<path d="M18.3 5.6L9.9 16.9l-4.6-3.4-.9 1.2 5.8 4.3 9.3-12.6z"></path>
																</svg>
															</span>
															<label class="njt-duplicate-checkbox-control-label" for="njt-duplicate-<?php echo esc_attr( $postType->name ); ?>"><?php echo esc_html( translate_user_role( $postType->labels->name ) ); ?></label><br />
														</div>
													</div>
											<?php endforeach; ?>
										</div>
									</div>
									<div class="njt-duplicate-control">
										<label class="njt-duplicate-base-control-label njt-duplicate-text" for="inspector-select-control-text"><?php echo esc_html( __( 'Duplicate Page Link Text', 'wp-duplicate-page' ) ); ?></label>
										<div class="njt-duplicate-base-control">
											<div class="njt-duplicate-base-control-field">
												<?php $duplicateTextLink = get_option( 'njt_duplicate_text_link' ) == false || get_option( 'njt_duplicate_text_link' ) == '' ? 'Duplicate' : get_option( 'njt_duplicate_text_link' ); ?>
												<input name="njt_duplicate_text_link"  class="njt-duplicate-text-control-input" type="text" id="inspector-text-control-2" value="<?php echo esc_attr( $duplicateTextLink ); ?>">
											</div>
											<p id="inspector-text-control-2-help" class="njt-duplicate-base-control-help"><?php echo esc_html( __( 'Text for duplicate page link. Default: ', 'wp-duplicate-page' ) ); ?><span class="njt-duplicate-default-text"><?php echo esc_html( __( 'Duplicate', 'wp-duplicate-page' ) ); ?></span></p>
										</div>
									</div>
									<p class="submit">
										<input 
											type="submit" 
											class="njt-duplicate-button is-primary"
											value="<?php esc_html_e( 'Save changes', 'wp-duplicate-page' ); ?>" 
										/>
									</p>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
