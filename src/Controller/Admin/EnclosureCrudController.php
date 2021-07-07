<?php

namespace App\Controller\Admin;

use App\Entity\Enclosure;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EnclosureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Enclosure::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
