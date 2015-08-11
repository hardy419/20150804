<?php
namespace Admin\Controller;
use Think\Controller;
class ListController extends BaseController{
    public function index(){
        $type=I('get.type');
        $pid=I('get.pid');
        if(!in_array($type,array('banner','catagory','page','project','projectphoto'))) $this->error('',U('Index/index'));
        $tname=$type;
        $map=array();
        if(!empty($pid)&&is_numeric($pid)){
            $map['pid']=$pid;
        }else{
            $pid=0;
        }

        $sort = 'desc';

        if('project' == $type) {
            $this->_select($tname,$map,'id',$sort,false,'SELECT a.id,a.title,a.date,b.name as catagory FROM gc_project as a LEFT JOIN gc_catagory as b ON a.cid=b.id');
        }
        else {
            $this->_select($tname,$map,'id',$sort);
        }
        $this->assign('type',$type);
        $this->assign('pid',$pid);
        $current=cookie('current');
        cookie('current',null);
        $this->assign('current',$current);
        cookie("__CURRENTURL__",__SELF__);

        if('banner' != $type && 'project' != $type) {
            $this->display();
        }
        else {
            $this->display('view'.$type);
        }
    }

    public function admin() {
        $type=I('get.type', 'user');
        $pid=I('get.pid', null);
        $id=I('get.id', null);
        if(!in_array($type,array('user','page','property','schooltype','education','news','country','city','suburb','ebuy','testimonial'))) $this->error('',U('Index/index'));
        if ('user' == $type) {
            $tname=$type;
        }
        else {
            $tname=$type.'_'.$this->lang;
        }

        // Sort
        $order = I('request.o','id');
        $sort = I('request.s','DESC');
        $this->assign ('order', $order);
        $this->assign ('sort', $sort);

        // Search
        $keyword = I('request.keyword',null);
        $datefrom = I('request.datefrom',null);
        $dateto = I('request.dateto',null);
        $this->assign ('keyword', $keyword);
        $this->assign ('datefrom', $datefrom);
        $this->assign ('dateto', $dateto);

        // page
        $page = I('request.page',1);
        $this->assign ('page', $page);

        $map = array ();
        
        if('property' == $type) {
            if (null != $keyword) {
                $map['name'] = array ('like', "%{$keyword}%");
            }
            if (null != $datefrom) {
                $map['addtime'] = array ('egt', $datefrom);
            }
            if (null != $dateto) {
                if (isset ($map['addtime'])) {
                    $map['addtime'] = array ('between', array($datefrom, $dateto));
                }
                else {
                    $map['addtime'] = array ('elt', $dateto);
                }
            }

            $country_list = array();
            $city_list = array();
            $suburb_list = array();
            $school_list = array();
            $country_rows = M('country_'.$this->lang)->select();
            $city_rows = M('city_'.$this->lang)->select();
            $suburb_rows = M('suburb_'.$this->lang)->select();
            $school_rows = M('education_'.$this->lang)->select();
            foreach ($country_rows as $row) {
                $country_list[$row['id']]['name'] = $row['name'];
            }
            foreach ($city_rows as $row) {
                $city_list[$row['id']]['name'] = $row['name'];
                $city_list[$row['id']]['country_id'] = $row['country_id'];
            }
            foreach ($suburb_rows as $row) {
                $suburb_list[$row['id']]['name'] = $row['name'];
                $suburb_list[$row['id']]['city_id'] = $row['city_id'];
            }
            foreach ($school_rows as $row) {
                $school_list[$row['id']]['name'] = $row['name'];
            }
            $this->assign('country_list', $country_list);
            $this->assign('city_list', $city_list);
            $this->assign('suburb_list', $suburb_list);
            $this->assign('school_list', $school_list);
        }
        else if('city' == $type) {
            $country_list = array();
            $country_rows = M('country_'.$this->lang)->select();
            foreach ($country_rows as $row) {
                $country_list[$row['id']]['name'] = $row['name'];
            }
            $this->assign('country_list', $country_list);
        }
        else if('suburb' == $type) {
            $city_list = array();
            $city_rows = M('city_'.$this->lang)->select();
            foreach ($city_rows as $row) {
                $city_list[$row['id']]['name'] = $row['name'];
            }
            $this->assign('city_list', $city_list);
        }
        else if('education' == $type) {
            $country_list = array();
            $city_list = array();
            $suburb_list = array();
            $schooltype_list = array();
            $country_rows = M('country_'.$this->lang)->select();
            $city_rows = M('city_'.$this->lang)->select();
            $suburb_rows = M('suburb_'.$this->lang)->select();
            $schooltype_rows = M('schooltype_'.$this->lang)->select();
            foreach ($country_rows as $row) {
                $country_list[$row['id']]['name'] = $row['name'];
            }
            foreach ($city_rows as $row) {
                $city_list[$row['id']]['name'] = $row['name'];
                $city_list[$row['id']]['country_id'] = $row['country_id'];
            }
            foreach ($suburb_rows as $row) {
                $suburb_list[$row['id']]['name'] = $row['name'];
                $suburb_list[$row['id']]['city_id'] = $row['city_id'];
            }
            foreach ($schooltype_rows as $row) {
                $schooltype_list[$row['id']]['name'] = $row['name'];
            }
            $this->assign('country_list', $country_list);
            $this->assign('city_list', $city_list);
            $this->assign('suburb_list', $suburb_list);
            $this->assign('schooltype_list', $schooltype_list);
        }
        else if('news' == $type) {
            if (null != $keyword) {
                $map['title'] = array ('like', "%{$keyword}%");
            }
            if (null != $datefrom) {
                $map['date'] = array ('egt', $datefrom);
            }
            if (null != $dateto) {
                if (isset ($map['date'])) {
                    $map['date'] = array ('between', array($datefrom, $dateto));
                }
                else {
                    $map['date'] = array ('elt', $dateto);
                }
            }
        }
        else if('ebuy' == $type) {
            if (null != $keyword) {
                $map['name'] = array ('like', "%{$keyword}%");
            }
            if (null != $datefrom) {
                $map['date'] = array ('egt', $datefrom);
            }
            if (null != $dateto) {
                if (isset ($map['date'])) {
                    $map['date'] = array ('between', array($datefrom, $dateto));
                }
                else {
                    $map['date'] = array ('elt', $dateto);
                }
            }
        }
        else if('testimonial' == $type) {
            if (null != $keyword) {
                $map['name'] = array ('like', "%{$keyword}%");
            }
            if (null != $datefrom) {
                $map['date'] = array ('egt', $datefrom);
            }
            if (null != $dateto) {
                if (isset ($map['date'])) {
                    $map['date'] = array ('between', array($datefrom, $dateto));
                }
                else {
                    $map['date'] = array ('elt', $dateto);
                }
            }
        }

        if (null !== $id) {
            $this->_edit ($tname, $id);
            if ('page' == $type) {
                cookie("__CURRENTURL__",__SELF__);
            }
        }
        else {
            $this->_select($tname, $map, $order, $sort);
            cookie("__CURRENTURL__",__SELF__);
        }
        $this->assign('type',$type);

        $current=cookie('current');
        cookie('current',null);
        $this->assign('current',$current);

        if ('user' == $type) {
            $this->display();
        }
        else {
            $this->display($type);
        }
    }

