<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactMessageType extends AbstractType
{

    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $maxSize = $this->params->get('MAX_ATTACHMENT_SIZE');

        $builder
            ->add('subject', TextType::class, ['label' => 'Sujet'])
            ->add('message', TextareaType::class, ['label' => 'Message'])
            ->add('attachment', FileType::class, [
                'label' => 'Pièce jointe (max 2Mo)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => $maxSize,
                        'mimeTypes' => ['application/pdf','image/*','text/plain'],
                        'mimeTypesMessage' => 'Fichier PDF, image ou texte seulement',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
    }
}
