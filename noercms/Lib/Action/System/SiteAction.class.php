<?php
class SiteAction extends BackAction{
	public function _initialize() {
        parent::_initialize();  //RBAC 验证接口初始化
    }
	
	public function index(){
		$groups=M('User_group')->order('id ASC')->select();
		$this->assign('groups',$groups);
		$this->display();
	}
	public function email(){
		
		$this->display();
	}	
	public function alipay(){
		$this->display();
	}
	public function safe(){
		$this->display();
	}
	public function upfile(){
		$this->display();
	}
	public function insert(){
		$file=$this->_post('files');
		unset($_POST['files']);
		unset($_POST[C('TOKEN_NAME')]);
		
		if($this->update_config($_POST,CONF_PATH.$file)){
			$this->success('操作成功',U('Site/index',array('pid'=>6,'level'=>3)));
		}else{
			$this->success('操作失败',U('Site/index',array('pid'=>6,'level'=>3)));
		}
	}
	
	public function tenpay(){
		$file=$this->_post('tfiles');
		unset($_POST['tfiles']);
		//unset($_POST[C('TOKEN_NAME')]);

		if($this->update_config($_POST,CONF_PATH.$file)){
			$this->success('操作成功',U('Site/index',array('pid'=>6,'level'=>3)));
		}else{
			$this->success('操作失败',U('Site/index',array('pid'=>6,'level'=>3)));
		}
	}

	private function update_config($config, $config_file = '') {
		!is_file($config_file) && $config_file = CONF_PATH . 'web.php';
		if (is_writable($config_file)) {
			//$config = require $config_file;
			//$config = array_merge($config, $new_config);
			//dump($config);EXIT;
			file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
			@unlink(RUNTIME_FILE);
			return true;
		} else {
			die("目录".$config_file."没用写权限，请联系您的服务器提供商检查noercmsdata是不是有写权限。");
			return false;
		}
	}
}