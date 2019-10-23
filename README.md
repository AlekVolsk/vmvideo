# VmVideo

![Version](https://img.shields.io/badge/VERSION-1.0.5-0366d6.svg?style=for-the-badge)
![Joomla](https://img.shields.io/badge/joomla-3.7+-1A3867.svg?style=for-the-badge)
![Php](https://img.shields.io/badge/php-5.6+-8892BF.svg?style=for-the-badge)

_description in Russian [here](README.ru.md)_

Content plugin for Joomla! 3 for video output from Vimeo.

This solution compares favorably with others in that it downloads video from Vimeo not when loading the page, but only after the playback starts, thus creating no delays when loading the page.

The background image pre-cached and supports lazy loading (available in settings, enabled by default).

Shortcode format:
```
{vmvideo full_url[|[ratio]|title]}
```

For example:
```
{vmvideo https://vimeo.com/131191237|16:9|The New JavaScript: ES6 - Rob Eisenberg}
```

Some parts of the shortcode may be missing, but their order must be preserved: address|ratio|title.

Allowable aspect ratios are: `4:3`, `16:10`, `16:9`, `18:9` (a minus sign is allowed to be substituted for a colon). Incorrect aspect ratio will be part of the heading following it.

Specifying a title is optional. To quickly insert a shortcode, there is an editor button that opens a dialog box that allows you to enter the url and title of the video in the appropriate fields.

By default, the video shows the title, owner name and owner portrait, which can be disabled in the plugin settings.

Also in the settings of the plugin, you can specify the color of not black-and-white player controls.
