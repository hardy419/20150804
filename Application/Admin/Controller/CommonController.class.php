<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
class CommonController extends Controller {
      public function login(){
          if(!$this->checkStatus()){
          $this->display();
          }else{
              $this->redirect(U('Index/index'));
          }
      }
      public function password(){
          if(!$this->checkStatus()){
              $this->error('請登錄');
          }else{
              $this->assign ('user', I('get.user',''));
              $this->display('changepassword');
          }
      }
      public function changePassword(){
          if(!$this->checkStatus()){
              $this->error('請登錄');
          }else{
              $map=array();
              $map['status']=1;
              if ('' === I('post.user', '')) {
                $map['account']=$_SESSION['loginUserName'];
              }
              else {
                $map['account']=I('post.user');
              }

              $userdb = M('user');
              $cpwd = $userdb->where($map)->getField('password');
              if (md5($_POST['cpwd']) == $cpwd) {
                  $userdb->where($map)->setField('password', md5($_POST['pwd']));
                  $this->success('密碼已修改');
              }
              else {
                  $this->error('當前密碼錯誤');
              }
          }
      }
      public  function checkStatus(){
      	 $loginMarked=md5(C('AUTH_TOKEN'));
      	 
      	 if(!$_SESSION[$loginMarked]){
      	 	return false;
      	 }
      	 $cookie=explode("_",cookie($loginMarked));
      	 $outtime=C('LOGIN_TIMEOUT');
      	 
      	 if(intval(time())>($outtime+end($cookie)))return false;
      	 if($cookie[0]!=$loginMarked)return false;
      	 cookie($loginMarked,$cookie[0].'_'.time(),0,'/');
      	 return true;
      }
      public function checkPrivilege() {
        $userdb = M('user');
        $loginMarked=md5(C('AUTH_TOKEN'));
        $t = $userdb->where(array ('id'=>$_SESSION[$loginMarked]))->getField ('acct_name');
        $hr = explode('&', $_SERVER['QUERY_STRING']);
        $type = I('request.type', '');
        if ('Manager' == $t && (in_array('c=List', $hr) && 'banner' != $type && 'news' != $type && 'ads' != $type && 'category' != $type && 'project' != $type)) {
            return false;
        }
        else if ('Operator' == $t && (in_array('c=List', $hr) && 'project' != $type)) {
            return false;
        }
        return true;
      }
      public function logout(){
      	  $loginMarked=md5(C('AUTH_TOKEN'));
          if(isset($_SESSION[$loginMarked])){
              unset($_SESSION[$loginMarked]);
              $this->success('Logout Success',U('Common/login'));
          }else{
             
              $this->success('Logout Success',U('Common/login'));
          }
      }
      
     protected function check_verify($code, $id = ''){   
      	$verify = new \Think\Verify();  
      	  return $verify->check($code, $id);
      }
      public function checkLogin(){
          $verify=$_POST['seccode'];
          if(empty($_POST['account'])){
              $this->error('請輸入用戶名！');
          }else if(empty($_POST['password'])){
              $this->error('請輸入密碼！');
          }else if(empty($verify)){
              $this->error('請輸入驗證碼！');
          }else if(!$this->check_verify($verify)){
              $this->error('驗證碼輸入有誤！');
          }
          
          $map=array();
          $map['status']=1;
          $map['account']=$_POST['account'];
          $authInfo=M('user')->where($map)->find();
          
          if($authInfo==false){
              $this->error('用戶名或者密碼不正確！');
          }else{
          
             if($authInfo['password']!=md5($_POST['password'])){
                $this->error('用戶名或者密碼不正確！');
             }
            $loginMarked=md5(C('AUTH_TOKEN'));
            $_SESSION[$loginMarked]=$authInfo['id'];
            $_SESSION['email']	=	$authInfo['email'];
            $_SESSION['loginUserName']		=	$authInfo['account'];
            $_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
            $_SESSION['login_count']	=	$authInfo['login_count'];
            if($authInfo['account']=='admin') {
                $_SESSION['administrator']		=	true;
            }
            cookie($loginMarked,$loginMarked.'_'.time(),0,'/');
            //保存登录信息
            $DB=M('user');
            $data=array();
            $data['id']=$authInfo['id'];
            //$data['last_login_ip']=get_client_ip();
            $data['last_login_time']=time();
            $data['login_count']=array('exp','login_count+1');
            $DB->save($data);
            
            $this->success('Login Success',U('Index/index'));
          }          
      }
      public function verify(){
      	  $config =    array(   
      	  		  'imageW'=>100,    // 验证码字体大小   
      	  		  'imageH'=>30,
      	  		  'length'=>4,     // 验证码位数   \
      	  		  'useCurve'=>false,
      	  		  'fontSize'=>14,
      	  		  'useNoise'    =>false, // 关闭验证码杂点
      	  		  );
          $Verify = new \Think\Verify($config);
          ob_end_clean();
          $Verify->entry();
      }  
}
?>