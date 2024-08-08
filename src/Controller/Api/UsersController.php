<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * @return void
     * @throws \Exception
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function index()
    {
        $users = $this->Users->find('all');
        $this->set([
            'users' => $users,
            '_serialize' => ['users'],
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\NotFoundException When the user is not found.
     */
    public function view($id)
    {
        $user = $this->Users->get($id, ['contain' => []]);
        if (!$user) {
            throw new NotFoundException('User not found');
        }
        $this->set([
            'user' => $user,
            '_serialize' => ['user'],
        ]);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\UnauthorizedException When login fails.
     */
    public function login()
    {
        $this->request->allowMethod(['post']);

        $user = $this->Auth->identify();
        if (!$user) {
            $this->response = $this->response->withStatus(400);
            $this->set([
                'message' => 'Invalid credentials',
                '_serialize' => ['message'],
            ]);

            return;
        }

        $this->Auth->setUser($user);

        $token = JWT::encode([
            'sub' => $user['id'],
            'exp' => time() + 3600,
        ], Security::getSalt());

        $this->set([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                ],
            ],
            '_serialize' => ['status', 'data'],
        ]);
    }

    /**
     * Register method
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\BadRequestException When the user cannot be saved.
     */
    public function register()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->set([
                    'message' => 'User registered successfully',
                    'user' => $user,
                    '_serialize' => ['message', 'user'],
                ]);
            } else {
                $this->set([
                    'errors' => $user->getErrors(),
                    '_serialize' => ['errors'],
                ]);
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->set([
                    'message' => 'User updated successfully',
                    'user' => $user,
                    '_serialize' => ['message', 'user'],
                ]);
            } else {
                throw new BadRequestException('The user could not be saved. Please, try again.');
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\NotFoundException When the user is not found.
     * @throws \Cake\Http\Exception\BadRequestException When the user cannot be deleted.
     */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->set([
                'message' => 'User deleted successfully',
                '_serialize' => ['message'],
            ]);
        } else {
            throw new BadRequestException('Unable to delete user');
        }
    }
    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

}
