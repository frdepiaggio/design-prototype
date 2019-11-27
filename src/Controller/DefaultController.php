<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {

        $usuarioActual = $this->getUser();

        $tieneAccesoGerenteGeneral = $this->isGranted('ROLE_GERENTE_GENERAL');
        $tieneAccesoGerenteProduccion = $this->isGranted('ROLE_GERENTE_PRODUCCION');
        $tieneAccesoGerenteComercializacion = $this->isGranted('ROLE_GERENTE_COMERCIALIZACION');

        if ($tieneAccesoGerenteGeneral) {
            return $this->render('dashboard/gerente-general.html.twig');
        }
        if ($tieneAccesoGerenteComercializacion) {
            return $this->render('dashboard/gerente-comercializacion.html.twig');
        }
        if ($tieneAccesoGerenteProduccion) {
            return $this->render('dashboard/gerente-produccion.html.twig');
        }
        return $this->render('dashboard/gerente-general.html.twig');
    }
}
