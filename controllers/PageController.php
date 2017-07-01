<?php
class EmanCitation_PageController extends Omeka_Controller_AbstractActionController
{
	public function getCitationForm()
	{
	
		$form = new Zend_Form();
		$form->setName('EmanCitation');
		
		$title = new Zend_Form_Element_Note('citation_items_title');
		$title->setValue("<h4>Items : Texte de la citation</h4>");
// 		$title->setBelongsTo($id);
		$form->addElement($title);		
		$citation = new Zend_Form_Element_Textarea('citation_items');
		$citation->setLabel('Items : Texte de la citation');
		$citation->setValue(get_option('eman_citation'));
		$citation->setAttribs(array('rows'=> 5, 'cols' => 30));
		$form->addElement($citation);
		
		$title = new Zend_Form_Element_Note('citation_files_title');
		$title->setValue("<h4>Fichiers : Texte de la citation</h4>");
// 		$title->setBelongsTo($id);
		$form->addElement($title);		
		$citation = new Zend_Form_Element_Textarea('citation_files');
		$citation->setLabel('Fichiers : Texte de la citation');
		$citation->setValue(get_option('eman_citation_files'));
		$citation->setAttribs(array('rows'=> 5, 'cols' => 30));
		$form->addElement($citation);
		
		$title = new Zend_Form_Element_Note('citation_collections_title');
		$title->setValue("<h4>Collections : Texte de la citation</h4>");
// 		$title->setBelongsTo($id);
		$form->addElement($title);		
		$citation = new Zend_Form_Element_Textarea('citation_collections');
		$citation->setLabel('Collections : Texte de la citation');
		$citation->setValue(get_option('eman_citation_collections'));
		$citation->setAttribs(array('rows'=> 5, 'cols' => 30));
		$form->addElement($citation);
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Save Citation');
		$form->addElement($submit);
	
		// Prettify form
		$elements = $form->getElements();
		foreach ($elements as $elem) {
			$elem->setDecorators(array(
					'ViewHelper',
					// 					array( 'Label', array('placement' => 'prepend')),
					array('HtmlTag', array('tag' => 'div', 'class' => 'eman-citation')),
			));
		}
	
		return $form;
	}
	
	public function citationAction()
	{
		$form = $this->getCitationForm();
	
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				// Tri des blocs avant sauvegarde
				$citation = $form->getValues();
				// Save setting for overriding on items/show
				set_option('eman_citation', $citation['citation_items']);
				// Save setting for overriding on items/show
				set_option('eman_citation_files', $citation['citation_files']);
				// Save setting for overriding on items/show
				set_option('eman_citation_collections', $citation['citation_collections']);
				
				// Sauvegarde form dans DB
				$db = get_db();
	
				$this->_helper->flashMessenger('Modèles de citations sauvegardés.');
			}
		}
		$this->view->content = $form;
		$db = get_db();
		$maxItemId = $db->query("SELECT MAX(id) id FROM `$db->Items`")->fetchAll(); 
		if ($maxItemId[0]['id']) {
			$citation = EmanCitationPlugin::citationTokens($maxItemId[0]['id']);
			$this->view->preview = "<em>Voici un aper&ccedil;u de la citation telle qu'elle apparaîtra sur la page :</em><br /><br /><div style='background:#eee;'>$citation</div>";				
		} else {
			$this->view->preview = "Créez au moins un item pour obtenir une prévisualisation.";
		}
		$this->render();
	}	
}