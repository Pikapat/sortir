<?php

namespace App\Form;

use App\Entity\Campus;
use App\Form\Model\SortieFilters;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFiltersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                "choice_label" => "nom",
                "expanded" => false,
                "multiple" => false
            ])
            ->add('textFilter', TextType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'attr' => ['placeholder' => 'Rechercher'],
                'required' => false
            ])
            ->add('dateDebut',DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('dateFin',DateType::class, [
                'label' => 'et',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('userOrga', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur',
                'value' => false,
                'required' => false,

            ])
            ->add('userInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit',
                'value' => false,
                'required' => false,

            ])
            ->add('userNonInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit',
                'value' => false,
                'required' => false,
            ])
            ->add('sortiePassee', CheckboxType::class, [
                'label' => 'Sorties passées',
                'value' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieFilters::class,
        ]);
    }
}
