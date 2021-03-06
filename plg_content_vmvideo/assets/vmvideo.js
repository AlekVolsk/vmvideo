/*
 * @package     Joomla.Plugin
 * @subpackage  Content.vmvideo
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

(function () {
    if (!Array.prototype.forEach) {
        Array.prototype.forEach = function (callback, thisArg) {
            thisArg = thisArg || window;
            for (var i = 0; i < this.length; i++) {
                callback.call(thisArg, this[i], i, this);
            }
        };
    }
    if (typeof NodeList.prototype.forEach !== "function") {
        NodeList.prototype.forEach = Array.prototype.forEach;
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.vmvideo > .vmvideo-cover').forEach(function (el) {
        el.addEventListener('click', function (e) {
            var target = e.target || e.srcElement;
            var params = JSON.parse(target.dataset.videoparams.replace(/\'/g, '"'));
            var query = [];
            for (key in params) {
                query.push(encodeURIComponent(key) + '=' + encodeURIComponent(params[key]));
            }
            target.innerHTML = '<iframe src="//player.vimeo.com/video/' + target.dataset.videosrc + '?' + query.join('&') + '" allow="autoplay;fullscreen" allowfullscreen></iframe>';
        });
    });
});
