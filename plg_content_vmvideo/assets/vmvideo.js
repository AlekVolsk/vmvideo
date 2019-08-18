/*
 * @package     Joomla.Plugin
 * @subpackage  Content.vmvideo
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */
document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.vmvideo > .vmvideo-cover')) {
        document.querySelector('.vmvideo > .vmvideo-cover').addEventListener('click', function (e) {
            var target = e.target || e.srcElement;
            var params = JSON.parse(target.dataset.videoparams.replace(/\'/g, '"'));
            var query = [];
            for (key in params) {
                query.push(encodeURIComponent(key) + '=' + encodeURIComponent(params[key]));
            }
            target.innerHTML = '<embed src="//player.vimeo.com/video/' + target.dataset.videosrc + '?' + query.join('&') + '" allow="accelerometer;autoplay;encrypted-media;gyroscope;picture-in-picture" allowfullscreen style="border:0;"></embed>';
        });
    }
});
