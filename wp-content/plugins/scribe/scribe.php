<?php
/*
 Plugin Name: Scribe SEO
 Plugin URI: http://scribeseo.com
 Description: Quickly and easily check your content against SEO best practices utilizing the Scribe Content Optimizer.  You will need a <a href="https://my.scribeseo.com" title="My Scribe API key">Scribe API Key</a> in order to use the application. If you do not have an API Key, go to <a href="http://scribeseo.com" title="Get Scribe API Key">http://scribeseo.com</a>. <strong>Please</strong> make sure you are using a supported theme or plugin. For an updated list, go to <a href="http://scribeseo.com/compatibility/" title="Scribe Compatibility List">http://scribeseo.com/compatibility/</a>.
 Version: 3.0.11
 Author: Copyblogger Media
 Author URI: http://www.copyblogger.com
 */

define( 'ECORDIA_DEBUG', false );

require_once ('lib/ecordia-access/ecordia-content-optimizer.class.php');
require_once ('lib/ecordia-access/ecordia-user-account.class.php');
require_once ('lib/ecordia-access/ecordia-keyword-alternates.class.php');
require_once ('lib/ecordia-access/ecordia-keyword-research.class.php');
require_once ('lib/ecordia-access/ecordia-link-building-research.class.php');

if (!function_exists('json_encode') && file_exists(ABSPATH.'/wp-includes/js/tinymce/plugins/spellchecker/classes/utils/JSON.php')) {
	require_once (ABSPATH.'/wp-includes/js/tinymce/plugins/spellchecker/classes/utils/JSON.php');
	function json_encode($data) {
		$json == new Moxiecode_JSON();
		return $json->encode($data);
	}
}

