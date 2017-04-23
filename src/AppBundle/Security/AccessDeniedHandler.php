<?php
/**
 * Created by PhpStorm.
 * User: divanova.v
 * Date: 22-Apr-17
 * Time: 13:00
 */

namespace AppBundle\Security;


use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        // ...

        return new Response('Access denied!', Response::HTTP_NOT_FOUND);
    }

}