<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Controller\Component\ApiAuthComponent;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
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
     * @return void Renders view
     */
    public function index()
    {
        $categories = $this->Categories->find('all');
        $this->set([
            'categories' => $categories,
            '_serialize' => ['categories'],
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id)
    {
        $category = $this->Categories->get($id, ['contain' => []]);
        if (!$category) {
            throw new NotFoundException('Category not found');
        }
        $this->set([
            'category' => $category,
            '_serialize' => ['category'],
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->set([
                    'message' => 'Category created successfully',
                    'category' => $category,
                    '_serialize' => ['message', 'category'],
                ]);
            } else {
                $this->set([
                    'errors' => $category->getErrors(),
                    '_serialize' => ['errors'],
                ]);
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $category = $this->Categories->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->set([
                    'message' => 'Category updated successfully',
                    'category' => $category,
                    '_serialize' => ['message', 'category'],
                ]);
            } else {
                throw new BadRequestException('Unable to update category');
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->set([
                'message' => 'Category deleted successfully',
                '_serialize' => ['message'],
            ]);
        } else {
            throw new BadRequestException('Unable to delete category');
        }
    }
}
