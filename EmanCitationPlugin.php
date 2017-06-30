<?php

/* 
 * E-man Plugin
 *
 * Functions to customize Omeka for the E-man Project
 *
 */

class EmanCitationPlugin extends Omeka_Plugin_AbstractPlugin 
{

  protected $_hooks = array(
  	'define_routes',  		
  	'define_acl',
  );
  
  protected $_filters = array(
  	'admin_navigation_main',
  );

  function hookDefineRoutes($args)
  {
  	// Don't add these routes on the public side to avoid conflicts.
//   	if (is_admin_theme()) {
  		$router = $args['router'];
   		$router->addRoute(
   				'eman_citation',
   				new Zend_Controller_Router_Route(
   						'emancitation',
   						array(
   								'module' => 'eman-citation',
   								'controller'   => 'page',
   								'action'       => 'citation',
   						)
   				)
   		);
  }
  
  /**
   * Add the pages to the public main navigation options.
   *
   * @param array Navigation array.
   * @return array Filtered navigation array.
   */
  public function filterAdminNavigationMain($nav)
  {
    $nav[] = array(
                    'label' => __('Eman Citation'),
                    'uri' => url('emancitation'),
    								'resource' => 'EmanCitation_Page',
                  );
    return $nav;
  }

  function hookDefineAcl($args)
  {
  	$acl = $args['acl'];
  	$EmanCitationAdmin = new Zend_Acl_Resource('EmanCitation_Page');
  	$acl->add($EmanCitationAdmin);
  }
  
  
  static function citationTokens($contentId, $type = 'item') {

  	switch ($type) {
  		case 'item' :
  			$citation = get_option('eman_citation');  			
  			$content = get_record_by_id('Item', $contentId);
  			if (element_exists('Item Type Metadata', 'Sous-titre')) {
  				$soustitre = strip_tags(metadata($content, array('Item Type Metadata', 'Sous-titre')));
  				$soustitre = $soustitre ? "&laquo; $soustitre &raquo;," : '';
  			} else {
  				$soustitre = ' Soustitre de la muerte';
  			}
  			if (element_exists('Item Type Metadata', 'Auteur description')) {
  				$auteurdesc = strip_tags(metadata($content, array('Item Type Metadata', 'Auteur description')));
  				$auteurdesc = $auteurdesc ? "&Eacute;dition de la fiche : $auteurdesc.<br /> " : '';
  			} else {
  				$auteurdesc = ' Description de la muerte';
  			}
  			if (element_exists('Item Type Metadata', 'Auteur transcription')) {
  				$auteurtrans = strip_tags(metadata($content, array('Item Type Metadata', 'Auteur transcription')));
  				$auteurtrans = $auteurtrans ? "Transcription du document : $auteurtrans.<br />" : '';
  			} else {
  				$auteurtrans = ' Transcription de la muerte';
  			}  			
  			$url = '/items/show/';
  			break;
  		case 'collection' :
  			$citation = get_option('eman_citation_collections');  			
  			$content = get_record_by_id('Collection', $contentId);
  			$soustitre = $auteurdesc = $auteurtrans = "";
  			$url = '/collections/show/';
  			break;
  		case 'file' :
  			$citation = get_option('eman_citation_files');
  			$content = get_record_by_id('File', $contentId);
  			$soustitre = $auteurdesc = $auteurtrans = "";
  			$url = '/files/show/';
  			break;
  	}
  
  	$tokens = array(
  			'{titre}',
  			'{createur}',
  			'{editeur}',
  			'{date}',
  			'{soustitre}',
  			'{auteurdesc}',
  			'{auteurtrans}',
  			'{url}',
  			'{consulte}'
  	);

  	$editeur = strip_tags(metadata($content, array('Dublin Core', 'Publisher')));
  	$editeur = $editeur ? "&Eacute;diteur : $editeur" : '';
  	$titre = "<em>" . metadata($content, array('Dublin Core', 'Title')) . "</em>";
  	$data = array(
  			$titre,
  			strip_tags(metadata($content, array('Dublin Core', 'Creator'))),
  			$editeur,
  			strip_tags(metadata($content, array('Dublin Core', 'Date'))),
  			$soustitre,
  			$auteurdesc,
  			$auteurtrans,
  			WEB_ROOT . $url . $contentId,
  			date('d/m/Y'),
  	);  	 
  	$data = array_map(function($n) {
  											return strip_tags($n, '<em>');
  										}, 
  										$data);
  	$citation = nl2br(str_replace($tokens, $data, $citation));
  	return $citation;
  }
  
}
