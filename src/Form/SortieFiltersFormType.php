<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\SortieFilters;
use App\Entity\Ville;
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
                "query_builder" => function(EntityRepository $er){
                    return $er->createQueryBuilder("s")->orderBy("s.nom", "ASC");
                },
                "choice_label" => "nom",
                "expanded" => false,
                "multiple" => false
            ])
            ->add('textFilter', TextType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'attr' => ['placeholder' => 'Rechercher']
            ])
            ->add('dateDebut',DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text'
            ])
            ->add('dateFin',DateType::class, [
                'label' => 'et',
                'widget' => 'single_text'
            ])
            ->add('userOrga', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur'
            ])
            ->add('userInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit'
            ])
            ->add('userNonInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit'
            ])
            ->add('sortiePassee', CheckboxType::class, [
                'label' => 'Sorties passées'
            ])
            ->add('Rechercher', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieFilters::class,
        ]);
    }
}
