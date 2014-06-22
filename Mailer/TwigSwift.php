<?php

namespace Magice\Bundle\UserBundle\Mailer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Magice\Bundle\UserBundle\OAuth\Response\ResponseInterface;
use FOS\UserBundle\Model\UserInterface;

class TwigSwift
{
    protected $mailer;
    protected $router;
    protected $twig;
    protected $parameters;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, array $parameters)
    {
        $this->mailer     = $mailer;
        $this->router     = $router;
        $this->twig       = $twig;
        $this->parameters = $parameters;
    }

    public function sendNewConnectedEmailMessage(UserInterface $user, ResponseInterface $response)
    {
        $template = $this->parameters['template']['connect_new'];

        $context = array(
            'user'      => $user,
            'response'  => $response,
            'site_name' => $this->parameters['site_name']
        );

        $this->sendMessage($template, $context, $user->getEmail(), $this->parameters['from_mail']);
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $toEmail
     * @param array  $from
     */
    protected function sendMessage($templateName, $context, $toEmail, $from)
    {
        $context  = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject  = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from['mail'], $from['name'])
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}
