<?php

namespace App\Security;

use App\Entity\Korisnik;
use App\Repository\KorisnikRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{


    use TargetPathTrait;
    /**
     * @var KorisnikRepository
     */
    private $korisnikRepository;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(KorisnikRepository $korisnikRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $encoder,EntityManagerInterface $entityManager)
    {

        $this->korisnikRepository = $korisnikRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
    }


    public function supports(Request $request)
    {

         return 'user_login'===$request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials=array(
          'username'=>$request->request->get('username'),
          'password'=>$request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
      );

        $request->getSession()->set(Security::LAST_USERNAME,$credentials['username']);

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate',$credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        $users=$this->korisnikRepository->findOneBy(['username'=>$credentials['username']]);

        return $users;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {

      return $this->encoder->isPasswordValid($user,$credentials['password']) && $user->getAktiviran()===true && $user->getBlokiran() === false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if($targetPath=$this->getTargetPath($request->getSession(),$providerKey)){
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->router->generate('pocetakStranice'));
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $korisnik = $this->korisnikRepository->findOneBy([
            'username'=>$request->request->get('username')
        ]);
        if(!empty($korisnik) && $korisnik->getPokusajPrijave()<=3 && $korisnik->getBlokiran()===false){
            $korisnik->setPokusajPrijave($korisnik->getPokusajPrijave()+1);
            if($korisnik->getPokusajPrijave() === 3)$korisnik->setBlokiran(true);
            $this->entityManager->persist($korisnik);
            $this->entityManager->flush();

        }
    }

    protected function getLoginUrl()
    {
       return $this->router->generate('user_login');
    }
}
