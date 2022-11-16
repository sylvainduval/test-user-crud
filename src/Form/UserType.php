<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', EmailType::class, [
				'label' => 'Email',
				'invalid_message' => 'Cet email n\'est pas valide',
				'constraints' => [
					new Email(),
				],
			])
			->add('firstName', TextType::class, [
				'label' => 'Prénom',
			])
			->add('lastName', TextType::class, [
				'label' => 'Nom',
			])
			->add('birthdayDate', DateType::class, [
				'label' => 'Date de naissance',
				'years' => range(date('Y') - 100, date('Y') - 5),
			])
			->add('save', SubmitType::class, [
				'label' => 'Enregistrer',
			]);
	}
}