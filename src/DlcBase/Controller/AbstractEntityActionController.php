<?php
namespace DlcBase\Controller;

use DlcBase\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

/**
 * Abstract controller class for basic entity operations
 */
abstract class AbstractEntityActionController extends AbstractActionController
{
    /**
     * Index action for viewing a pagination list
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $query = (string) $this->params()->fromQuery('query', null);
        $query = strlen($query) > 0 ? $query : null;
        
        $orderBy = (string) $this->params()->fromQuery('orderBy', null);
        $orderBy = strlen($orderBy) > 0 ? $orderBy : null;
        
        $sort = (string) $this->params()->fromQuery('sort', null);
        $sort = strlen($sort) > 0 ? $sort : null;
        
        $page  = (int) $this->params()->fromRoute('page', 1);
        $limit = $this->getOptions()->getDefaultItemsPerPage();
        
        $entities = $this->getService()->pagination($page, $limit, $query, $orderBy, $sort);
        
        $view = new ViewModel(array(
            'options'  => $this->getOptions(),
            'entities' => $entities,
            'query'    => $query,
            'orderBy'  => $orderBy,
            'sort'     => $sort,
        ));
        
        return $view;
    }
    
    /**
     * List action for viewing a pagination list
     * 
     * @return ViewModel
     */
    public function listAction()
    {
        $page  = (int) $this->params()->fromRoute('page', 1);
        return $this->forward()->dispatch($this->getRouteIdentifierPrefix(), array('action' => 'index', 'page' => $page));
    }
    
    /**
     * Show action for viewing a single entity
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function showAction()
    {
        $id = (int) $this->params()->fromRoute('id', null);
        
        $service = $this->getService();
        $entity  = $service->getById($id);
        
        return new ViewModel(array(
            'entity' => $entity
        ));
    }
    
    /**
     * Add action for adding a new entity
     * 
     * @return ViewModel
     */
    public function addAction()
    {
        $request = $this->getRequest();
        $service = $this->getService();
        $form    = $this->getAddForm();

        //if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
        if ($request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        $redirectUrl = $this->url()->fromRoute($this->getAddActionRoute())
                     . ($redirect ? '?redirect=' . $redirect : '');
        
        $prg = $this->prg($redirectUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'form'     => $form,
                'redirect' => $redirect,
            );
        }

        $post   = $prg;
        $entity = $service->create($post);

        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$entity) {
            return array(
                'form'     => $form,
                'redirect' => $redirect,
            );
        }

        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()->fromRoute($this->getRouteIdentifierPrefix()) . ($redirect ? '?redirect='.$redirect : ''));
    }
    
    /**
     * Edit action for editing an entity
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', null);
        
        $request = $this->getRequest();
        $service = $this->getService();
        $entity  = $service->getById($id);
        
        $form     = $this->getEditForm();
        $form->bind($entity);
        
        if ($request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }
        
        $redirectUrl = $this->url()->fromRoute($this->getEditActionRoute(), array('id' => $id)) . ($redirect ? '?redirect=' . $redirect : '');
        $prg = $this->prg($redirectUrl, true);
        
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'id'       => $id,
                'form'     => $form,
                'redirect' => $redirect,
            );
        }
        
        $post   = $prg;
        $entity = $service->update($id, $post);
        
        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;
        
        if (!$entity) {
            return array(
                'id'       => $id,
                'form'     => $form,
                'redirect' => $redirect,
            );
        }
        
        return $this->redirect()->toUrl($this->url()->fromRoute($this->getRouteIdentifierPrefix()) . ($redirect ? '?redirect='.$redirect : ''));
    }
    
    /**
     * Delete action for deleting an entity
     * 
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', null);
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $confirmed = $request->getPost('confirmed', false);
            if ($confirmed) {
                $id = (int) $request->getPost('id');
        
                $this->getService()->delete($id);
        
                return $this->redirect()->toRoute($this->getRouteIdentifierPrefix());
            }
        }
        
        return new ViewModel(array(
        
        ));
    }
}