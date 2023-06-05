<?php

namespace App\Controller\Admin;

use App\Entity\College;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CollegeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return College::class;
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
