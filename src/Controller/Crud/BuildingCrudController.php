<?php

namespace App\Controller\Crud;

use App\Entity\Building;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BuildingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Building::class;
    }


//    public function configureFields(string $pageName): iterable
//    {
//        return [
//            IdField::new('id'),
//            TextField::new('title'),
//            TextEditorField::new('description'),
//        ];
//    }

    function deletebuilding($id){
        try
        {
            if(!is_array($id))
            {
                $id = array($ids);
            }
            foreach($ids as $id)
            {
                $entity = $this->em->getPartialReference("building", $id);
                $this->em->remove($entity);
            }
            $this->em->flush();
            return TRUE;
        }
        catch(Exception $err)
        {
            return FALSE;
        }
    }

    public function createBuilding()
    {

    }

}
