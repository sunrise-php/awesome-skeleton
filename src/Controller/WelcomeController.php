<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dictionary\LanguageCode;
use App\Dictionary\TranslationDomain;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Message\Response\HtmlResponse;
use Sunrise\Http\Router\Annotation\GetRoute;
use Sunrise\Http\Router\Helper\TemplateRenderer;
use Sunrise\Http\Router\ServerRequest;
use Sunrise\Translator\TranslatorManagerInterface;

#[GetRoute('welcome', '/')]
final class WelcomeController implements RequestHandlerInterface
{
    public const LANGUAGE_CODE_VAR_NAME = 'language_code';
    public const GREETING_MESSAGE_VAR_NAME = 'greeting_message';

    private const TEMPLATE_FILENAME = __DIR__ . '/../../resources/templates/welcome.phtml';

    public function __construct(
        private readonly TranslatorManagerInterface $translatorManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $clientPreferredLanguage = ServerRequest::create($request)
            ->getClientPreferredLanguage(...LanguageCode::cases())
                ?? LanguageCode::English;

        $translatedGreetingMessage = $this->translatorManager->translate(
            TranslationDomain::APP,
            $clientPreferredLanguage->getCode(),
            'Welcome!',
        );

        $template = TemplateRenderer::renderTemplate(self::TEMPLATE_FILENAME, [
            self::LANGUAGE_CODE_VAR_NAME => $clientPreferredLanguage->getCode(),
            self::GREETING_MESSAGE_VAR_NAME => $translatedGreetingMessage,
        ]);

        return new HtmlResponse(200, $template);
    }
}
