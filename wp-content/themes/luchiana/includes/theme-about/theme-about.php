<?php

if ( ! function_exists( 'ideapark_about_after_switch_theme' ) ) {
	add_action( 'after_switch_theme', 'ideapark_about_after_switch_theme', 1000, 2 );
	function ideapark_about_after_switch_theme( $old_name, $old_theme ) {
		/* @var $old_theme WP_Theme */
		if ( $old_theme->get_template() != IDEAPARK_SLUG ) {
			update_option( IDEAPARK_SLUG . '_about_page', 1 );
		}
	}
}

if ( ! function_exists( 'ideapark_about_revslider_redirect' ) ) {
	add_action( 'admin_init', 'ideapark_about_revslider_redirect', 1 );
	function ideapark_about_revslider_redirect() {
		if ( get_transient( '_revslider_welcome_screen_activation_redirect' ) ) {
			delete_transient( '_revslider_welcome_screen_activation_redirect' );
		}
	}
}

if ( ! function_exists( 'ideapark_about_after_setup_theme' ) ) {
	add_action( 'init', 'ideapark_about_after_setup_theme', 1000 );
	function ideapark_about_after_setup_theme() {
		if ( IDEAPARK_IS_AJAX ) {
			delete_option( 'activate-woo-variation-swatches' );

			return;
		}
		if ( get_transient( '_wc_activation_redirect' ) ) {
			delete_transient( '_wc_activation_redirect' );
		}
		if ( get_transient( 'elementor_activation_redirect' ) ) {
			delete_transient( 'elementor_activation_redirect' );
		}

		$option_name = IDEAPARK_SLUG . '_about_page';

		if ( ! defined( 'WP_CLI' ) && ( get_option( $option_name ) == 1 ) ) {
			wp_cache_delete( $option_name, 'options' );
			wp_cache_delete( 'alloptions', 'options' );
			wp_cache_delete( 'notoptions', 'options' );
			delete_option( $option_name );
			if ( strpos( filter_input( INPUT_SERVER, 'REQUEST_URI' ), 'page=ideapark_about' ) === false ) {
				wp_redirect( admin_url() . 'themes.php?page=ideapark_about' );
				exit();
			}
		}
	}
}

if ( ! function_exists( 'ideapark_about_add_menu_items' ) ) {
	add_action( 'admin_menu', 'ideapark_about_add_menu_items' );
	function ideapark_about_add_menu_items() {
		add_theme_page(
			sprintf( esc_html__( '%s Theme', 'luchiana' ), IDEAPARK_NAME ),
			sprintf( esc_html__( '%s Theme', 'luchiana' ), IDEAPARK_NAME ),
			'manage_options',
			'ideapark_about',
			'ideapark_about_page',
			0
		);
	}
}

if ( ! function_exists( 'ideapark_about_enqueue_scripts' ) ) {
	add_action( 'admin_enqueue_scripts', 'ideapark_about_enqueue_scripts' );
	function ideapark_about_enqueue_scripts() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		if ( ! empty( $screen->id ) && false !== strpos( $screen->id, '_page_ideapark_about' ) ) {
			wp_enqueue_script( 'plugin-install' );
			wp_enqueue_script( 'updates' );
			wp_enqueue_script( 'ideapark-plugins-installer', IDEAPARK_URI . '/includes/theme-about/plugins-installer.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_DIR . '/includes/theme-about/plugins-installer.js' ), true );
			wp_localize_script( 'ideapark-plugins-installer', 'ideapark_pi_vars', [
				'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
				'dashboardUrl' => admin_url( 'themes.php?page=ideapark_about' ),
				'ajaxNonce'    => wp_create_nonce( 'theme_about_nonce' ),
				'errorText'    => esc_html__( 'Something went wrong...', 'luchiana' )
			] );
			wp_enqueue_style( 'ideapark-about', IDEAPARK_URI . '/includes/theme-about/theme-about.css', [], ideapark_mtime( IDEAPARK_DIR . '/includes/theme-about/theme-about.css' ) );
		}
	}
}

if ( ! function_exists( 'ideapark_about_ajax' ) ) {
	add_action( 'wp_ajax_ideapark_about_ajax', 'ideapark_about_ajax' );
	function ideapark_about_ajax() {

		extract( ideapark_about_plugins() );
		/* @var $next_action array
		 * @var $other_plugin_action         array
		 * @var $other_plugin_list           array
		 * @var $other_plugin_unchecked      array
		 * @var $main_plugin_update_action   array
		 * @var $main_plugin_list            array
		 * @var $main_plugin_action          array
		 */

		if ( ! empty( $_POST['is_core_update'] ) ) {

			if ( ! empty( $main_plugin_update_action ) ) {
				echo json_encode( $main_plugin_update_action );
			} else {
				echo json_encode( [ 'success' => true ] );
			}

		} elseif ( ! empty( $_POST['is_additional'] ) ) {

			if ( ! empty( $other_plugin_action ) ) {
				echo json_encode( $other_plugin_action );
			} else {
				ob_start();
				ideapark_additional_plugin_list( $other_plugin_list, $other_plugin_unchecked );
				$list = ob_get_clean();
				echo json_encode(
					[
						'success' => true,
						'list'    => trim( $list )
					] );
			}

		} elseif ( ! empty( $_POST['is_main'] ) ) {

			if ( ! empty( $main_plugin_action ) ) {
				echo json_encode( $main_plugin_action );
			} else {
				ob_start();
				ideapark_main_plugin_list( $main_plugin_list );
				$list = ob_get_clean();
				echo json_encode(
					[
						'success' => true,
						'list'    => trim( $list )
					] );
			}

		} else {

			if ( ! empty( $next_action ) ) {
				echo json_encode( $next_action );
			} else {
				if ( ideapark_is_elementor() ) {
					$elementor_instance = Elementor\Plugin::instance();
					$elementor_instance->files_manager->clear_cache();
				}
				echo json_encode( [ 'success' => true ] );
			}
		}

		die();
	}
}

if ( ! function_exists( 'ideapark_is_installed_all_required_plugins' ) ) {
	function ideapark_is_installed_all_required_plugins() {
		extract( ideapark_about_plugins() );

		/* @var $next_action array */
		return empty( $next_action );
	}
}

if ( ! function_exists( 'ideapark_about_plugins' ) ) {
	function ideapark_about_plugins() {
		static $cache;

		if ( $cache ) {
			return $cache;
		}

		$plugins                   = ideapark_get_required_plugins();
		$next_action               = [];
		$main_plugin_list          = [];
		$main_plugin_update_action = [];
		$main_plugin_action        = [];
		$plugin_names              = [];
		$other_plugin_list         = [];
		$other_plugin_action       = [];
		$other_plugin_unchecked    = [];
		$filter                    = [];

		if ( ! empty( $_POST['plugins'] ) ) {
			$filter = explode( ',', $_POST['plugins'] );
		}

		$get_action = function ( $plugin ) {
			return [
				'name'   => sprintf( $plugin['state'] == 'install' ? esc_html__( 'Install %s', 'luchiana' ) : ( $plugin['state'] == 'update' ? esc_html__( 'Update %s', 'luchiana' ) : esc_html__( 'Activate %s', 'luchiana' ) ), $plugin['name'] ),
				'slug'   => $plugin['slug'],
				'state'  => $plugin['state'],
				'action' => ideapark_plugins_installer_get_action( $plugin )
			];
		};

		foreach ( $plugins as $plugin ) {
			$is_required     = ! empty( $plugin['required'] );
			$plugin['state'] = ideapark_plugins_installer_check_plugin_state( $plugin['slug'] );
			if ( ! empty( $plugin['notice_disable'] ) ) {
				$other_plugin_unchecked[] = $plugin['slug'];
			}
			if ( in_array( $plugin['state'], [ 'install', 'activate', 'update' ] ) ) {
				if ( $is_required ) {
					if ( ! $next_action ) {
						$next_action = $get_action( $plugin );
					}
					if ( ( $plugin['slug'] == 'ideapark-luchiana' ) ) {
						$main_plugin_update_action = $get_action( $plugin );
					} else {
						if ( ! $main_plugin_action && in_array( $plugin['slug'], $filter ) ) {
							$main_plugin_action = $get_action( $plugin );
						}
						$main_plugin_list[ $plugin['slug'] ] = esc_html( $plugin['name'] ) . ideapark_wrap( $plugin['state'] == 'install' ? esc_html__( 'Install and activate', 'luchiana' ) : ( $plugin['state'] == 'update' ? esc_html__( 'Update and activate', 'luchiana' ) : esc_html__( 'Activate', 'luchiana' ) ), '<span class="action_name">', '</span>' );
					}
					$plugin_names[] = $plugin['name'];

				} else {
					if ( ! empty( $plugin['type'] ) && $plugin['type'] == 'api' && ! ( ( $code = ideapark_get_purchase_code() ) && $code !== IDEAPARK_SKIP_REGISTER ) ) {
						continue;
					}
					if ( ! $other_plugin_action && in_array( $plugin['slug'], $filter ) ) {
						$other_plugin_action = $get_action( $plugin );
					}
					$other_plugin_list[ $plugin['slug'] ] = esc_html( $plugin['name'] ) . ideapark_wrap( $plugin['state'] == 'install' ? esc_html__( 'Install and activate', 'luchiana' ) : ( $plugin['state'] == 'update' ? esc_html__( 'Update and activate', 'luchiana' ) : esc_html__( 'Activate', 'luchiana' ) ), '<span class="action_name">', '</span>' );
				}
			}
		}

		return $cache = [
			'plugin_names'              => $plugin_names,
			'next_action'               => $next_action,
			'other_plugin_action'       => $other_plugin_action,
			'other_plugin_list'         => $other_plugin_list,
			'other_plugin_unchecked'    => $other_plugin_unchecked,
			'main_plugin_update_action' => $main_plugin_update_action,
			'main_plugin_list'          => $main_plugin_list,
			'main_plugin_action'        => $main_plugin_action,
		];
	}
}

