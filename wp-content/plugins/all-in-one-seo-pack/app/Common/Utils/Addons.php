<?php
namespace AIOSEO\Plugin\Common\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use AIOSEO\Plugin\Common\Utils;

/**
 * Contains helper methods specific to the addons.
 *
 * @since 4.0.0
 */
class Addons {
	/**
	 * Holds our list of loaded addons.
	 *
	 * @since 4.1.0
	 *
	 * @var array
	 */
	protected $loadedAddons = [];

	/**
	 * The licensing URL.
	 *
	 * @since 4.0.13
	 *
	 * @var string
	 */
	protected $licensingUrl = 'https://licensing-cdn.aioseo.com/keys/lite/all-in-one-seo-pack-pro.json';

	/**
	 * Returns our addons.
	 *
	 * @since 4.0.0
	 *
	 * @param  boolean $flushCache Whether or not to flush the cache.
	 * @return array               An array of addon data.
	 */
	public function getAddons( $flushCache = false ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$addons = aioseo()->cache->get( 'addons' );
		if ( null === $addons || $flushCache ) {
			$response = wp_remote_get( $this->getLicensingUrl() );
			if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
				$addons = json_decode( wp_remote_retrieve_body( $response ) );
			}
		}

		if ( ! $addons || ! empty( $addons->error ) ) {
			$addons = $this->getDefaultAddons();
		}

		aioseo()->cache->update( 'addons', $addons );

		// The API request will tell us if we can activate a plugin, but let's check if its already active.
		$installedPlugins = array_keys( get_plugins() );
		foreach ( $addons as $key => $addon ) {
			$addons[ $key ]->basename    = $this->getAddonBasename( $addon->sku );
			$addons[ $key ]->installed   = in_array( $this->getAddonBasename( $addon->sku ), $installedPlugins, true );
			$addons[ $key ]->isActive    = is_plugin_active( $addons[ $key ]->basename );
			$addons[ $key ]->canInstall  = $this->canInstall();
			$addons[ $key ]->canActivate = $this->canActivate();
			$addons[ $key ]->capability  = $this->getManageCapability( $addon->sku );
		}

