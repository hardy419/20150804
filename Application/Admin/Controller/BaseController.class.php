<?php 
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {

	protected $lang;

	public function __construct(){
		parent::__construct();
		$common=new CommonController();
		
		if(!$common->checkStatus()){
			$this->redirect('Common/login');
		} 

		if(!$common->checkPrivilege()){
			$this->error('你所在的用戶組沒有此權限');
		} 

		$this->lang = I('cookie.lang', 'zh');
		$this->assign ('lang', $this->lang);
	}
	public function _upload($module,$cpath,$thumb,$width,$height){
		$module=$module=""?'file':$module;//δ֪ģ�齫����file�ļ���
		$path='/'.$module.'/';
		if (!is_dir($path))	mkdir($path,0755,true);
	
		
		$upload = new \Think\Upload();
		$upload->maxSize=C(ATTACHSIZE);
		$upload->exts=explode(',',C(ATTACHEXT));
		$upload->savePath=$path;
		$upload->autoSub  = true;
		$upload->subName  =$cpath;
		$upload->saveName = array('uniqid','');
		$info=$upload->upload();
		if(!$info){
			return $this->error($upload->getError());
		}else{
			return $info;
		}
	}
	
}
?>