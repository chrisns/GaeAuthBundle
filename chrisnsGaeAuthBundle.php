<?php

namespace chrisns\GaeAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class chrisnsGaeAuthBundle extends Bundle
{
  public function getParent()
  {
    return "FOSUserBundle";
  }
}
