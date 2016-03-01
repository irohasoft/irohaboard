<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohasoft.jp/irohaboard
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an
 * application
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array();

	/**
	 * Displays a view
	 *
	 * @return void
	 * @throws NotFoundException When the view file could not be found
	 *         or MissingViewException in debug mode.
	 */
	public function display()
	{
		$path = func_get_args();
		
		$count = count($path);
		if (! $count)
		{
			return $this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;
		
		if (! empty($path[0]))
		{
			$page = $path[0];
		}
		if (! empty($path[1]))
		{
			$subpage = $path[1];
		}
		if (! empty($path[$count - 1]))
		{
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		
		try
		{
			$this->render(implode('/', $path));
		}
		catch (MissingViewException $e)
		{
			if (Configure::read('debug'))
			{
				throw $e;
			}
			throw new NotFoundException();
		}
	}
}
