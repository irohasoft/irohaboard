<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController
{
	public $components = [
		'Session',
		'Paginator',
		'Security' => [
			'csrfUseOnce' => false,
			'unlockedActions' => ['login', 'admin_login'],
		],
		'Search.Prg',
		'Cookie',
		'Auth' => [
			'allowedActions' => [
				'index',
				'login',
				'logout'
			]
		]
	];
	
	/**
	 * ホーム画面（受講コース一覧）へリダイレクト
	 */
	public function index()
	{
		$this->redirect("/users_courses");
	}

	/**
	 * ログイン
	 */
	public function login()
	{
		$username = '';
		$password = '';
		
		// 自動ログイン処理
		// Check cookie's login info.
		if($this->Cookie->check('Auth'))
		{
			// クッキー上のアカウントでログイン
			$this->request->data = $this->Cookie->read('Auth');
			
			if($this->Auth->login())
			{
				// 最終ログイン日時を保存
				$this->User->id = $this->readAuthUser('id');
				$this->User->saveField('last_logined', date(date('Y-m-d H:i:s')));
				return $this->redirect( $this->Auth->redirect());
			}
			else
			{
				// ログインに失敗した場合、クッキーを削除
				$this->Cookie->delete('Auth');
			}
		}
		
		// 通常ログイン処理
		if($this->request->is('post'))
		{
			if($this->Auth->login())
			{
				if(isset($this->data['User']['remember_me']))
				{
					// Remove remember_me data.
					unset( $this->request->data['User']['remember_me']);
					
					// Save login info to cookie.
					$cookie = $this->request->data;
					$this->Cookie->write( 'Auth', $cookie, true, '+2 weeks');
				}
				
				// 最終ログイン日時を保存
				$this->User->id = $this->readAuthUser('id');
				$this->User->saveField('last_logined', date(date('Y-m-d H:i:s')));
				$this->writeLog('user_logined', '');
				$this->deleteSession('Auth.redirect');
				$this->redirect($this->Auth->redirect());
			}
			else
			{
				$this->writeLog('login_error', $this->request->data['User']['username']);
				$this->Flash->error(__('ログインID、もしくはパスワードが正しくありません'));
			}
		}
		else
		{
			// デモモードの場合、ログインID、パスワードの初期値を指定
			if(Configure::read('demo_mode'))
			{
				$username = Configure::read('demo_login_id');
				$password = Configure::read('demo_password');
			}
		}
		
		$this->set(compact('username', 'password'));
	}

	/**
	 * ログアウト
	 */
	public function logout()
	{
		$this->Cookie->delete('Auth');
		$this->redirect($this->Auth->logout());
	}

	/**
	 * ユーザ一覧を表示
	 */
	public function admin_index()
	{
		// SearchPluginの呼び出し
		$this->Prg->commonProcess();
		
		// Model の filterArgs に定義した内容にしたがって検索条件を作成
		$conditions = $this->User->parseCriteria($this->Prg->parsedParams());
		
		// 選択中のグループをセッションから取得
		if($this->hasQuery('group_id'))
			$this->writeSession('Iroha.group_id', intval($this->getQuery('group_id')));
		
		// GETパラメータから検索条件を抽出
		$group_id = ($this->hasQuery('group_id')) ? $this->getQuery('group_id') : $this->readSession('Iroha.group_id');
		
		// 独自の検索条件を追加（指定したグループに所属するユーザを検索）
		if($group_id != '')
			$conditions['User.id'] = $this->Group->getUserIdByGroupID($group_id);
		
		$this->paginate = [
			'fields' => ['*',
				// 所属グループ一覧 ※パフォーマンス改善
				'(SELECT group_concat(g.title order by g.id SEPARATOR \', \') as group_title  FROM ib_users_groups  ug INNER JOIN ib_groups  g ON g.id = ug.group_id  WHERE ug.user_id = User.id) as group_title',
				// 受講コース一覧   ※パフォーマンス改善
				'(SELECT group_concat(c.title order by c.id SEPARATOR \', \') as course_title FROM ib_users_courses uc INNER JOIN ib_courses c ON c.id = uc.course_id WHERE uc.user_id = User.id) as course_title',
			],
			'conditions' => $conditions,
			'limit' => 20,
			'order' => 'created desc',
		];
		
		// ユーザ一覧を取得
		try
		{
			$users = $this->paginate();
		}
		catch(Exception $e)
		{
			// 指定したページが存在しなかった場合（主に検索条件変更時に発生）、1ページ目を設定
			$this->request->params['named']['page'] = 1;
			$users = $this->paginate();
		}
		
		// グループ一覧を取得
		$groups = $this->Group->find('list');
		
		$this->set(compact('groups', 'users', 'group_id'));
	}

	/**
	 * ユーザを追加（編集画面へ）
	 */
	public function admin_add()
	{
		$this->admin_edit();
		$this->render('admin_edit');
	}

	/**
	 * ユーザ情報編集
	 * @param int $user_id 編集対象のユーザのID
	 */
	public function admin_edit($user_id = null)
	{
		if($this->isEditPage() && !$this->User->exists($user_id))
		{
			throw new NotFoundException(__('Invalid user'));
		}
		
		$username = '';
		
		if($this->request->is(['post', 'put']))
		{
			if(Configure::read('demo_mode'))
				return;
			
			if($this->request->data['User']['new_password'] !== '')
				$this->request->data['User']['password'] = $this->request->data['User']['new_password'];

			if($this->User->save($this->request->data))
			{
				$this->Flash->success(__('ユーザ情報が保存されました'));
				unset($this->request->data['User']['new_password']);
				return $this->redirect(['action' => 'index']);
			}
			else
			{
				$this->Flash->error(__('ユーザ情報が保存できませんでした'));
			}
		}
		else
		{
			$this->request->data = $this->User->get($user_id);
			
			if($this->request->data)
				$username = $this->request->data['User']['username'];
		}

		$courses = $this->User->Course->find('list');
		$groups = $this->User->Group->find('list');
		
		$this->set(compact('courses', 'groups', 'username'));
	}

	/**
	 * ユーザの削除
	 *
	 * @param int $user_id 削除するユーザのID
	 */
	public function admin_delete($user_id = null)
	{
		if(Configure::read('demo_mode'))
			return;
		
		$this->User->id = $user_id;
		
		if(!$this->User->exists())
		{
			throw new NotFoundException(__('Invalid user'));
		}
		
		$this->request->allowMethod('post', 'delete');
		
		if($this->User->delete())
		{
			$this->Flash->success(__('ユーザが削除されました'));
		}
		else
		{
			$this->Flash->error(__('ユーザを削除できませんでした'));
		}
		
		return $this->redirect(['action' => 'index']);
	}

	/**
	 * ユーザの学習履歴のクリア
	 *
	 * @param int $user_id 学習履歴をクリアするユーザのID
	 */
	public function admin_clear($user_id)
	{
		$this->request->allowMethod('post', 'delete');
		$this->User->deleteUserRecords($user_id);
		$this->Flash->success(__('学習履歴を削除しました'));
		return $this->redirect(['action' => 'edit', $user_id]);
	}

	/**
	 * パスワード変更
	 */
	public function setting()
	{
		if($this->request->is(['post', 'put']))
		{
			if(Configure::read('demo_mode'))
				return;
			
			$this->request->data['User']['id'] = $this->readAuthUser('id');
			
			if($this->request->data['User']['new_password'] != $this->request->data['User']['new_password2'])
			{
				$this->Flash->error(__('入力された「パスワード」と「パスワード（確認用）」が一致しません'));
				return;
			}

			if($this->request->data['User']['new_password'] !== '')
			{
				$this->request->data['User']['password'] = $this->request->data['User']['new_password'];
				
				if($this->User->save($this->request->data))
				{
					$this->Flash->success(__('パスワードが保存されました'));
				}
				else
				{
					$this->Flash->error(__('パスワードが保存できませんでした'));
				}
			}
			else
			{
				$this->Flash->error(__('パスワードを入力して下さい'));
			}
		}
		else
		{
			$this->request->data = $this->User->get($this->readAuthUser('id'));
		}
	}

	/**
	 * パスワード変更
	 */
	public function admin_setting()
	{
		$this->setting();
	}

	/**
	 * ログイン
	 */
	public function admin_login()
	{
		$this->login();
	}

	/**
	 * ログアウト
	 */
	public function admin_logout()
	{
		$this->logout();
	}

	/**
	 * ユーザ情報のインポート
	 */
	public function admin_import()
	{
		if(Configure::read('demo_mode'))
			return;
		
		$group_count  = Configure::read('import_group_count');		// 所属グループの列数
		$course_count = Configure::read('import_course_count');		// 受講コースの列数
		
		//------------------------------//
		//	列番号の定義				//
		//------------------------------//
		define('COL_LOGINID',	0);
		define('COL_PASSWORD',	1);
		define('COL_NAME',		2);
		define('COL_ROLE',		3);
		define('COL_EMAIL',		4);
		define('COL_COMMENT',	5);
		define('COL_GROUP',		6);
		define('COL_COURSE',	6 + $group_count);
		
		$err_msg = '';
		
		if($this->request->is(['post', 'put']))
		{
			//------------------------------//
			//	CSVファイルの読み込み		//
			//------------------------------//
			// 制限時間を120秒に設定
			set_time_limit(120);
			
			$csvfile = $this->request->data['User']['csvfile'];
			
			// インポートファイルが指定されていない場合、エラーメッセージを表示
			if($csvfile['error'] != 0)
			{
				$this->Flash->error(__('インポートファイルが指定されていません'));
				$this->set(compact('err_msg'));
				return;
			}
			
			// CSVファイルの読み込み
			$csv = Utils::getCsvData($csvfile['tmp_name']);
			
			$i = 0;
			
			$ds = $this->User->getDataSource();
			$ds->begin();
			
			try
			{
				$is_error = false;
				
				$group_list  = $this->User->Group->find('list');	// 所属グループ
				$course_list = $this->User->Course->find('list');	// 受講コース
				
				// 1行ごとにデータを登録
				foreach($csv as $row)
				{
					$i++;
					
					if($i < 2)
						continue;
					
					if(count($row) < 5)
						continue;
					
					$is_new = false;
					
					//------------------------------//
					//	ユーザ情報の作成			//
					//------------------------------//
					$data = $this->User->find()
						->where(['User.username' => $row[COL_LOGINID]])
						->first();
					
					// 指定したログインIDのユーザが存在しない場合、新規追加とする
					if(!$data)
					{
						$data = [];
						$data['User'] = [];
						$this->User->create();
						$data['User']['created'] = date('Y-m-d H:i:s');
						$is_new = true;
					}
					
					// ユーザ名
					$data['User']['username'] = $row[COL_LOGINID];
					
					// パスワード
					if($row[COL_PASSWORD] == '')
					{
						unset($data['User']['password']);
					}
					else
					{
						$data['User']['password'] = $row[COL_PASSWORD];
					}
					
					$data['User']['name'] = $row[COL_NAME];											// 氏名
					$data['User']['role'] = Utils::getKeyByValue('user_role', $row[COL_ROLE]);		// 権限
					$data['User']['email'] = $row[COL_EMAIL];										// メールアドレス
					$data['User']['comment'] = Utils::issetOr($row[COL_COMMENT]);					// 備考
					
					//----------------------------------//
					//	所属グループ・受講コースの割当	//
					//----------------------------------//
					$data['Group']['Group'] = [];		// 所属グループの割当の初期化
					$data['Course']['Course'] = [];		// 受講コースの割当の初期化
					
					// 所属グループの割当
					for($n=0; $n < $group_count; $n++)
					{
						$title = Utils::issetOr($row[COL_GROUP + $n], '');
						
						if($title == '')
							continue;
						
						$group = Utils::getIdByTitle($group_list, $title);
						
						if($group == null)
							continue;
						
						$data['Group']['Group'][] = $group;
					}
					
					// 受講コースの割当
					for($n=0; $n < $course_count; $n++)
					{
						$title = Utils::issetOr($row[COL_COURSE + $n], '');
						
						if($title == '')
							continue;
						
						$course = Utils::getIdByTitle($course_list, $title);
						
						if($course == null)
							continue;
						
						$data['Course']['Course'][] = $course;
					}
					
					$data['User']['modified'] = date('Y-m-d H:i:s');
					
					//------------------------------//
					//	保存						//
					//------------------------------//
					if(!$this->User->save($data))
					{
						//debug($data);
						//debug($this->User->validationErrors);
						
						// 保存時にエラーが発生した場合、モデルからエラー情報を抽出
						$err_list = $this->User->validationErrors;
						
						foreach($err_list as $err)
						{
							$err_msg .= '<li>'.$i.'行目 : '.$err[0].'</li>';
						}
						
						$is_error = true;
					}
				}
				
				//------------------------------//
				//	エラー処理					//
				//------------------------------//
				if($is_error)
				{
					$ds->rollback();
					$this->Flash->error(__('インポートに失敗しました'));
				}
				else
				{
					$ds->commit();
					$this->Flash->success(__('インポートが完了しました'));
					return $this->redirect(['action' => 'index']);
				}
			}
			catch(Exception $e)
			{
				$ds->rollback();
				$this->Flash->error(__('インポートに失敗しました'));
			}
		}
		
		$this->set(compact('err_msg'));
	}

	/**
	 * ユーザ情報のエクスポート
	 */
	public function admin_export()
	{
		$group_count  = Configure::read('import_group_count');		// 所属グループの列数
		$course_count = Configure::read('import_course_count');		// 受講コースの列数
		
		$this->autoRender = false;
		Configure::write('debug', 0);

		//Content-Typeを指定
		$this->response->type('csv');
		
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="users_'.date('Ymd').'.csv"');
		
		$fp = fopen('php://output','w');
		
		//------------------------------//
		//	ヘッダー行の作成			//
		//------------------------------//
		$header = [
			__('ログインID'),
			__('パスワード'),
			__('氏名'),
			__('権限'),
			__('メールアドレス'),
			__('備考'),
		];
		
		for($n=0; $n < $group_count; $n++)
		{
			$header[] = __('グループ').($n+1);
		}
		
		for($n=0; $n < $course_count; $n++)
		{
			$header[] = __('コース').($n+1);
		}
		
		// ヘッダー行をCSV出力
		mb_convert_variables('SJIS-win', 'UTF-8', $header);
		fputcsv($fp, $header);
		
		//------------------------------//
		//	ユーザ情報の取得			//
		//------------------------------//
		
		// パフォーマンスの改善の為、一定件数に分割してデータを取得
		$limit      = 500;
		$user_count = $this->User->find()->count();	// ユーザ数を取得
		$page_size  = ceil($user_count / $limit);	// ページ数（ユーザ数 / ページ単位）
		
		// ページ単位でユーザを取得
		for($page=1; $page <= $page_size; $page++)
		{
			// ユーザ情報を取得
			$this->User->recursive = 1;
			$rows = $this->User->find()
				->limit($limit)
				->page($page)
				->all();
			
			foreach($rows as $row)
			{
				//------------------------------//
				//	出力するデータを作成		//
				//------------------------------//
				$groups  = [];
				$courses = [];
				
				for($n=0; $n < $group_count; $n++)
					$groups[] = '';
				
				for($n=0; $n < $course_count; $n++)
					$courses[] = '';
				
				$i = 0;
				
				// 所属グループのリストを作成
				foreach($row['Group'] as $group)
				{
					$groups[$i] = $group['title'];
					$i++;
				}
				
				$i = 0;
				
				// 受講コースのリストを作成
				foreach($row['Course'] as $course)
				{
					$courses[$i] = $course['title'];
					$i++;
				}
				
				// 出力行を作成
				$line = [
					$row['User']['username'],							// ユーザ名
					'',													// パスワード
					$row['User']['name'],								// 氏名
					Configure::read('user_role.'.$row['User']['role']),	// 権限
					$row['User']['email'],								// メールアドレス
					$row['User']['comment'],							// 備考
				];
				
				// 所属グループを出力
				for($n=0; $n < $group_count; $n++)
				{
					$line[] = $groups[$n];
				}
				
				// 受講コースを出力
				for($n=0; $n < $course_count; $n++)
				{
					$line[] = $courses[$n];
				}
				
				// CSV出力
				mb_convert_variables('SJIS-win', 'UTF-8', $line);
				fputcsv($fp, $line);
			}
		}
		
		fclose($fp);
	}
}
