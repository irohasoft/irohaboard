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
			'unlockedActions' => array('admin_order', 'admin_preview', 'admin_upload_image'),
		),
	);

	public function index($id, $user_id = null)
	{
		$id = intval($id);
		
		// コースの情報を取得
		$this->loadModel('Course');
		$course = $this->Course->find('first', array(
			'conditions' => array(
				'Course.id' => $id
			)
		));
		
		// ロールを取得
		$role = $this->Session->read('Auth.User.role');
		
		// 管理者かつ、学習履歴表示モードの場合、
		if(
			($role == 'admin')&&
			($this->action == 'admin_record')
		)
		{
			$contents = $this->Content->getContentRecord($user_id, $id);
		}
		else
		{
			// コースの閲覧権限の確認
			if(! $this->Course->hasRight($this->Session->read('Auth.User.id'), $id))
			{
				throw new NotFoundException(__('Invalid access'));
			}
			
			$contents = $this->Content->getContentRecord($this->Session->read('Auth.User.id'), $id);
		}
		
		$this->set(compact('course', 'contents'));
	}

	public function view($id)
	{
		$id = intval($id);
		
		if (! $this->Content->exists($id))
		{
			throw new NotFoundException(__('Invalid content'));
		}

		// ヘッダー、フッターを非表示
		$this->layout = '';

		$options = array(
			'conditions' => array(
				'Content.' . $this->Content->primaryKey => $id
			)
		);
		
		$content = $this->Content->find('first', $options);
		
		// コンテンツの閲覧権限の確認
		$this->loadModel('Course');
		
		if(! $this->Course->hasRight($this->Session->read('Auth.User.id'), $content['Content']['course_id']))
		{
			throw new NotFoundException(__('Invalid access'));
		}
		
		$this->set(compact('content'));
	}

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

	public function preview()
	{
		// ヘッダー、フッターを非表示
		$this->layout = '';
		$this->set('content', $this->Session->read('Iroha.preview_content'));
		$this->render('view');
	}

	public function admin_delete($id)
	{
		if(Configure::read('demo_mode'))
			return;
		
		$this->Content->id = $id;
		if (! $this->Content->exists())
		{
			throw new NotFoundException(__('Invalid content'));
		}
		
		// コンテンツ情報を取得
		$content = $this->Content->find('first', array(
			'conditions' => array(
				'Content.id' => $id
			)
		));
		
		$this->request->allowMethod('post', 'delete');
		
		if ($this->Content->delete())
		{
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

	public function admin_index($id)
	{
		$id = intval($id);
		
		$this->Content->recursive = 0;

		// コースの情報を取得
		$course = $this->Content->Course->find('first', array(
			'conditions' => array(
				'Course.id' => $id
			)
		));

		$contents = $this->Content->find('all', array(
			'conditions' => array('Content.course_id' => $id),
			'order' => array('Content.sort_no' => 'asc')
		));

		// コース情報を取得
		$course = $this->Content->Course->find('first', array(
			'conditions' => array(
				'Course.id' => $id
			)
		));
		
		$this->set(compact('contents', 'course'));
	}

	public function admin_add($course_id)
	{
		$this->admin_edit($course_id);
		$this->render('admin_edit');
	}

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
				$this->request->data['Content']['user_id']   = $this->Session->read('Auth.User.id');
				$this->request->data['Content']['course_id'] = $course_id;
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
		
		$this->set(compact('course'));
	}

	public function admin_upload($file_type)
	{
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
		
		$fileUpload->setExtension($upload_extensions);
		$fileUpload->setMaxSize($upload_maxsize);
		
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if(Configure::read('demo_mode'))
				return;
			
			$fileUpload->readFile( $this->request->data['Content']['file'] );						//	ファイルの読み込み

			$new_name = date("YmdHis").$fileUpload->getExtension( $fileUpload->get_file_name() );	//	ファイル名：YYYYMMDDHHNNSS形式＋"既存の拡張子"

			$file_name = WWW_ROOT.DS."uploads".DS.$new_name;										//	ファイルのパス
			$file_url = $this->webroot.'uploads/'.$new_name;										//	ファイルのURL

			$result = $fileUpload->saveFile( $file_name );											//	ファイルの保存

			if($result)																				//	結果によってメッセージを設定
			{
				$this->Flash->success('ファイルのアップロードが完了いたしました');
				$mode = 'complete';
			}
			else
			{
				$this->Flash->error('ファイルのアップロードに失敗しました');
				$mode = 'error';
			}
		}

		$this->set('mode',					$mode);
		$this->set('file_url',				$file_url);
		$this->set('upload_extensions',		join(', ', $upload_extensions));
		$this->set('upload_maxsize',		$upload_maxsize);
	}
	
	// リッチテキストエディタ(Summernote) の画像アップロード機能との連携用
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

			$file_name = WWW_ROOT.DS."uploads".DS.$new_name;										//	ファイル格納フォルダ
			$file_url = $this->webroot.'uploads/'.$new_name;

			$result = $fileUpload->saveFile( $file_name );											//	ファイルの保存
			
			//debug($result);
			$response = $result ? array($file_url) : array(false);
			echo json_encode($response);
		}
	}
	
	public function admin_order()
	{
		$this->autoRender = FALSE;
		if($this->request->is('ajax'))
		{
			$this->Content->setOrder($this->data['id_list']);
			return "OK";
		}
	}

	public function admin_record($course_id, $user_id)
	{
		$this->index($course_id, $user_id);
		$this->render('index');
	}
}
