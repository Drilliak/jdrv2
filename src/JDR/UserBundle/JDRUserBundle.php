<?php

namespace JDR\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JDRUserBundle extends Bundle
{
    public function getParent(){
        return 'FOSUserBundle';
    }
}
