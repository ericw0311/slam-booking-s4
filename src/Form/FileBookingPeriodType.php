<?php
// src/Form/FileBookingPeriodType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\FileBookingPeriod;

class FileBookingPeriodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('before', CheckboxType::class, array('label' => 'file.booking.period.before', 'translation_domain' => 'messages', 'required' => false))
			->add('periodType', ChoiceType::class, array(
			'label' => 'period.type',
			'translation_domain' => 'messages',
			'choices' => array('DAY' => 'DAY', 'WEEK' => 'WEEK', 'MONTH' => 'MONTH', 'YEAR' => 'YEAR'),
			'choice_label' => function ($value, $key, $index) { return 'period.type.'.$key; }
        ))
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array('data_class' => FileBookingPeriod::class));
	}
}
