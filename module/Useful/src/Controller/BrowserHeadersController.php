<?php
/**
 * Created by PhpStorm.
 * User: Raquel
 * Date: 28/01/2019
 * Time: 23:41
 */

namespace Useful\Controller;

/**
 * Class BrowserHeadersController
 * @package Useful\Controller
 */
class BrowserHeadersController
{
    /**
     * Obtem o idioma
     * See https://www.dyeager.org/2008/10/getting-browser-default-language-php.html
     * @return string
     */
    public static function getDefaultLanguage()
    {
        if (isset ($_SERVER ["HTTP_ACCEPT_LANGUAGE"]))
            return self::parseDefaultLanguage($_SERVER ["HTTP_ACCEPT_LANGUAGE"]);
        else
            return self::parseDefaultLanguage(NULL);
    }

    /**
     * @param $http_accept
     * @param string $deflang
     * @return string
     */
    public static function parseDefaultLanguage($http_accept, $deflang = "not set")
    {
        if (isset ($http_accept) && strlen($http_accept) > 1) {
            // Split possible languages into array
            $x = explode(",", $http_accept);
            foreach ($x as $val) {
                // check for q-value and create associative array. No q-value means 1 by rule
                if (preg_match("/(.*);q=([0-1]{0,1}.\d{0,4})/i", $val, $matches))
                    $lang [$matches [1]] = ( float )$matches [2];
                else
                    $lang [$val] = 1.0;
            }

            // return default language (highest q-value)
            $qval = 0.0;
            foreach ($lang as $key => $value) {
                if ($value > $qval) {
                    $qval = ( float )$value;
                    $deflang = $key;
                }
            }
        }
        return strtolower($deflang);
    }

    /**
     * Obtem o OS pelo browser
     * @return bool|mixed
     */
    public static function getOS()
    {
        $user_agent = isset($_SERVER ['HTTP_USER_AGENT']) ? $_SERVER ['HTTP_USER_AGENT'] : null;
        $os_platform = false;
        $os_array = array(
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }

        return $os_platform;
    }

    /**
     * To my surprise I found that none of the get_browser alternatives output the correct name / version combination that I was looking for using Opera or Chrome.
     * They either give the wrong name eg Safari when in fact it should be Chrome and if the ua string includes a version number as with the latest versions of Chrome and Opera the wrong number is reported. So I took bits and pieces from the various examples and combined them and added a check for version.
     *
     * @return array
     */
    public static function getBrowser()
    {
        $u_agent = isset ($_SERVER ['HTTP_USER_AGENT']) ? $_SERVER ['HTTP_USER_AGENT'] : null;
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";
        $ub = 'API';
        // First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array(
            'Version',
            $ub,
            'other'
        );
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches ['browser']);
        if ($i != 1) {
            // we will have two since we are not using 'other' argument yet
            // see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = isset ($matches ['version'] [0]) ? $matches ['version'] [0] : null;
            } else {
                $version = isset ($matches ['version'] [1]) ? $matches ['version'] [1] : null;
            }
        } else {
            $version = isset ($matches ['version'] [0]) ? $matches ['version'] [0] : null;
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }
}