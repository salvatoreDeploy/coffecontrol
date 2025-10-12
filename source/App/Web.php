<?php

namespace Source\App;

use Source\Core\Controller;

class Web extends Controller
{ 
  public function __construct(){
    parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
  }

  public function home()
  {
    $head = $this->seo->render(
      CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
      CONF_SITE_DESC,
      url(),
      url("/assets/images/share.jpg"),
    );

    echo $this->view->render("home", [
      "head" => $head,
      "video" => "5GYmqCAKAIc"
    ]);
  }

  public function error(array $data)
  {
   
   $error = new \stdClass();
   $error->code = $data['errcode'];
   $error->title =  "Ooops. Conteúdo indisponivel :/";
   $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponivel no momento.";
   $error->linkTitle = "Continue navegando!";
   $error->link = url_back();

   $head = $this->seo->render(
    "{$error->code} | {$error->title}",
    $error->message,
    url_back("/ooops/{$error->code}"),
    url("/assets/images/share.jpg"),
    false
   );

   echo $this->view->render("error", [
    "head" => $head,
    "error" => $error
   ]);
  }

  /**
   * Pagina About
   */

  public function about()
  {
    $head = $this->seo->render(
      "Descubra o " . CONF_SITE_NAME . " - " . CONF_SITE_DESC,
      CONF_SITE_DESC,
      url("/sobre"),
      url("/assets/images/share.jpg"),
    );

    echo $this->view->render("about", [
      "head" => $head,
      "video" => "5GYmqCAKAIc"
    ]);
  }

   /**
   * Pagina Termos de Uso
   */
  public function terms()
  {
    $head = $this->seo->render(
      CONF_SITE_NAME . " - Termos de uso.",
      CONF_SITE_DESC,
      url("/termos"),
      url("/assets/images/share.jpg"),
    );

    echo $this->view->render("terms", [
      "head" => $head,
    ]);
  }
}
