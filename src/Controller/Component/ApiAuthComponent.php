<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

class ApiAuthComponent extends Component
{
    /**
     * @param array $config
     * @return void
     * @throws \Exception
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->getController()->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password',
                    ],
                    'userModel' => 'Users',
                ],
//                'ADmad/JwtAuth.Jwt' => [
////                    'scope' => ['Users.status' => 1],
//                    'parameter' => '_token',
//                   // 'userModel' => 'Users',
////                    'fields' => [
////                        'username' => 'email',
////                    ],
//                ],
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login',
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize',
            'authorize' => ['Controller'],

        ]);
    }
}
