#
#<?php die('Forbidden.'); ?>
#Date: 2019-09-23 12:20:29 UTC
#Software: Joomla Platform 13.1.0 Stable [ Curiosity ] 24-Apr-2013 00:00 GMT

#Fields: datetime	priority clientip	category	message
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Factory::getUri is deprecated. Use JUri directly.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	JFile::read is deprecated. Use native file_get_contents() syntax.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Factory::getXml is deprecated. Use SimpleXML directly.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	JXMLElement is deprecated. Use SimpleXMLElement.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	JFile::read is deprecated. Use native file_get_contents() syntax.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Factory::getXml is deprecated. Use SimpleXML directly.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	JFile::read is deprecated. Use native file_get_contents() syntax.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	JFile::read is deprecated. Use native file_get_contents() syntax.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Factory::getXml is deprecated. Use SimpleXML directly.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	JDatabase::getErrorNum() is deprecated, use exception handling instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Layout\FileLayout::setLayout() is deprecated, use FileLayout::setLayoutId() instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Layout\FileLayout::refreshIncludePaths() is deprecated, use FileLayout::clearIncludePaths() instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Layout\FileLayout::refreshIncludePaths() is deprecated, use FileLayout::clearIncludePaths() instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Layout\FileLayout::setLayout() is deprecated, use FileLayout::setLayoutId() instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Layout\FileLayout::refreshIncludePaths() is deprecated, use FileLayout::clearIncludePaths() instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	Joomla\CMS\Layout\FileLayout::refreshIncludePaths() is deprecated, use FileLayout::clearIncludePaths() instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	WARNING 185.234.216.205	deprecated	The addStyleSheet method signature used has changed, use (url, options, attributes) instead.
2019-09-23T12:20:29+00:00	CRITICAL 185.234.216.205	error	Uncaught \Throwable of type Error thrown. Stack trace: #0 /var/www/webcity/plugins/content/jxtcimagegallery/jxtcimagegallery.php(170): plgContentjxtcimagegallery->getCacheUrl('imagegallery', Object(stdClass), '105', '105', '1', '0', 'FFFFFF')
#1 /var/www/webcity/plugins/content/jxtcimagegallery/jxtcimagegallery.php(70): plgContentjxtcimagegallery->folder('media/k2/galler...', Array)
#2 /var/www/webcity/libraries/joomla/event/event.php(70): plgContentjxtcimagegallery->onContentPrepare('com_content.art...', Object(stdClass), Object(Joomla\Registry\Registry), 0)
#3 /var/www/webcity/libraries/joomla/event/dispatcher.php(160): JEvent->update(Array)
#4 /var/www/webcity/modules/mod_jxtc_k2contentwall/tmpl/default_parse.php(301): JEventDispatcher->trigger('oncontentprepar...', Array)
#5 /var/www/webcity/modules/mod_jxtc_k2contentwall/tmpl/bootstrap.php(116): require('/var/www/webcit...')
#6 /var/www/webcity/modules/mod_jxtc_k2contentwall/mod_jxtc_k2contentwall.php(157): require('/var/www/webcit...')
#7 /var/www/webcity/libraries/src/Helper/ModuleHelper.php(201): include('/var/www/webcit...')
#8 /var/www/webcity/libraries/src/Document/Renderer/Html/ModuleRenderer.php(98): Joomla\CMS\Helper\ModuleHelper::renderModule(Object(stdClass), Array)
#9 /var/www/webcity/libraries/src/Document/Renderer/Html/ModulesRenderer.php(47): Joomla\CMS\Document\Renderer\Html\ModuleRenderer->render(Object(stdClass), Array, NULL)
#10 /var/www/webcity/libraries/src/Document/HtmlDocument.php(491): Joomla\CMS\Document\Renderer\Html\ModulesRenderer->render('right', Array, NULL)
#11 /var/www/webcity/libraries/src/Document/HtmlDocument.php(783): Joomla\CMS\Document\HtmlDocument->getBuffer('modules', 'right', Array)
#12 /var/www/webcity/libraries/src/Document/HtmlDocument.php(557): Joomla\CMS\Document\HtmlDocument->_renderTemplate()
#13 /var/www/webcity/libraries/src/Application/CMSApplication.php(1140): Joomla\CMS\Document\HtmlDocument->render(false, Array)
#14 /var/www/webcity/libraries/src/Application/SiteApplication.php(780): Joomla\CMS\Application\CMSApplication->render()
#15 /var/www/webcity/libraries/src/Application/CMSApplication.php(309): Joomla\CMS\Application\SiteApplication->render()
#16 /var/www/webcity/index.php(49): Joomla\CMS\Application\CMSApplication->execute()
#17 {main}
