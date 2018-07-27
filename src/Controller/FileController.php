<?php
namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Entity\UserContext;
use App\Entity\ListContext;
use App\Form\FileType;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class FileController extends Controller
{
    /**
     * @Route("/file/{page}", name="file", requirements={"page"="\d+"})
     */
    public function index($page)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $fRepository = $em->getRepository(File::Class);
    $numberRecords = $fRepository->getUserFilesCount($connectedUser);

    $listContext = new ListContext($em, $connectedUser, 'file', $page, $numberRecords);

    $listFiles = $fRepository->getUserDisplayedFiles($connectedUser, $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
    
	return $this->render('file/index.html.twig', array('userContext' => $userContext, 'listContext' => $listContext, 'listFiles' => $listFiles));
    }
	
    /**
     * @Route("/file/add", name="file_add")
     */
    public function add(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$file = new File($connectedUser);
	$form = $this->createForm(FileType::class, $file);
	
	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($file);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'file.created.ok');
			return $this->redirectToRoute('file', array('page' => 1));
		}
    }

	return $this->render('file/add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }
}
