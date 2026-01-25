<?php

namespace Source\App;

use Source\Core\Connect;
use Source\Core\Controller;
use Source\Models\Category;
use Source\Models\Faq\Channel;
use Source\Models\Faq\Question;
use Source\Models\Post;
use Source\Models\User;
use Source\Support\Email;
use Source\Support\Pager;
use Source\Models\Auth;

class Web extends Controller
{ 
  public function __construct(){

    /* COLOCAR site me manutenção */
    // redirect("/ooops/problemas");

    parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");

    $email = new Email();
    $email->bootstrap(
      "Teste de fila de e-mail ".time(),
      "Este é apenas um teste de envio",
      "liderhenrique@gmail.com",
      "Henrique J. Araujo"
    )->sendQueue();
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
      "video" => "5GYmqCAKAIc",
      "blog" => (new Post())->find()->order("post_at DESC")->limit(6)->fetch(true)
    ]);
  }

  public function error(array $data)
  {
   
   $error = new \stdClass();

   switch($data['errcode']) {
    case "problemas":
      $error->code = "OPS";
      $error->title =  "Estamos enfrentando problemas!";
      $error->message = "Parece que nosso serviço não esta disponivel no momento. Ja estamos verificando o motivo, caso precise nos envie um E-mail";
      $error->linkTitle = "ENVIAR E-MAIL";
      $error->link = "malito:" . CONF_MAIL_SUPPORT;
      break;

    case "manutencao":
      $error->code = "OPS";
      $error->title =  "Ooops. Conteúdo indisponivel :/";
      $error->message = "Voltamos logo! Por hora estamos trabalhando para melhorar nosso conteúdo para voce voltar a controlar de forma melhor";
      $error->linkTitle = null;
      $error->link = null;
      break;
    
    default:
      $error->code = $data['errcode'];
      $error->title =  "Ooops. Conteúdo indisponivel :/";
      $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponivel no momento.";
      $error->linkTitle = "Continue navegando!";
      $error->link = url_back();
      break;
   }

   
   $head = $this->seo->render(
    "{$error->code} | {$error->title}",
    $error->message,
    url("/ooops/{$error->code}"),
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
      "video" => "5GYmqCAKAIc",
      "faq" => (new Question())->find("channel_id = :id", "id=1", "question, response")->order("order_by")->fetch(true)
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

    $blog = (new Post())->find();

    $pager = new Pager(url("/blog/p/"));
    $pager->pager($blog->count(), 9, ($data['page'] ?? 1));

    echo $this->view->render("blog", [
      "head" => $head,
      "blog" => $blog->limit($pager->limit())->offset($pager->offset())->fetch(true),
      "paginator" => $pager->render()
    ]);
  }
  
  /**
   * Summary of blogSearch
   * @param array $data
   * @return void
   */
  public function blogSearch(array $data)
  {
    if(!empty($data['s'])){
      $search = trim($data['s']);

        echo json_encode([
            'redirect' => url("/blog/buscar?terms=" . urlencode($search) . "&page=1")
        ]);
        return;
    }

    // 🔹 acesso direto sem termo
    $search = $data['terms'] ?? filter_input(INPUT_GET, 'terms') ?? null;
    $page = $data['page'] ?? filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;

    if (empty($search)) {
        echo $this->view->render("blog", [
            "head" => $this->seo->render(
                "Pesquisa - " . CONF_SITE_NAME,
                "Nenhum termo informado para busca.",
                url("/blog/buscar"),
                theme("/assets/images/share.jpg")
            ),
            "title" => "PESQUISA",
            "search" => "'Nenhum termo informado.'",
            "blog" => [],
            "paginator" => null
        ]);
        return;
    }

    // 🔹 tratamento seguro
    $search = $data['terms'] ?? filter_input(INPUT_GET, 'terms') ?? null;

    if ($search === null) {
        echo $this->view->render("blog", [
            "head" => $this->seo->render(
                "Pesquisa - " . CONF_SITE_NAME,
                "Nenhum termo informado para busca.",
                url("/blog/buscar"),
                theme("/assets/images/share.jpg")
            ),
            "title" => "PESQUISA",
            "search" => "Nenhum termo informado.",
            "blog" => [],
            "paginator" => null
        ]);
        return;
    }

    $page = $data['page'] ?? filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;

    // 🔹 SEO
    $head = $this->seo->render(
        "Pesquisa por {$search} - " . CONF_SITE_NAME,
        "Confira os resultados de sua pesquisa para {$search}",
        url("/blog/buscar?terms=" . urlencode($search) . "&page={$page}"),
        theme("/assets/images/share.jpg")
    );

    // 🔹 busca no banco
    $blogSearch = (new Post())->find(
        "MATCH(title, subtitle) AGAINST(:s)",
        "s={$search}"
    );

    if (!$blogSearch->count()) {
        echo $this->view->render("blog", [
            "head"   => $head,
            "title"  => "PESQUISA POR:",
            "search" => $search
        ]);
        return;
    }

    // 🔹 pager
    $pager = new Pager(
        url("/blog/buscar?terms=" . urlencode($search) . "&page=")
    );
    $pager->pager($blogSearch->count(), 9, $page);

    echo $this->view->render("blog", [
      "head" => $head,
      "title" => "PESQUISA POR:",
      "search"=> $search,
      "blog" => $blogSearch->limit($pager->limit())->offset($pager->offset())->fetch(true),
      "paginator" => $pager->render()
    ]);
  }

  public function blogCategory(array $data){
    
   $categoryQueryParams = $data['category'] ?? filter_input(INPUT_GET, 'category') ?? null;
    
   $category = (new Category())->findByUri($categoryQueryParams);

   if(!$categoryQueryParams){
      redirect("/blog");
   }

   $blogCategory = (new Post())->find("category = :c", "c={$category->id}");

   $page = $data['page'] ?? filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
   $pager = new Pager(
        url("/blog/buscar/categoria?category=" . urlencode($category->uri) . "&page=")
    );
   $pager->pager($blogCategory->count(), 9, $page);

    // 🔹 SEO
    $head = $this->seo->render(
        "Artigos em {$category->title} - " . CONF_SITE_NAME,
        $category->description,
        url("/blog/buscar/categoria?category=" . urlencode($category->uri) . "&page={$page}"),
        ($category->cover ? image($category->cover,1200, 628) : theme("assets/images/share.jpg"))
    );

     echo $this->view->render("blog", [
      "head" => $head,
      "title" => "Artigos em {$category->title}",
      "desc"=> $category->description,
      "blog" => $blogCategory->limit($pager->limit())->offset($pager->offset())->order("post_at DESC")->fetch(true),
      "paginator" => $pager->render()
    ]);
  }

  public function blogPost(array $data)
  {
     $post = (new Post())->findByUri($data['uri']);
     
     if(!$post){
      redirect("/404");
     }

     $post->views += 1;
     $post->save();

     $head = $this->seo->render(
      "{$post->title}" . CONF_SITE_NAME,
      $post->subtitle,
      url("/blog/{$post->uri}"),
      image($post->cover, 1200, 628),
    );

    echo $this->view->render("blog-post", [
      "head" => $head,
      "post" => $post,
      "related" => (new Post())->find("category = :c AND id != :i", "c={$post->category}&i={$post->id}")->order("rand()")->limit(3)->fetch(true)
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

   public function login(?array $data)
   {

    if(!empty($data['csrf'])){

      if(!csrf_verify($data)){
        $json['message'] = $this->message->error("Erro ao enviar, favor use o formulario")->render();
        echo json_encode($json);
        return;
      }

      if(empty($data['email']) || empty($data['password'])){
        $json['message'] = $this->message->warning("Informe seu email e senha para entrar")->render();
        echo json_encode($json);
        return;
      }

      $save = (!empty($data['save']) ? true : false);
      $auth = new Auth();
      $login = $auth->login($data['email'], $data['password'], $save);

      if($login){
        $json['redirect'] = url('/app');
      }else{
        $json['message'] = $auth->message()->render();
      }

      echo json_encode($json);
      return;
    }

    $head = $this->seo->render(
      "Entrar - " . CONF_SITE_NAME,
      CONF_SITE_DESC,
      url("/entrar"),
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("auth-login", [
      "head" => $head,
      "cookie" => filter_input(INPUT_COOKIE, "authEmail")
    ]);
   }

   /**
   * Autenticação Recuperação
   * @param null|array $data
   */

   public function forget(?array $data)
   {

    if(!empty($data['csrf'])){

      if(!csrf_verify($data)){
        $json['message'] = $this->message->error("Erro ao enviar, favor use o formulario")->render();
        echo json_encode($json);
        return;
      }

      if(empty($data["email"])){
        $json["message"] = $this->message->info("Informe seu e-mail para continuar")->render();
        echo json_encode($json);
        return;
      }

      $auth = new Auth();

      if($auth->forget($data['email'])){
        $json['message'] =  $this->message->success("Acesse seu e-mail para recuperar a senha")->render();
      }else{
        $json["message"] = $auth->message()->render();
      }

      echo json_encode($json);
      return;
      
    }  

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

    public function reset(?array $data)
   {
    if(!empty($data['csrf'])){

      if(!csrf_verify($data)){
        $json['message'] = $this->message->error("Erro ao enviar, favor use o formulario")->render();
        echo json_encode($json);
        return;
      }

      if(empty($data["password"]) || empty($data["password_repeat"])){
        $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
        echo json_encode($json);
        return;
      }

      $codeDecoded = urldecode($data["code"]);

      list($email, $code) = explode("|", $codeDecoded);
      $auth = new Auth();

      if($auth->reset($email, $code, $data["password"], $data["password_repeat"])){
        $this->message->success("Senha alterada com sucesso. Vamos Controlar")->flash();
        $json["redirect"] = url("/entrar");
      }else{
        $json["message"] = $auth->message()->render();
      }

      echo json_encode($json);
      return;
      
    }  

     $head = $this->seo->render(
      "Crie sua nova senha no - " . CONF_SITE_NAME,
      CONF_SITE_DESC,
      url("/recuperar"),
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("auth-reset", [
      "head" => $head,
      "code" => $data["code"]
    ]);
   }

   /**
   * Autenticação Cadastro
   */

   public function register(?array $data)
   {

    if(!empty($data['csrf'])){
      if (!csrf_verify($data)){
        $json['message'] = $this->message->error("Erro ao enviar, favor use o formulario")->render();
        echo json_encode($json);
        return;
      }

      if(in_array("", $data)){
        $json['message'] = $this->message->info("Informe seus dados para criar sua conta")->render();
        echo json_encode($json);
        return;
      }

      $auth = new Auth();
      $user = new User();

      $user->bootstrap(
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $data['password'],
      );

      if($auth->register($user)){
        $json['redirect'] = url("/confirma");
      }else{
        $json['message'] = $auth->message()->render();
      }

      echo json_encode($json);
      return;
    }

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

   /**
   * Confirmação de Autenticação
   */

   public function confirm()
   {
     $head = $this->seo->render(
      "Confirme seu Cadastro - " . CONF_SITE_NAME,
      CONF_SITE_DESC,
      url("/confirma"),
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("optin", [
      "head" => $head,
      "data" => (object) [
        "title" => "Falta pouco! Confirme seu cadastro.",
        "desc" => "Enviamos um link de confirmação para seu e-mail. Acesse e siga as instruções para concluir seu cadastro e comece a controlar com o CaféControl",
        "image" => theme("/assets/images/optin-confirm.jpg")
      ]
    ]);
   }

   /**
    * Sucesso na autenticação
    * @param array $data
    */

   public function success(array $data)
   {

    $email = base64_decode($data["email"]);
    $user = (new User())->findByEmail($email);

    if($user && $user->status != "confirmed"){
      $user->status = "confirmed";
      $user->save();
    }

    $head = $this->seo->render(
      "Bem-vindo(a) ao " . CONF_SITE_NAME,
      CONF_SITE_DESC,
      url("/obrigado"),
      theme("/assets/images/share.jpg"),
    );

    echo $this->view->render("optin", [
      "head" => $head,
      "data" => (object) [
        "title" => "Tudo pronto. Você já pode controlar :)",
        "desc" => "Bem-vindo(a) ao seu controle de contas, vamos tomar um café?",
        "image" => theme("/assets/images/optin-success.jpg"),
        "link" => url("/entrar"),
        "linkTitle" => "Fazer Login"
      ]
    ]);
   }
}
