<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Constants;
use App\Entity\UserContext;
use App\Entity\UserParameter;
use App\Entity\UserParameterNLC;
use App\Form\UserParameterNLCType;

use App\Api\AdministrationApi;

class ParameterController extends Controller
{
    /**
     * @Route("/parameter/numberLinesColumns/{entityCode}/{listPath}", name="parameter_numberLinesColumns")
     */
    public function index($entityCode, $listPath, Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$numberLines = AdministrationApi::getNumberLines($em, $connectedUser, $entityCode);
	$numberColumns = AdministrationApi::getNumberColumns($em, $connectedUser, $entityCode);

	$upRepository = $em->getRepository(UserParameter::Class);
	$userParameterNLC = new UserParameterNLC($numberLines, $numberColumns);
	$form = $this->createForm(UserParameterNLCType::class, $userParameterNLC);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {

			AdministrationApi::setNumberLines($em, $connectedUser, $entityCode, $userParameterNLC->getNumberLines());
			AdministrationApi::setNumberColumns($em, $connectedUser, $entityCode, $userParameterNLC->getNumberColumns());
			$request->getSession()->getFlashBag()->add('notice', 'number.lines.columns.updated.ok');
			return $this->redirectToRoute($listPath, array('page' => 1));
		}
	}

	return $this->render('parameter/numberLinesColumns.html.twig', array('userContext' => $userContext, 'entityCode' => $entityCode, 'listPath' => $listPath, 'form' => $form->createView()));
	}
}
