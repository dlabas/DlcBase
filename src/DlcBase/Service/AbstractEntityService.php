<?php
namespace DlcBase\Service;

use DlcBase\Mapper\AbstractMapper;
use DlcBase\Module\ModuleNamespaceAwareInterface;
use DlcBase\Options\ModuleOptionsAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Abstract service class
 */
class AbstractEntityService extends AbstractService
    implements ModuleNamespaceAwareInterface,
               ModuleOptionsAwareInterface,
               ServiceLocatorAwareInterface
{
    /**
     * Returns a list containig all entities
     */
    public function findAll()
    {
        return $this->getMapper()->findAll();
    }

    /**
     * Returns a single entity
     *
     * @param int $id
     */
    public function getById($id)
    {
        return $this->getMapper()->find($id);
    }

    /**
     * createFromForm
     *
     * @param array $data
     * @return \DlcUseCase\Entity\TypeInterface
     * @throws Exception\InvalidArgumentException
     */
    public function create(array $data, $skipCsrfCheck = true)
    {
        $class  = $this->getMapper()->getEntityClass();
        $entity = new $class;
        $form   = $this->getAddForm();

        if ($skipCsrfCheck) {
            $form->remove('csrf');
        }

        $form->bind($entity);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        //$this->getEventManager()->trigger(__FUNCTION__, $this, array('entity' => $entity, 'form' => $form));
        $this->getMapper()->save($entity);
        //$this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('entity' => $entity, 'form' => $form));

        return $entity;
    }

    /**
     * Updates an entity
     *
     * @param int $id
     * @param array $data
     * @return boolean|\DlcBase\Entity\AbstractEntity
     */
    public function update($id, array $data)
    {
        $this->validateId($id);

        $entity = $this->getById($id);
        $form   = $this->getEditForm();

        $form->bind($entity);
        $form->setData($data);

        if (!$valid = $form->isValid()) {
            return false;
        }

        $this->getMapper()->save($entity);

        return $entity;
    }

    /**
     * Deletes an entity
     *
     * @param int $id
     * @return \DlcUseCase\Service\Type
     */
    public function delete($id)
    {
        $this->validateId($id);

        $entity = $this->getMapper()->find($id);

        if (null === $entity) {
            throw new \InvalidArgumentException('No entity found for id "' . $id . '"');
        }

        $this->getMapper()->remove($entity);

        return $this;
    }

    /**
     * Returns a pagination object with entities
     *
     * @param int $page
     * @param int $limit
     * @param null|string $query
     * @param null|string $orderBy
     * @param string $sort
     * @param null|array $filter
     * @return \Zend\Paginator\Paginator
     */
    public function pagination($page, $limit, $query = null, $orderBy = null, $sort = 'ASC', $filter = null)
    {
        return $this->getMapper()->pagination($page, $limit, $query, $orderBy, $sort, $filter);
    }

    /**
     * Validates the ID of an entity
     *
     * @param int $id
     * @throws \InvalidArgumentException
     */
    public function validateId($id)
    {
        if (null === $id) {
            throw new \InvalidArgumentException('Category id missing');
        } elseif (!is_numeric($id) || (int)$id != $id) {
            throw new \InvalidArgumentException('Invalid data type for category id');
        }
    }
}