if ( ! function_exists( 'ideapark_system_status' ) ) {
	function ideapark_system_status() { ?>
		<table class="ideapark_about_system_status">
			<?php if ( str_replace( '-child', '', wp_get_theme()->get( 'TextDomain' ) ) != IDEAPARK_DOMAIN ) { ?>
				<tr>
					<td width="180"><?php esc_html_e( 'Theme Text Domain:', 'luchiana' ); ?></td>
					<td>
						<?php
						echo sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> ', __( 'Non-standard', 'luchiana' ) );
						echo ideapark_wp_kses( sprintf( __( 'For the theme to work, the "Text Domain" parameter in the style.css file must be <strong>%s</strong>', 'luchiana' ), IDEAPARK_DOMAIN ) );
						?>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td width="180"><?php esc_html_e( 'Install Location:', 'luchiana' ); ?></td>
				<td>
					<?php
					if ( get_template() === IDEAPARK_SLUG ) {
						echo sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code>', esc_html__( 'Standard', 'luchiana' ) );
					} else {
						echo sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code>', __( 'Non-standard', 'luchiana' ) );
						echo ideapark_wp_kses( sprintf( __( 'Using %s Theme from non-standard install location or having a different directory name could lead to issues in receiving and installing updates. Please make sure that theme folder name is <strong>%s</strong>, without spaces.', 'luchiana' ), IDEAPARK_NAME, 'luchiana' ) );
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'File System Accessible:', 'luchiana' ); ?></td>
				<td>
					<?php
					global $wp_filesystem;

					if ( ( $wp_filesystem || WP_Filesystem() ) && ideapark_is_file( IDEAPARK_DIR . '/plugins/class-tgm-plugin-activation.php' ) ) {
						echo sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code>', esc_html__( 'Yes', 'luchiana' ) );
					} else {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s',
								__( 'No', 'luchiana' ),
								__( 'Theme has no direct access to the file system. Therefore plugins and pre-made websites installation is not possible to work properly.<br>Please try to insert the following code: <code>define( "FS_METHOD", "direct" );</code><br>before <code>/* That\'s all, stop editing! Happy blogging. */</code> in <code>wp-config.php</code>.', 'luchiana' ) )
						);
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Uploads Folder Writable:', 'luchiana' ); ?></td>
				<td>
					<?php
					$wp_uploads = wp_get_upload_dir();
					if ( wp_is_writable( trailingslashit( $wp_uploads['basedir'] ) ) ) {
						echo sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code>', esc_html__( 'Yes', 'luchiana' ) );
					} else {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s',
								__( 'No', 'luchiana' ),
								__( 'Uploads folder must be writable to allow WordPress function properly.<br>See <a href="https://codex.wordpress.org/Changing_File_Permissions" target="_blank">changing file permissions</a> or contact your hosting provider.', 'luchiana' ) )
						);
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'ZipArchive Support:', 'luchiana' ); ?></td>
				<td>
					<?php
					if ( class_exists( 'ZipArchive' ) ) {
						echo sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code>', esc_html__( 'Yes', 'luchiana' ) );
					} else {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s',
								__( 'No', 'luchiana' ),
								__( 'ZipArchive is required for plugins installation and pre-made websites import.<br>Please contact your hosting provider.', 'luchiana' ) )
						);
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'PHP Version:', 'luchiana' ); ?></td>
				<td>
					<?php
					$php_version = PHP_VERSION;
					if ( version_compare( '7.4.0', $php_version, '>' ) ) {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--warning">%s</code> %s',
								$php_version,
								__( 'Current version is sufficient. However <strong>v.7.4.0</strong> or greater is recommended to improve the performance.', 'luchiana' ) )
						);
					} else {
						echo sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code> %s',
							$php_version,
							esc_html__( 'Current version is sufficient.', 'luchiana' )
						);
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'PHP Max Input Vars:', 'luchiana' ); ?></td>
				<td>
					<?php
					$max_input_vars = ini_get( 'max_input_vars' );
					if ( $max_input_vars < 1000 ) {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s',
								$max_input_vars,
								__( 'Minimum value is <strong>1000</strong>. <strong>2000</strong> is recommended. <strong>3000</strong> or more may be required if lots of plugins are in use and/or you have a large amount of menu items.', 'luchiana' ) )
						);

					} elseif ( $max_input_vars < 2000 ) {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--warning">%s</code> %s',
								$max_input_vars,
								__( 'Current limit is sufficient for most tasks. <strong>2000</strong> is recommended. <strong>3000</strong> or more may be required if lots of plugins are in use and/or you have a large amount of menu items.', 'luchiana' ) )
						);
					} elseif ( $max_input_vars < 3000 ) {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code> %s',
								$max_input_vars,
								__( 'Current limit is sufficient. However, up to <strong>3000</strong> or more may be required if lots of plugins are in use and/or you have a large amount of menu items.', 'luchiana' ) )
						);
					} else {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code> %s',
								$max_input_vars,
								__( 'Current limit is sufficient.', 'luchiana' ) )
						);
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'WP Memory Limit:', 'luchiana' ); ?></td>
				<td>
					<?php

					$memory = wp_convert_hr_to_bytes( @ini_get( 'memory_limit' ) );

					$tip = ideapark_wp_kses( sprintf( __( '<br><small>See <a href="%1$s" target="_blank">increasing memory allocated to PHP</a> or contact your hosting provider.</small>', 'luchiana' ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) );

					if ( $memory < 67108864 ) {
						echo ideapark_wp_kses(
							sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s %s',
								size_format( $memory ),
								__( 'Minimum value is <strong>64 MB</strong>. <strong>128 MB</strong> is recommended. <strong>256 MB</strong> or more may be required if lots of plugins are in use and/or you want to install the Demo.', 'luchiana' ),
								$tip
							)
						);
					} elseif ( $memory < 134217728 ) {
						echo ideapark_wp_kses(
							sprintf( '<code class="ideapark_about_flag ideapark_about_flag--warning">%s</code> %s %s',
								size_format( $memory ),
								__( 'Current memory limit is sufficient for most tasks. However, recommended value is <strong>128 MB</strong>. <strong>256 MB</strong> or more may be required if lots of plugins are in use and/or you want to install the Demo.', 'luchiana' ),
								$tip
							)
						);
					} elseif ( $memory < 268435456 ) {
						echo ideapark_wp_kses(
							sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code> %s %s',
								size_format( $memory ),
								__( 'Current memory limit is sufficient. However, <strong>256 MB</strong> or more may be required if lots of plugins are in use and/or you want to install the Demo.', 'luchiana' ),
								$tip
							)
						);
					} else {
						echo sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code> %s',
							size_format( $memory ),
							esc_html__( 'Current memory limit is sufficient.', 'luchiana' )
						);
					}
					?>
				</td>
			</tr>
			<?php if ( function_exists( 'ini_get' ) ) : ?>
				<tr>
					<td><?php esc_html_e( 'PHP Time Limit:', 'luchiana' ); ?></td>
					<td>
						<?php
						$time_limit = ini_get( 'max_execution_time' );

						// translators: %1$s - wp codex article url.
						$tip = ideapark_wp_kses( sprintf( __( '<br><small>See <a href="%1$s" target="_blank">increasing max PHP execution time</a> or contact your hosting provider.</small>', 'luchiana' ), 'https://wordpress.org/support/article/common-wordpress-errors/#fatal-errors-and-warnings' ) );

						if ( 30 > $time_limit && 0 != $time_limit ) {
							echo ideapark_wp_kses(
								sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s %s',
									$time_limit,
									__( 'Minimum value is <strong>30</strong>. <strong>60</strong> is recommended.', 'luchiana' ),
									$tip
								)
							);
						} elseif ( ( 60 > $time_limit && 30 <= $time_limit ) && 0 != $time_limit ) {
							echo ideapark_wp_kses(
								sprintf( '<code class="ideapark_about_flag ideapark_about_flag--warning">%s</code> %s %s',
									$time_limit,
									__( 'Current time limit is sufficient, however <strong>60</strong> is recommended.', 'luchiana' ),
									$tip
								)
							);
						} elseif ( 60 <= $time_limit && 0 != $time_limit ) {
							echo ideapark_wp_kses(
								sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code> %s %s',
									$time_limit,
									__( 'Current time limit should be sufficient.', 'luchiana' ),
									$tip
								)
							);
						} else {
							echo ideapark_wp_kses(
								sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code> %s',
									_x( 'unlimited', 'Time limit status.', 'luchiana' ),
									__( 'Current time limit is sufficient.', 'luchiana' )
								)
							);
						}
						?>
					</td>
				</tr>
			<?php endif; ?>
			<?php if ( function_exists( 'ini_get' ) ) : ?>
				<tr>
					<td><?php esc_html_e( 'Zlib Output Compression:', 'luchiana' ); ?></td>
					<td>
						<?php
						$zlib_output_compression = ini_get( 'zlib.output_compression' );

						if ( strtolower( $zlib_output_compression ) == 'on' ) {
							echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s',
									__( 'On', 'luchiana' ),
									__( 'zlib.output_compression is problematic and throws errors most of the time in WordPress. Make sure to disable it.', 'luchiana' ) )
							);
						} else {
							echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code>', esc_html__( 'Off', 'luchiana' ) ) );
						}
						?>
					</td>
				</tr>
			<?php endif; ?>

			<tr>
				<td><?php esc_html_e( 'XML support:', 'luchiana' ); ?></td>
				<td>
					<?php
					if ( extension_loaded( 'xml' ) || extension_loaded( 'simplexml' ) ) {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code>', esc_html__( 'Yes', 'luchiana' ) ) );
					} else {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s',
								__( 'No', 'luchiana' ),
								__( 'XML or SIMPLEXML extensions must be installed in PHP. This will allow you to import demo content', 'luchiana' ) )
						);
					}
					?>
				</td>
			</tr>

			<tr>
				<td><?php esc_html_e( 'CURL support:', 'luchiana' ); ?></td>
				<td>
					<?php
					if ( extension_loaded( 'curl' ) ) {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--success">%s</code>', esc_html__( 'Yes', 'luchiana' ) ) );
					} else {
						echo ideapark_wp_kses( sprintf( '<code class="ideapark_about_flag ideapark_about_flag--danger">%s</code> %s',
								__( 'No', 'luchiana' ),
								__( 'CURL extensions must be installed in PHP. This will allow you to import demo content', 'luchiana' ) )
						);
					}
					?>
				</td>
			</tr>
		</table>
		<p>
			<a href="" onclick="return false" style="color: #bbb"
			   data-url="<?php echo esc_attr( get_option( 'ideapark_api_url' ) ); ?>"
			   id="ideapark-set-alt-api"><?php esc_html_e( 'Set alternative API', 'luchiana' ); ?></a>
		</p>
	<?php }
}

