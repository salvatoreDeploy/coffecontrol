<?php

namespace Source\Models\Faq;

use Source\Core\Model;

/**
 * Summary of Question
 * @package Source\Models\Faq
 */
class Question extends Model
{
  /**
   * Question __construct
   */
  public function __construct()
  {
    parent::__construct("faq_questions", ["id"], ["channel_id","question", "response"]);
  }

  /**
   * Summary of save
   * @return bool
   */
  public function save(): bool
  {
    
  }
}
