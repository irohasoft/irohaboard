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
App::uses('Course', 'Course');
App::uses('Record', 'Record');

/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class ContentsController extends AppController
{

	public $components = array(
		'Security' => array(
			'validatePost' => false,
			'csrfUseOnce' => false,
			'unlockedActions' => array('admin_order', 'admin_preview', 'admin_upload_image'),
		),
	);

	/**
	 * 学習コンテンツ一覧を表示
	 * @param int $course_id コースID
	 * @param int $user_id 学習履歴を表示するユーザのID
	 */
	public function index($course_id, $user_id = null)
	{
		$course_id = intval($course_id);
		
		// コースの情報を取得
		$this->loadModel('Course');
		$course = $this->Course->find('first', array(
			'conditions' => array(
				'Course.id' => $course_id
			)
		));
		
		// ロールを取得
		$role = $this->Auth->user('role');
		
		// 管理者かつ、学習履歴表示モードの場合、
		if($this->action == 'admin_record')
		{
			$contents = $this->Content->getContentRecord($user_id, $course_id, $role);
		}
		else
		{
			// コースの閲覧権限の確認
			if(! $this->Course->hasRight($this->Auth->user('id'), $course_id))
			{
				throw new NotFoundException(__('Invalid access'));
			}
			
			$contents = $this->Content->getContentRecord($this->Auth->user('id'), $course_id, $role);
		}
		
		$this->set(compact('course', 'contents'));
	}

	/**
	 * コンテンツの表示
	 * @param int $content_id 表示するコンテンツのID
	 */
	public function view($content_id)
	{
		$content_id = intval($content_id);
		
		if (! $this->Content->exists($content_id))
		{
			throw new NotFoundException(__('Invalid content'));
		}

		// ヘッダー、フッターを非表示
		$this->layout = '';

		$options = array(
			'conditions' => array(
				'Content.' . $this->Content->primaryKey => $content_id
			)
		);
		
		$content = $this->Content->find('first', $options);
		
		// コンテンツの閲覧権限の確認
		$this->loadModel('Course');
		
		if(! $this->Course->hasRight($this->Auth->user('id'), $content['Content']['course_id']))
		{
			throw new NotFoundException(__('Invalid access'));
		}
		
		$this->set(compact('content'));
	}

	/**
	 * プレビュー用に入力内容をセッションに保存
	 */
	public function admin_preview()
	{
		$this->autoRender = FALSE;
		if($this->request->is('ajax'))
		{
			$data = array(
				'Content' => array(
					'id'     => 0,
					'title'  => $this->data['content_title'],
					'kind'   => $this->data['content_kind'],
					'url'    => $this->data['content_url'],
					'body'  => $this->data['content_body']
				),
				'Course' => array(
					'id'     => 0,
				)
			);
			
			$this->Session->write("Iroha.preview_content", $data);
		}
	}

	/**
	 * セッションに保存された情報を元にプレビュー
	 */
	public function preview()
	{
		// ヘッダー、フッターを非表示
		$this->layout = '';
		$this->set('content', $this->Session->read('Iroha.preview_content'));
		$this->render('view');
	}



	/**
	 * コンテンツの削除
	 *
	 * @param int $content_id 削除するコンテンツのID
	 */
	public function admin_delete($content_id)
	{
		if(Configure::read('demo_mode'))
			return;
		
		$this->Content->id = $content_id;
		if (! $this->Content->exists())
		{
			throw new NotFoundException(__('Invalid content'));
		}
		
		// コンテンツ情報を取得
		$content = $this->Content->find('first', array(
			'conditions' => array(
				'Content.id' => $content_id
			)
		));
		
		$this->request->allowMethod('post', 'delete');
		
		if ($this->Content->delete())
		{
			// コンテンツに紐づくテスト問題も削除
			$this->LoadModel('ContentsQuestion');
			$this->ContentsQuestion->deleteAll(array('ContentsQuestion.content_id' => $content_id), false);
			$this->request->allowMethod('post', 'delete');
			$this->Flash->success(__('コンテンツが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The content could not be deleted. Please, try again.'));
		}
		
		return $this->redirect(array(
			'action' => 'index/' . $content['Course']['id']
		));
	}

	/**
	 * コンテンツ一覧の表示
	 *
	 * @param int $course_id コースID
	 */
	public function admin_index($course_id)
	{
		$course_id = intval($course_id);
		
		$this->Content->recursive = 0;

		// コースの情報を取得
		$course = $this->Content->Course->find('first', array(
			'conditions' => array(
				'Course.id' => $course_id
			)
		));

		$contents = $this->Content->find('all', array(
			'conditions' => array('Content.course_id' => $course_id),
			'order' => array('Content.sort_no' => 'asc')
		));

		// コース情報を取得
		$course = $this->Content->Course->find('first', array(
			'conditions' => array(
				'Course.id' => $course_id
			)
		));
		
		$this->set(compact('contents', 'course'));
	}

	/**
	 * コンテンツの追加
	 *
	 * @param int $course_id コースID
	 */
	public function admin_add($course_id)
	{
		$this->admin_edit($course_id);
		$this->render('admin_edit');
	}

	/**
	 * コンテンツの編集
	 *
	 * @param int $course_id 所属するコースのID
	 * @param int $content_id 編集するコンテンツのID (指定しない場合、追加)
	 */
	public function admin_edit($course_id, $content_id = null)
	{
		$course_id = intval($course_id);
		
		if ($this->action == 'admin_edit' && ! $this->Content->exists($content_id))
		{
			throw new NotFoundException(__('Invalid content'));
		}
		
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if(Configure::read('demo_mode'))
				return;
			
			// 新規追加の場合、コンテンツの作成者と所属コースを指定
			if($this->action == 'admin_add')
			{
				$this->request->data['Content']['user_id']   = $this->Auth->user('id');
				$this->request->data['Content']['course_id'] = $course_id;
				$this->request->data['Content']['sort_no']   = $this->Content->getNextSortNo($course_id);
			}
			
			if ($this->Content->save($this->request->data))
			{
				$this->Flash->success(__('コンテンツが保存されました'));
				return $this->redirect( array(
					'action' => 'index/' . $course_id
				));
			}
			else
			{
				$this->Flash->error(__('The content could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
				'conditions' => array(
					'Content.' . $this->Content->primaryKey => $content_id
				)
			);
			$this->request->data = $this->Content->find('first', $options);
		}
		
		// コース情報を取得
		$course = $this->Content->Course->find('first', array(
			'conditions' => array(
				'Course.id' => $course_id
			)
		));
		
		$courses = $this->Content->Course->find('list');
		$this->set(compact('course', 'courses'));
	}

	/**
	 * ファイル（配布資料、動画）のアップロード
	 *
	 * @param int $file_type ファイルの種類
	 */
	public function admin_upload($file_type)
	{
		header("X-Frame-Options: SAMEORIGIN");
		
		//$this->layout = "";
		App::import ( "Vendor", "FileUpload" );

		$fileUpload = new FileUpload();

		$mode = '';
		$file_url = '';
		
		// ファイルの種類によって、アップロード可能な拡張子とファイルサイズを指定
		switch ($file_type)
		{
			case 'file' :
				$upload_extensions = (array)Configure::read('upload_extensions');
				$upload_maxsize = Configure::read('upload_maxsize');
				break;
			case 'image' :
				$upload_extensions = (array)Configure::read('upload_image_extensions');
				$upload_maxsize = Configure::read('upload_image_maxsize');
				break;
			case 'movie' :
				$upload_extensions = (array)Configure::read('upload_movie_extensions');
				$upload_maxsize = Configure::read('upload_movie_maxsize');
				break;
			default :
				throw new NotFoundException(__('Invalid access'));
		}
		
		// php.ini の upload_max_filesize, post_max_size の値を確認（互換性維持のためメソッドが存在する場合のみ）
		if(method_exists($fileUpload, 'getBytes'))
		{
			$upload_max_filesize = $fileUpload->getBytes(ini_get('upload_max_filesize'));
			$post_max_size		 = $fileUpload->getBytes(ini_get('post_max_size'));
			
			// upload_max_filesize が設定サイズより小さい場合、upload_max_filesize を優先する
			if($upload_max_filesize < $upload_maxsize)
				$upload_maxsize	= $upload_max_filesize;
			
			// post_max_size が設定サイズより小さい場合、post_max_size を優先する
			if($post_max_size < $upload_maxsize)
				$upload_maxsize	= $post_max_size;
		}
		
		$fileUpload->setExtension($upload_extensions);
		$fileUpload->setMaxSize($upload_maxsize);
		
		$original_file_name = '';
		
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if(Configure::read('demo_mode'))
				return;
			
			//debug($this->request->data);
			// ファイルの読み込み
			$fileUpload->readFile( $this->request->data['Content']['file'] );

			$error_code = 0;
			
			// エラーチェック（互換性維持のためメソッドが存在する場合のみ）
			if(method_exists($fileUpload, 'checkFile'))
				$error_code = $fileUpload->checkFile();
			
			if($error_code > 0)
			{
				$mode = 'error';
				
				// 拡張子エラー
				if($error_code == 1001)
					$this->Flash->error('アップロードされたファイルの形式は許可されていません');
				
				// ファイルサイズエラー
				if(($error_code == 1002)||($error_code == 1003))
				{
					$size = $this->request->data['Content']['file']['size'];
					$this->Flash->error('アップデートされたファイルサイズ（'.$size.'）は許可されていません');
				}
			}
			else
			{
				$original_file_name = $this->request->data['Content']['file']['name'];

				//	ファイル名：YYYYMMDDHHNNSS形式＋"既存の拡張子"
				$new_name = date("YmdHis").$fileUpload->getExtension( $fileUpload->get_file_name() );

				$file_name = WWW_ROOT."uploads".DS.$new_name;										//	ファイルのパス
				$file_url = $this->webroot.'uploads/'.$new_name;									//	ファイルのURL

				$result = $fileUpload->saveFile( $file_name );										//	ファイルの保存

				if($result)																			//	結果によってメッセージを設定
				{
					//$this->Flash->success('ファイルのアップロードが完了いたしました');
					$mode = 'complete';
				}
				else
				{
					$this->Flash->error('ファイルのアップロードに失敗しました');
					$mode = 'error';
				}
			}
		}

		$this->set('mode',					$mode);
		$this->set('file_url',				$file_url);
		$this->set('file_name',				$original_file_name);
		$this->set('upload_extensions',		join(', ', $upload_extensions));
		$this->set('upload_maxsize',		$upload_maxsize);
	}
	
	/**
	 * リッチテキストエディタ(Summernote) からPOSTされた画像を保存
	 *
	 * @return string アップロードした画像のURL(JSON形式)
	 */
	public function admin_upload_image()
	{
		$this->autoRender = FALSE;
		
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			App::import ( "Vendor", "FileUpload" );
			$fileUpload = new FileUpload();
			
			$upload_extensions = (array)Configure::read('upload_image_extensions');
			$upload_maxsize = Configure::read('upload_image_maxsize');
			
			$fileUpload->setExtension($upload_extensions);
			$fileUpload->setMaxSize($upload_maxsize);
			//debug($this->request->params['form']['file']);
			
			$fileUpload->readFile( $this->request->params['form']['file'] );						//	ファイルの読み込み
			
			$new_name = date("YmdHis").$fileUpload->getExtension( $fileUpload->get_file_name() );	//	ファイル名：YYYYMMDDHHNNSS形式＋"既存の拡張子"

			$file_name = WWW_ROOT."uploads".DS.$new_name;											//	ファイルのパス
			$file_url = $this->webroot.'uploads/'.$new_name;										//	ファイルのURL

			$result = $fileUpload->saveFile( $file_name );											//	ファイルの保存
			
			//debug($result);
			$response = $result ? array($file_url) : array(false);
			echo json_encode($response);
		}
	}
	
	/**
	 * Ajax によるコンテンツの並び替え
	 *
	 * @return string 実行結果
	 */
	public function admin_order()
	{
		$this->autoRender = FALSE;
		if($this->request->is('ajax'))
		{
			$this->Content->setOrder($this->data['id_list']);
			return "OK";
		}
	}

	/**
	 * 学習履歴の表示
     * @param int $course_id
     * @param int $user_id
	 */
	public function admin_record($course_id, $user_id)
	{
		$this->index($course_id, $user_id);
		$this->render('index');
	}

	/**
	 * コンテンツのコピー
     * @param int $course_id コピー先のコースのID
     * @param int $content_id コピーするコンテンツのID
	 */
	public function admin_copy($course_id, $content_id)
	{
		$options = array(
			'conditions' => array(
				'Content.' . $this->Content->primaryKey => $content_id
			)
		);
		
		// コンテンツのコピー
		$data = $this->Content->find('first', $options);
		$row  = $this->Content->find('first', array("fields" => "MAX(Content.id) as max_id"));
		$new_content_id = $row[0]['max_id'] + 1;
		
		$data['Content']['id'] = $new_content_id;
		$data['Content']['created'] = null;
		$data['Content']['modified'] = null;
		$data['Content']['status'] = 0;
		$data['Content']['title'] .= 'の複製';
		
		$this->Content->save($data);
		
		// テスト問題のコピー
		$this->LoadModel('ContentsQuestion');
		$contentsQuestions = $this->ContentsQuestion->find('all', array(
			'conditions' => array(
				'content_id' => $content_id
			),
			'order' => array('ContentsQuestion.sort_no' => 'asc')
		));
		
		$sort_no = 1;
		foreach ($contentsQuestions as $contentsQuestion)
		{
			$row = $this->ContentsQuestion->find('first', array("fields" => "MAX(ContentsQuestion.id) as max_id"));
			$new_question_id = $row[0]['max_id'] + 1;
			
			$contentsQuestion['ContentsQuestion']['id']			= null;
			$contentsQuestion['ContentsQuestion']['created']	= null;
			$contentsQuestion['ContentsQuestion']['modified']	= null;
			$contentsQuestion['ContentsQuestion']['content_id']	= $new_content_id;
			$contentsQuestion['ContentsQuestion']['sort_no']	= $sort_no;
			
			$this->ContentsQuestion->validate = null;
			
			$this->ContentsQuestion->create($contentsQuestion);
			$this->ContentsQuestion->save();
			
			$sort_no++;
		}
		
		return $this->redirect(array(
			'action' => 'index',
			$course_id
		));
	}
}
