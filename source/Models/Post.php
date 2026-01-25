<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Summary of Post
 * @package Source\Model
 */
class Post extends Model
{
  /**
   * Summary of all
   * @var bool
   */
  private $all;

  /**
   * Post __construct
   * @param string $entity
   * @param array $protected
   * @param array $required
   * @param bool $all = ignore status and post_at
   */
  public function __construct(bool $all = false)
  {
    $this->all = $all;
    parent::__construct("posts", ["id"], ["title", "id" ,"subtitle", "content"]);
  }

  /**
   * Summary of find
   * @param mixed $terms
   * @param mixed $params
   * @param string $columns
   * @return mixed|Model
   */
  public function find(?string $terms = null, ?string $params = null, string $columns = "*"): Model
  {
    if(!$this->all){
      $terms = "status = :status AND post_at <= NOW()" . ($terms ? " AND {$terms}" : "");
      $params = "status=post" . ($params ? "&{$params}" : "");
    }
    
    return parent::find($terms, $params, $columns);
  }

  /**
   * Summary of findByUri
   * @param string $uri
   * @param string $columns
   * @return array|bool|mixed|object|null
   */
  public function findByUri(string $uri, string $columns = "*"): ?Post
  {
    $find = $this->find("uri = :uri", "uri={$uri}", $columns);
    return $find->fetch();
  }

  /**
   * Summary of author
   * @return User|null
   */
  public function author(): ?User
  {
    if (!empty($this->author)) {
      return (new User())->findById((int)$this->author);
    }

    return null;
  }

  /**
   * Summary of category
   * @return Category|null
   */
  public function category(): ?Category
  {
     if (!empty($this->category)) {
      return (new Category())->findById((int)$this->category);
    }

    return null;
  }

    /**
   * Summary of save
   * @return bool
   */
  public function save(): bool
  {
    /** Post Update */
    if(!empty($this->id)){
      $postId = (int)$this->id;

      $this->update($this->safe(), "id = :id", "id={$postId}");

      if($this->fail()){
        $this->message->error("Erro ao atualizar, verifique os dados.");
      }

      /** Post Create */
      $this->data = $this->findById($postId)->data();
    }

    return true;
  }
}
