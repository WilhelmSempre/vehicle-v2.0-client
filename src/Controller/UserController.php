<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateForm;
use App\Form\LoginForm;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/user", name="user_")
 *
 * Class UserController
 * @package App\Controller
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class UserController extends AbstractController
{

    /**
     * @Route("/create", name="create")
     *
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param UserService $userService
     * @param TranslatorInterface $translator
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function createAction(Request $request, FormFactoryInterface $formFactory, UserService $userService, TranslatorInterface $translator): Response
    {
        $createForm = $formFactory->create(CreateForm::class);

        if ($request->isMethod(Request::METHOD_POST)) {
            $createForm->handleRequest($request);

            if (count($createForm->getErrors(true)) === 0) {

                /** @var User $user */
                $user = $createForm->getData();

                /** @var ResponseInterface $response */
                $isUser = $userService->isUser($user);

                if ($isUser) {
                    $this->addFlash('error', $translator->trans('user.create.alert.user_exists'));

                    return $this->redirectToRoute('user_create');
                }

                /** @var ResponseInterface $response */
                $response = $userService->createUser($user);

                if ($response && $response->getStatusCode() === Response::HTTP_OK) {
                    $this->addFlash('success', $translator->trans('user.create.alert.success'));
                } else {
                    $this->addFlash('error', $translator->trans('user.create.alert.error'));
                }

                return $this->redirectToRoute('user_create');
            }
        }

        $parameters = [
            'createForm' => $createForm->createView(),
        ];

        return $this->render('User/create.html.twig', $parameters);
    }

    /**
     * @Route("/login", name="login")
     *
     * @param FormFactoryInterface $formFactory
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function loginAction(FormFactoryInterface $formFactory, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $loginForm = $formFactory->create(LoginForm::class);

        $parameters = [
            'loginForm' => $loginForm->createView(),
            'error' => $error,
        ];

        return $this->render('User/login.html.twig', $parameters);
    }

    /**
     * @Route("/logout", name="logout")
     *
     * @return void
     */
    public function logoutAction()
    {

    }
}