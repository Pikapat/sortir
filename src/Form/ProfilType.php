<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('email')
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'password-field']],

                'first_options'  => ['label' => 'Password', 'required' => false],
                'second_options' => ['label' => 'Confirmation', 'required' => false],
                ])


            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                "class" => Campus::class,
                "query_builder" => function(EntityRepository $er){
                    return $er->createQueryBuilder("s")->orderBy("s.nom", "ASC");
                },
                "choice_label" => "nom",
                "expanded" => false,
                "multiple" => false
            ])

            ->add('picture', FileType::class, [
                'label' => 'Brochure (PDF file)',
                'mapped' => false,
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [

                        ],
                        'mimeTypesMessage' => 'Please upload a valid img document',
                    ])
                ],
            ])

            ->add('submit', SubmitType::class,[
                'label' => 'Enregistrer',
            ])

            ->add('reset', ResetType::class,[
                'label' => 'Réinitialiser',
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // mette la classe à etre associe au formulaire model
            'data_class' => User::class,
        ]);
    }
}