    public function category(){
        $type=I('get.type');
        if(!in_array($type,array('newscategory','industry','forget'))) $this->error('',U('Index/index'));
        $tname=$type;
        $this->_select($tname);
        $this->assign('type',$type);
        cookie("__CURRENTURL__",__SELF__);
        $current=cookie('current');
        cookie('current',null);
        $this->assign('current',$current);
        $this->display();
    }
    public function photos(){
        $type=I('get.type');
        $pid=I('get.pid');
        $jump=cookie("__CURRENTURL__");
        if(empty($pid)||!is_numeric($pid)){
            $this->error('invalid action',$jump);
        }
        if(!in_array($type,array('propertyphoto'))) $this->error('',U('Index/index'));
        $tname=$type.'_'.$this->lang;
        $this->_select($tname,array('pid'=>$pid),'sid','desc');
        $this->assign('type',$type);
        cookie("__CURRENTURL__",__SELF__);
        cookie('current',$pid);
        $this->display();
    }

    public function edit(){
        $id=I('get.id','');
        $pid=I('get.pid',0);
        $this->assign('pid',$pid);
        $type=I('get.type');
        if(!in_array($type,array('property','user','page','property','schooltype','education','news','country','city','suburb','ebuy','testimonial')))$this->error('',U('Index/index'));

        if('property' == $type) {
            $country_list = array();
            $city_list = array();
            $suburb_list = array();
            $school_list = array();
            $country_rows = M('country_'.$this->lang)->select();
            $city_rows = M('city_'.$this->lang)->select();
            $suburb_rows = M('suburb_'.$this->lang)->select();
            $school_rows = M('education_'.$this->lang)->select();
            foreach ($country_rows as $row) {
                $country_list[$row['id']]['name'] = $row['name'];
            }
            foreach ($city_rows as $row) {
                $city_list[$row['id']]['name'] = $row['name'];
                $city_list[$row['id']]['country_id'] = $row['country_id'];
            }
            foreach ($suburb_rows as $row) {
                $suburb_list[$row['id']]['name'] = $row['name'];
                $suburb_list[$row['id']]['city_id'] = $row['city_id'];
            }
            foreach ($school_rows as $row) {
                $school_list[$row['id']]['name'] = $row['name'];
            }
            $this->assign('country_list', $country_list);
            $this->assign('city_list', $city_list);
            $this->assign('suburb_list', $suburb_list);
            $this->assign('school_list', $school_list);
        }

        $this->assign('type',$type);
        $tname=$type.'_'.$this->lang;

        cookie('current',$id);
        if(!empty($id)){
            $this->_edit($tname,$id);
            $this->display('edit'.$type);
        }else{

            $this->display('edit'.$type);
        }
    }

