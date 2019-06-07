<?php defined('_JEXEC') or die;
/*
 * @package     Joomla.Plugin
 * @subpackage  Content.vmvideo
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>
<div class="vmvideo vmvideo-<?php echo $ratio; ?>">
	<a
		class="vmvideo-cover lazyload"
		data-videosrc="<?php echo $id; ?>"
		data-videoparams="<?php echo $videoParams; ?>"
		<?php if ($lazysizes) { ?>
		data-bgset="<?php echo $image; ?>"
		<?php } else { ?>
		style="background-image:url('<?php echo $image; ?>')"
		<?php } ?>
	>
		<?php if ( $title ) { ?>
		<span class="vmvideo-title"><?php echo $title; ?></span>
		<?php } ?>
	</a>
</div>
