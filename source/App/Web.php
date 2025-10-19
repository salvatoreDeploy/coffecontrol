<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Support\Pager;

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
      theme("/assets/images/share.jpg"),
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
    theme("/assets/images/share.jpg"),
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

  public function about(): void
  {
    $head = $this->seo->render(
      "Descubra o " . CONF_SITE_NAME . " - " . CONF_SITE_DESC,
      CONF_SITE_DESC,
      url("/sobre"),
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("about", [
      "head" => $head,
      "video" => "5GYmqCAKAIc"
    ]);
  }

  /**
   * Pagina Blog
   */

  public function blog(?array $data)
  {
    $head = $this->seo->render(
      "Blog - " . CONF_SITE_NAME,
      "Em nosso blog temos dicas e informações sobre Tecnologia e Ferramentas utilizadas para melhorar suas contas. Vamos com um café?",
      url("/blog"),
      theme("/assets/images/share.jpg"),
    );

    $pager = new Pager(url("/blog/page/"));
    $pager->pager(100, 10, $data['page'] ?? 1 );

     echo $this->view->render("blog", [
      "head" => $head,
      "paginator" => $pager->render()
    ]);
  }

  public function blogPost(array $data)
  {
    $postName = $data['postName'];

     $head = $this->seo->render(
      "POSTNAME - " . CONF_SITE_NAME,
      "POST HEADLINE",
      url("/blog/{$postName}"),
      theme("BLOG IMAGE"),
    );

    echo $this->view->render("blog-post", [
      "head" => $head,
      "data" => $this->seo->data()
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
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("terms", [
      "head" => $head,
    ]);
  }

  /**
   * Autenticação Login
   */

   public function login()
   {
    $head = $this->seo->render(
      "Entrar - " . CONF_SITE_NAME,
      CONF_SITE_DESC,
      url("/entrar"),
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("auth-login", [
      "head" => $head,
    ]);
   }

   /**
   * Autenticação Recuperação
   */

   public function forget()
   {
     $head = $this->seo->render(
      "Recuperar Senha - " . CONF_SITE_NAME,
      CONF_SITE_DESC,
      url("/recuperar"),
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("auth-forget", [
      "head" => $head,
    ]);
   }

   /**
   * Autenticação Cadastro
   */

   public function register()
   {
     $head = $this->seo->render(
      "Criar Conta - " . CONF_SITE_NAME,
      CONF_SITE_DESC,
      url("/registar"),
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("auth-register", [
      "head" => $head,
    ]);
   }
}
