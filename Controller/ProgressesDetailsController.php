<?php
/*
 * Ripple  Project
 *
 * @author        Enfu Guo
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
*/

App::uses('AppController',   'Controller');
App::uses('User',            'User');

class ProgressesDetailsController extends AppController{
  
	// 成果発表一覧の表示
  public function admin_index($progress_id){
		$this->loadModel('Progress');
		$this->loadModel('User');

		$progress_id = intval($progress_id);

    $progress_info = $this->Progress->find('first',array(
			'conditions' => array(
				'Progress.id' => $progress_id
			),
      'order' => array('Progress.id' => 'desc')
    ));

		$user_list = $this->User->find('list',array(
			'conditions' => array(
				'User.role' => 'user'
			),
			'order' => 'User.id asc'
		));


		$this->log($progress_info);

		$progress_details = $progress_info['ProgressesDetail'];

    $this->set(compact('progress_info','progress_details','user_list'));
  }

  // 成果発表の追加
  public function admin_add($progress_id){
    $this->admin_edit($progress_id);
		$this->render('admin_edit');
  }

  // 成果発表の修正
  public function admin_edit($progress_id , $progress_detail_id = null){
		$this->loadModel('User');
		$this->loadModel('Progress');

		$progress_info = $this->Progress->find('first',array(
			'conditions' => array(
				'Progress.id' => $progress_id
			),
      'order' => array('Progress.id' => 'desc')
    ));


		$user_list = $this->User->find('list',array(
			'conditions' => array(
				'User.role' => 'user'
			),
			'order' => 'User.id asc'
		));
		$user_name_list = $this->User->find('list',array(
			'conditions' => array(
				'User.role' => 'user'
			),
			'fields' => array('User.name'),
			'order' => 'User.id asc'
		));
		// $this->log($user_name_list);
		$tmp_data = array_values(array_unique($user_name_list));

		$user_name_list_json = json_encode($tmp_data);
		$this->log($user_name_list_json);
		$this->set(compact('user_name_list_json','progress_id','progress_info'));

    if ($this->action == 'edit' && ! $this->ProgressesDetail->exists($progress_detail_id))
		{
			throw new NotFoundException(__('Invalid Progress Detail'));
    }

    if ($this->request->is(array(
			'post',
			'put'
		)))
		{
			$request_data = $this->request->data;
			$user_name = $request_data['ProgressesDetail']['user_name'];
			$user_id = array_search($user_name, $user_list);
			$request_data['ProgressesDetail']['user_id'] = $user_id;
      if($this->ProgressesDetail->save($request_data)){
        $this->Flash->success(__('成果発表内容が保存されました'));
				return $this->redirect(array(
					'action' => 'index', $progress_id
				));
      }else{
				$this->Flash->error(__('The progress could not be saved. Please, try again.'));
			}
    }else{
      $options = array(
				'conditions' => array(
					'ProgressesDetail.' . $this->ProgressesDetail->primaryKey => $progress_detail_id
				)
			);
			$this->request->data = $this->ProgressesDetail->find('first', $options);
			$user_id = $this->request->data['ProgressesDetail']['user_id'];
			$user_name = $user_list[$user_id];
			$this->set(compact('user_name'));
    }

  }

  /**
	 * 成果発表の削除
	 * @param int $course_id コースID
	 */
	public function admin_delete($progress_id = null)
	{

		$this->Progress->id = $progress_id;
		if (! $this->Progress->exists())
		{
			throw new NotFoundException(__('Invalid progress'));
		}

		$this->request->allowMethod('post', 'delete');
		$this->Progress->deleteProgress($progress_id);
		$this->Flash->success(__('成果発表が削除されました'));

		return $this->redirect(array(
				'action' => 'index'
		));
	}

	/**
	 * ファイル（配布資料、動画）のアップロード
	 *
	 * @param int $file_type ファイルの種類
	 */
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
			
			case 'slide' :
				$upload_extensions = (array)Configure::read('upload_slide_extensions');
				$upload_maxsize = Configure::read('upload_slide_maxsize');
				break;
			default :
				throw new NotFoundException(__('Invalid access'));
		}

		$fileUpload->setExtension($upload_extensions);
		$fileUpload->setMaxSize($upload_maxsize);

		$original_file_name = '';

		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			$this->log($this->request->data);

			$fileUpload->readFile( $this->request->data['Content']['file'] );						//	ファイルの読み込み

			$original_file_name = $this->request->data['Content']['file']['name'];

			$new_name = date("YmdHis").$fileUpload->getExtension( $fileUpload->get_file_name() );	//	ファイル名：YYYYMMDDHHNNSS形式＋"既存の拡張子"

			if($file_type == 'slide'){
				$file_name = WWW_ROOT."slide".DS.$new_name;													//	ファイルのパス
				$file_url  = $this->webroot.'slide/'.$new_name;											//	ファイルのURL
				
			}else{
				$file_name = WWW_ROOT."uploads".DS.$new_name;												//	ファイルのパス
				$file_url = $this->webroot.'uploads/'.$new_name;										//	ファイルのURL
			}

			$result = $fileUpload->saveFile( $file_name );											//	ファイルの保存
			
			if($result)																				//	結果によってメッセージを設定
			{
				// うまくいかない時は php.ini を確認
				if($file_type == 'slide'){
					$cmd = Configure::read('unzip_path').' -o '.$file_name.' -d '.WWW_ROOT."slide".DS;
					$this->log(shell_exec($cmd));
				}
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
		$this->set('file_name',				$original_file_name);
		$this->set('upload_extensions',		join(', ', $upload_extensions));
		$this->set('upload_maxsize',		$upload_maxsize);
	}
}
?>