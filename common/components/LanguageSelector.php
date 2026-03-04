<?php

namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Cookie;

class LanguageSelector implements BootstrapInterface
{
    public $supportedLanguages = ['en-US', 'uz-UZ', 'ru-RU'];

    public function bootstrap($app)
    {
        // AGAR KONSOL BO'LSA, TUXTATISH (SHU QATORNI QO'SHING)
        if ($app->request instanceof \yii\console\Request) {
            return;
        }
        $cookieLanguage = $app->request->cookies->getValue('language');
        $paramLanguage = $app->request->get('lang');

        // 1. Agar URL da ?lang=uz deb kelsa
        if ($paramLanguage !== null && in_array($paramLanguage, $this->supportedLanguages)) {
            $app->language = $paramLanguage;
            $cookie = new Cookie([
                'name' => 'language',
                'value' => $paramLanguage,
                'expire' => time() + 86400 * 365, // 1 yil
            ]);
            $app->response->cookies->add($cookie);
        }
        // 2. URLda yo'q, lekin Cookieda bor bo'lsa
        elseif ($cookieLanguage !== null && in_array($cookieLanguage, $this->supportedLanguages)) {
            $app->language = $cookieLanguage;
        }
    }
}
