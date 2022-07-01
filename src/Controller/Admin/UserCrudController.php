<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection as CollectionFilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository as OrmEntityRepository;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private OrmEntityRepository $entityRepo,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, CollectionFilterCollection $filters): QueryBuilder
    {
        $userId = $this->getUser()->getId();

        $response = $this->entityRepo->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.id != :userId')->setParameter('userId', $userId );

        return $response;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('User');
    }
        
  
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username');

        yield EmailField::new('email');

        yield TextField::new('password')->onlyOnForms()
        ->setFormType(PasswordType::class);

        yield ChoiceField::new('roles')
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_ADMIN' => 'success',
                'ROLE_AUTHOR' => 'warning'
            ])
            ->setChoices([
                'Administrator' => 'ROLE_ADMIN',
                'Author' => 'ROLE_AUTHOR'
            ]);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
                /** @var User $user */
                $user = $entityInstance;

                $plainPassword = $user->getPassword();
                $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        
                $user->setPassword($hashedPassword);
        
                parent::persistEntity($entityManager, $user);
    }

}
