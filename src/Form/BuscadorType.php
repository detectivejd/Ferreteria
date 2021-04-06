<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
class BuscadorType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder	
            ->add('dato',TextType::class,[
                "required" => false,
                "attr" => [
                    "class" => "form-control border-secondary border-right-0 rounded-0",
                    "placeholder" => "Iniciar búsqueda"
                
                ]
            ])
            ;
    }    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver){}
}
