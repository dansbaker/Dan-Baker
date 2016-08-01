<?php
namespace AppBundle\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use \AppBundle\Entity\User;
use PHPUnit_Framework_TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



class MessageTest extends KernelTestCase
{
    private $entityManager;
    private $container;
    private $message;

    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->entityManager  = $this->getMockBuilder(EntityManager::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $this->message = new Message($this->entityManager);
    }


    public function testGetMessagesSince()
    {
        $time = date('Y-m-d H:i:s');

        $repository = $this->getMockBuilder(EntityRepository::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $this->entityManager->expects($this->once())
                            ->method('getRepository')
                            ->with(Message::class)
                            ->will($this->returnValue($repository));

        $queryBuilder = $this->getMockBuilder(QueryBuilder::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $repository->expects($this->once())
                   ->method('createQueryBuilder')
                   ->with('e')
                   ->will($this->returnValue($queryBuilder));

        $queryBuilder->expects($this->at(0))
                     ->method('where')
                     ->with('e.timestamp > :sql_date')
                     ->will($this->returnValue($queryBuilder));

        $queryBuilder->expects($this->at(1))
                     ->method('setParameter')
                     ->with('sql_date', $time)
                     ->will($this->returnValue($queryBuilder));

        $getQuery = $this->getMockBuilder(AbstractQuery::class)
                         ->setMethods(array('getResult'))
                         ->disableOriginalConstructor()
                         ->getMockForAbstractClass();

        $queryBuilder->expects($this->at(2))
                     ->method('getQuery')
                     ->will($this->returnValue($getQuery));

        $this->message->getMessagesSince($time);
    }

    public function testSaveMessage()
    {
         $message = $this->getMockBuilder(Message::class)->disableOriginalConstructor()->getMock($this->entityManager);
         $user = $this->container->get('app.users')->findById(1); //TODO: Mock User Class
         $message->expects($this->once())
                 ->method('saveMessage')
                 ->with($user, 'test')
                 ->will($this->returnValue(true));
        $message->saveMessage($user, 'test');
    }

   
}
