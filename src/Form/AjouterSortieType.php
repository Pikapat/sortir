<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Boolean;
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
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterSortieType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('titre', TextType::class, [
                    'label' => 'Titre : '
                ])
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
                    "attr" => ['onChange' => 'changeLieu()']

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
                        "attr" => ['onChange' => 'changeInfo()']
                    ])
                ->add('enregistrer', SubmitType::class, [
                ])
                ->add('publier', SubmitType::class, [
                ])
                ->add('annuler', ResetType::class,[
                ]);

                $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
                $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

                protected function addElements(FormInterface $form, Ville $ville = null, bool $clearLieus){

                    $form->add('ville', EntityType::class,  [
                        'mapped' => false,
                        'label' => 'Ville : ',
                        'class' => Ville::class,
                        "query_builder" => function(EntityRepository $er){
                            return $er->createQueryBuilder("s")->orderBy("s.nom", "ASC");
                        },
                        "choice_label" => "nom",
                        "expanded" => false,
                        "multiple" => false,
                        "attr" => ['onChange' => 'changeLieu()'],
                        'data' => $ville,
                        'placeholder' => 'Choisir une ville...'

                    ]);

                    if ($clearLieus)
                    {
                        $lieus = [];
                    }
                    else{
                        $lieus = $ville->getLieus();
                    }

                    $form->add('lieu', EntityType::class, [
                        'label' => 'Lieu : ',
                        "class" => Lieu::class,
                        "query_builder" => function(EntityRepository $er){
                            return $er->createQueryBuilder("s")->orderBy("s.nom", "ASC");
                        },
                        "choice_label" => "nom",
                        "expanded" => false,
                        "multiple" => false,
                        "attr" => ['onChange' => 'changeInfo()'],
                        'choices' => $lieus,
                        'placeholder' => 'Choisir une ville d\'abord...'
                    ]);
                }

                function onPreSubmit(FormEvent $event){

                    $form = $event->getForm();
                    $data = $event->getData();

                    $ville = $this->em->getRepository(Ville::class)->find($data['ville']);

                    $this->addElements($form, $ville, false);

                }
                function onPreSetData(FormEvent $event){

                    $sortie = $event->getData();
                    $form = $event->getForm();

                    $ville = null;

                    $this->addElements($form, $ville, true);

                }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
