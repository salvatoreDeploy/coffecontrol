<?php

namespace Source\Models\Faq;

use Source\Core\Model;

/**
 * Summary of Channel
 * @package Source\Models\Faq
 */
class Channel extends Model
{
  /**
   * Channel __construct
   */
  public function __construct()
  {
    parent::__construct("faq_channels", ["id"], ["channel","description"]);
  }

  /**
   * Summary of save
   * @return bool
   */
  public function save(): bool
  {

  }
}
