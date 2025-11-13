<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Summary of Category
 * @package Source\Models
 */
class Category extends Model
{
  public function __construct()
  {
    parent::__construct("categories", ["id"], ["title", "id"]);
  }

  /**
   * Summary of findByUri
   * @param string $uri
   * @param string $columns
   * @return array|bool|mixed|object|null
   */
  public function findByUri(string $uri, string $columns = "*"): ?Category
  {
    $find = $this->find("uri = :uri", "uri={$uri}", $columns);
    return $find->fetch();
  }

  /**
   * Summary of save
   * @return bool
   */
  public function save(): bool
  {

  }
}
