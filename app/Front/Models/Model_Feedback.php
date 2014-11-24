<?php
namespace Front\Models;
class Model_Feedback extends \Core\Model {
    protected $mailer;

	function __construct() {
       parent::__construct();
       $this->mailer = new \Generic\Mailer;

   }
    function message(){
        if (($_REQUEST['email']) && ($_REQUEST['phone'])){
            $message = filter_var($_REQUEST['message'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
            $name = filter_var($_REQUEST['name'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
            $phone = filter_var($_REQUEST['phone'], FILTER_SANITIZE_NUMBER_INT);
            $email = filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL);
            $type = filter_var($_REQUEST['formtype'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);

            $feedback = array(
                'name'=>$name,
                'message' => $message,
                'phone' => $phone,
                'email' => $email
            );
            $data = array(
                'type' => $type,
                'value' => serialize($feedback),
                'date' => date('c')
            );
            if ($_REQUEST['itemid']){
                $data['itemid'] = $_REQUEST['itemid'];
            }

            $this->query = $this->insert_query_generator($data, 'feedback');
            $result = $this->set_data($data);/*
            до поры до времени...
            if ($result){
                $message = 'Вам был оставлен отзыв от пользователя по имени '.$name.'</br>';
                if($phone){
                    $message .= 'Номер телефона: '.$name.'</br>';
                }
                if($email){
                    $message .= 'Email: '.$email.'</br>';
                }
                $message .= '<a href="http://'.$_SERVER['HTTP_HOST'].'/admin/feedback">Посмотреть в админ-панели</a>';
                $mail = $this->mailer->callmail($message);
                return true;
            }*/
            if ($result){
                $sendmessage = '
                Вам был оставлен отзыв от пользователя по имени '.$name.'. ';
                if($phone){
                    $sendmessage .= '
                    Номер телефона: '.$phone.' ';
                }
                if($email){
                    $sendmessage .= '
                    Email: '.$email.' ';
                }
                if($message){
                    $sendmessage .= '
                    Сообщение: '.$message.' ';
                }
                //$message .= 'Посмотреть в админ-панели - http://'.$_SERVER['HTTP_HOST'].'/admin/feedback';
                $mail = $this->mailer->callmail($sendmessage);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
    function callme(){
        if ($_REQUEST['phonenumber']){
            $string = $_REQUEST['phonenumber'];
            $string = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
            $this->query = 'INSERT INTO `feedback` (`id` , `type` , `value` , `date`) VALUES (NULL , :type, :message, CURRENT_TIMESTAMP)';
            $result = $this->set_data(array('type' => 'call', 'message' => $string));
            if ($result){
                $message = 'Вам была оставлена просьба презвонить по телефону '.$string;
                $mail = $this->mailer->callmail($message);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
    function file(){
        if (!is_null($_FILES)){
            $upfile = $_FILES['resume'];
            $uploaddir = site_path.DS.'upfiles'.DS;
            if (!is_dir($uploaddir)){
                mkdir($uploaddir, 0755);
            }
            $extensions = array(
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'doc' => 'application/msword',
                'pdf' => 'application/pdf'
            );
            $tmpfile = self::checkfile($upfile, $uploaddir, $extensions);
            if ($tmpfile){
                $this->query = 'INSERT INTO `feedback` (`id` , `type` , `value` , `date`) VALUES (NULL , :type, :value, CURRENT_TIMESTAMP)';
                $result = $this->set_data(array('type' => 'file', 'value' => $tmpfile));
                if ($result){
                    $message = 'Вам было отправлено новое резюме с сайта ТМК. Имя файла - '.$tmpfile;
                    $mail = $this->mailer->callmail($message);
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }

        }
        else {
            return false;
        }
    }
    function getfform(){

    }
   
	}
