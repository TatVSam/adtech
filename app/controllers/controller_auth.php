<?php

class Controller_Auth extends Controller
{
          
    public function __construct() {
        parent::__construct();
    }
        
    function generateCode($length=6) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
                $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
      } 
    
    public function login() 
    {
        session_start();

       

        if(isset($_POST['login'])) {
          if($_POST["token"] == $_SESSION["CSRF"])
          {
            
              // Вытаскиваем из БД запись, у которой логин равняется введенному
            require_once 'app/models/entities/User.php';

            $user_object = new User;

            $user_object->setUserName($_POST['user_name']);

            $user_data = $user_object->get_user_data_by_user_name();


             
              if(password_verify($_POST['user_password'], $user_data['user_password']))
              {
                  // Генерируем случайное число и шифруем его
                  $hash = md5($this->generateCode(10));
          
                  $user_object->setUserHash($hash);
                
                  $user_object->save_hash();
                  // Записываем в БД новый хеш авторизации
       
                  // Ставим куки
                  setcookie("user_id", $user_data['user_id'], time()+60*60*24*30, "/");
                  setcookie("user_hash", $hash, time()+60*60*24*30, "/", null, null, true); // httponly !!! 
                  // Переадресовываем браузер на страницу проверки нашего скрипта
                  $url = "http://" . $_SERVER['SERVER_NAME'] . "/dashboard";
                  header("Location: $url");
                  
                  }
              else
              {
                  $no_auth = "Вы ввели неправильный логин/пароль";
                  
                  echo $no_auth;

              }
              
          }
         
        }
          //Генерируем токен и сохраняем его в сессию
       
        
          $this->view->generate('/auth/login.phtml', 'template_view.phtml');
        
    }
    
    public function register() 
    {
        session_start();
        /* $error = '';

        $success_message = '';*/

        $errors = [];

        if(isset($_POST["register"]) && (count($this->verify_reg($_POST))) <> 0)
        {
            $errors = $this->verify_reg($_POST);
         
         
        }

        if(isset($_POST["register"]) && (count($this->verify_reg($_POST))) == 0)
        {
            
            require_once 'app/models/entities/User.php';

            $user_object = new User;

            $user_object->setUserName($_POST['user_name']);

            $user_object->setUserEmail($_POST['user_email']);

            $user_object->setUserPassword(password_hash($_POST['user_password'], PASSWORD_DEFAULT));
            $user_object->setUserRole($_POST['user_role']);
            
            $user_object->save_data();

            unset($_POST['user_name']);
            unset($_POST['user_email']);
            unset($_POST['user_password']);

            unset($_POST['user_role']);
            unset($_POST["register"]);

            $url = "http://adtech/auth/login";
            header("Location: $url"); 
        

        }

       
        $this->view->generate('/auth/reg.phtml', 'template_view.phtml', $errors);  


      
    }  

    public function verify_reg ($reg_data)
    {
            $err = [];
            // проверяем логин
            if(!preg_match("/^[a-zA-Z0-9]+$/",$reg_data['user_name']))
            {
                $err[] = "Логин может состоять только из букв английского алфавита и цифр";
            } 
            if(strlen($reg_data['user_name']) < 3 or strlen($reg_data['user_name']) > 30)
            {
                $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
            } 
            if ($reg_data['user_password'] <> $reg_data['user_password_repeat'])
            {
                $err[] = "Пароли не совпадают!";
            } 
        
            if(strlen($reg_data['user_password']) < 5)
            {
                $err[] = "Пароль содержит менее пяти символов!";
            } 
            // проверяем, не существует ли пользователя с таким именем
            require_once 'app/models/entities/User.php';

            $user_example = new User;

            if($user_example->user_name_is_taken($reg_data["user_name"]))
            {
                $err[] = "Пользователь с таким логином уже существует в базе данных";
            } 
            // Если нет ошибок, то добавляем в БД нового пользователя
       
            return $err;
        
    
    }

    public function success() 
    {
        $this->view->generate('/auth/success.phtml', 'template_view.phtml');      
    } 

    public function logout() 
    {
        session_start();
        session_destroy();
        setcookie("user_id", "", time() - 3600*24*30*12, "/");
        setcookie("user_hash", "", time() - 3600*24*30*12, "/",null,null,true);
        // Переадресовываем браузер на страницу проверки нашего скрипта
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/dashboard";
        header("Location: $url"); 
        exit; 
   
    } 
    
}
