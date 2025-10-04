<?php

namespace Source\Core;

use Source\Support\Seo;
use Source\Core\View;


/**
 * DeveloperService | Class Controller
 *
 * @author Henrique J. Araujo <liderhenrique@gmail.com>
 * @package Source\Core
 */
class Controller
{
  /** @var View */
  protected $view;
  /** @var Seo */
  protected $seo;

  public function __construct(string $pathToView = null){
    $this->view = new View($pathToView);


    $this->seo = new Seo();
  }
}