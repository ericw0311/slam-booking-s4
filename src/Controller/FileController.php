<?php
namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Entity\UserContext;
use App\Entity\ListContext;

use Symfony\Component\Routing\Annotation\Route;
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
    public function add()
    {
	return $this->render('file/add.html.twig');
    }
}