    public function getPhoto(){
        $id=isset($_GET['id'])?I('get.id'):'';
        $type=I('get.type');
        $this->assign('type',$type);
        $tname=$type.'_'.$this->lang;
        if(!empty($id)){
            $list=M($tname)->where(array('id'=>$id))->find();
            echo json_encode($list);
        }
    }
    protected  function _edit($tname,$id){
        $list=M($tname)->where(array('id'=>$id))->find();

        // Special columns
        if (isset ($list['c_certificate'])) {
            $list['c_certificate'] = explode (',', $list['c_certificate']);
        }

        $this->assign('list',$list);
    }

    public function save(){
        if(''==I('post.id','') || 0==I('post.id','')) unset($_POST['id']);
        $type=I('post.type');
        if(!in_array($type,array('user','page','property','schooltype','education','news','country','city','suburb','ebuy','testimonial')))$this->error('Invalid Operation',U('Index/index'));
        if ('user' == $type) {
            $tname=$type;
        }
        else {
            $tname=$type.'_'.$this->lang;
        }
        $jump=cookie("__CURRENTURL__");
        $db=D($tname);
        unset($_POST['pic']);
        if(!$db->create()){
            $this->error($db->getError(),$jump);
        }else{
            $id=I('post.id','');
            // File uploading
            foreach($_FILES as $key=>$file) {
                if(empty($file['name'])) unset($_FILES[$key]);
            }
            if(count($_FILES)>0){
                $path=date("Ymd");
                $files=$this->_upload($tname,$path);
                $pid=I('post.pid');
                foreach($_FILES as $key=>$file) {
                    //过滤无效的上传
                    if(!empty($file['name'])) {
                        foreach($files as $k=>$f){
                            $filename=$f['savepath'].$f['savename'];
                            $typename=$f['key'];
                            if($typename=='pic')$this->changePic($db, $type, $f,$filename,$path);
                            $db->$typename=$f['savepath'].$f['savename'];
                        }
                    }
            
                }
            }
            // Retrieve database fields
            $fields=$db->getDbFields();
            // Special cases
            $date=I('post.date');
            if(in_array('date',$fields)){
                if(empty($date))$db->date=date('Y-m-d');
                else $db->date=$date;
            }
            $addtime=I('post.addtime');
            if(in_array('addtime',$fields)){
                if(empty($addtime))$db->addtime=date('Y-m-d H:i:s');
                else $db->addtime=$addtime;
            }

            $pwd = I('post.password', null);
            if (null !== $pwd && in_array('password',$fields)) {
                $db->password = md5 ($pwd);
            }
            if (in_array('status',$fields)) {
                $status = I('post.status', null);
                if (null !== $status) {
                    $db->status = 1;
                }
                else {
                    $db->status = 0;
                }
            }
            if (in_array('visible',$fields)) {
                // radio buttons
                if (null === I('post.visible', null)) {
                    $db->visible = 'off';
                }
                if (null === I('post.hot_recomm', null)) {
                    $db->hot_recomm = 'off';
                }
                if (null === I('post.estate_recomm', null)) {
                    $db->estate_recomm = 'off';
                }
                if (null === I('post.small_business', null)) {
                    $db->small_business = 'off';
                }
            }
            $certs = I('post.c_certificate', null);
            if (null !== $certs && in_array('c_certificate',$fields)) {
                // Post var is an array (eg. checkbox)
                $db->c_certificate = implode (',', $certs);
            }

            // Special cases for this project
            if ('ads' == $type) {
                $db->type = M('ads_'.$this->lang)->where(array('id'=>$id))->getField('type');
            }

            // Add or Edit
            if(!empty($id)){
                $query=$db->save();
            }else{
                $query=$db->add();
                $id = $query;
            }

            cookie('current',$id);
            if ($query)$this->success('Success',$jump);
            else $this->error('Failure/Nothing changed',$jump);
        }
    }
    public function savePhotos(){
        $type=I('post.type');
        $tname=$type.'_'.$this->lang;
        $jump=cookie("__CURRENTURL__");
        $db=D($tname);
        
        $titles=I('post.title');
        $sids=I('post.sid');
        $pics=I('post.pic');
        if($type=='studentphotos'){
            $names=I('post.name');
        }
        if(!$db->create()){
            $this->error($db->getError());
        }else{
           if(count($pics)){
                foreach ($pics as $k=>$v){
                    if(!empty($v)){
                     $pid=I('post.pid');
                     $db->title=$titles[$k];
                     $db->pic=str_replace('./Uploads','',$v);
                     
                     $db->pid=$pid;
                     $db->sid=$sids[$k];
                     if($type=='studentphotos'){
                        $db->name=$names[$k];
                     }
                    $query=$db->add();
                    $db->where(array('id'=>$query))->setField('sid',$query);
                    }
                }
           }else{
              $query=$db->add();
           }	
            $this->success('Action Success',$jump);
        }
    }
    private function changePic($DB,$model,$f,$file,$path){
        $file='./Uploads'.$file;
        switch ($model){
            case 'courselist':
                $path=$f['savepath'];
                $basename=$f['savename'];
                $spic=$path.'s_'.$basename;
                $this->_thumb($file,$file,656,367);
                $this->_thumb($file,$spic,210,123);
                $DB->spic=$spic;
                break;
            case 'tutorslist':
                $this->_thumb($file,$file,80,100);
            break;
            case 'news':
                $this->_thumb($file,$file,153,96);
            break;
        }
    }
    protected function _thumb($file,$imgname,$width,$height){
        $image=new \Think\Image();
        if(!empty($file)){
            $image->open($file);
            $image->thumb($width, $height,3);
            $image->save($imgname);
        }
    }
    protected function _thumb1($file,$imgname,$width,$height){
        $image=new \Think\Image();
        if(!empty($file)){
            $image->open($file);
            $image->thumb($width, $height,1);
            $image->save($imgname);
        }
    }

