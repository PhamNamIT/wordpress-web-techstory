<?php
namespace AIOSEO\Plugin\Common\Models;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Post DB Model.
 *
 * @since 4.0.0
 */
class Post extends Model {
	/**
	 * The name of the table in the database, without the prefix.
	 *
	 * @since 4.0.0
	 *
	 * @var string
	 */
	protected $table = 'aioseo_posts';

	/**
	 * Fields that should be json encoded on save and decoded on get.
	 *
	 * @since 4.0.0
	 *
	 * @var array
	 */
	protected $jsonFields = [ 'images', 'videos' ]; // TODO: Update this.

	/**
	 * Fields that should be hidden when serialized.
	 *
	 * @since 4.0.0
	 *
	 * @var array
	 */
	protected $hidden = [ 'id' ];

	/**
	 * Fields that should be boolean values.
	 *
	 * @since 4.0.13
	 *
	 * @var array
	 */
	protected $booleanFields = [
		'twitter_use_og',
		'pillar_content',
		'robots_default',
		'robots_noindex',
		'robots_noarchive',
		'robots_nosnippet',
		'robots_nofollow',
		'robots_noimageindex',
		'robots_noodp',
		'robots_notranslate',
	];

	/**
	 * Returns a Post with a given ID.
	 *
	 * @since 4.0.0
	 *
	 * @param  int  $postId The post ID.
	 * @return Post         The Post object.
	 */
	public static function getPost( $postId ) {
		$post = aioseo()->db
			->start( 'aioseo_posts' )
			->where( 'post_id', $postId )
			->run()
			->model( 'AIOSEO\\Plugin\\Common\\Models\\Post' );

		if ( ! $post->exists() ) {
			$post->post_id = $postId;
			$post          = self::setDynamicDefaults( $post, $postId );
		}

		return $post;
	}

	/**
	 * Sets the dynamic defaults on the post object if it doesn't exist in the DB yet.
	 *
	 * @since 4.1.4
	 *
	 * @param  Post $post   The Post object.
	 * @param  int  $postId The post ID.
	 * @return Post         The modified Post object.
	 */
	private static function setDynamicDefaults( $post, $postId ) {
		if (
			'page' === get_post_type( $postId ) && // This check cannot be deleted and is required to prevent errors after WordPress cleans up the attachment it creates when a plugin is updated.
			(
				aioseo()->helpers->isWooCommerceCheckoutPage( $postId ) ||
				aioseo()->helpers->isWooCommerceCartPage( $postId ) ||
				aioseo()->helpers->isWooCommerceAccountPage( $postId )
			)
		) {
			$post->robots_default = false;
			$post->robots_noindex = true;
		}

		if ( aioseo()->helpers->isStaticHomePage( $postId ) ) {
			$post->og_object_type = 'website';
		}

		return $post;
	}

	/**
	 * Saves the Post object.
	 *
	 * @since 4.0.3
	 *
	 * @param  int              $postId The Post ID.
	 * @param  array            $data   The post data to save.
	 * @return bool|void|string         Whether the post data was saved or a DB error message.
	 */
	public static function savePost( $postId, $data ) {
		if ( empty( $data ) ) {
			return false;
		}

		$thePost = self::getPost( $postId );
		// Before setting the data, we check if the title/description are the same as the defaults and clear them if so.
		$data    = self::checkForDefaultFormat( $postId, $thePost, $data );
		$thePost = self::sanitizeAndSetDefaults( $postId, $thePost, $data );

		// Update traditional post meta so that it can be used by multilingual plugins.
		self::updatePostMeta( $postId, $data );

		$thePost->save();
		$thePost->reset();

		$lastError = aioseo()->db->lastError();
		if ( ! empty( $lastError ) ) {
			return $lastError;
		}
	}

	/**
	 * Checks if the title/description is the same as their default format in Search Appearance and nulls it if this is the case.
	 * Doing this ensures that updates to the default title/description format also propogate to the post.
	 *
	 * @since 4.1.5
	 *
	 * @param  int   $postId  The post ID.
	 * @param  Post  $thePost The Post object.
	 * @param  array $data    The data.
	 * @return array          The data.
	 */
	private static function checkForDefaultFormat( $postId, $thePost, $data ) {
		if ( $thePost->exists() ) {
			$post            = aioseo()->helpers->getPost( $postId );
			$metaTitle       = aioseo()->meta->title->getPostTypeTitle( $post->post_type );
			$metaDescription = aioseo()->meta->description->getPostTypeDescription( $post->post_type );
			if ( empty( $thePost->title ) && ! empty( $data['title'] ) && trim( $data['title'] ) === trim( $metaTitle ) ) {
				$data['title'] = null;
			}

			if ( empty( $thePost->description ) && ! empty( $data['description'] ) && trim( $data['description'] ) === trim( $metaDescription ) ) {
				$data['description'] = null;
			}
		}

		return $data;
	}