if ( ! function_exists( 'ideapark_about_page' ) ) {
	function ideapark_about_page() {

		extract( ideapark_about_plugins() );
		/* @var $plugin_names array */
		/* @var $next_action array */
		/* @var $other_plugin_unchecked array */
		/* @var $main_plugin_update_action array */
		/* @var $main_plugin_list array */

		$purchase_code = ideapark_get_purchase_code();

		if ( isset( $_REQUEST['clear_cache'] ) ) {
			ideapark_clear_cache();
			exit();
		} elseif ( isset( $_REQUEST['skip_registration'] ) ) {
			ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
			wp_redirect( admin_url( 'themes.php?page=ideapark_about' ) );
			exit();
		}

		?>
		<div class="ideapark_about">
			<div class="ideapark_about_wrap">
				<h1 class="ideapark_about_title">
					<?php
					echo esc_html(
						sprintf(
							__( 'Welcome to %1$s v.%2$s', 'luchiana' ),
							IDEAPARK_NAME,
							IDEAPARK_VERSION
						)
					);
					?>
				</h1>
				<div class="ideapark_about_change_log">
					<?php echo ideapark_get_theme_update_info(); ?>
					<a href="<?php echo esc_url( IDEAPARK_CHANGELOG ); ?>"
					   target="_blank"><?php esc_html_e( 'See Changelog', 'luchiana' ); ?></a>
					&nbsp;&nbsp;&nbsp; <a href="" onclick="return false"
										  class="ideapark_about_check button button-link js-check-for-updates"><?php esc_html_e( 'Check for updates', 'luchiana' ); ?></a>
				</div>

				<?php if ( ! $purchase_code ) { ?>
					<p class="ideapark_about_first"><?php echo sprintf( esc_html__( 'First things first, let’s register your copy of %s Theme to enable theme updates, importing demos, installing and updating premium plugins.', 'luchiana' ), IDEAPARK_NAME ) ?></p>
					<?php ideapark_register_form();
				} else {
					if ( $purchase_code === IDEAPARK_SKIP_REGISTER ) {
						ideapark_register_form( true );
					} else {
						ideapark_deregister_form();
					}

					$is_initial_activation = $plugin_names && ! ideapark_core_plugin_on();

					if ( $is_initial_activation ) { ?>
						<div class="ideapark_about_required_plugins">
							<div class="ideapark_about_description">
								<p>
									<?php
									echo ideapark_wp_kses(
										sprintf(
											__( 'The required theme plugins will be installed or updated and activated:<br><b>%1$s</b>', 'luchiana' ),
											implode( ', ', $plugin_names )
										)
									);
									?>
								</p>
							</div>

							<div class="ideapark_about_buttons">
								<a class="ideapark_plugins_installer_link button button-primary install-now"
								   href="" onclick="return false"><?php esc_html_e( 'Continue', 'luchiana' ) ?></a>
								<div class="ideapark_plugins_installer_error"></div>
								<div class="ideapark_plugins_installer_success hidden">
									<p>
								<span
									class="dashicons dashicons-yes"></span> <?php esc_html_e( 'All required plugins have been successfully installed.', 'luchiana' ); ?>
									</p>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="ideapark_about_next_step <?php if ( $is_initial_activation ) { ?>hidden<?php } ?>">
						<?php if ( current_user_can( 'install_plugins' ) ) { ?>
							<?php $need_break = false; ?>
							<?php if ( ! $is_initial_activation && ( defined( 'IDEAPARK_LUCHIANA_FUNC_VERSION' ) && version_compare( IDEAPARK_LUCHIANA_FUNC_VERSION, IDEAPARK_VERSION, '<' ) ) ) { ?>
								<div class="step">
									<b><?php echo sprintf( esc_html__( '%s Core Plugin', 'luchiana' ), IDEAPARK_NAME ); ?></b>
									<p><?php echo sprintf( __( 'You need to update plugin <strong>%s Core</strong> to version %s', 'luchiana' ), IDEAPARK_NAME, IDEAPARK_VERSION ); ?></p>
									<a href="" onclick="return false"
									   class="ideapark_plugins_installer_link core button button-primary button-danger"><?php esc_html_e( 'Update', 'luchiana' ); ?></a>
									<p class="core-plugins-updated hidden">
										<b><?php esc_html_e( 'Core plugin has been successfully updated.', 'luchiana' ); ?></b>
									</p>
								</div>
								<?php $need_break = true; ?>
							<?php } ?>
							<?php if ( ! $is_initial_activation && $main_plugin_list ) { ?>
								<div class="step">
									<b><?php esc_html_e( 'Required Plugins', 'luchiana' ); ?></b>
									<p><?php esc_html_e( 'Install and activate or update required plugins', 'luchiana' ); ?></p>
									<?php if ( ! empty( $main_plugin_list ) && ideapark_plugins_installer_tgmpa_menu() ) { ?>
										<?php ideapark_main_plugin_list( $main_plugin_list ); ?>
										<a href="<?php echo admin_url( 'themes.php?page=tgmpa-install-plugins' ); ?>"
										   class="ideapark_plugins_installer_link main button button-primary button-danger"><?php esc_html_e( 'Continue', 'luchiana' ); ?></a>
									<?php } ?>
									<p class="main-plugins-installed <?php if ( ! empty( $main_plugin_list ) && ideapark_plugins_installer_tgmpa_menu() ) { ?> hidden<?php } ?>">
										<b><?php esc_html_e( 'All required plugins are installed.', 'luchiana' ); ?></b>
									</p>
								</div>
								<?php $need_break = true; ?>
							<?php } ?>
							<?php if ( $need_break ) { ?>
								<div class="step-break"></div><?php } ?>
							<?php if ( $other_plugin_list ) { ?>
								<div class="step">
									<b><?php esc_html_e( 'Additional Plugins', 'luchiana' ); ?></b>
									<p><?php esc_html_e( 'You can install additional plugins to extend the functionality of the theme', 'luchiana' ); ?></p>
									<?php if ( ! empty( $other_plugin_list ) && ideapark_plugins_installer_tgmpa_menu() ) { ?>
										<?php ideapark_additional_plugin_list( $other_plugin_list, $other_plugin_unchecked ); ?>
										<a href="<?php echo admin_url( 'themes.php?page=tgmpa-install-plugins' ); ?>"
										   class="ideapark_plugins_installer_link additional button button-primary"><?php esc_html_e( 'Continue', 'luchiana' ); ?></a>
									<?php } ?>
									<p class="additional-plugins-installed <?php if ( ! empty( $other_plugin_list ) && ideapark_plugins_installer_tgmpa_menu() ) { ?> hidden<?php } ?>">
										<b><?php esc_html_e( 'All additional plugins are installed.', 'luchiana' ); ?></b>
									</p>
								</div>
							<?php } ?>
						<?php } ?>
						<?php if ( ! ideapark_get_child_theme() ) { ?>
							<div class="step">
								<b><?php esc_html_e( 'Setup Child Theme', 'luchiana' ); ?></b>
								<p><?php echo ideapark_wp_kses( sprintf( __( 'Child theme is used when you want to <a href="%s" target="_blank">modify the theme</a> without losing the ability to update the theme.', 'luchiana' ), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/#adding-template-files' ) ); ?></p>
								<p><?php esc_html_e( 'Install and activate Child theme, and finally copy settings from the parent theme', 'luchiana' ); ?></p>
								<a href="" onclick="return false"
								   class="ideapark_child_installer_link button button-primary"><?php esc_html_e( 'Install Child Theme', 'luchiana' ); ?></a>
							</div>
						<?php } ?>
						<div class="step">
							<b><?php esc_html_e( 'Import Demo', 'luchiana' ); ?></b>
							<p><?php esc_html_e( 'Use our Demo Content Importer to make your website similar to our demos', 'luchiana' ); ?>
								<br><br><b><?php esc_html_e( 'Please install necessary additional plugins before importing', 'luchiana' ); ?></b>
							</p>
							<?php if ( ( $code = ideapark_get_purchase_code() ) && $code !== IDEAPARK_SKIP_REGISTER ) { ?>
								<a href="<?php echo admin_url( 'themes.php?page=ideapark_themes_importer_page' ); ?>"
								   target="_blank"
									<?php if ( str_replace( '-child', '', wp_get_theme()->get( 'TextDomain' ) ) != IDEAPARK_DOMAIN ) { ?>
										disabled
										onclick="alert('<?php echo esc_attr( __( 'Fix the issues shown in the System Status section', 'luchiana' ) ); ?>');return false;"
									<?php } ?>
								   class="button button-primary"><?php esc_html_e( 'Import Demo', 'luchiana' ); ?></a>
							<?php } else { ?>
								<p class="ideapark_about_warning"><?php esc_html_e( 'Available only to registered theme', 'luchiana' ); ?></p>
							<?php } ?>
						</div>
						<div class="step">
							<b><?php esc_html_e( 'Theme Customization', 'luchiana' ); ?></b>
							<p><?php echo ideapark_wp_kses( sprintf( __( 'Explore the <a href="%s" target="_blank">documentation</a> and then start customizing the theme', 'luchiana' ), IDEAPARK_MANUAL ) ); ?></p>
							<a href="<?php echo admin_url( 'customize.php' ); ?>" target="_blank"
							   class="button button-primary"><?php esc_html_e( 'Customize', 'luchiana' ); ?></a>
						</div>
						<div class="step">
							<b><?php esc_html_e( 'Get Support', 'luchiana' ); ?></b>
							<p><?php esc_html_e( 'We offer outstanding support through our ticket system. To get submit a ticket first you need to register an account and verify your purchase.', 'luchiana' ); ?></p>
							<a href="<?php echo esc_url( IDEAPARK_SUPPORT ); ?>" target="_blank"
							   class="button button-primary"><?php esc_html_e( 'Submit a Ticket', 'luchiana' ); ?></a>
							<p><a style="color: #bbb"
								  href="<?php echo admin_url( 'themes.php?page=ideapark_about&clear_cache&noheader' ); ?>"><?php esc_html_e( 'Clear theme cache', 'luchiana' ); ?></a>
							</p>
						</div>
					</div>
				<?php } ?>

				<div class="ideapark_about_requirements">
					<b class="title"><?php esc_html_e( 'System Status', 'luchiana' ); ?></b>
					<p><?php esc_html_e( 'Please make sure that all system requirements are met (green color).', 'luchiana' ); ?></p>
					<?php ideapark_system_status(); ?>
				</div>
			</div>
		</div>
	<?php }
}

if ( ! function_exists( 'ideapark_additional_plugin_list' ) ) {
	function ideapark_additional_plugin_list( $other_plugin_list, $other_plugin_unchecked = [] ) {
		$filter = false;
		if ( isset( $_POST['plugins'] ) ) {
			$filter = explode( ',', $_POST['plugins'] );
		}
		$tooltips = [];
		$plugins  = ideapark_get_required_plugins();
		foreach ( $plugins as $plugin ) {
			if ( ! empty( $plugin['tooltip'] ) ) {
				$tooltips[ $plugin['slug'] ] = $plugin['tooltip'];
			}
		}
		?>
		<?php if ( $other_plugin_list ) { ?>
			<ul class="plugins_list">
				<?php foreach ( $other_plugin_list as $plugin_code => $plugin_name ) { ?>
					<li class="plugins_list__item">
						<label><input type="checkbox" class="ideapark_additional_plugin" name="plugin[]"
									  value="<?php echo esc_attr( $plugin_code ); ?>"
						              <?php if ( ( $filter === false || in_array( $plugin_code, $filter ) ) && $filter === false && ! in_array( $plugin_code, $other_plugin_unchecked ) ) { ?>checked<?php } ?>> <?php echo ideapark_wrap( $plugin_name ); ?>
							<?php if ( ! empty( $tooltips[ $plugin_code ] ) ) { ?>
								<div class="ideapark_tooltip ideapark_tooltip--large ideapark_tooltip--top">
									<?php echo esc_html( $tooltips[ $plugin_code ] ); ?>
								</div>
							<?php } ?>
						</label>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
		<?php
	}
}

if ( ! function_exists( 'ideapark_main_plugin_list' ) ) {
	function ideapark_main_plugin_list( $main_plugin_list, $main_plugin_unchecked = [] ) {
		$filter = false;
		if ( isset( $_POST['plugins'] ) ) {
			$filter = explode( ',', $_POST['plugins'] );
		}
		$tooltips = [];
		$plugins  = ideapark_get_required_plugins();
		foreach ( $plugins as $plugin ) {
			if ( ! empty( $plugin['tooltip'] ) ) {
				$tooltips[ $plugin['slug'] ] = $plugin['tooltip'];
			}
		}
		?>
		<?php if ( $main_plugin_list ) { ?>
			<ul class="plugins_list">
				<?php foreach ( $main_plugin_list as $plugin_code => $plugin_name ) { ?>
					<li class="plugins_list__item">
						<label><input type="checkbox" class="ideapark_main_plugin" name="plugin[]"
									  value="<?php echo esc_attr( $plugin_code ); ?>"
						              <?php if ( ( $filter === false || in_array( $plugin_code, $filter ) ) && $filter === false && ! in_array( $plugin_code, $main_plugin_unchecked ) ) { ?>checked<?php } ?>> <?php echo ideapark_wrap( $plugin_name ); ?>
							<?php if ( ! empty( $tooltips[ $plugin_code ] ) ) { ?>
								<div class="ideapark_tooltip ideapark_tooltip--large ideapark_tooltip--top">
									<?php echo esc_html( $tooltips[ $plugin_code ] ); ?>
								</div>
							<?php } ?>
						</label>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
		<?php
	}
}

if ( ! function_exists( 'ideapark_about_page_disable_tgmpa_notice' ) ) {
	add_filter( 'tgmpa_show_admin_notice_capability', 'ideapark_about_page_disable_tgmpa_notice' );
	function ideapark_about_page_disable_tgmpa_notice( $capability ) {
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'ideapark_about' ) {
			$capability = 'unfiltered_upload';
		}

		return $capability;
	}
}

if ( ! function_exists( 'ideapark_plugins_installer_get_action' ) ) {
	function ideapark_plugins_installer_get_action( $plugin ) {
		$output = '';
		if ( ! empty( $plugin['slug'] ) ) {
			$slug = $plugin['slug'];
			switch ( $plugin['state'] ) {
				case 'install':
					if ( class_exists( 'TGM_Plugin_Activation' ) ) {
						$instance = call_user_func( [ get_class( $GLOBALS['tgmpa'] ), 'get_instance' ] );
						$nonce    = wp_nonce_url(
							add_query_arg(
								[
									'plugin'        => urlencode( $slug ),
									'tgmpa-install' => 'install-plugin',
								],
								$instance->get_tgmpa_url()
							),
							'tgmpa-install',
							'tgmpa-nonce'
						);
					} else {
						$nonce = wp_nonce_url(
							add_query_arg(
								[
									'action' => 'install-plugin',
									'from'   => 'import',
									'plugin' => urlencode( $slug ),
								],
								network_admin_url( 'update.php' )
							),
							'install-plugin_' . trim( $slug )
						);
					}
					$output = $nonce;
					break;

				case 'activate':
					if ( class_exists( 'TGM_Plugin_Activation' ) ) {
						$instance = call_user_func( [ get_class( $GLOBALS['tgmpa'] ), 'get_instance' ] );
						$nonce    = wp_nonce_url(
							add_query_arg(
								[
									'plugin'         => urlencode( $slug ),
									'tgmpa-activate' => 'activate-plugin',
								],
								$instance->get_tgmpa_url()
							),
							'tgmpa-activate',
							'tgmpa-nonce'
						);
					} else {
						$plugin_link = $slug . '/' . $slug . '.php';
						$nonce       = add_query_arg(
							[
								'action'        => 'activate',
								'plugin'        => rawurlencode( $plugin_link ),
								'plugin_status' => 'all',
								'paged'         => '1',
								'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin_link ),
							],
							network_admin_url( 'plugins.php' )
						);
					}
					$output = $nonce;
					break;

				case 'update':
					if ( class_exists( 'TGM_Plugin_Activation' ) ) {
						$instance = call_user_func( [ get_class( $GLOBALS['tgmpa'] ), 'get_instance' ] );
						$nonce    = wp_nonce_url(
							add_query_arg(
								[
									'plugin'       => urlencode( $slug ),
									'tgmpa-update' => 'update-plugin',
								],
								$instance->get_tgmpa_url()
							),
							'tgmpa-update',
							'tgmpa-nonce'
						);
					} else {
						$plugin_link = $slug . '/' . $slug . '.php';
						$nonce       = add_query_arg(
							[
								'action'        => 'update',
								'plugin'        => rawurlencode( $plugin_link ),
								'plugin_status' => 'all',
								'paged'         => '1',
								'_wpnonce'      => wp_create_nonce( 'update-plugin_' . $plugin_link ),
							],
							network_admin_url( 'plugins.php' )
						);
					}
					$output = $nonce;
					break;
			}
		}

		return str_replace( '&amp;', '&', $output );
	}
}

if ( ! function_exists( 'ideapark_plugins_installer_check_plugin_state' ) ) {
	function ideapark_plugins_installer_check_plugin_state( $slug ) {

		static $installed_plugins;

		$state = 'install';

		if ( empty( $installed_plugins ) ) {
			ideapark_register_required_plugins();
			$installed_plugins = true;
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		foreach ( $plugins as $path => $plugin ) {
			if ( strpos( $path, $slug . '/' ) === 0 ) {
				$state = is_plugin_inactive( $path ) ? 'activate' : 'deactivate';
			}
		}

		if ( $state != 'install' && ! empty( $GLOBALS['tgmpa'] ) && $GLOBALS['tgmpa']->does_plugin_have_update( $slug ) ) {
			$state = 'update';
		} elseif ( $state != 'install' && ! empty( $GLOBALS['tgmpa'] ) && $GLOBALS['tgmpa']->does_plugin_require_update( $slug ) ) {
			$state = 'update';
		}

		return $state;
	}
}

if ( ! function_exists( 'ideapark_plugins_installer_tgmpa_menu' ) ) {
	function ideapark_plugins_installer_tgmpa_menu() {

		static $installed_plugins;

		$state = true;

		if ( empty( $installed_plugins ) ) {
			ideapark_register_required_plugins();
			$installed_plugins = true;
		}

		if ( empty( $GLOBALS['tgmpa'] ) || true == $GLOBALS['tgmpa']->is_tgmpa_complete() ) {
			$state = false;
		}

		return $state;
	}
}

if ( ! function_exists( 'ideapark_get_child_theme' ) ) {
	function ideapark_get_child_theme() {
		$child_theme              = false;
		$current_installed_themes = wp_get_themes();
		$active_theme             = wp_get_theme();
		$theme_folder_name        = $active_theme->get_template();

		if ( is_array( $current_installed_themes ) ) {
			foreach ( $current_installed_themes as $key => $theme_obj ) {
				if ( $theme_obj->get( 'Template' ) === $theme_folder_name ) {
					$child_theme = $theme_obj;
				}
			}
		}

		return $child_theme;
	}
}

if ( ! function_exists( 'ideapark_install_child_theme' ) ) {
	function ideapark_install_child_theme() {

		$url = IDEAPARK_DIR . '/plugins/luchiana-child.zip';

		if ( ! current_user_can( 'install_themes' ) ) {
			return new WP_Error( 'ideapark-error-fbn', __( 'Forbidden to install child themes', 'luchiana' ) );
		}

		if ( ! class_exists( 'Theme_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$skin = new Automatic_Upgrader_Skin();

		$upgrader = new Theme_Upgrader( $skin, [ 'clear_destination' => true ] );
		$result   = $upgrader->install( $url );

		if ( $result === null && ! empty( $skin->result ) ) {
			$result = $skin->result;
		}

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return true;
	}
}

if ( ! function_exists( 'ideapark_install_child' ) ) {
	function ideapark_install_child() {

		if ( ! check_ajax_referer( 'theme_about_nonce', 'security', false ) ) {
			wp_send_json_error( esc_html__( 'Invalid security nonce! Reload page and try again.', 'luchiana' ) );
		}

		if ( ( $result = ideapark_install_child_theme() ) && ! is_wp_error( $result ) ) {

			$child_theme = ideapark_get_child_theme();

			if ( $child_theme !== false ) {
				switch_theme( $child_theme->get_stylesheet() );
				ideapark_migrate_mods_child();
			}

			wp_send_json_success( esc_html__( 'The child theme has been successfully set up', 'luchiana' ) );
		}

		wp_send_json_error( is_wp_error( $result ) ? $result->get_error_message() : esc_html__( 'Something went wrong. Please try again!', 'luchiana' ) );
	}

	add_action( 'wp_ajax_ideapark_install_child', 'ideapark_install_child' );
}

if ( ! function_exists( 'ideapark_migrate_mods_child' ) ) {
	function ideapark_migrate_mods_child() {
		$theme_name          = IDEAPARK_SLUG;
		$child_theme_name    = $theme_name . '-child';
		$child_theme_options = sprintf( 'theme_mods_%s', $child_theme_name );

		if ( ( $mods = get_option( sprintf( 'theme_mods_%s', $theme_name ) ) ) && update_option( $child_theme_options, $mods ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'ideapark_register_form' ) ) {
	function ideapark_register_form( $short_form = false ) {

		$ideapark_purchase_code = isset( $_POST['ideapark_purchase_code'] ) ? trim( $_POST['ideapark_purchase_code'] ) : '';
		?>
		<form class="js-register-form" method="post" action="">
			<div class="ideapark_purchase_form">
				<label for="ideapark-purchase-code"
					   class="ideapark_purchase_label --required"><?php esc_html_e( 'Your Purchase Code', 'luchiana' ); ?></label>
				<input id="ideapark-purchase-code" class="ideapark_purchase_code" name="ideapark_purchase_code"
					   type="text"
					   value="<?php echo esc_attr( $ideapark_purchase_code ) ?>"
					   placeholder="<?php esc_attr_e( 'e.g. cb0e057f-a05d-4758-b314-024db98eff85', 'luchiana' ); ?>"
					   required
					   pattern="[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$"
					   autocomplete="off"
					   size="42"/>
				<?php if ( ! $short_form ) { ?>
			</div>
			<div class="ideapark_purchase_error js-purchase-error"></div>
			<p class="ideapark_purchase_help"><?php echo ideapark_wp_kses( sprintf( __( 'Login to your Envato Account and access <a href="%s" target="_blank">%s\'s Support Tab</a> where you can find all your %s Theme purchase codes.', 'luchiana' ), preg_replace( '~#.+$~', '', IDEAPARK_CHANGELOG ) . '/support', IDEAPARK_NAME, IDEAPARK_NAME ) ) ?></p>
			<div class="ideapark_about_purchase_buttons">
				<button type="submit"
						class="button button-primary js-register-theme"><?php esc_html_e( 'Continue', 'luchiana' ) ?></button>
				<a href="<?php echo admin_url( 'themes.php?page=ideapark_about&skip_registration&noheader' ); ?>"
				   class="button js-skip-registration">
					<?php esc_html_e( 'Skip Registration', 'luchiana' ) ?>
					<div class="ideapark_tooltip ideapark_tooltip--large ideapark_tooltip--top">
						<?php echo ideapark_wp_kses( __( '<strong>Are you sure?</strong> Skipping registration will result in certain features to be disabled( such as importing demos, installing premium plugins or even theme updates).', 'luchiana' ) ) ?>
					</div>
				</a>
			</div>
			<?php } else { ?>
				<button type="submit"
						class="button button-small js-register-theme"><?php esc_html_e( 'Register', 'luchiana' ) ?></button>
				</div>
				<div class="ideapark_purchase_error js-purchase-error"></div>
			<?php } ?>

		</form>
	<?php }
}

if ( ! function_exists( 'ideapark_deregister_form' ) ) {
	function ideapark_deregister_form() { ?>
		<div class="ideapark_about_deregister_form">
			The theme is registered.
			<a href="" onclick="return false"
			   class="js-deregister-theme ideapark_about_deregister_button"
			   data-confirm="<?php esc_attr_e( 'Are you sure?', 'luchiana' ) ?>"><?php esc_html_e( 'Deregister', 'luchiana' ) ?></a>
			<div class="ideapark_purchase_error js-purchase-error"></div>
		</div>
	<?php }
}

define( 'IDEAPARK_API_URL', 'https://parkofideas.com/api/' );
define( 'IDEAPARK_SKIP_REGISTER', 'skip' );

if ( ! function_exists( 'ideapark_api_url' ) ) {
	function ideapark_api_url() {
		return get_option( 'ideapark_api_url' ) ?: IDEAPARK_API_URL;
	}
}

if ( ! function_exists( 'ideapark_set_api_url' ) ) {
	function ideapark_set_api_url() {
		if ( ! check_ajax_referer( 'theme_about_nonce', 'security', false ) ) {
			wp_send_json_error( esc_html__( 'Invalid security nonce! Reload page and try again.', 'luchiana' ) );
		}
		if ( $url = isset( $_POST['url'] ) ? trim( $_POST['url'] ) : '' ) {

			$response = ideapark_heartbeat('', $url);

			if ( is_wp_error( $response ) || empty( $response['body'] ) || ! ( $data = json_decode( $response['body'], JSON_OBJECT_AS_ARRAY ) ) || empty( $data['success'] ) ) {
				wp_send_json_error( esc_html__( 'Invalid API url.', 'luchiana' ) );
			} else {
				update_option( 'ideapark_api_url', $url );
				wp_send_json_success( esc_html__( 'The url has been successfully set', 'luchiana' ) );
			}
		} else {
			delete_option( 'ideapark_api_url' );
			wp_send_json_success( esc_html__( 'The url has been successfully reset', 'luchiana' ) );
		}
	}

	add_action( 'wp_ajax_ideapark_set_api_url', 'ideapark_set_api_url' );
}

if ( ! function_exists( 'ideapark_api' ) ) {
	function ideapark_api_post( $args, $timeout = 40 ) {
		if ( empty( $args['version'] ) ) {
			$args['site'] = get_option( 'siteurl' );
			$args['slug'] = IDEAPARK_SLUG;
		}
		$response = wp_remote_post( ideapark_api_url(), [
			'timeout'   => $timeout,
			'body'      => (array) $args,
			'sslverify' => false
		] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $data ) || ! is_array( $data ) ) {
			return new WP_Error( 'no_json', ideapark_wp_kses( sprintf( __( 'Response from API server seems to be empty. Please try again, or make sure your server is not blocking the API calls towards %s.', 'luchiana' ), ideapark_api_url() ) ) );
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== (int) $response_code ) {
			return new WP_Error( $response_code, sprintf(
				esc_html__( '%s (HTTP Error)', 'luchiana' ),
				$response_code
			) );
		}

		return $data;
	}
}

if ( ! function_exists( 'ideapark_api_get_theme_version' ) ) {
	function ideapark_api_get_theme_version() {
		$result = ideapark_api_post( [ 'version' => IDEAPARK_SLUG ] );
		if ( is_wp_error( $result ) ) {
			return $result;
		} elseif ( ! empty( $result['version'] ) ) {
			return $result['version'];
		} elseif ( ! empty( $result['error'] ) ) {
			return new WP_Error( 'ideapark_api_error', $result['error'] );
		}
	}
}

if ( ! function_exists( 'ideapark_check_purchase' ) ) {
	function ideapark_api_theme_register( $code ) {
		if ( ! ideapark_check_code( $code ) ) {
			return new WP_Error( 'ideapark_invalid_purchase', esc_html__( 'Please enter the correct purchase code format, eg: 00000000-0000-0000-0000-000000000000', 'luchiana' ) );
		}
		$result = ideapark_api_post( [ 'code' => $code ], 5 );
		if ( is_wp_error( $result ) ) {
			return $result;
		} elseif ( ! empty( $result['success'] ) ) {
			return true;
		} elseif ( ! empty( $result['error'] ) ) {
			return new WP_Error( 'ideapark_api_error', $result['error'] );
		}
	}
}

if ( ! function_exists( 'ideapark_check_purchase' ) ) {
	function ideapark_api_theme_deregister() {
		if ( ( $code = ideapark_get_purchase_code() ) && $code !== IDEAPARK_SKIP_REGISTER ) {
			$result = ideapark_api_post( [ 'deregister' => $code ] );
			if ( is_wp_error( $result ) ) {
				return $result;
			} elseif ( ! empty( $result['success'] ) ) {
				return true;
			} elseif ( ! empty( $result['error'] ) ) {
				return new WP_Error( 'ideapark_api_error', $result['error'] );
			}
		}
	}
}


if ( ! function_exists( 'ideapark_api_theme_get_file' ) ) {
	function ideapark_api_theme_get_file( $file, $demo = '' ) {
		if ( ( $code = ideapark_get_purchase_code() ) && $code !== IDEAPARK_SKIP_REGISTER ) {
			$args = [ 'file' => $file, 'code' => $code ];
			if ( $demo ) {
				$args['demo'] = $demo;
			}
			$result = ideapark_api_post( $args );
			if ( is_wp_error( $result ) ) {
				return $result;
			} elseif ( ! empty( $result['success'] ) && ! empty( $result['url'] ) ) {
				if ( ! ideapark_is_dir( IDEAPARK_UPLOAD_DIR ) ) {
					ideapark_mkdir( IDEAPARK_UPLOAD_DIR );
				}
				$fn = IDEAPARK_UPLOAD_DIR . 'demo.dat';
				if ( ideapark_is_file( $fn ) ) {
					ideapark_delete_file( $fn );
				}

				static $api;

				if ( ! $api ) {
					$api = ideapark_api_url();
				}
				if ( $api != IDEAPARK_API_URL ) {
					$api_host_from = parse_url( IDEAPARK_API_URL, PHP_URL_HOST );
					$api_host_to   = parse_url( $api, PHP_URL_HOST );
					$result['url'] = str_replace( $api_host_from, $api_host_to, $result['url'] );
				}

				$response = wp_remote_get( $result['url'], [
					'filename'  => $fn,
					'stream'    => true,
					'timeout'   => 30,
					'sslverify' => false
				] );

				if ( ! $response || is_wp_error( $response ) ) {
					ideapark_delete_file( $fn );

					return new WP_Error( 'import_file_error', __( 'Remote server did not respond', 'luchiana' ) . ( is_wp_error( $response ) ? ': ' . $response->get_error_message() : '' ) );
				}

				// make sure the fetch was successful
				if ( $response['response']['code'] != '200' ) {
					ideapark_delete_file( $fn );

					return new WP_Error( 'import_file_error', sprintf( __( 'Remote server returned error response %1$d %2$s', 'luchiana' ), esc_html( $response['response']['code'] ), get_status_header_desc( $response['response']['code'] ) ) );
				}

				$filesize = filesize( $fn );

				if ( isset( $response['headers']['content-length'] ) && $filesize != $response['headers']['content-length'] ) {
					ideapark_delete_file( $fn );

					return new WP_Error( 'import_file_error', __( 'Remote file is incorrect size', 'luchiana' ) );
				}

				if ( 0 == $filesize ) {
					ideapark_delete_file( $fn );

					return new WP_Error( 'import_file_error', __( 'Zero size file downloaded', 'luchiana' ) );
				}

				return $fn;
			} elseif ( ! empty( $result['error'] ) ) {
				if ( preg_match( '~Invalid purchase code~i', $result['error'] ) ) {
					ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
				}

				return new WP_Error( 'ideapark_api_error', $result['error'] );
			}
		}
	}
}

if ( ! function_exists( 'ideapark_set_purchase_code' ) ) {
	function ideapark_set_purchase_code( $code ) {
		update_site_option( 'ideapark_' . IDEAPARK_SLUG . '_purchase_code', $code );
	}
}

if ( ! function_exists( 'ideapark_check_code' ) ) {
	function ideapark_check_code( $code ) {
		return preg_match( '~[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$~', $code );
	}
}

if ( ! function_exists( 'ideapark_get_purchase_code' ) ) {
	function ideapark_get_purchase_code() {
		$code = get_site_option( 'ideapark_' . IDEAPARK_SLUG . '_purchase_code' );
		if ( $code && ! ( ideapark_check_code( $code ) || $code == IDEAPARK_SKIP_REGISTER ) ) {
			update_option( '_is_wrong_code', $code );
			$code = null;
			ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
		}

		return $code;
	}
}

if ( ! function_exists( 'ideapark_theme_register' ) ) {
	function ideapark_theme_register() {

		if ( ! check_ajax_referer( 'theme_about_nonce', 'security', false ) ) {
			wp_send_json_error( esc_html__( 'Invalid security nonce! Reload page and try again.', 'luchiana' ) );
		}

		if ( isset( $_POST['code'] ) && ( $code = trim( $_POST['code'] ) ) ) {
			if ( ( $result = ideapark_api_theme_register( $code ) ) && ! is_wp_error( $result ) ) {
				ideapark_set_purchase_code( $code );
				wp_send_json_success();
			} else {
				$error_message = is_wp_error( $result ) ? $result->get_error_message() : esc_html__( 'Something went wrong. Please try again!', 'luchiana' );
				if ( preg_match( '~cURL error 28~', $error_message ) ) {
					$error_message = esc_html__( 'There is no access to the remote host! Please contact your hosting provider to unblock access from your server to the host parkofideas.com (IP: 185.185.68.160)', 'luchiana' );
				}
				wp_send_json_error( $error_message );
			}
		} else {
			wp_send_json_error( esc_html__( 'Please enter purchase code', 'luchiana' ) );
		}
	}

	add_action( 'wp_ajax_ideapark_theme_register', 'ideapark_theme_register' );
}

if ( ! function_exists( 'ideapark_theme_deregister' ) ) {
	function ideapark_theme_deregister() {

		if ( ! check_ajax_referer( 'theme_about_nonce', 'security', false ) ) {
			wp_send_json_error( esc_html__( 'Invalid security nonce! Reload page and try again.', 'luchiana' ) );
		}

		if ( ( $result = ideapark_api_theme_deregister() ) && ! is_wp_error( $result ) ) {
			ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
			wp_send_json_success();
		} else {
			if ( is_wp_error( $result ) && preg_match( '~Invalid purchase code~i', $result->get_error_message() ) ) {
				ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
				wp_send_json_success();
			}
			wp_send_json_error( is_wp_error( $result ) ? $result->get_error_message() : esc_html__( 'Something went wrong. Please try again!', 'luchiana' ) );
		}

	}

	add_action( 'wp_ajax_ideapark_theme_deregister', 'ideapark_theme_deregister' );
}

if ( ! function_exists( 'ideapark_theme_check' ) ) {
	function ideapark_theme_check() {

		if ( ! check_ajax_referer( 'theme_about_nonce', 'security', false ) ) {
			wp_send_json_error( esc_html__( 'Invalid security nonce! Reload page and try again.', 'luchiana' ) );
		}

		if ( ( $theme_version = ideapark_api_get_theme_version() ) && ! is_wp_error( $theme_version ) ) {
			set_transient( 'ideapark_api_' . IDEAPARK_SLUG . '_version', $theme_version, DAY_IN_SECONDS );
			$is_up_to_date = version_compare( IDEAPARK_VERSION, $theme_version, '>=' );
			if ( ! $is_up_to_date ) {
				delete_site_transient( 'update_themes' );
				if ( ( $code = ideapark_get_purchase_code() ) && ( $code !== IDEAPARK_SKIP_REGISTER ) ) {
					$result = ideapark_api_theme_register( $code );
					if ( is_wp_error( $result ) ) {
						if ( preg_match( '~Invalid purchase code~i', $result->get_error_message() ) ) {
							ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
						}
					}
				}
			}
			wp_send_json_success( [ 'is_up_to_date' => $is_up_to_date ? esc_html__( 'The theme is up to date', 'luchiana' ) : false ] );
		} else {
			wp_send_json_error( is_wp_error( $theme_version ) ? $theme_version->get_error_message() : esc_html__( 'Something went wrong. Please try again!', 'luchiana' ) );
		}

	}

	add_action( 'wp_ajax_ideapark_theme_check', 'ideapark_theme_check' );
}

if ( ! function_exists( 'ideapark_get_theme_update_info' ) ) {
	function ideapark_get_theme_update_info() {

		$html = '';

		if ( ! current_user_can( 'update_themes' ) ) {
			return $html;
		}

		if ( ! ( ( $code = ideapark_get_purchase_code() ) && $code !== IDEAPARK_SKIP_REGISTER ) ) {
			if ( ! $theme_version = get_transient( $key = 'ideapark_api_' . IDEAPARK_SLUG . '_version' ) ) {
				$theme_version = ideapark_api_get_theme_version();

				if ( is_wp_error( $theme_version ) || ! $theme_version ) {
					return $html;
				}

				set_transient( $key, $theme_version, DAY_IN_SECONDS );
			}

			if ( version_compare( IDEAPARK_VERSION, $theme_version, '<' ) ) {
				$html = sprintf(
					'<span class="ideapark_about_new">New Version Available: <span class="ideapark_about_new_version_no">v.%s</span> &nbsp;&nbsp;Register your copy of %s Theme to enable theme updates</span>',
					esc_attr( $theme_version ),
					IDEAPARK_NAME
				);
			}

			return $html;
		}

		$themes_update = get_site_transient( 'update_themes' );

		$theme_instance = wp_get_theme( IDEAPARK_SLUG );
		$stylesheet     = $theme_instance->get_stylesheet();

		if ( isset( $themes_update->response[ $stylesheet ] ) ) {

			$update = $themes_update->response[ $stylesheet ];

			if ( version_compare( IDEAPARK_VERSION, $update['new_version'], '<' ) ) {

				if ( ! is_multisite() ) {
					$update_url = wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $stylesheet ) ), 'upgrade-theme_' . $stylesheet );
				} else {
					$update_url = network_admin_url( 'update-core.php' );
				}

				$html = sprintf(
					'<span class="ideapark_about_new">New Version Available: <span class="ideapark_about_new_version_no">v.%s</span> &nbsp;&nbsp;<a href="%s">Update Now</a></span>',
					esc_attr( $update['new_version'] ),
					esc_url( $update_url )
				);
			}

		}

		return $html;
	}
}

if ( ! function_exists( 'ideapark_pre_set_transient_update_theme' ) ) {
	function ideapark_pre_set_transient_update_theme( $transient ) {
		if ( empty( $transient->checked[ IDEAPARK_SLUG ] ) ) {
			return $transient;
		}

		if ( ! ( ( $code = ideapark_get_purchase_code() ) && $code !== IDEAPARK_SKIP_REGISTER ) ) {
			if ( isset( $transient->response[ IDEAPARK_SLUG ] ) ) {
				unset( $transient->response[ IDEAPARK_SLUG ] );
			}
			ideapark_heartbeat();

			return $transient;
		}

		$theme_version = ideapark_api_get_theme_version();

		if ( is_wp_error( $theme_version ) || ! $theme_version ) {
			ideapark_heartbeat();

			return $transient;
		}

		if ( version_compare( IDEAPARK_VERSION, $theme_version, '<' ) ) {
			$result = ideapark_api_post( [ 'file' => 'theme/' . IDEAPARK_SLUG . '.zip', 'code' => $code ] );
			if ( is_wp_error( $result ) ) {
				if ( preg_match( '~Invalid purchase code~i', $result->get_error_message() ) ) {
					ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
				}

				return $transient;
			} elseif ( isset( $result['success'] ) && ! $result['success'] && ! empty( $result['error'] ) ) {
				ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
			} elseif ( ! empty( $result['success'] ) && ! empty( $result['url'] ) ) {

				$api = ideapark_api_url();
				if ( $api != IDEAPARK_API_URL ) {
					$api_host_from = parse_url( IDEAPARK_API_URL, PHP_URL_HOST );
					$api_host_to   = parse_url( $api, PHP_URL_HOST );
					$result['url'] = str_replace( $api_host_from, $api_host_to, $result['url'] );
				}

				$transient->response[ IDEAPARK_SLUG ] = [
					'new_version' => esc_html( $theme_version ),
					'url'         => esc_url( wp_get_theme( IDEAPARK_SLUG )->get( 'ThemeURI' ) ),
					'package'     => $result['url']
				];
			}
		}

		return $transient;
	}

	add_filter( 'pre_set_site_transient_update_themes', 'ideapark_pre_set_transient_update_theme' );
}

if ( ! function_exists( 'ideapark_pre_upgraded_filter' ) ) {
	function ideapark_pre_upgraded_filter( $reply, $package, $upgrader ) {

		if ( ! ( ( $code = ideapark_get_purchase_code() ) && $code !== IDEAPARK_SKIP_REGISTER ) ) {
			return $reply;
		}

		if ( ! ( $plugins = ideapark_get_required_plugins() ) ) {
			return $reply;
		}

		$plugin_slug = '';

		foreach ( $plugins as $plugin ) {
			if ( isset( $plugin['source'] ) && $plugin['source'] == $package && ! empty( $plugin['type'] ) && $plugin['type'] == 'api' && ! empty( $plugin['slug'] ) ) {
				$plugin_slug = $plugin['slug'];
				break;
			}
		}

		if ( empty( $plugin_slug ) ) {
			return $reply;
		}

		$upgrader->strings['downloading_package_url'] = esc_html__( 'Getting download link...', 'luchiana' );
		$upgrader->skin->feedback( 'downloading_package_url' );

		$download_link = false;

		$result = ideapark_api_post( [ 'file' => 'plugin/' . $plugin_slug . '.zip', 'code' => $code ] );
		if ( is_wp_error( $result ) ) {
			if ( preg_match( '~Invalid purchase code~i', $result->get_error_message() ) ) {
				ideapark_set_purchase_code( IDEAPARK_SKIP_REGISTER );
			}

			return new WP_Error( 'no_credentials', esc_html__( 'Download link could not be retrieved', 'luchiana' ) . ' (' . $result->get_error_message() . ')' );

		} elseif ( ! empty( $result['success'] ) && ! empty( $result['url'] ) ) {

			$api = ideapark_api_url();
			if ( $api != IDEAPARK_API_URL ) {
				$api_host_from = parse_url( IDEAPARK_API_URL, PHP_URL_HOST );
				$api_host_to   = parse_url( $api, PHP_URL_HOST );
				$result['url'] = str_replace( $api_host_from, $api_host_to, $result['url'] );
			}

			$download_link = $result['url'];
		}

		if ( ! $download_link ) {
			return new WP_Error( 'no_credentials', esc_html__( 'Download link could not be retrieved', 'luchiana' ) );
		}

		$upgrader->strings['downloading_package'] = esc_html__( 'Downloading package...', 'luchiana' );
		$upgrader->skin->feedback( 'downloading_package' );

		$download_file = download_url( $download_link );

		if ( is_wp_error( $download_file ) && ! $download_file->get_error_data( 'softfail-filename' ) ) {
			return new WP_Error( 'download_failed', $upgrader->strings['download_failed'], $download_file->get_error_message() );
		}

		return $download_file;
	}

	add_filter( 'upgrader_pre_download', 'ideapark_pre_upgraded_filter', 10, 4 );
}

if ( ! function_exists( 'ideapark_deactivate_envato_market' ) ) {
	function ideapark_deactivate_envato_market( $old_version = '', $new_version = '' ) {
		if ( ! get_option( '_envato_market_deactivated' ) ) {
			update_option( '_envato_market_deactivated', 1 );
			try {
				if ( ! function_exists( 'deactivate_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				$plugin_name = 'envato-market/envato-market.php';

				if ( defined( 'ENVATO_MARKET_VERSION' ) ) {
					deactivate_plugins( $plugin_name );
					delete_plugins( [ $plugin_name ] );
				}
			} catch ( Exception $e ) {
			}
		}
	}

	add_action( 'after_update_theme_late', 'ideapark_deactivate_envato_market', 10, 2 );
}