    /***************************************************
     * _select()
     * $tname: table name
     * $map :  where condition
     * This function returns nothing but sets up pages and cookies,
     * assigns "show","list","listRows","fields"
     ***************************************************/
    public function _select($tname,$map=array(),$order='',$sort='',$asc=false,$query=null){
        $model=D($tname);
         
        if(isset($_REQUEST['_order'])){
            $order=$_REQUEST['_order'];
        }else{
            $order=!empty($order)?$order:$model->getPK();
        }

        if (null === $query) {
            $count=$model->where($map)->count();
        }
        else {
            $count=$model->count();
        }
         
        if ($count>0){
            if(!empty($_REQUEST['listRows'])){
                $listRows=$_REQUEST['listRows'];
                cookie($tname."_listRows",$listRows,3600);
            }elseif(cookie($tname."_listRows")){
                $listRows=cookie($tname."_listRows");
            }else{
                $pageNum=C("PAGENUM");
                $listRows=!empty($pageNum)?$pageNum:12;
            }
             
            $p=new \Org\Util\Page($count,$listRows);
            $p->setConfig('prev', '«');
            $p->setConfig('next', '»');
            $p->setConfig('last', 'the last Page');
            $p->setConfig('first', 'the first Page');
            $p->setConfig('theme','%upPage% %first%  %prePage% %linkPage%  %downPage%  %nextPage% %end%');
            if (null === $query) {
                $list=$model->where($map)->order("`" . $order . "` " . $sort)->limit($p->firstRow.",".$p->listRows)->select();
            }
            else {
                $query .= " ORDER BY `{$order}` {$sort} LIMIT {$p->firstRow},{$p->listRows}";
                $list=$model->query($query);
            }
            $show=$p->show();
            $this->assign("show",$show);
            $this->assign('list',$list);
             
            $this->assign("listRows",$listRows);
            $fields=$model->getDbFields();
            $this->assign('fields',$fields);
        }
    }

