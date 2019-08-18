<?php defined('_JEXEC') or die;
/*
 * @package     Joomla.Plugin
 * @subpackage  Content.vmvideo
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\FileSystem\Path;
//use Joomla\CMS\FileSystem\Folder;

class plgContentVmvideo extends CMSPlugin
{

    public function onContentPrepare($context, &$article, &$params, $page = 0)
    {
        if ($context == 'com_finder.indexer') {
            return false;
        }

        $results = [];
        preg_match_all('|{vmvideo\s(.*?)}|U', $article->text, $results);
        foreach ($results as $k => $result) {
            if (!$result) {
                unset($results[$k]);
            }
        }
        if (!$results) {
            return false;
        }

        $cachFolder = Path::clean(Factory::getConfig()->get('cache_path', ''));
        $cachFolder = is_dir($cachFolder) ? $cachFolder . DIRECTORY_SEPARATOR . 'plg_content_vmvideo' . DIRECTORY_SEPARATOR : '';
        if ($cachFolder && !is_dir($cachFolder)) {
            JFolder::create($cachFolder, 0755);
        }

        $layout = PluginHelper::getLayoutPath('content', 'vmvideo');
        $format = $this->params->get('format', '16-9');

        HTMLHelper::script('plugins/content/vmvideo/assets/vmvideo.js', [], ['options' => ['version' => 'auto']]);

        if ($this->params->get('includes') == '1') {
            $css = str_replace(JPATH_ROOT, '', dirname($layout) . '/' . basename($layout, '.php') . '.css');
            if (!file_exists(JPATH_ROOT . $css)) {
                $css = 'plugins/content/vmvideo/assets/vmvideo.css';
            }
            $css = str_replace('\\', '/', $css);
            HTMLHelper::stylesheet($css, [], ['options' => ['version' => 'auto']]);
        }

        $lazysizes = $this->params->get('lazysizes') == '1';
        if ($lazysizes) {
            HTMLHelper::script('plugins/content/vmvideo/assets/lazysizes/ls.bgset.min.js', [], ['options' => ['version' => 'auto']]);
            HTMLHelper::script('plugins/content/vmvideo/assets/lazysizes/lazysizes.min.js', [], ['options' => ['version' => 'auto']]);
        }

        $videoParams = [];
        $videoParams['fun'] = 0;
        $videoParams['autoplay'] = 1;
        $videoParams['title'] = (int)$this->params->get('showtitle');
        $videoParams['byline'] = (int)$this->params->get('showowner');
        $videoParams['portrait'] = (int)$this->params->get('showportrait');
        $videoParams['color'] = str_replace('#', '', $this->params->get('pcolor'));
        $videoParams = str_replace('"', "'", json_encode($videoParams));
        
        foreach ($results[1] as $key => $link) {
            $tmp = explode('|', strip_tags($link));

            $link = trim($tmp[0]);
            unset($tmp[0]);

            $ratio = $format;
            if (count($tmp) && isset($tmp[1])) {
                $r_tmp = str_replace([':', ' '], ['-' . ''], preg_replace('/[0-9]-:[0-9]/', '', $tmp[1]));
                if (in_array($r_tmp, ['18:9', '16:9', '16:10', '4:3', '18-9', '16-9', '16-10', '4-3'])) {
                    $ratio = $r_tmp;
                    unset($tmp[1]);
                }
            }

            $title = '';
            if (count($tmp)) {
                $title = trim(implode(' ', $tmp));
            }

            $match = [];
            preg_match('/(?:www\.|)vimeo\.com\/([a-zA-Z0-9_\-]+)(&.+)?/i', $link, $match);

            if (count($match) > 1) {
                $id = $match[1];
                $cachedImage = $cachFolder . $id . '.jpg';

                $vimeoRes = @file_get_contents('http://vimeo.com/api/v2/video/' . $id . '.json');

                if (!file_exists($cachedImage)) {
                    if ((bool)$vimeoRes !== false) {
                        $vimeoRes = json_decode($vimeoRes, true);
                        if (!$title) {
                            $title = $vimeoRes[0]['title'];
                        }
                        $image = pathinfo($vimeoRes[0]['thumbnail_large'], PATHINFO_DIRNAME) . '/' . explode('_', pathinfo($vimeoRes[0]['thumbnail_large'], PATHINFO_FILENAME))[0] . '.' . pathinfo($vimeoRes[0]['thumbnail_large'], PATHINFO_EXTENSION);
                        $buffer = @file_get_contents($image);
                        if ((bool)$buffer !== false) {
                            if ($cachFolder) {
                                file_put_contents($cachedImage, $buffer);
                                $image = str_replace('\\', '/', str_replace(Path::clean(JPATH_ROOT), '', $cachedImage));
                            }
                        }
                    } else {
                        $image = '/' . $this->params->get('emptyimg', 'plugins/content/vmvideo/assets/empty.png');
                    }
                } else {
                    $image = str_replace('\\', '/', str_replace(Path::clean(JPATH_ROOT), '', $cachedImage));
                }

                ob_start();
                include $layout;
                $article->text = str_replace($results[0][$key], ob_get_clean(), $article->text);
            }
        }
    }
}
