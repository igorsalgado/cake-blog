<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Controller\Component\ApiAuthComponent;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
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

        $this->loadComponent(ApiAuthComponent::class);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $comments = $this->paginate($this->Comments->find('all', [
            'contain' => ['Posts']
        ]));
        $this->set([
            'comments' => $comments,
            '_serialize' => ['comments']
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => ['Posts']
        ]);
        if (!$comment) {
            throw new NotFoundException('Comment not found');
        }
        $this->set([
            'comment' => $comment,
            '_serialize' => ['comment']
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $comment = $this->Comments->newEmptyEntity();
        if ($this->request->is('post')) {
            $comment = $this->Comments->patchEntity($comment, $this->request->getData());
            if ($this->Comments->save($comment)) {
                $this->set([
                    'message' => 'Comment created successfully',
                    'comment' => $comment,
                    '_serialize' => ['message', 'comment']
                ]);
            } else {
                $this->set([
                    'errors' => $comment->getErrors(),
                    '_serialize' => ['errors']
                ]);
            }
        }
    }


    /**
     * edit method
     *
     * @param $id
     * @return void
     */
    public function edit($id = null)
    {
        $comment = $this->Comments->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comment = $this->Comments->patchEntity($comment, $this->request->getData());
            if ($this->Comments->save($comment)) {
                $this->set([
                    'message' => 'Comment updated successfully',
                    'comment' => $comment,
                    '_serialize' => ['message', 'comment']
                ]);
            } else {
                throw new BadRequestException('The comment could not be updated. Please, try again.');
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        if ($this->Comments->delete($comment)) {
            $this->set([
                'message' => 'Comment deleted successfully',
                '_serialize' => ['message']
            ]);
        } else {
            throw new BadRequestException('Unable to delete comment');
        }
    }
}
