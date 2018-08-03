<?php
// src/Form/UserFileAddType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\UserFile;

class UserFileAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('email', HiddenType::class)
			->add('lastName', TextType::class, array('label' => 'user.lastName', 'translation_domain' => 'messages'))
			->add('firstName', TextType::class, array('label' => 'user.firstName', 'translation_domain' => 'messages'))
			->add('administrator', HiddenType::class, array('data' => 0));
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array('data_class' => UserFile::class));
	}
}
