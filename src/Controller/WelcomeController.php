<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dictionary\LanguageCode;
use App\Dictionary\MediaType;
use App\Dictionary\TranslationDomain;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Message\Response\HtmlResponse;
use Sunrise\Http\Message\Response\JsonResponse;
use Sunrise\Http\Router\Annotation\GetRoute;
use Sunrise\Http\Router\Helper\TemplateRenderer;
use Sunrise\Http\Router\ServerRequest;
use Sunrise\Translator\TranslatorManagerInterface;

#[GetRoute('welcome', '/')]
final class WelcomeController implements RequestHandlerInterface
{
    public const LANGUAGE_CODE_VAR_NAME = 'language_code';
    public const WELCOME_MESSAGE_VAR_NAME = 'welcome_message';

    private const TEMPLATE_FILENAME = __DIR__ . '/../../resources/templates/welcome.phtml';

    public function __construct(
        private readonly TranslatorManagerInterface $translatorManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $serverRequest = ServerRequest::create($request);

        $clientPreferredLanguage = $serverRequest
            ->getClientPreferredLanguage(...LanguageCode::cases())
                ?? LanguageCode::English;

        $clientPreferredMediaType = $serverRequest
            ->getClientPreferredMediaType(...MediaType::cases())
                ?? MediaType::JSON;

        $translatedWelcomeMessage = $this->translatorManager->translate(
            domain: TranslationDomain::APP,
            locale: $clientPreferredLanguage->getCode(),
            template: 'Welcome!',
        );

        return match ($clientPreferredMediaType) {
            MediaType::HTML => new HtmlResponse(200, TemplateRenderer::renderTemplate(self::TEMPLATE_FILENAME, [
                self::LANGUAGE_CODE_VAR_NAME => $clientPreferredLanguage->getCode(),
                self::WELCOME_MESSAGE_VAR_NAME => $translatedWelcomeMessage,
            ])),
            MediaType::JSON => new JsonResponse(200, [
                'message' => $translatedWelcomeMessage,
            ]),
        };
    }
}
