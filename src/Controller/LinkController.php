<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\QrCode;

use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface as GoogleAuthenticatorTwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\QrCode\QrCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LinkController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserRepository $repository,EntityManagerInterface $em){

        $this->repository = $repository;
        $this->em = $em;

    }

    /**
     * @Route("/link", name="link")
     */
    public function index(): Response
    {
        return $this->render('link/index.html.twig', [
            'controller_name' => 'LinkController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'LinkController',
        ]);
    }



    /**
     * @Route("/user", name="user")
     */
    public function user(TokenStorageInterface $tokenStorage)
    {
        $user = $tokenStorage->getToken()->getUser();

        return $this->render('link/user.html.twig', [
            'displayQrCodeGa' => $user instanceof GoogleAuthenticatorTwoFactorInterface,
        ]);
    }

    /**
     * @Route("/user/qr/ga", name="qr_code_ga")
     */
    public function displayGoogleAuthenticatorQrCode(TokenStorageInterface $tokenStorage, QrCodeGenerator $qrCodeGenerator)
    {
        $user = $tokenStorage->getToken()->getUser();
        if (!($user instanceof GoogleAuthenticatorTwoFactorInterface)) {
            throw new NotFoundHttpException('Cannot display QR code');
        }

        return $this->displayQrCode($qrCodeGenerator->getGoogleAuthenticatorQrCode($user));
    }

    

    private function displayQrCode(QrCode $qrCode): Response
    {
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setSize(200);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setMargin(0);

        return new Response($qrCode->writeString(), 200, ['Content-Type' => 'image/png']);
    }

    /**
     * @Route("/user/backup-codes", name="backup_codes")
     */
    public function generateBackupCodes(TokenStorageInterface $tokenStorage)
    {
        
        $userId = $this->getUser()->getId();
        echo $userId;
        $user = $this->repository->findOneBy(array('id'=> $userId));
        print_r($user) ;
        if(!$user){
            throw $this->createNotFoundException(
                'No user found for this email '. $userId
            );
         }else{
                $user->setBackup_code1(mt_rand(100000,999999));
                $user->setBackup_code2(mt_rand(100000,999999));
                $user->setBackup_code3(mt_rand(100000,999999));
                $user->setBackup_code4(mt_rand(100000,999999));
                $user->setBackup_code5(mt_rand(100000,999999));
                $this->em->flush();
            
        }
        return $this->redirectToRoute('user');
        



    }


}
