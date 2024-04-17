<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function register(ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        $regform = $this->createFormBuilder()
        ->add('username', TextType::class,[
            'label'=> 'User'
        ])
        ->add('password', RepeatedType::class,[
            'type' => PasswordType::class,
            'required' => true,
            'first_options'=>['label' => 'Password'],
            'second_options'=>['label' => 'Repeat Password']
        ])
        ->add('Register', SubmitType::class)
        ->getForm(); //creo el formulario

        $regform->handleRequest($request); //hago la llamada al entity manager
        if($regform -> isSubmitted()){ //si el formulario se envio
            $em = $doctrine->getManager(); //utilizo la llamada al em
            $input = $regform->getData(); //traigo los datos que mando del form y los guardo en $input
            // dump($input); //muestro los datos que traigo del form enviado
            $user = new User(); //Creo una nueva instancia del objeto User
            $user->setUsername($input['username']); //$user->setUsername() es como cuando hago user.Username() en python/js
            $user->setPassword(
                $passwordHasher->hashPassword($user, $input['password']) //hasheo la password antes de subirla a la db
            );
            $em -> persist($user); //commiteo la db
            $em->flush(); //confirmo el commit

            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('registration/index.html.twig', [
            'regform' => $regform->createView()
        ]);


    }
}