		return $addons;
	}

	/**
	 * Returns the required capability to manage the addon.
	 *
	 * @since 4.1.3
	 *
	 * @param  string $sku The addon sku.
	 * @return string      The required capability.
	 */
	protected function getManageCapability( $sku ) {
		$capability = apply_filters( 'aioseo_manage_seo', 'aioseo_manage_seo' );

		switch ( $sku ) {
			case 'aioseo-image-seo':
				$capability = 'aioseo_search_appearance_settings';
				break;
			case 'aioseo-video-sitemap':
			case 'aioseo-news-sitemap':
				$capability = 'aioseo_sitemap_settings';
				break;
			case 'aioseo-redirects':
				$capability = 'aioseo_redirects_settings';
				break;
			case 'aioseo-local-business':
				$capability = 'aioseo_local_seo_settings';
				break;
		}
		return $capability;
	}

	/**
	 * Check to see if there are unlicensed addons installed and activated.
	 *
	 * @since 4.1.3
	 *
	 * @return boolean True if there are unlicensed addons, false if not.
	 */
	public function unlicensedAddons() {
		$unlicensed = [
			'addons'  => [],
			// Translators: 1 - Opening bold tag, 2 - Plugin short name ("AIOSEO"), 3 - "Pro", 4 - Closing bold tag.
			'message' => sprintf(
				__( 'The following addons cannot be used, because they require %1$s%2$s %3$s%4$s to work:', 'all-in-one-seo-pack' ),
				'<strong>',
				AIOSEO_PLUGIN_SHORT_NAME,
				'Pro',
				'</strong>'
			)
		];

		$addons = $this->getAddons();
		foreach ( $addons as $addon ) {
			if ( $addon->isActive ) {
				$unlicensed['addons'][] = $addon;
			}
		}

		return $unlicensed;
	}

	/**
	 * Get the data for a specific addon.
	 *
	 * We need this function to refresh the data of a given addon because installation links expire after one hour.
	 *
	 * @since 4.0.0
	 *
	 * @param  string  $sku        The addon sku.
	 * @param  boolean $flushCache Whether or not to flush the cache.
	 * @return object              The addon.
	 */
	public function getAddon( $sku, $flushCache = false ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$addon = aioseo()->cache->get( 'addon_' . $sku );
		if ( null === $addon || $flushCache ) {
			$addon = aioseo()->helpers->sendRequest( $this->getLicensingUrl() . 'addons/', $this->getAddonPayload( $sku ) );
			aioseo()->cache->update( 'addon_' . $sku, $addon, DAY_IN_SECONDS );
		}

		if ( ! $addon || ! empty( $addon->error ) ) {
			$addon = $this->getDefaultAddon( $sku );
			aioseo()->cache->update( 'addon_' . $sku, $addon, 10 * MINUTE_IN_SECONDS );
		}

		// The API request will tell us if we can activate a plugin, but let's check if its already active.
		$installedPlugins  = array_keys( get_plugins() );
		$addon->basename   = $this->getAddonBasename( $addon->sku );
		$addon->installed  = in_array( $this->getAddonBasename( $addon->sku ), $installedPlugins, true );
		$addon->isActive   = is_plugin_active( $addon->basename );
		$addon->canInstall = $this->canInstall();

		return $addon;
	}

	/**
	 * Gets the payload to send in the request.
	 *
	 * @since 4.0.0
	 *
	 * @param  string $sku The sku to use in the request.
	 * @return array       A payload array.
	 */
	protected function getAddonPayload( $sku = 'all-in-one-seo-pack-pro' ) {
		$payload = [
			'license'     => aioseo()->options->has( 'general' ) && aioseo()->options->general->has( 'licenseKey' )
				? aioseo()->options->general->licenseKey
				: '',
			'domain'      => aioseo()->helpers->getSiteDomain(),
			'sku'         => $sku,
			'version'     => AIOSEO_VERSION,
			'php_version' => PHP_VERSION,
			'wp_version'  => get_bloginfo( 'version' )
		];

		if ( defined( 'AIOSEO_INTERNAL_ADDONS' ) && AIOSEO_INTERNAL_ADDONS ) {
			$payload['internal'] = true;
		}

		return $payload;
	}

	/**
	 * Checks if the specified addon is activated.
	 *
	 * @since 4.0.0
	 *
	 * @param  string $sku The sku to check.
	 * @return string      The addon basename.
	 */
	public function getAddonBasename( $sku ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		$plugins = get_plugins();

		$keys = array_keys( $plugins );
		foreach ( $keys as $key ) {
			if ( preg_match( '|^' . $sku . '|', $key ) ) {
				return $key;
			}
		}

		return $sku;
	}

	/**
	 * Returns an array of levels connected to an addon.
	 *
	 * @since 4.0.0
	 *
	 * @param  string $addonName The addon name.
	 * @return array             The array of levels.
	 */
	public function getAddonLevels( $addonName ) {
		$addons = $this->getAddons();
		foreach ( $addons as $addon ) {
			if ( $addonName !== $addon->sku ) {
				continue;
			}

			if ( ! isset( $addon->levels ) ) {
				return [];
			}

			return $addon->levels;
		}
		return [];
	}

	/**
	 * Get the URL to check licenses.
	 *
	 * @since 4.0.0
	 *
	 * @return string The URL.
	 */
	protected function getLicensingUrl() {
		if ( defined( 'AIOSEO_LICENSING_URL' ) ) {
			return AIOSEO_LICENSING_URL;
		}
		return $this->licensingUrl;
	}

	/**
	 * Installs and activates a given addon or plugin.
	 *
	 * @since 4.0.0
	 *
	 * @return boolean Whether or not the installation was succesful.
	 */
	public function installAddon( $name ) {
		if ( ! $this->canInstall() ) {
			return false;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/template.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
		require_once ABSPATH . 'wp-admin/includes/screen.php';

		// Set the current screen to avoid undefined notices.
		set_current_screen( 'toplevel_page_aioseo' );

		// Prepare variables.
		$url = esc_url_raw(
			add_query_arg(
				[
					'page' => 'aioseo-settings',
				],
				admin_url( 'admin.php' )
			)
		);

		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', [ 'Language_Pack_Upgrader', 'async_upgrade' ], 20 );

		// Create the plugin upgrader with our custom skin.
		$installer = new Utils\PluginUpgraderSilentAjax( new Utils\PluginUpgraderSkin() );

		// Activate the plugin silently.
		$pluginUrl = ! empty( $installer->pluginSlugs[ $name ] ) ? $installer->pluginSlugs[ $name ] : $name;
		$activated = activate_plugin( $pluginUrl );

		if ( ! is_wp_error( $activated ) ) {
			return $name;
		}

		// Using output buffering to prevent the FTP form from being displayed in the screen.
		ob_start();
		$creds = request_filesystem_credentials( $url, '', false, false, null );
		ob_end_clean();

		// Check for file system permissions.
		if ( false === $creds || ! aioseo()->helpers->wpfs( $creds ) ) {
			return false;
		}

		// Error check.
		if ( ! method_exists( $installer, 'install' ) ) {
			return false;
		}

		$installLink = ! empty( $installer->pluginLinks[ $name ] ) ? $installer->pluginLinks[ $name ] : null;

		// Check if this is an addon and if we have a download link.
		if ( empty( $installLink ) ) {
			$addon = aioseo()->addons->getAddon( $name, true );
			if ( empty( $addon->downloadUrl ) ) {
				return false;
			}

			$installLink = $addon->downloadUrl;
		}

		$installer->install( $installLink );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$pluginBasename = $installer->plugin_info();
		if ( ! $pluginBasename ) {
			return false;
		}

		// Activate the plugin silently.
		$activated = activate_plugin( $pluginBasename );

		if ( is_wp_error( $activated ) ) {
			return false;
		}

		return $pluginBasename;
	}

	/**
	 * Determine if addons/plugins can be installed.
	 *
	 * @since 4.0.0
	 *
	 * @return bool True if yes, false if not.
	 */
	public function canInstall() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		// Determine whether file modifications are allowed.
		if ( ! wp_is_file_mod_allowed( 'aioseo_can_install' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if addons/plugins can be activated.
	 *
	 * @since 4.1.3
	 *
	 * @return bool True if yes, false if not.
	 */
	public function canActivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Load an addon into aioseo
	 *
	 * @since 4.1.0
	 *
	 * @param string $slug
	 * @param object $addon Addon class instance
	 *
	 * @return void
	 */
	public function loadAddon( $slug, $addon ) {
		$this->{$slug}        = $addon;
		$this->loadedAddons[] = $slug;
	}

	/**
	 * Return a loaded addon
	 *
	 * @since 4.1.0
	 *
	 * @param string $slug
	 *
	 * @return object|null
	 */
	public function getLoadedAddon( $slug ) {
		return isset( $this->{$slug} ) ? $this->{$slug} : null;
	}

	/**
	 * Returns loaded addons
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */
	public function getLoadedAddons() {
		$loadedAddonsList = [];
		if ( ! empty( $this->loadedAddons ) ) {
			foreach ( $this->loadedAddons as $addonSlug ) {
				$loadedAddonsList[ $addonSlug ] = $this->{$addonSlug};
			}
		}

		return $loadedAddonsList;
	}

	/**
	 * Retrieves a default addon with whatever information is needed if the API cannot be reached.
	 *
	 * @since 4.0.0
	 *
	 * @param  string $sku The sku of the addon.
	 * @return array       An array of addon data.
	 */
	public function getDefaultAddon( $sku ) {
		$addons = $this->getDefaultAddons();
		$addon  = [];
		foreach ( $addons as $a ) {
			if ( $a->sku === $sku ) {
				$addon = $a;
			}
		}

		return $addon;
	}

	/**
	 * Retrieves a default list of addons if the API cannot be reached.
	 *
	 * @since 4.0.0
	 *
	 * @return array An array of addons.
	 */
	protected function getDefaultAddons() {
		return json_decode( wp_json_encode( [
			[
				'sku'                => 'aioseo-image-seo',
				'name'               => 'Image SEO',
				'version'            => '1.0.0',
				'image'              => null,
				'icon'               => 'svg-image-seo',
				'levels'             => [
					'business',
					'agency',
					'plus',
					'pro',
					'elite',
				],
				'currentLevels'      => [
					'plus',
					'pro',
					'elite'
				],
				'requiresUpgrade'    => false,
				'description'        => '<p>Globally control the Title attribute and Alt text for images in your content. These attributes are essential for both accessibility and SEO.</p>',
				'descriptionVersion' => 0,
				'downloadUrl'        => '',
				'productUrl'         => 'https://aioseo.com/image-seo',
				'learnMoreUrl'       => 'https://aioseo.com/image-seo',
				'manageUrl'          => 'https://route#aioseo-search-appearance:media',
				'basename'           => 'aioseo-image-seo/aioseo-image-seo.php',
				'installed'          => false,
				'isActive'           => false,
				'canInstall'         => false
			],
			[
				'sku'                => 'aioseo-video-sitemap',
				'name'               => 'Video Sitemap',
				'version'            => '1.0.0',
				'image'              => null,
				'icon'               => 'svg-sitemaps-pro',
				'levels'             => [
					'individual',
					'business',
					'agency',
					'pro',
					'elite'
				],
				'currentLevels'      => [
					'pro',
					'elite'
				],
				'requiresUpgrade'    => false,
				'description'        => '<p>The Video Sitemap works in much the same way as the XML Sitemap module, it generates an XML Sitemap specifically for video content on your site. Search engines use this information to display rich snippet information in search results.</p>', // phpcs:ignore Generic.Files.LineLength.MaxExceeded
				'descriptionVersion' => 0,
				'downloadUrl'        => '',
				'productUrl'         => 'https://aioseo.com/video-sitemap',
				'learnMoreUrl'       => 'https://aioseo.com/video-sitemap',
				'manageUrl'          => 'https://route#aioseo-sitemaps:video-sitemap',
				'basename'           => 'aioseo-video-sitemap/aioseo-video-sitemap.php',
				'installed'          => false,
				'isActive'           => false,
				'canInstall'         => false
			],
			[
				'sku'                => 'aioseo-local-business',
				'name'               => 'Local Business SEO',
				'version'            => '1.0.0',
				'image'              => null,
				'icon'               => 'svg-local-business',
				'levels'             => [
					'business',
					'agency',
					'plus',
					'pro',
					'elite'
				],
				'currentLevels'      => [
					'plus',
					'pro',
					'elite'
				],
				'requiresUpgrade'    => false,
				'description'        => '<p>Local Business schema markup enables you to tell Google about your business, including your business name, address and phone number, opening hours and price range. This information may be displayed as a Knowledge Graph card or business carousel.</p>', // phpcs:ignore Generic.Files.LineLength.MaxExceeded
				'descriptionVersion' => 0,
				'downloadUrl'        => '',
				'productUrl'         => 'https://aioseo.com/local-business',
				'learnMoreUrl'       => 'https://aioseo.com/local-business',
				'manageUrl'          => 'https://route#aioseo-local-seo:locations',
				'basename'           => 'aioseo-local-business/aioseo-local-business.php',
				'installed'          => false,
				'isActive'           => false,
				'canInstall'         => false
			],
			[
				'sku'                => 'aioseo-news-sitemap',
				'name'               => 'News Sitemap',
				'version'            => '1.0.0',
				'image'              => null,
				'icon'               => 'svg-sitemaps-pro',
				'levels'             => [
					'business',
					'agency',
					'pro',
					'elite'
				],
				'currentLevels'      => [
					'pro',
					'elite'
				],
				'requiresUpgrade'    => false,
				'description'        => '<p>Our Google News Sitemap lets you control which content you submit to Google News and only contains articles that were published in the last 48 hours. In order to submit a News Sitemap to Google, you must have added your site to Google’s Publisher Center and had it approved.</p>', // phpcs:ignore Generic.Files.LineLength.MaxExceeded
				'descriptionVersion' => 0,
				'downloadUrl'        => '',
				'productUrl'         => 'https://aioseo.com/news-sitemap',
				'learnMoreUrl'       => 'https://aioseo.com/news-sitemap',
				'manageUrl'          => 'https://route#aioseo-sitemaps:news-sitemap',
				'basename'           => 'aioseo-news-sitemap/aioseo-news-sitemap.php',
				'installed'          => false,
				'isActive'           => false,
				'canInstall'         => false
			],
			[
				'sku'                => 'aioseo-redirects',
				'name'               => 'Redirection Manager',
				'version'            => '1.0.0',
				'image'              => null,
				'icon'               => 'svg-redirect',
				'levels'             => [
					'agency',
					'basic',
					'plus',
					'pro',
					'elite'
				],
				'currentLevels'      => [
					'basic',
					'plus',
					'pro',
					'elite'
				],
				'requiresUpgrade'    => false,
				'description'        => '<p>Our Redirection Manager allows you to easily create and manage redirects for your broken links to avoid confusing search engines and users, as well as losing valuable backlinks. It even automatically sends users and search engines from your old URLs to your new ones.</p>', // phpcs:ignore Generic.Files.LineLength.MaxExceeded
				'descriptionVersion' => 0,
				'downloadUrl'        => '',
				'productUrl'         => 'https://aioseo.com/redirection-manager',
				'learnMoreUrl'       => 'https://aioseo.com/redirection-manager',
				'manageUrl'          => 'https://route#aioseo-redirects',
				'basename'           => 'aioseo-redirects/aioseo-redirects.php',
				'installed'          => false,
				'isActive'           => false,
				'canInstall'         => false
			]
		] ) );
	}
}