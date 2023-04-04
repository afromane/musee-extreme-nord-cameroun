<?php

namespace App\Controller\Admin;

use App\Entity\Musee;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class MuseeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Musee::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
           // IdField::new('id'),
            TextField::new('nom'),
            TextField::new('ville'),
            TextField::new('region'),
            TextEditorField::new('description'),

            CollectionField::new('images')
                ->setEntryType(ImageType::class)
                ->onlyOnForms()
        ];
    }
    
}
