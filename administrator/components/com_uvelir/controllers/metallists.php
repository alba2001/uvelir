<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Metallists list controller class.
 */
class UvelirControllerMetallists extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'metallist', $prefix = 'UvelirModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
 
    /**
     * Пакетное изменение группы
     */
    public function change_groups()
    {
        $cids = JRequest::getVar('cid',array(),'','array');
        $change_group = $this->getModel('Metallists')->change_groups($cids, 0);
        $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.'&change_group='.$change_group, false));
    }
   
}