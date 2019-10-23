<?php
 /*
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.vmvideobtn
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\Path;

class PlgEditorsXtdVmvideobtn extends CMSPlugin
{
    protected $autoloadLanguage = true;

    public function onDisplay($name, $asset, $author)
    {
        $layout = Path::clean(PluginHelper::getLayoutPath('editors-xtd', 'vmvideobtn'));
        if (file_exists($layout)) {
            include $layout;
            Factory::getDocument()->addStyleDeclaration('#vmvideo-modal{top:50%;left:50%;width:600px;max-width:98%;margin-left:0;transform:translate(-50%,-50%);}#vmvideo-modal .modal-body{box-sizing:border-box;padding:15px 30px 15px 15px;}');

            $button = new CMSObject;
            $button->modal   = false;
            $button->class   = 'btn btn-danger';
            $button->link    = '#';
            $button->text    = Text::_('Vimeo video');
            $button->name    = 'vimeo';
            $button->onclick = 'insertVmvideo(\'' . $name . '\');return false;';

            return $button;
        } else return;
    }
}
