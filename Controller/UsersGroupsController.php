<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

/**
 * UsersGroups Controller
 *
 * @property UsersGroup $UsersGroup
 * @property PaginatorComponent $Paginator
 */
class UsersGroupsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
			'Paginator'
	);

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->UsersGroup->recursive = 0;
		$this->set('usersGroups', $this->Paginator->paginate());
	}
}