	/**
	 * Sanitizes the post data and sets it (or the default value) to the Post object.
	 *
	 * @since 4.1.5
	 *
	 * @param  int   $postId  The post ID.
	 * @param  Post  $thePost The Post object.
	 * @param  array $data    The data.
	 * @return Post           The Post object with data set.
	 */
	private static function sanitizeAndSetDefaults( $postId, $thePost, $data ) {
		// General
		$thePost->post_id                     = $postId;
		$thePost->title                       = ! empty( $data['title'] ) ? sanitize_text_field( $data['title'] ) : null;
		$thePost->description                 = ! empty( $data['description'] ) ? sanitize_text_field( $data['description'] ) : null;
		$thePost->canonical_url               = ! empty( $data['canonicalUrl'] ) ? esc_url_raw( $data['canonicalUrl'] ) : null;
		$thePost->keywords                    = ! empty( $data['keywords'] ) ? sanitize_text_field( $data['keywords'] ) : null;
		$thePost->pillar_content              = isset( $data['pillar_content'] ) ? rest_sanitize_boolean( $data['pillar_content'] ) : 0;
		// TruSEO
		$thePost->keyphrases                  = ! empty( $data['keyphrases'] ) ? wp_json_encode( $data['keyphrases'] ) : null;
		$thePost->page_analysis               = ! empty( $data['page_analysis'] ) ? wp_json_encode( $data['page_analysis'] ) : null;
		$thePost->seo_score                   = ! empty( $data['seo_score'] ) ? sanitize_text_field( $data['seo_score'] ) : 0;
		// Sitemap
		$thePost->priority                    = ! empty( $data['priority'] ) ? sanitize_text_field( $data['priority'] ) : null;
		$thePost->frequency                   = ! empty( $data['frequency'] ) ? sanitize_text_field( $data['frequency'] ) : null;
		// Robots Meta
		$thePost->robots_default              = isset( $data['default'] ) ? rest_sanitize_boolean( $data['default'] ) : 1;
		$thePost->robots_noindex              = isset( $data['noindex'] ) ? rest_sanitize_boolean( $data['noindex'] ) : 0;
		$thePost->robots_nofollow             = isset( $data['nofollow'] ) ? rest_sanitize_boolean( $data['nofollow'] ) : 0;
		$thePost->robots_noarchive            = isset( $data['noarchive'] ) ? rest_sanitize_boolean( $data['noarchive'] ) : 0;
		$thePost->robots_notranslate          = isset( $data['notranslate'] ) ? rest_sanitize_boolean( $data['notranslate'] ) : 0;
		$thePost->robots_noimageindex         = isset( $data['noimageindex'] ) ? rest_sanitize_boolean( $data['noimageindex'] ) : 0;
		$thePost->robots_nosnippet            = isset( $data['nosnippet'] ) ? rest_sanitize_boolean( $data['nosnippet'] ) : 0;
		$thePost->robots_noodp                = isset( $data['noodp'] ) ? rest_sanitize_boolean( $data['noodp'] ) : 0;
		$thePost->robots_max_snippet          = ! empty( $data['maxSnippet'] ) ? (int) sanitize_text_field( $data['maxSnippet'] ) : -1;
		$thePost->robots_max_videopreview     = ! empty( $data['maxVideoPreview'] ) ? (int) sanitize_text_field( $data['maxVideoPreview'] ) : -1;
		$thePost->robots_max_imagepreview     = ! empty( $data['maxImagePreview'] ) ? sanitize_text_field( $data['maxImagePreview'] ) : 'large';
		// Open Graph Meta
		$thePost->og_title                    = ! empty( $data['og_title'] ) ? sanitize_text_field( $data['og_title'] ) : null;
		$thePost->og_description              = ! empty( $data['og_description'] ) ? sanitize_text_field( $data['og_description'] ) : null;
		$thePost->og_object_type              = ! empty( $data['og_object_type'] ) ? sanitize_text_field( $data['og_object_type'] ) : 'default';
		$thePost->og_image_custom_url         = ! empty( $data['og_image_custom_url'] ) ? esc_url_raw( $data['og_image_custom_url'] ) : null;
		$thePost->og_image_custom_fields      = ! empty( $data['og_image_custom_fields'] ) ? sanitize_text_field( $data['og_image_custom_fields'] ) : null;
		$thePost->og_image_type               = ! empty( $data['og_image_type'] ) ? sanitize_text_field( $data['og_image_type'] ) : 'default';
		$thePost->og_video                    = ! empty( $data['og_video'] ) ? sanitize_text_field( $data['og_video'] ) : '';
		$thePost->og_article_section          = ! empty( $data['og_article_section'] ) ? sanitize_text_field( $data['og_article_section'] ) : null;
		$thePost->og_article_tags             = ! empty( $data['og_article_tags'] ) ? sanitize_text_field( $data['og_article_tags'] ) : null;
		// Twitter Meta
		$thePost->twitter_title               = ! empty( $data['twitter_title'] ) ? sanitize_text_field( $data['twitter_title'] ) : null;
		$thePost->twitter_description         = ! empty( $data['twitter_description'] ) ? sanitize_text_field( $data['twitter_description'] ) : null;
		$thePost->twitter_use_og              = isset( $data['twitter_use_og'] ) ? rest_sanitize_boolean( $data['twitter_use_og'] ) : 0;
		$thePost->twitter_card                = ! empty( $data['twitter_card'] ) ? sanitize_text_field( $data['twitter_card'] ) : 'default';
		$thePost->twitter_image_custom_url    = ! empty( $data['twitter_image_custom_url'] ) ? esc_url_raw( $data['twitter_image_custom_url'] ) : null;
		$thePost->twitter_image_custom_fields = ! empty( $data['twitter_image_custom_fields'] ) ? sanitize_text_field( $data['twitter_image_custom_fields'] ) : null;
		$thePost->twitter_image_type          = ! empty( $data['twitter_image_type'] ) ? sanitize_text_field( $data['twitter_image_type'] ) : 'default';
		// Schema
		$thePost->schema_type                 = ! empty( $data['schema_type'] ) ? sanitize_text_field( $data['schema_type'] ) : 'default';
		$thePost->schema_type_options         = ! empty( $data['schema_type_options'] )
			? parent::getDefaultSchemaOptions( wp_json_encode( $data['schema_type_options'] ) )
			: parent::getDefaultSchemaOptions();
		// Miscellaneous
		$thePost->tabs                        = ! empty( $data['tabs'] ) ? wp_json_encode( $data['tabs'] ) : parent::getDefaultTabsOptions();
		$thePost->local_seo                   = ! empty( $data['local_seo'] ) ? wp_json_encode( $data['local_seo'] ) : null;
		$thePost->updated                     = gmdate( 'Y-m-d H:i:s' );

		if ( ! $thePost->exists() ) {
			$thePost->created = gmdate( 'Y-m-d H:i:s' );
		}

		return $thePost;
	}

