<?php

namespace Appsco\Dashboard\ApiBundle\Security\Http\Firewall\RelyingParty;

use Appsco\Dashboard\ApiBundle\OAuth\AppscoOAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\HttpUtils;

class OAuthStartRelyingParty implements RelyingPartyInterface
{
    /** @var  HttpUtils */
    protected $httpUtils;

    /** @var  AppscoOAuth */
    protected $oauth;

    /** @var  array|string[] */
    protected $scope;

    /** @var  string */
    protected $redirectUrl;

    /**
     * @param HttpUtils $httpUtils
     * @param AppscoOAuth $oauth
     * @param array $scope
     * @param $redirectUrl
     */
    public function __construct(HttpUtils $httpUtils, AppscoOAuth $oauth, array $scope, $redirectUrl)
    {
        $this->httpUtils = $httpUtils;
        $this->oauth = $oauth;
        $this->scope = $scope;
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $this->httpUtils->checkRequestPath($request, $request->attributes->get('oauth_start_path'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \InvalidArgumentException if cannot manage the Request
     * @return \Symfony\Component\HttpFoundation\Response|TokenInterface|null
     */
    public function manage(Request $request)
    {
        if (false == $this->supports($request)) {
            throw new \InvalidArgumentException('Unsupported request');
        }

        return $this->oauth->start($this->scope, $this->redirectUrl);
    }

}