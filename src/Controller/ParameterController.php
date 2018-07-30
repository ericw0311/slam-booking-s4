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

	$upRepository = $em->getRepository(UserParameter::Class);

	$userParameterNLFound = false; // Le parametre nombre de lignes est-il present ?
	$userParameterNL = $upRepository->findOneBy(array('user' => $connectedUser, 'parameterGroup' => ($entityCode.'.number.lines.columns'), 'parameter' => ($entityCode.'.number.lines')));
	if ($userParameterNL != null) { $userParameterNLFound = true; $numberLines = $userParameterNL->getIntegerValue(); } else { $numberLines =  constant(Constants::class.'::LIST_DEFAULT_NUMBER_LINES'); }
	$userParameterNCFound = false; // Le parametre nombre de colonnes est-il present ?
	$userParameterNC = $upRepository->findOneBy(array('user' => $connectedUser, 'parameterGroup' => ($entityCode.'.number.lines.columns'), 'parameter' => ($entityCode.'.number.columns')));
	if ($userParameterNC != null) { $userParameterNCFound = true; $numberColumns = $userParameterNC->getIntegerValue(); } else { $numberColumns =  constant(Constants::class.'::LIST_DEFAULT_NUMBER_COLUMNS'); }
	$userParameterNLC = new UserParameterNLC($numberLines, $numberColumns);
	
	$form = $this->createForm(UserParameterNLCType::class, $userParameterNLC);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {
			$numberLines = $userParameterNLC->getNumberLines();
			$numberColumns = $userParameterNLC->getNumberColumns();
			if ($userParameterNLFound) {
				$userParameterNL->setSBIntegerValue($numberLines);
			} else {
				$userParameterNL = new UserParameter($connectedUser, $entityCode.'.number.lines.columns', $entityCode.'.number.lines');
				$userParameterNL->setSBIntegerValue($numberLines);
				$em->persist($userParameterNL);
			}
			if ($userParameterNCFound) {
				$userParameterNC->setSBIntegerValue($numberColumns);
			} else {
				$userParameterNC = new UserParameter($connectedUser, $entityCode.'.number.lines.columns', $entityCode.'.number.columns');
				$userParameterNC->setSBIntegerValue($numberColumns);
				$em->persist($userParameterNC);
			}
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'number.lines.columns.updated.ok');
			return $this->redirectToRoute($listPath, array('page' => 1));
		}
	}

	return $this->render('parameter/numberLinesColumns.html.twig', array('userContext' => $userContext, 'entityCode' => $entityCode, 'listPath' => $listPath, 'form' => $form->createView()));
	}
}
