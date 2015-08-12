<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController{
    public function index(){
    	$data=array();
    	$data['serverinfo']=PHP_OS.'/'.PHP_VERSION;
    	$magic=get_magic_quotes_gpc()?'On':'Off';
    	$data['magic_quote_gpc']=$magic;
    	$this->assign('main',$data);
       $this->display();
    }


    public function formSubmit() {
        $name = I('request.guestname');
        $email = I('request.guestemail');
        $enquirytype = I('request.enquirytype');
        $properties = I('request.properties');
        $properties = explode (',', $properties);
        $subject = '[Message From: ML Property]';
        $body = 'Name: '.$name.'<br/>Email: '.$email.'<br/>Enquiry Type: '.$enquirytype.'<br/>Enquiry Properties: '.implode(',', $properties);
        $this->msg = $this->postMail ($body, $subject, '540115739@qq.com', '2757144278@qq.com');

        $db_header = M('member_enquiry_header');
        $db_details = M('member_enquiry_details');
        $header_id = $db_header->add (array (
                            'name'          => $name,
                            'email'         => $email,
                            'enquiry_type'  => $enquirytype,
                            'confirmed'     => 0)
                            );
        foreach ($properties as $property) {
            $db_details->add (array (
                            'header_id'     => $header_id,
                            'property_id'   => $property)
                            );
        }
    }

    public function confirmEmail() {
        $header_id = I('request.id');
        $name = I('request.name');
        $email = I('request.email');

        $db_header = M('member_enquiry_header');
        $confirmed = $db_header->where(array ('id'=>$header_id))->getField('confirmed');
        if (0 == $confirmed) {
            $subject = '[Message From: ML Property]';
            $body = 'We have received your enquiries. Thank you.';
            $this->msg = $this->postMail ($body, $subject, $email, $name);

            $db_header->where(array ('id'=>$header_id))->setField('confirmed', 1);
            $this->assign ('error', 'Confirm message sent successfully');
        }
        else {
            $this->assign ('error', 'Already confirmed');
        }

        unset ($_GET['id']);
        $ListController = A('List');
        $ListController->admin('member_enquiry_header');
    }

    public function postMail($body,$subject,$to,$name,$isHTML = true) {
        require('./ThinkPHP/Library/Vendor/PHPMailer/class.smtp.php');
        require('./ThinkPHP/Library/Vendor/PHPMailer/class.phpmailer.php');
        $mail = new \PHPMailer;
        $mail->CharSet = 'UTF-8';
        $mail->IsSMTP ();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Host = 'mail.lifespring.com.hk';
        $mail->Port = '26';
        $mail->Username = 'system@lifespring.com.hk';
        $mail->Password = '2201sys##';
        mb_internal_encoding ('UTF-8');
        $mail->Subject = mb_encode_mimeheader ($subject, 'UTF-8');
        $mail->From = 'system@lifespring.com.hk';
        $mail->FromName = 'ML Property';

        if (!$isHTML) {
            $mail->isHTML (false);
            $mail->Body = $body;
        }
        else {
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer.';
            $mail->MsgHTML ($body);
        }
        
        $mail->AddAddress ($to, $name);
        
        return $mail->Send() ? true : $mail->ErrorInfo;
    }

    // SQL execution 
    public function sql() {
        $q = "CREATE DATABASE `gc_db`
";
        $results = $this->db->query($q);
        /*$this->response->setOutput("<h2>{$q}</h2>".var_export($results,1));*/
        if(false !== $results) {
            $this->response->setOutput("<h2>{$q}</h2>Success!");
        }
        else {
            $this->response->setOutput("<h2>{$q}</h2>Failed!");
        }
    }
}