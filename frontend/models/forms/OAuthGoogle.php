<?php

// TODO refactor

namespace frontend\models\forms;

use frontend\controllers\SocialController;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class OAuthGoogle
 * @package frontend\models
 */
class OAuthGoogle
{
    private const ID = '258341387058-9u59vrr5g1o87po7166dti4v3dm9oggd.apps.googleusercontent.com';
    private const SCOPE = 'https://www.googleapis.com/auth/userinfo.profile';
    private const SECRET = 'ceAoGhaI8pf2wehDg0Eoe9pH';
    private const URL_AUTH = 'https://accounts.google.com/o/oauth2/auth';
    private const URL_TOKEN = 'https://accounts.google.com/o/oauth2/token';
    private const URL_USER_INFO = 'https://www.googleapis.com/oauth2/v1/userinfo';

    /**
     * @param string $redirectUrl
     * @return string
     */
    public static function getConnectUrl(string $redirectUrl): string
    {
        $params = [
            'redirect_uri' => Url::to(['social/' . $redirectUrl, 'id' => SocialController::GOOGLE], true),
            'response_type' => 'code',
            'client_id' => self::ID,
            'scope' => self::SCOPE,
        ];

        return self::URL_AUTH . '?' . urldecode(http_build_query($params));
    }

    /**
     * @param $redirectUrl
     * @return string
     */
    public static function getId($redirectUrl): string
    {
        $code = Yii::$app->request->get('code');

        if (!$code) {
            return '';
        }

        $params = [
            'client_id' => self::ID,
            'client_secret' => self::SECRET,
            'redirect_uri' => Url::to(['social/' . $redirectUrl, 'id' => SocialController::GOOGLE], true),
            'grant_type' => 'authorization_code',
            'code' => $code,
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::URL_TOKEN);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        $result = curl_exec($curl);
        curl_close($curl);

        $tokenInfo = Json::decode($result);
        if (!isset($tokenInfo['access_token'])) {
            return '';
        }

        $params['access_token'] = $tokenInfo['access_token'];

        $userInfo = Json::decode(file_get_contents(self::URL_USER_INFO . '?' . urldecode(http_build_query($params))));
        return $userInfo['id'] ?? '';
    }
}