if (!class_exists('Ecordia')) {
	class Ecordia {

		var $version = '3.0.11';
		var $_meta_seoInfo = '_ecordia_seo_info';
		var $_meta_linkResearchInfo = '_ecordia_link_research';
		var $_option_ecordiaSettings = '_ecordia_settings';
		var $_option_cachedUserResults = '_ecordia_cachedUserResults';
		var $_option_keywordResearchList = '_ecordia_keyword_research_history';

		var $settings = null;
		var $_utility_DependencyUrl = 'http://vesta.ecordia.com/optimizer/docs/scribe-dependencies.xml';
		var $_possible_Item = array();
		var $_possible_Items = array();
		var $_possible_CurrentType = array();
		var $_possible_CurrentData = array();

		function Ecordia() {
			$this->addActions();
			$this->addFilters();

			wp_register_style('ecordia', plugins_url('resources/ecordia.css', __FILE__), array(), $this->version);
			wp_register_script('ecordia', plugins_url('resources/ecordia.js', __FILE__), array('jquery'), $this->version);
		}

		function addActions() {
			add_action('admin_head', array(&$this, 'addAdminHeaderCode'));
			add_action('admin_init', array(&$this, 'settingsSave'));
			add_action('admin_menu', array(&$this, 'addAdminInterfaceItems'));
			add_action('manage_posts_custom_column', array(&$this, 'displayEcordiaPostsColumns'), 10, 2);
			add_action('manage_pages_custom_column', array(&$this, 'displayEcordiaPostsColumns'), 10, 2);
			add_action('save_post', array(&$this, 'saveSerializedValueToPreventOverriding'), 10, 2);
			add_action('update_scribe_user_info', array(&$this, 'getScheduledUserInfo'));

			$settings = $this->getSettings();
			if ( empty($settings['api-key'])) {
				add_action('admin_notices', array(&$this, 'displayAdminNoticeRegardingAPIKey'));
			}

			// Thickbox interfaces
			add_action('media_upload_ecordia-score', array(&$this, 'displayThickboxInterface'));
			add_action('media_upload_ecordia-keyword-analysis', array(&$this, 'displayThickboxInterface'));
			add_action('media_upload_ecordia-change-keywords', array(&$this, 'displayThickboxInterface'));
			add_action('media_upload_ecordia-keyword-alternates', array(&$this, 'displayThickboxInterface'));
			add_action('media_upload_ecordia-tags', array(&$this, 'displayThickboxInterface'));
			add_action('media_upload_ecordia-serp', array(&$this, 'displayThickboxInterface'));
			add_action('media_upload_ecordia-seo-best-practices', array(&$this, 'displayThickboxInterface'));
			add_action('media_upload_ecordia-error', array(&$this, 'displayThickboxInterface'));
			add_action('media_upload_ecordia-debug', array(&$this, 'displayThickboxInterface'));

			// Keyword Research Thickbox
			add_action('media_upload_ecordia-keyword-research', array(&$this, 'displayKeywordResearchThickboxInterface'));
			add_action('media_upload_ecordia-keyword-research-review', array(&$this, 'displayKeywordResearchThickboxInterface'));

			// Link Building Thickbox
			add_action('media_upload_ecordia-link-building-external', array(&$this, 'displayLinkBuildingResearchThickboxInterface'));
			add_action('media_upload_ecordia-link-building-internal', array(&$this, 'displayLinkBuildingResearchThickboxInterface'));
			add_action('media_upload_ecordia-link-building-social', array(&$this, 'displayLinkBuildingResearchThickboxInterface'));

			// AJAX stuff
			add_action('wp_ajax_ecordia_analyze', array(&$this, 'analyzeSeoContent'));
			add_action('wp_ajax_ecordia_user_info', array(&$this, 'fetchUserInfo'));
			add_action('wp_ajax_ecordia_keyword_alternates', array(&$this, 'getKeywordAlternates'));
			add_action('wp_ajax_scribe_keyword_research', array(&$this, 'getKeywordResearch'));
			add_action('wp_ajax_scribe_link_building_research', array(&$this, 'getLinkBuildingResearch'));
		}

		function addFilters() {
			add_filter('manage_posts_columns', array(&$this, 'addEcordiaPostsColumns'));
			add_filter('manage_pages_columns', array(&$this, 'addEcordiaPostsColumns'));
		}

		function addAdminHeaderCode() {
			global $pagenow;
			if (false !== strpos($pagenow, 'post') || false !== strpos($pagenow, 'page') || false !== strpos($pagenow, 'media-upload')) {
				include ('views/admin-header.php');
			}
		}

		function addAdminInterfaceItems() {
			add_options_page(__('Scribe Settings'), __('Scribe Settings'), 'manage_options', 'scribe', array(&$this, 'displaySettingsPage'));

			$permission = $this->getPermissionLevel();

			if(current_user_can($permission)) {
				$dependency = $this->getEcordiaDependency();
				$title = __('Scribe Content Optimizer');
				if ( empty($dependency)) {
					$displayFunction = array(&$this, 'displayMetaBoxError');
					$title .= __(' &mdash; <span class="ecordia-error">Error</span>');
				} else {
					$displayFunction = array(&$this, 'displayMetaBox');
				}

				foreach($this->getSupportedPostTypes() as $type) {
					add_meta_box('ecordia-keyword-research', __('Scribe Keyword Research'), array(&$this, 'displayKeywordResearchMetaBox'), $type, 'side', 'core');
					add_meta_box('ecordia', $title, $displayFunction, $type, 'side', 'core');
					add_meta_box('ecordia-link-building', __('Scribe Link Building'), array(&$this, 'displayLinkBuildingMetaBox'), $type, 'side', 'core');
				}
			}

			global $pagenow;
			if (false !== strpos($pagenow, 'post') || false !== strpos($pagenow, 'page') || false !== strpos($pagenow, 'scribe') || $_GET['page'] == 'scribe' || false !== strpos($pagenow, 'edit')) {
				wp_enqueue_style('ecordia');
				wp_enqueue_script('ecordia');
				add_filter('tiny_mce_before_init', array(&$this, 'addInitInstanceCallback'));
			}
		}

		function addEcordiaPostsColumns($columns) {
			$authorKey = array_search('author', array_keys($columns));
			//removed preserve_keys parameter for lt PHP5.0.2
			$before = array_slice($columns, 0, $authorKey + 1);
			$before['seo-score'] = __('Scribe Optimizer');
			$before['primary-seo-keywords'] = __('Primary Keywords');

			//removed preserve_keys parameter for lt PHP5.0.2
			$after = array_slice($columns, $authorKey + 1, count($columns) + 1);
			$columns = array_merge($before, $after);
			return $columns;
		}

		function addInitInstanceCallback($initArray) {
			$initArray['init_instance_callback'] = 'ecordia_addTinyMCEEvent';
			return $initArray;
		}

		function analyzeSeoContent() {
			$title = $this->sanitizeForCall(stripslashes($_POST['title']));
			$description = $this->sanitizeForCall(stripslashes($_POST['description']));
			$content = $this->sanitizeForCall(stripslashes($_POST['content']));
			$url = get_permalink($_POST['pid']);
			if(empty($url)) {
				$url = site_url('/');
			}
			$pid = intval($_POST['pid']);
			$settings = $this->getSettings();
			$results = array('success'=>false, 'message'=>__(''), 'extended'=>__(''));

			if ( empty($settings['api-key'])) {
				$results['message'] = __('You need to set your API key.');
				$results['extended'] = 'show-settings-prompt';
			} else {
				$optimizer = new EcordiaContentOptimizer($settings['api-key'], $settings['use-ssl']);
				$optimizer->GetAnalysis($title, $description, $content, $url);
				if ($optimizer->hasError()) {
					$results['message'] = __('Analysis Failure');
					$results['extended'] = $optimizer->getErrorMessage();
				} else {
					$this->getUserInfo(true);
					$results = $optimizer->getRawResults();
					$serialized = base64_encode(serialize($results));
					update_post_meta($pid, $this->_meta_seoInfo, $serialized);
					$results['success'] = true;
					ob_start();
					global $post;
					$post = get_post($pid);
					include ('views/meta-box/after.php');
					$results['meta'] = ob_get_clean();

					ob_start();
					include('views/link-building/meta-box-after.php');
					$results['linkMeta'] = ob_get_clean();
				}
			}


			print json_encode($results);
			exit();
		}
		function sanitizeForCall($value) {
			return str_replace(array('<![CDATA[',']]>'),array('',''),trim($value));
		}

		function getKeywordAlternatesInfo($seed) {
			$settings = $this->getSettings();
			$keywords = new EcordiaKeywordAlternates($settings['api-key'], $settings['use-ssl']);
			$keywords->GetAlternateKeywords($seed);
			return $keywords;
		}

		function getKeywordAlternates() {
			$seed = stripslashes($_POST['seed']);
			$keywords = $this->getKeywordAlternatesInfo($seed);
			if($keywords->hasError()) {
				?>
<p><?php _e('Error retrieving results.'); ?></p>
				<?php
			} else {
				$info = $keywords->getRawResults();
				$keywordAlternates = $info['GetKeywordAlternatesResult']['AlternateKeywords']['Keyword'];
				include('views/popup/alternate-keyword-table.php');
			}
			exit();
		}

		function getKeywordResearch() {
			$results = array('error'=>true);
			$data = stripslashes_deep($_POST);

			$phrase = $data['phrase'];
			$matchType = $data['match'];

			if(!empty($phrase) && in_array($matchType, array('broad','phrase','exact'))) {
				$keywords = $this->getKeywordIdeas($phrase, $matchType);
				$raw = $keywords->getRawResults();
				if(!$keywords->hasError()) {
					$this->getScheduledUserInfo();
					$results = $data;
					$results['number'] = $data['number'] - 1;
				}
			}

			echo json_encode($results);
			exit();
		}

		function getScheduledUserInfo() {
			$userInfo = $this->getUserInfo(true);
		}

		function getLinkBuildingResearch() {
			$data = stripslashes_deep($_POST);
			$id = absint($data['id']);
			$type = $data['type'];
			$terms = array_map('trim',explode(',', $data['terms']));
			$settings = $this->getSettings();
			$linkBuilding = new EcordiaLinkBuildingResearch($settings['api-key'], $settings['use-ssl']);

			if('social' == $type) {
				$linkBuilding->GetSocialMediaSearchResults($terms);
			} else {
//				$link = site_url('');
				$link = get_permalink($id);
				$linkBuilding->GetSearchEngineLinks($terms,$type,$link);
			}

			$research = $linkBuilding->getRawResults();
			$research['CurrentSelections'] = $terms;

			$this->setLinkBuildingResearchForPostAndType($id, $type, $research);

			include('views/link-building/research-results-list.php');

			exit();
		}

		function getKeywordIdeas($phrase, $matchType) {
			$settings = $this->getSettings();
			$keywords = new EcordiaKeywordResearch($settings['api-key'], $settings['use-ssl']);
			$keywords->GetKeywordIdeas($phrase, $matchType);
			if(!$keywords->hasError()) {
				$this->setKeywordResearchIdeas($phrase, $matchType, $keywords->getRawResults());
			}
			return $keywords;
		}

		function displayAdminNoticeRegardingAPIKey() {
			print '<div id="ecordia-empty-api-key" class="error"><p>'.sprintf(__('Your Scribe API Key is Empty.  Please <a href="%s">configure the Scribe Content Optimizer plugin</a>. If you need an API Key, please go to <a href="http://scribeseo.com/how-to-obtain-a-scribe-api-key/" title="Get a Scribe API Key" target="_blank">http://scribeseo.com/</a> to obtain a key.'), admin_url('options-general.php?page=scribe')).'</p></div>';
		}

		function displayEcordiaPostsColumns($columnName, $postId) {
			switch ($columnName) {
				case 'seo-score':
					$score = $this->getSeoScoreForPost($postId);
					if ($score) {
						printf(__('<span class="%1$s">%2$s%%</span>'), $this->getSeoScoreClassForPost($score), $score);
					} else {
						_e('NA');
					}
					break;
				case 'primary-seo-keywords':
					$keywords = $this->getSeoPrimaryKeywordsForPost($postId);
					if (is_array($keywords)) {
						if ( empty($keywords)) {
							echo '<span class="ecordia-error">'.__('None').'</span>';
						} else {
							$output = '<ul>';
							foreach ($keywords as $keyword) {
								$output .= "<li>{$keyword}</li>";
							}
							$output .= '</ul>';
						}
						print $output;
					} else {
						_e('NA');
					}
					break;
			}
		}

		function fetchUserInfo() {
			$userInfo = $this->getUserInfo(true);
			include('views/account-info.php');
			exit();
		}


		function saveSerializedValueToPreventOverriding($postId) {
			if(isset( $_POST['serialized-ecordia-results']) && !empty($_POST['serialized-ecordia-results'])) {
				if(false !== (wp_is_post_autosave($postId) || wp_is_post_revision($postId))) {
					return;
				}
				update_post_meta($postId, $this->_meta_seoInfo, stripslashes($_POST['serialized-ecordia-results']));
			}
		}

		// DISPLAY CALLBACKS

		function displayMetaBoxError() {
			include ('views/meta-box/error.php');
		}

		function displayMetaBox($post) {
			if ($this->postHasBeenAnalyzed($post->ID)) {
				include ('views/meta-box/after.php');
			} else {
				include ('views/meta-box/before.php');
			}
		}

		function displayKeywordResearchMetaBox($post) {
			include('views/keyword-research/meta-box.php');
		}

		function displayLinkBuildingMetaBox($post) {
			if($this->postHasBeenAnalyzed($post->ID)) {
				include('views/link-building/meta-box-after.php');
			} else {
				include('views/link-building/meta-box-before.php');
			}
		}

		function displaySettingsPage() {
			include ('views/settings.php');
		}

		function displayThickboxInterface() {
			wp_enqueue_style('ecordia');
			wp_enqueue_script('ecordia');
			wp_enqueue_style('global');
			wp_enqueue_style('media');
			wp_iframe('ecordia_thickbox_include');
		}

		function displayKeywordResearchThickboxInterface() {
			wp_enqueue_style('ecordia');
			wp_enqueue_script('ecordia');
			wp_enqueue_style('global');
			wp_enqueue_style('media');
			wp_iframe('ecordia_keyword_research_thickbox_include');
		}

		function displayLinkBuildingResearchThickboxInterface() {
			wp_enqueue_style('ecordia');
			wp_enqueue_script('ecordia');
			wp_enqueue_style('global');
			wp_enqueue_style('media');
			wp_iframe('ecordia_link_building_research_thickbox_include');
		}



		function thickboxInclude() {
			$pages = array('ecordia-score', 'ecordia-keyword-analysis', 'ecordia-change-keywords', 'ecordia-keyword-alternates', 'ecordia-tags', 'ecordia-serp', 'ecordia-seo-best-practices', 'ecordia-error');
			if( defined( 'ECORDIA_DEBUG' ) && ECORDIA_DEBUG ) {
				$pages[] = 'ecordia-debug';
			}
			$tab = in_array($_GET['tab'], $pages) ? $_GET['tab'] : 'ecordia-score';
			$page = str_replace('ecordia-', '', $tab);


			if (false === strpos($tab, 'error')) {
				add_filter('media_upload_tabs', array(&$this, 'thickboxTabs'));
				media_upload_header();
			}

			$info = $this->getSeoInfoForPost($_GET['post']);
			if (false === $info && false === strpos($tab, 'error')) {
				print '<form><p>No analysis present.</p></form>';
				return;
			}

			include ('views/popup/'.$page.'.php');
		}

		function keywordResearchThickboxInclude() {
			$phrase = urldecode($_GET['phrase']);
			$type = urldecode($_GET['match-type']);


			if($_GET['tab'] == 'ecordia-keyword-research-review') {
				include('views/keyword-research/keyword-research-review.php');
			} else {
				$info = $this->getKeywordResearchIdeas($phrase, $type);
				include('views/keyword-research/keyword-research.php');
			}
		}

		function linkBuildingResearchThickboxInclude() {
			$pages = array('ecordia-link-building-external', 'ecordia-link-building-internal', 'ecordia-link-building-social');

			$tab = in_array($_GET['tab'], $pages) ? $_GET['tab'] : 'ecordia-link-building-external';
			$page = str_replace('ecordia-', '', $tab);


			if (false === strpos($tab, 'error')) {
				add_filter('media_upload_tabs', array(&$this, 'linkBuildingThickboxTabs'));
				media_upload_header();
			}

			$info = $this->getSeoInfoForPost($_GET['post']);
			$keywordInfo = $info['GetAnalysisResult']['Analysis']['PrimaryKeywords'];
			$keywords = $keywordInfo['Keywords']['Keyword'];
			$properKeywords = array();
			foreach($keywords as $keyword) {
				if(in_array($keyword['Rank'],array('Primary', 'Important', 'Significant'))) {
					$properKeywords[] = $keyword;
				}
			}

			$type = str_replace('link-building-','',$page);
			$research = $this->getLinkBuildingResearchForPostAndType($_GET['post'], $type);
			if (false === $info && false === strpos($tab, 'error')) {
				print '<form><p>No analysis present.</p></form>';
				return;
			}

			echo '<span id="link-building-type" data-type="'.$type.'"></span>';
			echo '<span id="link-building-post-id" data-id="'.absint($_GET['post']).'"></span>';
			$klout_logo_url = plugins_url('/resources/images/klout-powered-gray.png', __FILE__);
			include ('views/link-building/'.$page.'.php');
		}

		function settingsSave() {
			if (isset($_POST['save-ecordia-api-key-information']) && current_user_can('manage_options') && check_admin_referer('save-ecordia-api-key-information')) {
				$settings = $this->getSettings();
				$settings['api-key'] = trim(stripslashes($_POST['ecordia-api-key']));
				$settings['use-ssl'] = stripslashes($_POST['ecordia-connection-method']) == 'https';

				$permissions = array('manage_options','delete_others_posts','delete_published_posts','delete_posts');
				$settings['permissions-level'] = in_array($_POST['ecordia-permissions-level'],array_keys($permissions)) ? $_POST['ecordia-permissions-level'] : 'manage_options';

				$settings['seo-tool-method'] = empty($_POST['ecordia-seo-tool-method']) ? '' : '1';
				$settings['seo-tool'] = empty($_POST['ecordia-seo-tool-chooser']) ? array() : unserialize(stripslashes($_POST['ecordia-seo-tool-chooser']));

				$settings['ecordia-post-types'] = !is_array($_POST['ecordia-post-types']) ? array() : $_POST['ecordia-post-types'];


				$this->setSettings($settings);
				wp_redirect(admin_url('options-general.php?page=scribe&updated=true'));
				exit();
			}
		}

		function thickboxTabs($tabs) {
			$pages = array('ecordia-score'=>__('SEO Score'), 'ecordia-keyword-analysis'=>__('Keyword Analysis'), 'ecordia-change-keywords'=>__('Change Keywords'), 'ecordia-keyword-alternates'=>__('Alternate Keywords'), 'ecordia-tags'=>__('Tags'), 'ecordia-serp'=>__('SERP'), 'ecordia-seo-best-practices'=>__('SEO Best Practices'));
			if( defined( 'ECORDIA_DEBUG' ) && ECORDIA_DEBUG ) {
				$pages['ecordia-debug'] = __( 'Debug Info' );
			}
			return $pages;
		}
		function linkBuildingThickboxTabs($tabs) {
			return array('ecordia-link-building-external'=>__('External Links'), 'ecordia-link-building-internal'=>__('Internal Links'), 'ecordia-link-building-social'=>__('Social Media'));
		}

		// UTILITY - changed the order to support AIOSEO first before themes

		function getSupportedPostTypes() {
			$settings = $this->getSettings();
			if(!is_array($settings['ecordia-post-types'])) {
				return array('post','page');
			} else {
				return $settings['ecordia-post-types'];
			}
		}

		function getNumberEvaluationsRemaining() {
			$userInfo = $this->getUserInfo(false);
			if(is_wp_error($userInfo)) {
				return '...';
			} else {
				return $userInfo->getCreditsRemaining();
			}
		}

		function getNumberKeywordEvaluationsRemaining() {
			$userInfo = $this->getUserInfo(false);
			if(is_wp_error($userInfo)) {
				return '...';
			} else {
				return $userInfo->getKeywordCreditsRemaining();
			}
		}

		function getAutomaticDependency() {
			$themeName = substr(trim(get_current_theme()),0,6);
			$templateName = strtolower(trim(get_template()));
			$frameworkName = THEME_FRAMEWORK;
			if (is_plugin_active('fv-all-in-one-seo-pack/fv-all-in-one-seo-pack.php')) {
				return 'fvaioseo';
			} else if (is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')) {
				return 'aioseo';
			} else if (is_plugin_active('all-in-one-seo-pack-pro/all_in_one_seo_pack.php')) {
				return 'aioseo';
			} else if ($frameworkName == 'woothemes'){
				return 'woothemes';
			} else if (in_array($themeName, array('Thesis', 'Hybrid', 'Headwa'))) {
				return strtolower($themeName);
			} else if (in_array($templateName, array('hybrid','genesis'))) {
				return $templateName;
			}
		}

		function getAutomaticDependencyNiceName($automatic) {
			switch( $automatic ) {
				case 'aioseo':
					$currently = __( 'All in One SEO Pack Plugin' );
					break;
				case 'headwa':
					$currently = __( 'Headway Theme' );
					break;
				case 'hybrid':
					$currently = __( 'Hybrid Theme' );
					break;
				case 'genesis':
					$currently = __( 'Genesis Theme' );
					break;
				case 'thesis':
					$currently = __( 'Thesis Theme' );
					break;
				case 'fvaioseo':
					$currently = __( 'FV All in One SEO Pack' );
					break;
				case 'woothemes':
					$currently = __( 'WooThemes' );
					break;
				default:
					$currently = sprintf( __( '<span class="ecordia-error">unable to detected theme/plugin</span>.  Please select the "Choose the SEO tool" option below. To see a list of compatible plugins and themes go to <a href="%1$s" target="_blank">http://scribeseo.com/compatibility</a>.' ), 'http://scribeseo.com/compatibility'  );
					break;
			}
			return $currently;
		}

		function getEcordiaDependency() {
			$settings = $this->getSettings();
			if($settings['seo-tool-method'] == '') {
				return $this->getAutomaticDependency();
			} else {
				return 'user-defined';
			}
		}

		function getPossibleDependencies() {
			$response = wp_remote_get($this->_utility_DependencyUrl);
			if(is_wp_error($response)) {
				return array('plugins'=>array(),'themes'=>array());
			} else {
				$xml = $response['body'];
				$parser = xml_parser_create();
				xml_set_element_handler($parser,array($this,'getPossibleDependenciesStartTag'),array($this,'getPossibleDependenciesEndTag'));
				xml_set_character_data_handler($parser, array($this,'getPossibleDependenciesContents'));
				if(!xml_parse($parser,$xml)) {
					return array();
				} else {
					return $this->_possible_Items;
				}
			}
		}

		function getPossibleDependenciesStartTag($parser,$data) {
			$name = strtolower($data);
			switch($name) {
				case 'plugins':
				case 'themes':
					$this->_possible_CurrentType = $name;
					break;
				case 'name':
				case 'titleelementid':
				case 'descriptionelementid':
					$this->_possible_CurrentData = str_replace('elementid','',$name);
					break;
				case 'item':
					$this->_possible_Item = array();
					break;
			}
		}

		function getPossibleDependenciesEndTag($parser,$data) {
			$name = strtolower($data);
			switch($name) {
				case 'item':
					$this->_possible_Items[$this->_possible_CurrentType][] = $this->_possible_Item;
					break;
				case 'name':
				case 'titleelementid':
				case 'descriptionelementid':
					$this->_possible_CurrentData = '';
					break;
			}
		}

		function getPossibleDependenciesContents($parser,$data) {
			if(!empty($this->_possible_CurrentData)) {
				$this->_possible_Item[$this->_possible_CurrentData] = $data;
			}
		}

		function getSeoInfoForPost($postId) {
			$info = get_post_meta($postId, $this->_meta_seoInfo, true);
			if ( empty($info)) {
				$info = false;
			} else {
				if (is_array($info)) {
					$info = base64_encode(serialize($info));
				}
				$info = unserialize(base64_decode($info));
			}
			return $info;
		}
		function getLinkBuildingResearchForPostAndType($postId, $type) {
			$info = get_post_meta($postId, $this->_meta_linkResearchInfo.'_'.$type, true);
			if(empty($info)) {
				$info = false;
			} else {
				$info = unserialize(base64_decode($info));
			}
			return $info;
		}
		function setLinkBuildingResearchForPostAndType($postId, $type, $info) {
			if(is_array($info)) {
				$info = base64_encode(serialize($info));
				update_post_meta($postId, $this->_meta_linkResearchInfo.'_'.$type, $info);
			}
		}
		function getNumberExternalLinkResearch($postId) {
			$info = $this->getLinkBuildingResearchForPostAndType($postId, 'external');
			if(!$info) {
				return 0;
			} else {
				$links = $info['GetSearchEngineResultsResult']['Links']['SearchEngineLink'];
				return is_array($links) ? count($links) : 0;
			}
		}
		function getNumberInternalLinkResearch($postId) {
			$info = $this->getLinkBuildingResearchForPostAndType($postId, 'internal');
			if(!$info) {
				return 0;
			} else {
				$links = $info['GetSearchEngineResultsResult']['Links']['SearchEngineLink'];
				return is_array($links) ? count($links) : 0;
			}
		}
		function getNumberSocialLinkResearch($postId) {
			$info = $this->getLinkBuildingResearchForPostAndType($postId, 'social');
			if(!$info) {
				return 0;
			} else {
				$links = $info['GetSocialMediaSearchResultsResult']['Entries']['SocialMediaUser'];
				return count($links);
			}
		}

		function setKeywordResearchIdeas($phrase, $matchType, $ideas) {
			$key = 'keyword_research_info_'.md5($phrase.$matchType);
			$this->addKeywordResearchHistory($key, $phrase, $matchType);
			if(!is_array($ideas)) {
				$ideas = array();
			}
			update_option($key, $ideas);
		}

		function getKeywordResearchIdeas($phrase, $matchType) {
			$key = 'keyword_research_info_'.md5($phrase.$matchType);
			$info = get_option($key, array());
			if(!is_array($info)) {
				$info = array();
			}
			return $info;
		}

		function addKeywordResearchHistory($key, $phrase, $matchType) {
			$meta = $this->getKeywordResearchHistory();
			$meta[$key] = array('phrase'=>$phrase, 'type'=>$matchType);
			update_option($this->_option_keywordResearchList, $meta);
		}

		function getKeywordResearchHistory() {
			$meta = get_option($this->_option_keywordResearchList, array());
			if(!is_array($meta)) {
				$meta = array();
			}

			usort($meta, create_function('$a, $b', 'return strcmp($a["phrase"], $b["phrase"]);'));

			return $meta;
		}

		function getSeoScoreForPost($postId) {
			$info = $this->getSeoInfoForPost($postId);
			if (@is_numeric($info['GetAnalysisResult']['Analysis']['SeoScore']['Score']['Value'])) {
				return intval($info['GetAnalysisResult']['Analysis']['SeoScore']['Score']['Value']);
			}
			return false;
		}

		function getSeoScoreClassForPost($score) {
			$score = intval($score);
			if ($score <= 50) {
				return 'ecordia-score-low';
			} elseif ($score <= 75) {
				return 'ecordia-score-medium';
			} else {
				return 'ecordia-score-high';
			}
		}

		function getSeoPrimaryKeywordsForPost($postId) {
			$info = $this->getSeoInfoForPost($postId);
			if (false === $info) {
				return array();
			} else {
				$allKeywords = (array) $info['GetAnalysisResult']['Analysis']['KeywordAnalysis']['Keywords']['Keyword'];
				$primaryKeywords = array();
				foreach ($allKeywords as $keyword) {
					if ($keyword['Rank'] == 'Primary') {
						$primaryKeywords[] = $keyword['Term'];
					}
				}
				return $primaryKeywords;
			}
		}

		function postHasBeenAnalyzed($postId) {
			return false !== $this->getSeoInfoForPost($postId);
		}

		function getPostSeoData($postId) {
			$seoData = get_post_meta($postId, $this->_meta_seoInfo, true);
			if (!$seoData) {
				return false;
			} else {
				return $seoData;
			}
		}

		function getUserInfo($live = false) {

			$settings = $this->getSettings();
			if ( $live && empty( $settings['api-key'] ) ) {

				delete_option( $this->_option_cachedUserResults );
				return new WP_Error(-1, __('You must set an API key.'));

			}

			$userAccountAccess = new EcordiaUserAccount( $settings['api-key'], $settings['use-ssl'], $live );

			if ( $live ) {

				$userAccountAccess->UserAccountStatus();
				if ( $userAccountAccess->hasError() )
					return new WP_Error($userAccountAccess->getErrorType(), $userAccountAccess->getErrorMessage() . $userAccountAccess->client->response . '<br /> ' .$userAccountAccess->client->request, $userAccountAccess);

			} elseif ( ! $userAccountAccess->has_results() ) {

				return new WP_Error(-100, __('Fetching Information...'));

			}

			return $userAccountAccess;

		}

		function getSettings() {
			if (null === $this->settings) {
				$this->settings = get_option($this->_option_ecordiaSettings, array());
				$this->settings = is_array($this->settings) ? $this->settings : array();
			}
			return $this->settings;
		}
		function getPermissionLevel() {
			$settings = $this->getSettings();
			if(empty($settings['permissions-level'])) {
				$settings['permissions-level'] = 'administrator';
				$this->setSettings($settings);
			}
			return $settings['permissions-level'];
		}

		function setSettings($settings) {
			if (!is_array($settings)) {
				return;
			}
			$this->settings = $settings;
			update_option($this->_option_ecordiaSettings, $this->settings);
		}

		function displaySection($section) {
			include ('views/misc/section-display.php');
		}

		function getPostTypes() {
			global $wp_version;
			if(version_compare($wp_version,'3','>=')) {
				return get_post_types(array('show_ui'=>true), 'objects');
			} else {
				$post = new stdClass;
				$post->labels = new stdClass;
				$post->labels->name = __('Posts');

				$page = new stdClass;
				$page->labels = new stdClass;
				$page->labels->name = __('Pages');

				return array('post'=>$post, 'page'=>$page);
			}
		}
	}


	$ecordia = new Ecordia;
	function ecordia_thickbox_include() {
		global $ecordia;
		$ecordia->thickboxInclude();
	}

	function ecordia_keyword_research_thickbox_include() {
		global $ecordia;
		$ecordia->keywordResearchThickboxInclude();
	}
	function ecordia_link_building_research_thickbox_include() {
		global $ecordia;
		$ecordia->linkBuildingResearchThickboxInclude();
	}
}
