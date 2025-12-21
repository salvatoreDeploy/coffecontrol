<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Support\Message;

class App extends Controller
{
  public function __construct()
  {
    parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP);

    // Restrição Usuario deve estar logado:

    if(!Auth::user()){
      $this->message->warning("Efetue o login para ter acesso ao APP.")->flash();
      redirect("entrar");
    }
  }

  public function home()
  {
    echo flash();
    var_dump(Auth::user());

    echo "<a title='Sair' href='" .url("app/sair") ."'>Sair</a>";
  }

  public function logout()
  {
    (new Message())->info("Voce saiu com sucesso! " . Auth::user()->first_name . ". Volte logo :)")->flash();

    Auth::logout();

    redirect("/entrar");
  }
}
