<?php

namespace AppBundle\Repository;

use App\Entity\Task;
use Doctrine\ORM\EntityRepository;

/**
 * @method task|null find($id, $lockMode = null, $lockVersion = null)
 * @method task|null findOneBy(array $criteria, array $orderBy = null)
 * @method task[]    findAll()
 * @method task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends EntityRepository
{
}
