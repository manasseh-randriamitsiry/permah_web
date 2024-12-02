<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    private function formatFileSize(?int $bytes): string
    {
        if ($bytes === null) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')
                ->setColumns(6),
            TextEditorField::new('description')
                ->setColumns(12),
            DateTimeField::new('date')
                ->setColumns(6),
            TextField::new('location')
                ->setColumns(6),
            MoneyField::new('price')
                ->setCurrency('USD')
                ->setColumns(6),
            AssociationField::new('user')
                ->setColumns(6)
                ->setFormTypeOption('choice_label', 'email')
                ->formatValue(function ($value, $entity) {
                    return $entity->getUser() ? $entity->getUser()->getEmail() : '';
                }),
            Field::new('mediaFile')
                ->setFormType(VichFileType::class)
                ->onlyOnForms()
                ->setFormTypeOptions([
                    'allow_delete' => true,
                    'download_uri' => true,
                ])
                ->setColumns(12),
            ImageField::new('mediaName', 'Media')
                ->setBasePath('/uploads/event_media')
                ->hideOnForm()
                ->setColumns(12),
            TextField::new('mediaMimeType', 'File Type')
                ->hideOnForm()
                ->setColumns(6),
            IntegerField::new('mediaSize', 'File Size')
                ->hideOnForm()
                ->setColumns(6)
                ->formatValue(function ($value, $entity) {
                    return $this->formatFileSize($value);
                }),
        ];
    }
}
