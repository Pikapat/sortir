<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterSortieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('titre', TextType::class, [
                    'label' => 'Titre : '
                ] )
            ->add('dateHeureDebut', DateType::class, [
                    'label' => 'Début : ',
                    'widget' => 'single_text'
            ])
            ->add('duree', IntegerType::class, [
                    'label' => 'Durée en heure : '
            ])
            ->add('dateLimiteInscription', DateType::class, [
                    'label' => 'Date limite d\'inscription : ',
                    'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                    'label' => 'Nombre d\'inscription maximum : '
            ])
            ->add('infosSortie', TextareaType::class, [
                    'label' => 'Informations : '
            ])
            ->add('siteOrganisateur', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                "query_builder" => function(EntityRepository $er){
                    return $er->createQueryBuilder("s")->orderBy("s.nom", "ASC");
                },
                "choice_label" => "nom",
                "expanded" => false,
                "multiple" => false,

            ])
            ->add('ville', EntityType::class,  [
                    'mapped' => false,
                    'label' => 'Ville : ',
                    'class' => Ville::class,
                    "query_builder" => function(EntityRepository $er){
                        return $er->createQueryBuilder("s")->orderBy("s.nom", "ASC");
                    },
                    "choice_label" => "nom",
                    "expanded" => false,
                    "multiple" => false,
                    "attr" => ['onChange => changeLieu()']

            ])
            ->add('lieu', EntityType::class, [
                    'label' => 'Lieu : ',
                    "class" => Lieu::class,
                    "query_builder" => function(EntityRepository $er){
                        return $er->createQueryBuilder("s")->orderBy("s.nom", "ASC");
                    },
                    "choice_label" => "nom",
                    "expanded" => false,
                    "multiple" => false,


                ])

            ->add('enregistrer', SubmitType::class, [
            ])
            ->add('publier', SubmitType::class, [
            ])
            ->add('annuler', ResetType::class,[

            ])

            ;

                }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