    public function uploadPic(){
        $module=I('get.model');
        $path=date("Ymd");
        $path=C(ATTACHPATH).'/'.$module.'/'.$path.'/';
        if (!is_dir($path)) mkdir($path,0755,true);
        import('Org.Util.UploadFile');
        $upload = new \UploadFile();
        $upload->maxSize=C(ATTACHSIZE);
        $upload->allowExts=explode(',',C(ATTACHEXT));
        $upload->savePath=$path;
        $upload->saveRule='uniqid';
        $data=array();
        $info=$data['result'];
        if(!$upload->upload()){
            $info['files'][0]['name']='';
            $info['files'][0]['error']=$upload->getErrorMsg();
            $json=json_encode($info);
            echo $json;
        }else{
            $file=$upload->getUploadFileInfo();
            $filename=$file[0]['savepath'].$file[0]['savename'];
            $info['files'][0]['name']=$filename;
            $this->switchPic($module, $filename);
            $json=json_encode($info);
            echo  $json;
        }
    }
    private function switchPic($module,$filename){
        $pid=I('get.pid');
        switch ($module){
            case 'newsphotoslist':
            $this->_thumb1($filename,$filename,467,350);
            break;
            case 'tutorsphotoslist':
                $this->_thumb($filename,$filename,460,344);
            break;
            case 'studentphotoslist':
                if($pid==3)
                    {
                        $this->_thumb($filename,$filename,467,350);
                    }else
                    {
                        $this->_thumb1($filename,$filename,1000,1200);
                    }
            break;
        }
    }
    public function uploadPdf(){
        $module="document";
        $path=date("Ymd");
        $path=C(ATTACHPATH).'/'.$module.'/';
        if (!is_dir($path)) mkdir($path,0755,true);
        import('Org.Util.UploadFile');
        $upload = new \UploadFile();
        $upload->maxSize=C(ATTACHSIZE);
        $upload->allowExts=explode(',',C(ATTACHEXT));
        $upload->savePath=$path;
        $upload->saveRule='uniqid';
        $data=array();
        $info=$data['result'];
        if(!$upload->upload()){
            $info['files'][0]['name']='';
            $info['files'][0]['error']=$upload->getErrorMsg();
            $json=json_encode($info);
            echo $json;
        }else{
            $file=$upload->getUploadFileInfo();
            $info['files'][0]['name']=$file[0]['savepath'].$file[0]['savename'];
            $json=json_encode($info);
            echo  $json;
        }
    }

    public function delCategory(){
        $type=I('get.type');
        if(!in_array($type,array('newscategory')))$this->error('',U('Index/index'));
        $this->assign('type',$type);
        $tname=$type;
        $id=isset($_GET['id'])?I('get.id'):'';
        $jump=cookie('__CURRENTURL__');
        if(empty($id))$this->error('Delete Failure',$jump);
        $count=M('newslist')->where(array('tid'=>$id))->count();
        if($count>0)$this->error("Delete Failure,Please delete this category's news item",$jump);
        $query=M($tname)->where(array('id'=>$id))->delete();
        if ($query)$this->success('Delete Success',$jump);
        else $this->error('Delete Failure',$jump);
    }
    public function delBanner(){
        $tname='testimonial_'.$this->lang;
        $id=I('get.id',null);
        $jump=cookie('__CURRENTURL__');
        if(null===$id) $this->error('Remove Failure',$jump);
        
        $query=M($tname)->where(array('id'=>$id))->setField('photo','');
        if ($query)$this->success('Remove Success',$jump);
        else $this->error('Remove Failure',$jump);
    }

    public function checkChild($type,$id,$jump){
        switch ($type){
            case 'catagory':
                $count=M('project')->where(array('cid'=>$id))->count();
                if($count>0)$this->error("Delete Failure. Please delete all projects under this catagory.",$jump);
            break;
            case 'project':
            break;
        }
    }
    public function del(){
        $type=I('get.type');
        if(!in_array($type,array('user','page','property','propertyphoto','schooltype','education','news','country','city','suburb','ebuy','testimonial')))$this->error('',U('Index/index'));
        if ('user' == $type) {
            $tname=$type;
        }
        else {
            $tname=$type.'_'.$this->lang;
        }
        $id=I('get.id','');
        $jump=cookie('__CURRENTURL__');
        if(empty($id))$this->error('Delete Failure',$jump);

        // Special cases
        if ('ads' == $type) {
            $query=M($tname)->where(array('id'=>$id))->save(array('image'=>''));
            if ($query)$this->success('廣告移除成功',$jump);
            else $this->error('廣告移除失敗',$jump);
            return;
        }
        
        $this->checkChild($type,$id,$jump);
        $query=M($tname)->where(array('id'=>$id))->delete();
        if ($query)$this->success('Delete Success',$jump);
        else $this->error('Delete Failure',$jump);
    }
}