	/**
	 * Saves some of the data as post meta so that it can be used for localization.
	 *
	 * @since 4.1.5
	 *
	 * @param  int   $postId The post ID.
	 * @param  array $data   The data.
	 * @return void
	 */
	private static function updatePostMeta( $postId, $data ) {
		// Update the post meta as well for localization.
		$keywords      = ! empty( $data['keywords'] ) ? aioseo()->helpers->jsonTagsToCommaSeparatedList( $data['keywords'] ) : [];
		$ogArticleTags = ! empty( $data['og_article_tags'] ) ? aioseo()->helpers->jsonTagsToCommaSeparatedList( $data['og_article_tags'] ) : [];

		update_post_meta( $postId, '_aioseo_title', $data['title'] );
		update_post_meta( $postId, '_aioseo_description', $data['description'] );
		update_post_meta( $postId, '_aioseo_keywords', $keywords );
		update_post_meta( $postId, '_aioseo_og_title', $data['og_title'] );
		update_post_meta( $postId, '_aioseo_og_description', $data['og_description'] );
		update_post_meta( $postId, '_aioseo_og_article_section', $data['og_article_section'] );
		update_post_meta( $postId, '_aioseo_og_article_tags', $ogArticleTags );
		update_post_meta( $postId, '_aioseo_twitter_title', $data['twitter_title'] );
		update_post_meta( $postId, '_aioseo_twitter_description', $data['twitter_description'] );
	}

	/**
	 * Returns the default values for the TruSEO page analysis.
	 *
	 * @since 4.0.0
	 *
	 * @return object The default values.
	 */
	public static function getPageAnalysisDefaults() {
		$defaults = [
			'analysis' => [
				'basic'       => [
					'lengthContent' => [
						'error'       => 1,
						'maxScore'    => 9,
						'score'       => 6,
						'title'       => __( 'Content', 'all-in-one-seo-pack' ),
						'description' => __( 'Please add some content first.', 'all-in-one-seo-pack' )
					],
				],
				'title'       => [
					'titleLength' => [
						'error'       => 1,
						'maxScore'    => 9,
						'score'       => 1,
						'title'       => __( 'Title', 'all-in-one-seo-pack' ),
						'description' => __( 'Please add a title first.', 'all-in-one-seo-pack' )
					],
				],
				'readability' => [
					'contentHasAssets' => [
						'error'       => 1,
						'maxScore'    => 5,
						'score'       => 0,
						'title'       => __( 'Images/Videos in content', 'all-in-one-seo-pack' ),
						'description' => __( 'Please add some content first.', 'all-in-one-seo-pack' )
					],
				]
			]
		];

		return json_decode( wp_json_encode( $defaults ) );
	}
}