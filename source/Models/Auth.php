<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Core\Session;
use Source\Core\View;
use Source\Support\Email;

class Auth extends Model
{
  /**
   * Summary of __construct
   * @param string $entity
   * @param array $protected
   * @param array $required
   */
  public function __construct()
  {
    parent::__construct("user", ["id"], ["email", "password"]);
  }

  /**
   * Summary of user
   * @return mixed|Model|null
   */
  public static function user():?User
  {
    $session = new Session();
    if(!$session->has("authUser")){
      return null;
    }

    return (new User())->findById($session->authUser);
  }

  /**
   * Summary of logout
   * @return void
   */
  public static function logout()
  {
    $session = New Session();
    $session->unset("authUser");
  }

  /**
   * Summary of register
   * @param User $user
   * @return bool
   */
  public function register (User $user):bool
  {

    if(!$user->save()){
      $this->message = $user->message;
      return false;
    }

    $view = new View(__DIR__ . "/../../shared/views/email");

    $message = $view->render("confirm", [
      "first_name" => $user->first_name,
      "confirm_link" => url("/obrigado/" . base64_encode($user->email))
    ]);

    (new Email())->bootstrap(
      "Ative sua conta no " . CONF_SITE_NAME,
      $message,
      $user->email,
      "{$user->first_name} {$user->last_name}"
    )->send();

    return true;
  }

  /**
   * Summary of login
   * @param string $email
   * @param string $password
   * @param mixed $save
   * @return bool
   */
  public function login(string $email, string $password, $save = false):bool
  {
    if(!is_email($email)){
      $this->message->warning("O e-mail não é valido");
      return false;
    }

    if($save){
      setcookie("authEmail", $email, time() + 86400, "/");
    }else{
      setcookie("authEmail", null, time() - 3600, "/");
    }

    if(!is_passwd($password)){
      $this->message->warning("A senha informada não é valida");
      return false;
    }

    $user = (new User())->findByEmail($email);

    if(!$user){
      $this->message->error("O e-mail informado não foi encontrado");
      return false;
    }

    if(!passwd_verify($password, $user->password)){
      $this->message->error("A senha informada não confere");
      return false;
    }

    if(passwd_rehash($user->password)){
      $user->password = $password;
      $user->save();
    }

    // LOGIN
    (new Session())->set("authUser", $user->id);
    $this->message->success("Login efetuado com sucesso!")->flash();
    return true;
  }

  /**
   * TODO:
   * 
   * Refatorar para não usar o email na construção do url
   * Ex: /recuperar?token=udhsudisahdus
   */

  /**
   * Summary of forget
   * @param string $email
   * @return bool
   */
  public function forget(string $email): bool
  {
    $user = (new User())->findByEmail($email);

    if(!$user){
      $this->message->warning("O e-mail informado não esta cadastrado");
      return false;
    }

    $user->forget = md5(uniqid(rand(), true));
    $user->save();

    $view = new View(__DIR__ . "/../../shared/views/email");
    $message = $view->render("forget", [
      "first_name" => $user->first_name,
      "forget_link" => url("/recuperar/{$user->email}|{$user->forget}")
    ]);

    

    (new Email())->bootstrap(
      "Recupere sua senha no " . CONF_SITE_NAME,
      $message,
      $user->email,
      "{$user->first_name} {$user->last_name}"
    )->send();

    return true;
  }

  /**
   * Summary of reset
   * @param string $email
   * @param string $code
   * @param string $password
   * @param string $passwordRepeat
   * @return bool
   */
  public function reset(string $email, string $code, string $password, string $passwordRepeat): bool
  {
    $user = (new User())->findByEmail($email);

    if(!$user){
      $this->message->warning("A conta para recuperação não foi encontrada");
      return false;
    }

     if($user->forget != $code){
      $this->message->error("Desculpe, mas o código de verificação não é valido");
      return false;
    }

    if (!is_passwd($password)){
      $min = CONF_PASSWD_MIN_LEN;
      $max = CONF_PASSWD_MAX_LEN;
      $this->message->info("Sua senha deve conterr entre {$min} e {$max} caracteres!");
      return false;
    }

    if($password != $passwordRepeat){
      $this->message->warning("Você informou duas senhas diferentes");
      return false;
    }

    $user->password = $password;
    $user->forget = null;
    $user->save();

    return true;
  }

}
