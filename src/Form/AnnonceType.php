<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Tapez le titre de votre article !"))
            ->add('slug', TextType::class, $this->getconfiguration("Adresse web (automatique si non précisée)", "Tapez l'adresse web selon l'exemple suivant: mon-nom-d-article-! (automatique si non précisée)", [
                'required' => false
            ]))
            ->add('coverImage', TextType::class, $this->getConfiguration("URL de l'image principale", "Donnez l'adresse d'une image en lien avec l'article"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Renseigner une description de l'article"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Tapez une description complémentaire des informations techniques et du type de produit"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix de l'article", "Indiquez le prix de l'article"))

            /* Formulaire multi images
            ->add('images',CollectionType::class,[
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
                ]
            )*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
