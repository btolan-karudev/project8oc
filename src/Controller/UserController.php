<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\RoleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     * @IsGranted("ROLE_ADMIN")
     *
     */
    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('App:User')->findAll()]);
    }

    /**
     * @Route("/users/{id}/toggleAdmin", name="admin_toggle")
     * @IsGranted("ROLE_ADMIN")
     */
    public function toggleAdmin(User $user, RoleRepository $roleRepository)
    {
        $roleZero = $user->getRoles()[0] ? $user->getRoles()[0] : "ROLE_USER_LAMBDA";
        if ($roleZero == "ROLE_USER_LAMBDA") {
            $lambdaRole = $roleRepository->findOneBy(['title' => 'ROLE_USER_LAMBDA']);
            $adminRole = $roleRepository->findOneBy(['title' => 'ROLE_ADMIN']);
            $user->removeUserRole($lambdaRole);
            $user->addUserRole($adminRole);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf('Le rôle a été changé avec success.'));

        }
        if ($roleZero == "ROLE_ADMIN") {
            $lambdaRole = $roleRepository->findOneBy(['title' => 'ROLE_USER_LAMBDA']);
            $adminRole = $roleRepository->findOneBy(['title' => 'ROLE_ADMIN']);
            $user->removeUserRole($adminRole);
            $user->addUserRole($lambdaRole);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf('Le rôle a été changé avec success.'));

        }

        return $this->redirectToRoute('user_list');
    }


    /**
     * @Route("/users/create", name="user_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($user->getUserRoles() as $role) {
                $role->addUser($user);
            }

            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editAction(User $user, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

}
