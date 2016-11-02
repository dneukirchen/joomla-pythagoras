<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Content;

use Interop\Container\ContainerInterface;
use Joomla\Renderer\RendererInterface;

/**
 * ContentType Interface
 *
 * @package  Joomla/Content
 *
 * @since    __DEPLOY_VERSION__
 */
interface ContentTypeInterface
{
	/**
	 * Visits the content type.
	 *
	 * @param   ContentTypeVisitorInterface $visitor The Visitor
	 *
	 * @return  mixed
	 */
	public function accept(ContentTypeVisitorInterface $visitor);

	/**
	 * Gets the title for the content
	 *
	 * @return  string
	 */
	public function getTitle();
}
