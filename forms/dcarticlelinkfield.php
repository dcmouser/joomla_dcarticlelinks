<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldDcarticlelinkfield extends JFormField {
	
	protected $type = 'City';

	// getLabel() left out

	public function getInput() {
		$hretv = '';

		// try to get article id
		try {
			$form = $this->form;
			$formdata_registry = $form->getData();
			$dataobj = $formdata_registry->toArray();
			$articleid = $formdata_registry->get('id');
		} catch (Exception $e) {
			$hretv = '<strong>Unable to determine article ID; cannot display menu item links to this article.</strong>';
			return $hretv;
			}

		if (empty($articleid)) {
			$hretv = '<strong>Article ID# not set -- cannot display menu item links to this article.  Is this a new article?</strong>';
			return $hretv;
			}

		// ok we have an article id! great.
		$menulinks = $this->buildMenuLinks($articleid);
		$hretv = $this->buildHtmlLinksFromMenuLinks($articleid, $menulinks);

		return $hretv;
	}





	public function buildMenuLinks($articleid) {
		// get all menu items
		$menu = JMenu::getInstance('site');
		//
		$attributes=array();
		$values=array();
		//$attributes = array('alias');
		//$values = array('homepage');
		$menuitems = $menu->getItems($attributes, $values, false);

		if (empty($menuitems)) {
			return array();
		}

		// now walk through and find all refering to OUR article
		$menuitems_linking = array();

		foreach ($menuitems as $menuitem) {
			if ($menuitem->component!='com_content') {
				continue;
				}
			if ($menuitem->query['view']!='article') {
				continue;
				}
			if ($menuitem->query['id']!=$articleid) {
				continue;
				}
			// add it!
			$menuitems_linking[] = array(
				'id' => $menuitem->id,
				'title' => $menuitem->title,
				'route' => $menuitem->route,
				'link' => $menuitem->link,
				'img' => $menuitem->img,
				'titlepath' => $this->calcTitlePath($menuitem->tree, $menuitems),
				);
			}

		return $menuitems_linking;
	}



	public function calcTitlePath($tree, &$menuitems) {
		$titlepath = '';
		foreach ($tree as $treenode) {
			$node = $this->findTreeNode($treenode,$menuitems);
			$nodetitle = $node->title;
			if (empty($titlepath)) {
				$titlepath = $nodetitle;
				} else {
				$titlepath .= ' &#9658; ' . $nodetitle;
			}
		}
		return $titlepath;
	}

	public function findTreeNode($nodeid,&$menuitems) {
		foreach ($menuitems as $menuitem) {
			if ($menuitem->id==$nodeid) {
				return $menuitem;
				}
			}
		return null;
		}




	public function buildHtmlLinksFromMenuLinks($articleid, $menulinks) {
		$hretv = '';
		if (empty($menulinks)) {
			$hretv = '<strong>No menu items found pointing to this article (#' . $articleid . ')</strong>.';
			return $hretv;
			}

		//$hretv .= 'Menu items linking to this page:<br/>';
		$hretv .= '<br/>';
		$hretv .= '<ul>';
		foreach ($menulinks as $menulink) {
			$link_visit = JURI::root() . $menulink['route'];
			$link_edit = JURI::base() . 'index.php?option=com_menus&view=item&layout=edit&id=' . $menulink['id'];
			$route = $menulink['route'];
			$titlepath = $menulink['titlepath'];
			$hretv .= '<li>' . $titlepath . '<br/> Visit article page: <a href="' . $link_visit . '" target="_blank">' . $route . '</a><br/> Edit menu item: <a href="' . $link_edit . '" target="_blank">' . $link_edit . '</a></li>' . "\n";
			}
		$hretv .= '</ul>';

//		$hretv = 'Working with article #' . $articleid . '<br/><hr/>';
//		$hretv .= '<pre>menu = ' . htmlentities(print_r($menu,true)) . '</pre>';
//		$hretv .= '<pre>menuitems = ' . htmlentities(print_r($menulinks,true)) . '</pre>';

		return $hretv;
}









}