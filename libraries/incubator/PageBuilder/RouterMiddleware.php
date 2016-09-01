<?php
/**
 * Part of the Joomla PageBuilder Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\PageBuilder;

use Joomla\DI\Container;
use Joomla\Http\MiddlewareInterface;
use Joomla\ORM\Exception\EntityNotFoundException;
use Joomla\ORM\Operator;
use Joomla\ORM\Repository\RepositoryInterface;
use Joomla\PageBuilder\Entity\Page;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Determines the command to be executed.
 *
 * @package  Joomla/PageBuilder
 *
 * @since    __DEPLOY_VERSION__
 */
class RouterMiddleware implements MiddlewareInterface
{
	/** @var Container */
	private $container;

	/**
	 * RouterMiddleware constructor.
	 *
	 * @param   Container $container The container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Execute the middleware. Don't call this method directly; it is used by the `Application` internally.
	 *
	 * @internal
	 *
	 * @param   ServerRequestInterface $request  The request object
	 * @param   ResponseInterface      $response The response object
	 * @param   callable               $next     The next middleware handler
	 *
	 * @return  ResponseInterface
	 */
	public function handle(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
	{
		$attributes = $request->getAttributes();

		if (!isset($attributes['command']))
		{
			#try
			{
				/** @var RepositoryInterface $repository */
				$repository = $this->container->get('Repository')->forEntity(Page::class);

				$path = preg_replace('~^/?index.php/?~', '', $request->getUri()->getPath());
				$page = $repository
					->findOne()
					->with('url', Operator::EQUAL, $path)
					->getItem();

				$command = new DisplayPageCommand($page->id, $response->getBody(), $this->container);
				$request = $request->withAttribute('command', $command);
				// @todo Emit afterRouting event
			}
			#catch (EntityNotFoundException $e)
			{
				// Do nothing
			}
		}

		return $next($request, $response);
	}
}
