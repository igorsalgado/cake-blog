<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Controller\Component\ApiAuthComponent;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\UnauthorizedException;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AppController
{
    /**
     * @return void
     * @throws \Exception
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->RequestHandler->renderAs($this, 'json');
        //$this->loadComponent('Auth');
        //$this->loadComponent(ApiAuthComponent::class);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $posts = $this->paginate($this->Posts->find('all', [
            'contain' => ['Users', 'Categories'],
        ]));
        $this->set([
            'status' => 'success',
            'posts' => $posts,
            '_serialize' => ['posts'],
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $post = $this->Posts->get($id, [
            'contain' => ['Users', 'Categories', 'Comments'],
        ]);

        if (!$post) {
            throw new NotFoundException('Post not found');
        }

        $this->set([
            'post' => $post,
            '_serialize' => ['post'],
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $post = $this->Posts->newEmptyEntity();
//        $user = $this->checkAuth();
//        $post->user_id = $user['id'];

        if ($this->request->is('post')) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->set([
                    'message' => 'Post created successfully',
                    'post' => $post,
                    '_serialize' => ['message', 'post'],
                ]);
            } else {
                $this->set([
                    'errors' => $post->getErrors(),
                    '_serialize' => ['errors'],
                ]);
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $post = $this->Posts->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->set([
                    'message' => 'Post updated successfully',
                    'post' => $post,
                    '_serialize' => ['message', 'post'],
                ]);
            } else {
                throw new BadRequestException('The post could not be updated. Please, try again.');
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $post = $this->Posts->get($id);
        if ($this->Posts->delete($post)) {
            $this->set([
                'message' => 'Post deleted successfully',
                '_serialize' => ['message'],
            ]);
        } else {
            throw new BadRequestException('Unable to delete post');
        }
    }
}
