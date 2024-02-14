<?php /** @noinspection PhpDuplicateSwitchCaseBodyInspection */

namespace Useful\Controller;

use Admin\Controller\AbstractController;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use function GuzzleHttp\json_encode;
/**
 * Class UsefulController
 * @package Useful\Controller
 */
class UsefulController
{

    /**
     * Retorna um valor em um array, se existir
     *
     * @param $data
     * @param $index
     * @param null $default
     * @param int $maxlength , tamanho maximo da string, soh funciona para string|number
     * @param string $trimmarker , A string that is added to the end of string when string is truncated.
     * @param bool $slashes
     * @param bool $strip
     * @return array|mixed|string|null
     */


    public static function getValueInArray($data, $index, $default = null, $maxlength = 0, $trimmarker = "", $slashes = true, $strip = false)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }
        $result = $default;
        if (isset($data[$index])) {
            if (is_array($data[$index])) {
                $result = $data[$index];
            } else if (is_object($data[$index])) {
                $result = get_object_vars($data[$index]);
            } else {
                $str = $data[$index];
                if ($maxlength > 0) {
                    $str = mb_strimwidth($str, 0, $maxlength, $trimmarker);
                }
                $result = $slashes ? addslashes($str) : ($strip ? stripslashes($str) : $strip);
            }
        }
        return $result;
    }

    /**
     * Retorna um valor presente no header
     * @param \Laminas\Http\Headers $header
     * @param $name
     * @param null $default
     * @return string|null
     */
    public static function getValueInHeader(\Laminas\Http\Headers $header, $name, $default = null)
    {
        $result = $default;
        if (is_a($header, 'Laminas\Http\Headers')) {
            if ($header->has($name)) {
                return addslashes($header->get($name)->getFieldValue());
            }
        }
        return $result;
    }

    /**
     * @param $data
     * @return array|mixed
     */

    public static function unserialize2array($data)
    {
        $obj = unserialize($data);
        if (is_array($obj))
            return $obj;
        $arr = array();
        foreach ($obj as $k => $v) {
            $arr[$k] = $v;
        }
        unset($arr['__PHP_Incomplete_Class_Name']);
        return $arr;
    }

    /**
     * Codigo da plataforma
     *
     * @param array $data
     * @return number
     */
    public static function getPlatformInValueArray($data)
    {
        $platform = strtoupper(self::getValueInArray($data, 'platform', 'ANDROID'));
        if ($platform == 'GOOGLE' || $platform == 'ANDROID') {
            return 1;
        } else if ($platform == 'APPLE' || $platform == 'IOS') {
            return 2;
        }
        return 0;
    }

    /**
     * Retorna a diferenca dos arrays, se chave existe ambos os arrays
     * @param $haystack
     * @param $needle
     * @return mixed
     */
    public static function getValuesIfExistsIn($haystack, $needle)
    {
        foreach ($needle as $key => $item) {
            if (!is_null(self::getValueInArray($haystack, $key))) {
                unset($needle[$key]);
            }
        }
        return $needle;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return mixed
     */
    public static function getValuesIfExistsInReverse($haystack, $needle)
    {
        foreach ($needle as $key => $item) {
            if (is_null(self::getValueInArray($haystack, $key))) {
                unset($needle[$key]);
            }
        }
        return $needle;
    }

    /**
     * Retorna true se a variavel contem valor, eh sim ou maior que zero
     * @param $haystack
     * @return mixed
     */
    public static function getBooleanIfExistIn($haystack)
    {
        foreach ($haystack as $key => $value) {
            $contains = false;
            if (ValidadorController::isValidNotEmpty($value)) {
                $contains = (strtolower($value) == 'sim' || (ValidadorController::isValidDigits($value) && $value > 0));
            }
            $haystack[$key] = $contains;
        }
        return $haystack;
    }

    /**
     * Chega se eh um paginator e se sim, retorna o array com os resultados
     * @param $paginator
     * @return array
     */
    public static function paginatorToArray($paginator)
    {
        if (is_a($paginator, 'Laminas\Paginator\Paginator')) {
            if ($paginator->count() > 0) {
                return iterator_to_array($paginator->getCurrentItems());
            }
            return [];
        } elseif (is_object($paginator)) {
            return (array)$paginator;
        }
        return $paginator;
    }

    /**
     * Interator to Array
     * @param $array
     * @return array
     */
    public static function iteratorToArray($array): array
    {
        return iterator_to_array($array, true);
    }

    /**
     * Converte o paginator para array e utiliza o Stripslashes nas strings
     * @param $paginator
     * @param bool $copy
     * @return array
     */
    public static function paginatorToArrayStripslashes($paginator, $copy = false)
    {
        $items = self::paginatorToArray($paginator);
        if (is_array($items) && count($items) > 0) {
            foreach ($items as $key => $item) {
                $s = self::getStripslashes($item);
                if (is_a($s, 'Laminas\Stdlib\ArrayObject') && $copy) {
                    $items[$key] = $s->getArrayCopy();
                } else {
                    $items[$key] = $s;
                }
            }
            return $items;
        }
        return array();
    }

    /**
     * Desfaz o efeito de addslashes() nos atributos de um objeto
     *
     * @param $obj
     * @return array
     * @noinspection DuplicatedCode
     */
    public static function getStripslashes($obj)
    {
        if (!is_array($obj)) {
            foreach ($obj as $key => $value) {
                if (strpos($key, 'check_') !== false) {
                    $obj->{$key} = $value == '1' ? true : false;
                } elseif (strpos($key, '_check') !== false) {
                    $obj->{$key} = $value == '1' ? true : false;
                } elseif (strpos($key, 'collapsed_') !== false) {
                    $obj->{$key} = $value == '1' ? true : false;

                } elseif (strpos($key, 'action_global') !== false ||
                    strpos($key, 'action_created') !== false ||
                    strpos($key, 'action_started') !== false ||
                    strpos($key, 'action_paused') !== false ||
                    strpos($key, 'action_pending_piece') !== false ||
                    strpos($key, 'action_canceled') !== false ||
                    strpos($key, 'action_checked') !== false ||
                    strpos($key, 'action_undone') !== false ||
                    strpos($key, 'action_ended') !== false ||
                    strpos($key, 'action_edited') !== false ||
                    strpos($key, 'page_show_number') !== false ||
                    strpos($key, 'plan_expired') !== false) {
                    $obj->{$key} = (string)$value == '1' ? true : false;

                } elseif (strpos($key, 'phone') !== false) {
                    $obj->{$key} = self::removeAllCharactersExceptNumber($value);

                } elseif (strpos($key, 'encodedJson') !== false
                    || strpos($key, 'listOption') !== false
                    || strpos($key, 'listTeamUser') !== false
                ) {
                    $obj->{$key} = ValidadorController::isValidJson($value) ? \Laminas\Json\Json::decode($value, \Laminas\Json\Json::TYPE_OBJECT) : [];

                } elseif (!is_null($value) && is_array($value)) {
                    $obj->{$key} = $value;

                } elseif (strpos($key, 'ip_address') !== false) {
                    if (ValidadorController::isValidPackedIP($value)) {
                        $obj->{$key} = $value;
                    } else {
                        $obj->{$key} = "127.0.0.1";
                    }
                } else {
                    $obj->{$key} = stripslashes($value);
                }
            }
        } else {
            foreach ($obj as $key => $value) {
                if (strpos($key, 'check_') !== false) {
                    $obj[$key] = $value == '1' ? true : false;
                } elseif (strpos($key, 'collapsed_') !== false) {
                    $obj[$key] = $value == '1' ? true : false;

                } elseif (strpos($key, 'action_global') !== false ||
                    strpos($key, 'action_created') !== false ||
                    strpos($key, 'action_started') !== false ||
                    strpos($key, 'action_paused') !== false ||
                    strpos($key, 'action_pending_piece') !== false ||
                    strpos($key, 'action_canceled') !== false ||
                    strpos($key, 'action_checked') !== false ||
                    strpos($key, 'action_undone') !== false ||
                    strpos($key, 'action_ended') !== false ||
                    strpos($key, 'action_edited') !== false ||
                    strpos($key, 'page_show_number') !== false ||
                    strpos($key, 'plan_expired') !== false) {
                    $obj[$key] = (string)$value == '1' ? true : false;

                } elseif ($key == 'lat') {
                    $obj[$key] = floatval($value);
                } elseif ($key == 'lng') {
                    $obj[$key] = floatval($value);
                } elseif ($key == 'tnc') {
                    $obj[$key] = $value == '1' ? true : false;

                } elseif ($key == 'encodedJson'
                    || $key == 'listOption'
                    || $key == 'listTeamUser'
                ) {
                    $obj[$key] = ValidadorController::isValidJson($value) ? \Laminas\Json\Json::decode($value, \Laminas\Json\Json::TYPE_OBJECT) : [];
                } elseif ($key == 'zip_code') {
//                    $string = str_replace(array(
//                        '\n',
//                        '\r',
//                        '\t',
//                        '\s',
//                        '\\',
//                        '\/',
//                        '/',
//                        ' '
//                    ), '_', trim($value));
//                    $string = preg_replace("/[\\n\\r\\t\\s]+/", "", rtrim(trim($string)));
//                    $string = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($string, ENT_NOQUOTES));
//                    $obj[$key] = preg_replace("/[^0-9]/", "", $string);
                    $obj[$key] = strlen($value) > 0 ? stripslashes($value) : $value;
                } elseif (strpos($key, 'ip_address') !== false) {
                    if (ValidadorController::isValidPackedIP($value)) {
                        $obj[$key] = $value;
                    } else {
                        $obj[$key] = "127.0.0.1";
                    }
                } else {
                    $obj[$key] = strlen($value) > 0 ? stripslashes($value) : $value;
                }
            }
        }
        return $obj;
    }

    /**
     * Convertendo nome para URL amigavel
     *
     * @param $name
     * @return mixed|string
     */
    public static function getUrlFriendlyName($name)
    {
        $name = TexturizeController::remove_accents($name);
        $name = strtolower($name);
        $name = str_replace(array(
            ' ',
            '--'
        ), '-', $name);
        $name = ltrim(rtrim($name, '-'), '-');
        return $name;
    }

    /**
     * Preformata o valor para o banco de dados
     *
     * @param $data
     * @return array
     */
    public static function getAddslashes($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $index = strtolower($key);
            if (strpos($index, 'check_') !== false) {
                $result[$key] = $value == '1' ? true : false;
            } elseif (strpos($index, '_check') !== false) {
                $result[$key] = $value == '1' ? true : false;
            } elseif (strpos($index, 'collapsed_') !== false) {
                $result[$key] = $value == '1' ? true : false;
            } elseif ($index == 'e-mail') {
                $result['email'] = addslashes($value);
            } elseif (!is_null($value) && is_array($value)) {
                $result[$key] = $value;
            } else {
                $result[$key] = addslashes($value);
            }
        }
        return $result;
    }

    /**
     * Remove itens do busca padronizado
     *
     * @param $search
     * @param $counter
     * @param int $jumper
     * @return mixed
     */
    public static function getSearchLessOne($search, $counter, $jumper = 0)
    {
        $priorities = [
            "Characteristic",
            "Infrastructure"
        ];

        $unset = 0;
        foreach ($priorities as $item) {
            if (isset($search[$item])) {
                unset($search[$item]);
                ++$unset;
                if ($unset == $counter) {
                    break;
                }
            }
        }

        if ($jumper > 0) { // Consulta sem resultado,s vamos remover os principais
            if ($jumper == 1) {
                unset($search['locality_neighborhood_id']);
            } elseif ($jumper == 2) {
                unset($search['locality_neighborhood_id'], $search['locality_political_id']);
            } elseif ($jumper == 3) {
                unset($search['locality_neighborhood_id'], $search['locality_political_id'], $search['categories']);
            } elseif ($jumper == 4) {
                unset($search['locality_neighborhood_id'], $search['locality_political_id'], $search['categories'], $search['operations']);
            }
        }
        return $search;
    }

    /**
     * Formata a Sequencia removendo os zeros extra
     *
     * @param $seq
     * @return string
     */
    public static function formatCode($seq)
    {
        $seq = (int)$seq;
        if ($seq <= 1000) {
            return str_pad($seq, 5, "0", STR_PAD_LEFT);
        } elseif ($seq <= 10000) {
            return str_pad($seq, 7, "0", STR_PAD_LEFT);
        }
        return str_pad($seq, 5, "0", STR_PAD_LEFT);
    }

    /**
     * Formatando CEP
     *
     * @param $str
     * @return string
     */
    public static function formatCEP($str)
    {
        return str_pad($str, 8, "0", STR_PAD_RIGHT);
    }

    /**
     * Pre formatada as chaves invertendo posicao do codigo e key do array
     *
     * @param $values
     * @return array
     */
    public static function getKeysInIndex($values)
    {
        $rs = [];
        foreach ($values as $item) {
            $rs[$item['Codigo']] = $item['id'];
        }
        return $rs;
    }

    /**
     * Correcao de Chaves do Array
     *
     * @param $arr
     * @return array|false
     */
    public static function fixArrayKey($arr)
    {
        $arr = array_combine(array_map(function ($str) {
            return str_replace([
                " ",
                "\t",
                "\s"
            ], "", $str);
        }, array_keys($arr)), array_values($arr));

        return $arr;
    }

    /**
     * Retorna diferena entre duas datas
     *
     * @param $date_1
     * @param $date_2
     * @param string $differenceFormat
     * @return string
     */
    public static function getDateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }

    /**
     * Requisicao em POST
     *
     * @param $url
     * @param array $parameters
     * @param string $method
     * @param null $logger
     * @param null $RawBody
     * @param string $Content
     * @return mixed
     */
    public static function requestUrl($url, $parameters = array(), $method = 'POST', $logger = null, $RawBody = null, $Content = 'application/json; charset=UTF-8')
    {
        $headers = [
            'content-type' => "{$Content}",
            'cache-control' => 'no-cache',
            'accept' => 'application/json'
        ];
        $request = new \Laminas\Http\Request();
        $request->getHeaders()->addHeaders($headers);
        $request->setUri($url);
        $request->setMethod($method);

        $client = new \Laminas\Http\Client();
        $client->setOptions([
            'sslverifypeer' => false
        ]);
        $client->setHeaders($headers);

        if (is_array($parameters) && count($parameters) > 0) {
            // $request->setPost ( new \Laminas\Stdlib\Parameters ( $parameters ) );
            $client->setRawBody(\Laminas\Json\Json::encode($parameters));
        } elseif (!is_null($RawBody) && strlen($RawBody) > 0) {
            // $client->setRawBody ( $RawBody );
            $request->setContent($RawBody);
        }

        $response = $client->dispatch($request);
        $body = $response->getBody();
        // Debug
        if (!is_null($logger) && is_object($logger)) {
            $logger->info($url . PHP_EOL);
            $logger->info($RawBody . PHP_EOL);
            $logger->info($client->getResponse()
                ->getReasonPhrase());
            foreach ($client->getResponse()->getHeaders() as $header) {
                $logger->info($header->getFieldName() . ' with value ' . $header->getFieldValue());
            }
            $logger->info($body . PHP_EOL);
        }
        return json_decode($body, true);
    }

    /**
     * Scala para calculo no map
     * @param $current_zoom
     * @return int
     */
    public static function getScaleMapsZoom($current_zoom)
    {

        if ($current_zoom == 20) {
            return 1;
        } elseif ($current_zoom == 19) {
            return 1;
        } elseif ($current_zoom == 18) {
            return 1;
        } elseif ($current_zoom == 17) {
            return 2;
        } elseif ($current_zoom == 16) {
            return 2;
        } elseif ($current_zoom == 15) {
            return 2;
        } elseif ($current_zoom == 14) {
            return 6;
        } elseif ($current_zoom == 13) {
            return 7;
        } elseif ($current_zoom == 12) {
            return 7;
        } elseif ($current_zoom == 11) {
            return 8;
        } elseif ($current_zoom == 10) {
            return 8;
        } elseif ($current_zoom == 9) {
            return 10;
        } elseif ($current_zoom == 8) {
            return 20;
        } elseif ($current_zoom == 7) {
            return 50;
        } elseif ($current_zoom == 6) {
            return 400;
        } elseif ($current_zoom == 5) {
            return 1600;
        } elseif ($current_zoom == 4) {
            return 3200;
        } elseif ($current_zoom == 3) {
            return 4600;
        } elseif ($current_zoom <= 2) {
            return 6371;
        }
        return 10;
    }

    /**
     * Retorna uma string aleatoria
     *
     * @param int $length
     * @param null $charlist
     * @return string
     */
    public static function getRandomString($length = 6, $charlist = null)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($charlist != null && strlen($charlist) > 4) {
            $characters = $charlist;
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * Registra na sessao a timezone corrente
     * @param string $pref_timezone
     * @param string $pref_date
     * @param string $pref_time
     */
    public static function setCurrentTimezone(string $pref_timezone, string $pref_date, string $pref_time)
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_TIMEZONE');
        $Session->timezone = $pref_timezone;
        $Session->format = "{$pref_date} {$pref_time}";
        $Session->currency = $currency ?? 'BRL';
    }

    /**
     * Setando moeda padrao
     * @param string $currency
     */
    public static function setCurrentCurrency(string $currency = 'BRL')
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_CURRENCY');
        $Session->currency = $currency ?? 'BRL';
    }

    /**
     * Moeda
     * @return mixed|string
     */
    public static function getCurrentCurrency()
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_CURRENCY');
        return $Session->currency ?? 'BRL';
    }

    /**
     * Retorna a timezone salva em sessao
     */
    public static function getCurrentTimezone()
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_TIMEZONE');
        return $Session->timezone ?? 'UTC';
    }

    /**
     * Detect MIME Content-type for a file
     * @see https://www.php.net/manual/en/function.mime-content-type.php
     * @param $value
     * @return string
     */
    public static function getMimeContentTypeFromString($value): string
    {
        return mime_content_type($value);
    }


    /**
     * Formatando Moeda sem considerar a regiao, somente o formato da moeda
     * @param $value
     * @param $currency
     * @param bool $includeSymbol
     * @return string
     * @throws \Exception
     */
    public static function getFormattedCurrency($value, $currency, $includeSymbol = false)
    {
        $CurrencyController = new \Useful\Controller\CurrencyController($currency ?? 'BRL');
        $result = number_format($value, $CurrencyController->getPrecision(), $CurrencyController->getDecimalSeparator(), $CurrencyController->getThousandSeparator());
        $symbol = $includeSymbol ? $CurrencyController->getSymbol() : '';
        return "{$symbol} {$result}";
    }


    /**
     * Pre formato um numero
     * @param $value
     * @param string $country
     * @return array
     */
    public static function getFormattedNumber($value, $country = 'BR')
    {
        return self::getNumberMask($country, $value);

    }

    /**
     * Formato da moeda atual
     * @return array
     */
    public static function getCurrencyMask(): array
    {
        $prefix = 'R$';
        $radixPoint = '.';
        $groupSeparator = ',';
        $decimals = 2;
        $codeCurrency = self::getCurrentCurrency();
        try {
            $CurrencyController = new \Useful\Controller\CurrencyController($codeCurrency ?? 'BRL');
            $prefix = $CurrencyController->getSymbol();
            $radixPoint = $CurrencyController->getDecimalSeparator();
            $groupSeparator = $CurrencyController->getThousandSeparator();
            $decimals = $CurrencyController->getPrecision();
        } catch (\Exception $e) {
            //var_dump($e->getMessage());
        }

        return [$prefix, $radixPoint, $groupSeparator, $decimals, $codeCurrency];
    }

    /**
     * Mascara de moeda para campos de input
     * @return string
     */
    public static function getCurrentInputMask()
    {
        list($prefix, $radixPoint, $groupSeparator, $decimals) = self::getCurrencyMask();
        return "'alias': 'numeric', 'groupSeparator': '{$groupSeparator}', 'radixPoint':'{$radixPoint}','autoGroup': true, 'digits': '{$decimals}', 'digitsOptional': false, 'prefix': '{$prefix} ', 'placeholder': '0', 'showMaskOnFocus': false, 'showMaskOnHover': false, 'removeMaskOnSubmit': true";
    }

    /**
     * Idioma padrao setado na sessao
     * @return mixed|string
     */
    public static function getCurrentSessionLocale()
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_LANG');
        return $Session->locale ?? 'pt_BR';
    }

    /**
     * Mascara para numeros de acordo com o idioma padrao
     * @return string
     */
    public static function getNumberInputMask()
    {
        list($prefix, $radixPoint, $groupSeparator, $decimals) = self::getNumberMask();
        return "'alias': 'numeric', 'groupSeparator': '{$groupSeparator}', 'radixPoint':'{$radixPoint}','autoGroup': true, 'digits': '{$decimals}', 'digitsOptional': false, 'prefix': '{$prefix} ', 'placeholder': '0', 'showMaskOnFocus': false, 'showMaskOnHover': false, 'removeMaskOnSubmit': true";
    }

    /**
     * Formato da mascara para numeros
     * @param bool $country
     * @param null $value
     * @return array
     */
    private static function getNumberMask($country = null, $value = null)
    {
        $prefix = '';
        $radixPoint = '.';
        $groupSeparator = ',';
        $decimals = 2;
        $codeCountry = $country ?? self::getCurrentCountry();
        $isoCode = \Useful\Controller\Country2LocaleController::country2locale($codeCountry ?? 'BR', true);
        try {
            $formatter = new \NumberFormatter($isoCode, \NumberFormatter::DECIMAL);
            $radixPoint = $formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
            $groupSeparator = $formatter->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
            //Check has number
            if (
                ValidadorController::isValidNotEmpty($value)
                || (ValidadorController::isValidDigits($value) && $value == 0)
            ) {
                return $formatter->format(floatval($value));
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
        return [$prefix, $radixPoint, $groupSeparator, $decimals, $codeCountry, $value];
    }

    /**
     * Pais padrao para numeros
     * @return mixed|string
     */
    public static function getCurrentCountry()
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_COUNTRY');
        return $Session->country ?? 'BR';
    }

    /**
     * Setando o pais
     * @param string $country
     */
    public static function setCurrentCountry(string $country = 'BR')
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_COUNTRY');
        $Session->country = $country ?? 'BR';
    }

    /**
     * Formatado da mascara de data com base no idioma
     * @param bool $phpFormat , se usando php format date
     * @param null $lang
     * @return string
     */
    public static function getFormattedDateMask($phpFormat = false, $lang = null): string
    {
        $lang = $lang ?? self::getCurrentSessionLocale();
        $default = $phpFormat ? 'd/m/Y' : 'DD/MM/YYYY';

        switch ($lang) {
            case "en_US":
                $default = $phpFormat ? "Y-m-d" : "YYYY-MM-DD";
                break;

            case "pt_BR":
                $default = $phpFormat ? "d/m/Y" : "DD/MM/YYYY";
                break;

            case "es_ES":
                $default = $phpFormat ? "d-m-Y" : "DD-MM-YYYY";
                break;
        }

        return $default;
    }

    /**
     * @param bool $phpFormat
     * @return string
     */
    public static function getFormattedMonthDateMask($phpFormat = false): string
    {
        $lang = self::getCurrentSessionLocale();
        $default = $phpFormat ? 'd/m/Y' : 'DD/MM/YYYY';

        switch ($lang) {
            case "en_US":
                $default = $phpFormat ? "Y-m-d" : "YYYY-MM";
                break;

            case "pt_BR":
                $default = $phpFormat ? "d/m/Y" : "MM/YYYY";
                break;

            case "es_ES":
                $default = $phpFormat ? "d-m-Y" : "MM-YYYY";
                break;
        }

        return $default;
    }

    /**
     * Formatando horas e minutos com base no idioma
     * @param bool $phpFormat , se usando php format time
     * @return string
     */

    public static function getFormattedTimeMask($phpFormat = false): string
    {
        $lang = self::getCurrentSessionLocale();
        $default = $phpFormat ? '99:99' : '99:99';

        switch ($lang) {
            case "en_US":
                $default = $phpFormat ? "99:99" : "99:99";
                break;

            case "pt_BR":
                $default = $phpFormat ? "99:99" : "99:99";
                break;

            case "es_ES":
                $default = $phpFormat ? "99:99" : "99:99";
                break;
        }

        return $default;
    }

    /**
     * @param $dt
     * @param bool $hour
     * @param bool $isDecade
     * @return bool|string
     */
    public static function getFormattedDateJavascriptToDb($dt, $hour = false, $isDecade = true)
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_TIMEZONE');
        $gmt = $Session->timezone ?? 'UTC';
        $gmt = ValidadorController::isValidNotEmpty($gmt) ? $gmt : 'UTC';
        $fmt = $hour ? 'Y-m-d H:i:s' : 'Y-m-d';
        $parse = Carbon::parse($dt, $gmt);
        if ($parse->isValid()) {
            if ($isDecade && $parse->isCurrentDecade()) {
                return $parse->format($fmt);
            } else {
                return $parse->format($fmt);
            }
        }
        return false;
    }

    /**
     * @param string|null $date
     * @param string|null $hour
     * @return string
     */
    public static function getFilterCreatedDate(?string $date, ?string $hour): string
    {
        if (ValidadorController::isValidNotEmpty($date)) {
            $date = self::getFormattedDateToDb($date, false);
            if (ValidadorController::isValidDate($date)) {
                $created_at = "{$date} {$hour}";
                return $created_at;
            }
        }
        return false;
    }

    /**
     * Data Formatada para javascript
     * @param $dt
     * @param bool $hour
     * @return bool|string
     */
    public static function getFormattedDateDbToJavascript($dt, $hour = false)
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_TIMEZONE');
        $gmt = $Session->timezone ?? 'UTC';
        $gmt = ValidadorController::isValidNotEmpty($gmt) ? $gmt : 'UTC';
        $fmt = self::getFormattedDateMask();
        $fmt = $hour ? "{$fmt} H:i:s" : $fmt;
        $parse = Carbon::parse($dt, $gmt);
        //if ($parse->isValid() && $parse->isCurrentDecade()) {
        if ($parse->isValid()) {
            return $parse->format($fmt);
        }
        return false;
    }

    /**
     * Formatando sem timezone
     * @param $dt
     * @param bool $hour
     * @param null $lang
     * @return bool|string
     */
    public static function getFormattedDateDbToJavascriptWithoutTimezone($dt, $hour = false, $lang = null)
    {
        $fmt = self::getFormattedDateMask(true, $lang);
        $fmt = $hour ? "{$fmt} H:i:s" : $fmt;
        $parse = Carbon::parse($dt);
        //if ($parse->isValid() && $parse->isCurrentDecade()) {
        if ($parse->isValid()) {
            return $parse->format($fmt);
        }
        return false;
    }

    /**
     * @param $dt
     * @param bool $hour
     * @param string $fmt
     * @return false|string
     */
    public static function getFormattedJavascriptIso8601ToDateDb($dt, bool $hour = false, string $fmt = 'Y-m-d')
    {
        $parse = Carbon::parse($dt);
        if ($parse->isValid()) {
            $fmt = $hour ? "{$fmt} H:i:s" : $fmt;
            return $parse->format($fmt);
        }
        return false;
    }

    /**
     * @param $dt
     * @return false|string
     */
    public static function getFormattedDateDbToJavascriptIso8601($dt)
    {
        $parse = Carbon::parse($dt);
        if ($parse->isValid()) {
            return $parse->toIso8601String();
        }
        return false;
    }

    /**
     * @param $dt
     * @param bool $extended
     * @return false|string
     */
    public static function getFormattedDateDbToJavascriptRfc3339($dt, bool $extended = false)
    {
        $parse = Carbon::parse($dt);
        if ($parse->isValid()) {
            return $parse->toRfc3339String($extended);
        }
        return false;
    }

    /**
     * Validando timezone e preformatando data
     * @param $dt
     * @param $timezone
     * @param bool $hasHour
     * @param bool $hasFormat
     * @return string
     */
    public static function getFormattedDateTimeWithTimeZone($dt, $timezone, $hasHour = true, $hasFormat = true): string
    {
        if (ValidadorController::isValidNotEmpty($dt) && ValidadorController::isValidDatetime($dt)) {
            $Session = new \Laminas\Session\Container ('CALCULOIDEAL_TIMEZONE');
            $gmt = $Session->timezone ?? 'UTC';
            $fmt = $hasFormat ? ($Session->format ?? ($hasHour ? 'Y-m-d H:i:s' : 'Y-m-d')) : ($hasHour ? 'Y-m-d H:i:s' : 'Y-m-d');
            $timezone_at = TimezoneController::getDateTimeWithTimeZone($dt, $timezone ?? TimezoneController::getDefaultTimezone(), $gmt, $fmt, self::getCurrentSessionLocale());
            return $timezone_at;
        }
        return $dt ?? '';
    }

    /**
     * Retorna a data na timezone do usuario
     * @return string
     */
    public static function getCurrentDateInTimezone()
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_TIMEZONE');
        $gmt = $Session->timezone ?? 'UTC';
        $nowIntz = Carbon::now($gmt);
        return $nowIntz->format('Y-m-d H:i:s');
    }

    /**
     * Retorna uma data com a soma do periodo solicitado
     * @param $datetime
     * @param $value
     * @param string $period
     * @return Carbon
     */
    public static function addTimeInDatetime($datetime, $value, $period = 'hour')
    {
        return Carbon::parse($datetime)->add($value, $period)->format('Y-m-d H:i:s');
    }

    /**
     * @param $dt
     * @return string
     */
    public static function getFormattedDateToDb($dt): string
    {

        $fmt = self::getFormattedDateMask(true);
        $parse = Carbon::createFromFormat($fmt, $dt);
        $dt = $parse->format('Y-m-d');
        return $dt ?? '';
    }

    /**
     * Converte uma string para float
     * @param string|null $value
     * @return float
     */
    public static function getDoubleInValue(?string $value): float
    {
        $res = preg_replace("/[^0-9,.]/", "", $value);
        return self::tofloat($res);
    }

    /**
     * This function takes the last comma or dot (if any) to make a clean float, ignoring thousand separator, currency or any other letter
     * https://www.php.net/manual/pt_BR/function.floatval.php#114486
     * @param $num
     * @return float
     */
    public static function tofloat($num)
    {
        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }

        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep + 1, strlen($num)))
        );
    }

    /**
     * Gerador de RGB
     * @return array
     */
    public static function getRandomColor(): array
    {
        $result = array('rgb' => '', 'hex' => '');
        foreach (array('r', 'b', 'g') as $col) {
            $rand = mt_rand(0, 255);
            $result['rgb'][$col] = $rand;
            $dechex = dechex($rand);
            if (strlen($dechex) < 2) {
                $dechex = '0' . $dechex;
            }
            $result['hex'] .= $dechex;
        }
        return $result;
    }

    /**
     * mb_strimwidth — Get truncated string with specified width
     * @param string $str
     * @param int $width
     * @param string $trimmarker
     * @return string
     */
    public static function getStrimwidth(string $str, int $width, string $trimmarker = " ... "): string
    {
        return mb_strimwidth($str, 0, $width, $trimmarker);
    }

    /**
     * Retorna um endereco de email aleatorio
     * @return string
     */
    public static function getFakeEmailRandom()
    {
        return md5(uniqid(rand(), true)) . "@example.com";
    }

    /**
     * Encode Json to String
     * @param array $param
     * @return string
     */
    public static function encodeToJsonString(array $param): string
    {
        $res = \Laminas\Json\Json::encode($param, \Laminas\Json\Json::TYPE_OBJECT);
        return str_replace("\"", "\\\"", $res);
    }

    /**
     * Formata o endereco dentro do padrao esperado
     * @param array $data
     * @return array
     */
    public static function getFormattedAddress(&$data)
    {
        $data = self::iteratorToArray($data);
        foreach ($data as $key => $value) {
            $index = strtolower($key);
            if ($index == 'city') {
                $data['addr_city'] = addslashes($value);

            } else if ($index == 'neighborhood') {
                $data['addr_complement'] = addslashes($value);

            } else if ($index == 'country') {
                $data['addr_country'] = addslashes($value);

            } else if ($index == 'lat') {
                $data['addr_lat'] = doubleval($value);

            } else if ($index == 'lng') {
                $data['addr_lng'] = doubleval($value);

            } else if ($index == 'address_district') {
                $data['addr_neighborhood'] = addslashes($value);

            } else if ($index == 'number') {
                $data['addr_number'] = addslashes($value);

            } else if ($index == 'zip_code') {
                $data['addr_postal_code'] = addslashes($value);

            } else if ($index == 'state_province') {
                $data['addr_state'] = addslashes($value);

            } else if ($index == 'address') {
                $data['addr_street'] = addslashes($value);
            }
        }

        return $data;
    }

    /**
     * @param string|null $start
     * @param string|null $startHour
     * @param string|null $end
     * @param string|null $endHour
     * @return array
     */

    public static function getFilterDate(?string $start, ?string $startHour, ?string $end, ?string $endHour): array
    {
        if (ValidadorController::isValidNotEmpty($start) && ValidadorController::isValidNotEmpty($end)) {
            $start = self::getFormattedDateJavascriptToDb($start, false);
            $end = self::getFormattedDateJavascriptToDb($end, false);
            if (ValidadorController::isValidDate($start) && ValidadorController::isValidDate($end)) {
                $start = "{$start} {$startHour}";
                $end = "{$end} {$endHour}";
                return [$start, $end];
            }
        }
        return [false, false];
    }

    /**
     * @param array $where
     * @param string|null $start
     * @param string|null $end
     * @param string|null $diem
     */
    public static function getFilterDiem(array &$where, ?string $start, ?string $end, ?string $diem)
    {
        // Datas
        $from = null;
        $to = null;
        if (ValidadorController::isValidNotEmpty($start)) {
            $start = self::getFormattedDateJavascriptToDb($start, false);
            if (ValidadorController::isValidDate($start)) {
                $from = $start;
            }
        }
        if (ValidadorController::isValidNotEmpty($end)) {
            $end = self::getFormattedDateJavascriptToDb($end, false);
            if (ValidadorController::isValidDate($end)) {
                $to = $end;
            }
        }
        if ($from != null && $to == null) {
            $to = $from;
        } elseif ($to != null && $from == null) {
            $from = $to;
        } elseif ($from != null && $to != null) {
            if (strtotime($from) > strtotime($to)) {
                $f = $from;
                $t = $to;
                $from = $t;
                $to = $f;
            }
        }

        switch ($diem) {
            case 'CREATED':
            case 'created':
                $diem = 'created';
                break;
            case 'UPDATED':
            case 'updated':
                $diem = 'updated';
                break;
            case 'CALENDAR':
            case 'SCHEDULED':
            case 'scheduled':
                $diem = 'scheduled';
                break;
            case 'UNIVERSAL':
            case 'UTC':
                $diem = 'universal';
                break;
            case 'STARTED':
            case 'started':
                $diem = 'started';
                break;
            case 'FINISHED':
            case 'finished':
                $diem = 'finished';
                break;
            case 'COMPLETED':
            case 'completed':
                $diem = 'completed';
                break;
            case 'EXECUTED':
            case 'executed':
                $diem = 'executed';
                break;
            default:
                $diem = $from = $to = null;
                break;
        }

        $where['diem'] = $diem;
        $where['from'] = $from;
        $where['to'] = $to;
    }

    /**
     * Formato o filtro de pesquisa para O.S.
     * @param int $id_sys_customer
     * @param array $post
     * @param $count
     * @param $offset
     * @param bool $isJoinNps
     * @param bool $isJoinClassification
     * @param string $orderby
     * @return array
     */
    public static function getFilterOrder($id_sys_customer, $post, &$count, &$offset, $isJoinNps = false, $isJoinClassification = false, &$orderby = 'id DESC')
    {
//        var_dump($post);exit();
        $search = UsefulController::getValueInArray($post, 'search', null);
        $short = boolval(UsefulController::getValueInArray($post, 'short', false));
        $diem = UsefulController::getValueInArray($post, 'diem', null);
        $start = UsefulController::getValueInArray($post, 'start', null);
        $end = UsefulController::getValueInArray($post, 'end', null);
        $startStr = UsefulController::getValueInArray($post, 'startStr', null);
        $endStr = UsefulController::getValueInArray($post, 'endStr', null);
        $showAdvancedSearch = boolval(UsefulController::getValueInArray($post, 'ShowAdvancedSearch', false));
        $searchSequence = (int)(UsefulController::getValueInArray($post, 'searchSequence', null));
        $isCalendarSearch = boolval(UsefulController::getValueInArray($post, 'isCalendarSearch', false));
        $isSlaSearch = boolval(UsefulController::getValueInArray($post, 'isSlaSearch', false));
        $isListSearch = boolval(UsefulController::getValueInArray($post, 'isListSearch', false));
        $id_customer = (int)UsefulController::getValueInArray($post, 'id_customer', null);
        $id_unit = (int)UsefulController::getValueInArray($post, 'id_unit', null);
        $id_evolution = (int)UsefulController::getValueInArray($post, 'id_evolution', -1);
        $id_priority = (int)UsefulController::getValueInArray($post, 'id_priority', null);
        $id_user = (int)UsefulController::getValueInArray($post, 'id_user', null);
        $id_user_owner = (int)UsefulController::getValueInArray($post, 'id_user_owner', null);
        $id_reason = (int)UsefulController::getValueInArray($post, 'id_reason', null);
        $id_remark = (int)UsefulController::getValueInArray($post, 'id_remark', null);
        $id_group = (int)UsefulController::getValueInArray($post, 'id_group', null);
        $id_classification = (int)UsefulController::getValueInArray($post, 'id_classification', null);
        $id = (int)UsefulController::getValueInArray($post, 'id', 0);
        $statum = (int)UsefulController::getValueInArray($post, 'statum', 0);
        $check_occurrence = boolval(UsefulController::getValueInArray($post, 'check_occurrence', false));
        $check_occurrence_monitor = boolval(UsefulController::getValueInArray($post, 'check_occurrence_monitor', false));
        $check_bad_use = boolval(UsefulController::getValueInArray($post, 'bad_use', false));
        $st_region = (int)UsefulController::getValueInArray($post, 'st_region', null);
        $st_protocol = (int)UsefulController::getValueInArray($post, 'st_protocol', null);
        $state_registration = UsefulController::getValueInArray($post, 'state_registration', null);
        $st_assistance = (int)UsefulController::getValueInArray($post, 'st_assistance', null);
        $st_timetable = (int)UsefulController::getValueInArray($post, 'st_timetable', null);
        $address_state = UsefulController::getValueInArray($post, 'address_state', null);
        $unit_name = UsefulController::getValueInArray($post, 'unit_name', null);
        $dcs = boolval(UsefulController::getValueInArray($post, 'dcs', false));
        $orderby = 'id DESC';
        //Where
        if ($isSlaSearch && $id > 0) {
            $where = ['id_sys_customer' => $id_sys_customer,
                'id' => $id,
                'isJoinUsers' => false,
                'isJoinClient' => false,
                'isShortColumn' => true,
                'isJoinOwner' => false,
                'isJoinAppOsCustomers' => false,
                'isJoinClientUnit' => false,
                'isJoinEvolution' => false,
                'isJoinReasons' => false,
                'isJoinConcatJson' => false,
                'isJoinNps' => false,
                'isJoinClassification' => false,
                'isJoinRegion' => false,
                'isJoinTimetable' => false,
                'isJoinAssistance' => false,
                'isJoinGroup' => false,
                'isJoinPriority' => false,
                'isJoinRemark' => false,
                'check_occurrence' => false,
                'showAdvancedSearch' => false];
        } else {
            $where = ['id_sys_customer' => $id_sys_customer,
                'isJoinUsers' => true,
                'isJoinClient' => true,
                'isShortColumn' => $short,
                'isJoinOwner' => true,
                'isJoinAppOsCustomers' => false,
                'isJoinClientUnit' => true,
                'isJoinEvolution' => true,
                'isJoinReasons' => true,
                'isJoinConcatJson' => true,
                'isJoinNps' => $isJoinNps,
                'isJoinClassification' => $isJoinClassification,
                'isJoinRegion' => true,
                'isJoinTimetable' => true,
                'isJoinAssistance' => true,
                'isJoinOrderCustom' => true,
                'isJoinGroup' => $short,
                'isJoinPriority' => $short,
                'isJoinRemark' => $short,
                'statum' => $statum,
                'check_occurrence' => $check_occurrence,
                'check_occurrence_monitor' => $check_occurrence_monitor,
                'check_bad_use' => $check_bad_use,
                'showAdvancedSearch' => $showAdvancedSearch,
                'dcs' => $dcs];

            if ($showAdvancedSearch) { //central
                $where['id_customer'] = $id_customer ?? 0;
                $where['id_unit'] = $id_unit ?? 0;
                if ($id_evolution >= 0) {
                    $where['id_evolution'] = $id_evolution;
                } elseif ($id_evolution == -2) {               // GRUPO EM ABERTO
                    $where ['open_group'] = $id_evolution;
                }
                $where['id_priority'] = $id_priority ?? 0;
                $where['id_user'] = $id_user ?? 0;
                $where['id_user_owner'] = $id_user_owner ?? 0;
                $where['id_reason'] = $id_reason ?? 0;
                $where['id_remark'] = $id_remark ?? 0;
                $where['id_group'] = $id_group ?? 0;
                $where['id_classification'] = $id_classification ?? 0;
                $where['st_region'] = $st_region ?? 0;
                $where['st_protocol'] = $st_protocol ?? 0;
                $where['state_registration'] = $state_registration ?? 0;
                $where['st_assistance'] = $st_assistance ?? null;
                $where['st_timetable'] = $st_timetable ?? null;
                $where['state_province'] = $address_state ?? null;
                $where['unit_name'] = $unit_name ?? null;
                $where['sequence'] = $searchSequence ?? null;

                //Diem
                UsefulController::getFilterDiem($where, $start, $end, $diem);
            } elseif (ValidadorController::isValidNotEmpty($diem) && $diem == 'CALENDAR' //calendario
                && ValidadorController::isValidNotEmpty($startStr)
                && ValidadorController::isValidNotEmpty($endStr)) {
                UsefulController::getFilterDiem($where, $startStr, $endStr, $diem);
                $count = null;
                $offset = null;
                $where['id_priority'] = $id_priority ?? 0;
                // List User Selected
                $listEvolution = UsefulController::getValueInArray($post, 'listEvolution', []);
                UsefulController::getFilterOrderEvolution($where, $listEvolution);
                $listTeamUser = UsefulController::getValueInArray($post, 'listTeamUser', []);
                UsefulController::getFilterOrderTeamUser($where, $listTeamUser);
                // List Priority
                $listPriority = UsefulController::getValueInArray($post, 'listPriority', []);
                UsefulController::getFilterOrderPriority($where, $listPriority);
                //listAddressState
                $listAddressState = UsefulController::getValueInArray($post, 'listAddressState', []);
                UsefulController::getFilterOrdeAddressState($where, $listAddressState);
                //listClient
                $listClient = UsefulController::getValueInArray($post, 'listClient', []);
                UsefulController::getFilterOrderClientList($where, $listClient);
                //listGroup
                $listGroup = UsefulController::getValueInArray($post, 'listGroup', []);
                UsefulController::getFilterOrderGroupList($where, $listGroup);
                // List User Selected
                $listTeamUserSelected = UsefulController::getValueInArray($post, 'listTeamUserSelected', []);
                UsefulController::getFilterOrderTeamUser($where, $listTeamUserSelected);
                //$where['id_customer'] = $id_customer ?? 0;
                if ($id_evolution >= 0) {
                    $where['id_evolution'] = $id_evolution;
                } elseif ($id_evolution == -2) {               // GRUPO EM ABERTO
                    $where ['open_group'] = $id_evolution;
                } elseif ($id_evolution == -4) {
                    $where ['id_evolution'] = -4;
                } else {
                    $where ['id_evolution'] = -3;
                }
            } elseif (ValidadorController::isValidNotEmpty($start) || ValidadorController::isValidNotEmpty($end)) {
                UsefulController::getFilterDiem($where, $start, $end, 'CREATED');
            }
            // Filter Lister
            if ($showAdvancedSearch || $isListSearch) {
                // List Evolution
                $listEvolution = UsefulController::getValueInArray($post, 'listEvolution', []);
                UsefulController::getFilterOrderEvolution($where, $listEvolution);
                // List User Selected
                $listTeamUserSelected = UsefulController::getValueInArray($post, 'listTeamUserSelected', []);
                UsefulController::getFilterOrderTeamUser($where, $listTeamUserSelected);
                // List Owner
                $listTeamUserOwner = UsefulController::getValueInArray($post, 'listTeamUserOwner', []);
                UsefulController::getFilterOrderTeamOwner($where, $listTeamUserOwner);
                // List Service
                $listTypeService = UsefulController::getValueInArray($post, 'listTypeService', []);
                UsefulController::getFilterOrderServiceType($where, $listTypeService);
                // List Priority
                $listPriority = UsefulController::getValueInArray($post, 'listPriority', []);
                UsefulController::getFilterOrderPriority($where, $listPriority);
                // listClassification
                $listClassification = UsefulController::getValueInArray($post, 'listClassification', []);
                UsefulController::getFilterOrderClassification($where, $listClassification);
                // listStRegion
                $listStRegion = UsefulController::getValueInArray($post, 'listStRegion', []);
                UsefulController::getFilterOrderStRegion($where, $listStRegion);
                // listStTimetable
                $listStTimetable = UsefulController::getValueInArray($post, 'listStTimetable', []);
                UsefulController::getFilterOrderStTimetable($where, $listStTimetable);
                // listStAssistance
                $listStAssistance = UsefulController::getValueInArray($post, 'listStAssistance', []);
                UsefulController::getFilterOrderStAssistance($where, $listStAssistance);
                //listAddressState
                $listAddressState = UsefulController::getValueInArray($post, 'listAddressState', []);
                UsefulController::getFilterOrdeAddressState($where, $listAddressState);
                //listClient
                $listClient = UsefulController::getValueInArray($post, 'listClient', []);
                UsefulController::getFilterOrderClientList($where, $listClient);
                //listGroup
                $listGroup = UsefulController::getValueInArray($post, 'listGroup', []);
                UsefulController::getFilterOrderGroupList($where, $listGroup);
            }
            // Regex
            self::getFilterSearchRegexp($search, $where, $isCalendarSearch);
        }
        return $where;
    }

    /**
     * Filtro por Address by State
     * @param $where
     * @param array $listAddressState
     */
    public static function getFilterOrdeAddressState(&$where, array $listAddressState)
    {
        $listSt = [];
        if (is_array($listAddressState) && count($listAddressState) > 0) {
            foreach ($listAddressState as $k => $v) {
                $state = UsefulController::getValueInArray($v, 'id', 0);
                if (strlen($state) >= 2) {
                    $listSt[] = "'{$state}'";
                }
            }
            $counter = count($listSt);
            if ($counter == 1) {
                $where['state_province'] = $listSt[0];
            } else if ($counter > 1) {
                $where['listAddressState'] = $listSt;
            }
        }
    }


    /**
     * Filtro por Assistance
     * @param $where
     * @param array $listStAssistance
     */
    public static function getFilterOrderStAssistance(&$where, array $listStAssistance)
    {
        $listAs = [];
        if (is_array($listStAssistance) && count($listStAssistance) > 0) {
            foreach ($listStAssistance as $k => $v) {
                $st_assistance = UsefulController::getValueInArray($v, 'id', 0);
                if (strlen($st_assistance) > 0) {
                    $listAs[] = "'{$st_assistance}'";
                }
            }
            $counter = count($listAs);
            if ($counter == 1) {
                $where['st_assistance'] = $listAs[0];
            } else if ($counter > 1) {
                $where['listStAssistance'] = $listAs;
            }
        }
    }

    /**
     * Filtro por Timetable
     * @param $where
     * @param array $listStTimetable
     */
    public static function getFilterOrderStTimetable(&$where, array $listStTimetable)
    {
        $listTi = [];
        if (is_array($listStTimetable) && count($listStTimetable) > 0) {
            foreach ($listStTimetable as $k => $v) {
                $st_timetable = UsefulController::getValueInArray($v, 'id', null);
                if (strlen($st_timetable) > 0) {
                    $listTi[] = "'{$st_timetable}'";
                }
            }
            $counter = count($listTi);
            if ($counter == 1) {
                $where['st_timetable'] = $listTi[0];
            } else if ($counter > 1) {
                $where['listStTimetable'] = $listTi;
            }
        }
    }

    /**
     * Filtro por Regiao
     * @param $where
     * @param array $listStRegion
     */
    public static function getFilterOrderStRegion(&$where, array $listStRegion)
    {
        $listRe = [];
        if (is_array($listStRegion) && count($listStRegion) > 0) {
            foreach ($listStRegion as $k => $v) {
                $st_region = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($st_region > 0) {
                    $listRe[] = $st_region;
                }
            }
            $counter = count($listRe);
            if ($counter == 1) {
                $where['st_region'] = $listRe[0];
            } else if ($counter > 1) {
                $where['listStRegion'] = $listRe;
            }
        }
    }

    /**
     * Filtro por Classificacao
     * @param $where
     * @param array $listClassification
     */
    public static function getFilterOrderClassification(&$where, array $listClassification)
    {
        $listCl = [];
        if (is_array($listClassification) && count($listClassification) > 0) {
            foreach ($listClassification as $k => $v) {
                $id_classification = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_classification > 0) {
                    $listCl[] = $id_classification;
                }
            }
            $counter = count($listCl);
            if ($counter == 1) {
                $where['id_classification'] = $listCl[0];
            } else if ($counter > 1) {
                $where['listClassification'] = $listCl;
            }
        }
    }

    /**
     * Filtro por Prioridade
     * @param $where
     * @param array $listPriority
     */
    public static function getFilterOrderPriority(&$where, array $listPriority)
    {
        $listPr = [];
        if (is_array($listPriority) && count($listPriority) > 0) {
            foreach ($listPriority as $k => $v) {
                $id_priority = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_priority > 0) {
                    $listPr[] = $id_priority;
                }
            }
            $counter = count($listPr);
            if ($counter == 1) {
                $where['id_priority'] = $listPr[0];
            } else if ($counter > 1) {
                $where['listPriority'] = $listPr;
            }
        }
    }

    /**
     * Filtro por Service Type
     * @param $where
     * @param array $listTypeService
     */
    public static function getFilterOrderServiceType(&$where, array $listTypeService)
    {
        $listTs = [];
        if (is_array($listTypeService) && count($listTypeService) > 0) {
            foreach ($listTypeService as $k => $v) {
                $id_reason = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_reason > 0) {
                    $listTs[] = $id_reason;
                }
            }
            $counter = count($listTs);
            if ($counter == 1) {
                $where['id_reason'] = $listTs[0];
            } else if ($counter > 1) {
                $where['listTypeService'] = $listTs;
            }
        }
    }


    /**
     * Filtro por Evolution
     * @param $where
     * @param array $listEvolution
     */
    public static function getFilterOrderEvolution(&$where, array $listEvolution)
    {
        $listEv = [];
        if (is_array($listEvolution) && count($listEvolution) > 0) {
            foreach ($listEvolution as $k => $v) {
                $id_evolution = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_evolution >= 0) {
                    $listEv[] = $id_evolution;
                } elseif ($id_evolution == -2) {
                    array_push($listEv, 0, 1, 3, 13);
                }
            }
            $counter = count($listEv);
            if ($counter == 1) {
                $where['id_evolution'] = $listEv[0];
            } else if ($counter > 1) {
                $where['listEvolution'] = array_unique($listEv);
            }
        }
    }

    /**
     * Filtro por Team User
     * @param $where
     * @param array $listTeamUserSelected
     */
    public static function getFilterOrderTeamUser(&$where, array $listTeamUserSelected)
    {
        $listTu = [];
        if (is_array($listTeamUserSelected) && count($listTeamUserSelected) > 0) {
            foreach ($listTeamUserSelected as $k => $v) {
                $id_user = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_user > 0) {
                    $listTu[] = $id_user;
                }
            }

            $counter = count($listTu);
            if ($counter == 1) {
                $where['id_user'] = $listTu[0];
            } else if ($counter > 1) {
                $where['listTeamUserSelected'] = $listTu;
            }
        }
    }
    /**
     * Filtro por Cliente
     * @param $where
     * @param array $listClient
     */
    public static function getFilterOrderClientList(&$where, array $listClient)
    {
        $listCl = [];
        if (is_array($listClient) && count($listClient) > 0) {
            foreach ($listClient as $k => $v) {
                $id_customer = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_customer > 0) {
                    $listCl[] = $id_customer;
                }
            }
            $counter = count($listCl);
            if ($counter == 1) {
                $where['id_customer'] = $listCl[0];
            } else if ($counter > 1) {
                $where['listClient'] = $listCl;
            }
        }
    }
    /**
     * Filtro por Grupo
     * @param $where
     * @param array $listClient
     */
    public static function getFilterOrderGroupList(&$where, array $listGroup)
    {
        $listGr = [];
        if (is_array($listGroup) && count($listGroup) > 0) {
            foreach ($listGroup as $k => $v) {
                $id_group = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_group > 0) {
                    $listGr[] = $id_group;
                }
            }
            $counter = count($listGr);
            if ($counter == 1) {
                $where['id_group'] = $listGr[0];
            } else if ($counter > 1) {
                $where['listGroup'] = $listGr;
            }
        }
    }

    /**
     * Status externo de uma peça solicitada
     * @param $where
     * @param array $listPieceStatus
     */
    public static function getFilterOrderPieceStatus(&$where, array $listPieceStatus)
    {
        $listTu = [];
        if (is_array($listPieceStatus) && count($listPieceStatus) > 0) {
            foreach ($listPieceStatus as $k => $v) {
                $id = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id >= 0) {
                    $listTu[] = $id;
                }
            }
            $counter = count($listTu);
            if ($counter == 1) {
                $where['status'] = $listTu[0];
            } else if ($counter > 1) {
                $where['listPieceStatus'] = $listTu;
            }
        }
    }

    /**
     * Status interno de uma peça solicitada
     * @param $where
     * @param array $listPieceInternal
     */
    public static function getFilterOrderPieceInternal(&$where, array $listPieceInternal)
    {
        $listTu = [];
        if (is_array($listPieceInternal) && count($listPieceInternal) > 0) {
            foreach ($listPieceInternal as $k => $v) {
                $id = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id >= 0) {
                    $listTu[] = $id;
                }
            }
            $counter = count($listTu);
            if ($counter == 1) {
                $where['internal'] = $listTu[0];
            } else if ($counter > 1) {
                $where['listPieceInternal'] = $listTu;
            }
        }
    }

    /**
     * Filtro por Team Owner
     * @param $where
     * @param array $listTeamUserOwner
     */
    public static function getFilterOrderTeamOwner(&$where, array $listTeamUserOwner)
    {
        $listTo = [];
        if (is_array($listTeamUserOwner) && count($listTeamUserOwner) > 0) {
            foreach ($listTeamUserOwner as $k => $v) {
                $id_user = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_user > 0) {
                    $listTo[] = $id_user;
                }
            }
            $counter = count($listTo);
            if ($counter == 1) {
                $where['id_user_owner'] = $listTo[0];
            } else if ($counter > 1) {
                $where['listTeamUserOwner'] = $listTo;
            }
        }
    }

    /**
     * Filtro por Group
     * @param $where
     * @param array $listGroup
     */
    public static function getFilterUserGroup(&$where, array $listGroup)
    {
        $listGr = [];
        if (is_array($listGroup) && count($listGroup) > 0) {
            foreach ($listGroup as $k => $v) {
                $id_group = (int)UsefulController::getValueInArray($v, 'id', 0);
                if ($id_group > 0) {
                    $listGr[] = $id_group;
                }
            }
            $counter = count($listGr);
            if ($counter == 1) {
                $where['id_group'] = $listGr[0];
            } else if ($counter > 1) {
                unset($where['id_group']);
                $where['listGroup'] = $listGr;
            }
        }
    }

    /**
     * Subconsulta para aplicar expressoes no campo de pesquisa search
     * @param $search
     * @param $where
     * @param bool|false $isCalendarSearch
     * @param bool $isSearchSequence
     */
    public static function getFilterSearchRegexp($search, &$where, bool $isCalendarSearch = false, bool $isSearchSequence = false)
    {
        if (ValidadorController::isValidNotEmpty($search) && strlen($search) > 1) {
            if (ValidadorController::isValidDigits($search)) {
//                $where['sequence'] = addslashes($search);
                $where['sequence_protocol'] = addslashes($search);
                unset($where['search']);
            } else if (strpos($search, '\\') !== false) {
                $where['agency'] = $search;
                unset($where['search']);
            } else if (strpos($search, '#') !== false) {
                $where['pab'] = $search;
                unset($where['search']);
            } else if (strpos($search, '*') !== false) {
                $where['pae'] = $search;
                unset($where['search']);
            } else if (strpos($search, '%') !== false) {
                $where['piece_code'] = $search;
                unset($where['search']);
            } elseif ($isCalendarSearch) {
                $where['name'] = addslashes($search);
            } elseif ($isSearchSequence) {
                $where['research'] = addslashes($search);
                unset($where['search']);
            } else {
                $where['search'] = addslashes($search);
            }
        }
    }

    /**
     * @param array $stos
     * @return array|string
     */
    public static function getFilterStos(array $stos)
    {
        switch ($stos) {
            case 'ALL':
            case 'all':
                $stos = 'all';
                break;
            case 'WAITING':
            case 'waiting':
                $stos = 'waiting';
                break;
            case 'PAUSED':
            case 'paused':
                $stos = 'paused';
                break;
            case 'CANCELED':
            case 'canceled':
                $stos = 'canceled';
                break;
            case 'CKECKED':
            case 'checked':
                $stos = 'checked';
                break;
            case 'UNDONE':
            case 'undone':
                $stos = 'undone';
                break;
            case 'FINISHED':
            case 'finished':
                $stos = 'finished';
                break;
            case 'DELAYED':
            case 'delayed':
                $stos = 'delayed';
                break;
            case 'ALTERED':
            case 'altered':
                $stos = 'altered';
                break;
            case 'SYNCED':
            case 'synced':
                $stos = 'synced';
                break;
            case 'PROCESSED':
            case 'processed':
                $stos = 'processed';
                break;
            default:
                $stos = null;
                break;
        }

        return $stos;
    }

    /**
     * Remove caracteres e quebras de linhas de uma string com uma chave pub/priv
     * @param string $pKey
     * @return string
     */
    private static function formattedPkey(string $pKey): string
    {
        return trim(str_replace([
            '-----BEGIN PUBLIC KEY-----',
            '-----END PUBLIC KEY-----',
            '-----BEGIN RSA PRIVATE KEY-----',
            '-----END RSA PRIVATE KEY-----',
            '-----BEGIN RSA PUBLIC KEY-----',
            '-----END RSA PUBLIC KEY-----',
            "\r\n",
            "\n",
        ], '', $pKey));
    }

    /**
     * Converte um stdClass em array
     * @param $data
     * @return array|mixed
     */
    public static function stdClassToArray($data): array
    {
        return is_object($data) ? (array)$data : $data;
    }

    /**
     * Retorna um array com a copia dos dados de um ArrayObject
     * @param $arrayObject
     * @param bool $tryObject
     * @return array
     */
    public static function getCopyArrayObject($arrayObject, $tryObject = false): array
    {
        if (is_a($arrayObject, '\Laminas\Stdlib\ArrayObject')) {
            return $arrayObject->getArrayCopy();
        } else if ($tryObject && is_object($arrayObject)) {
            return self::stdClassToArray($arrayObject);
        }
        return [];
    }

    /**
     * Colores padroes para os promoters
     * @param string $key
     * @return string
     */
    public static function getNpsColor(string $key)
    {
        $color = "#cecece";
        $name = strtoupper($key);
        if ($name == 'PROMOTERS' || $name == 'PROMOTER') {
            $color = "#66bb6a";
        } elseif ($name == 'PASSIVES' || $name == 'PASSIVE') {
            $color = "#ffff8b";
        } elseif ($name == 'DETRACTORS' || $name == 'DETRACTOR') {
            $color = "#d50000";
        }
        return $color;
    }

    /**
     * Obtem o time de um datetime
     * @param $time
     * @param bool $short
     * @return false|string
     */
    public static function getFormattedTimeFromDateTime($time, bool $short = false)
    {
        return date(($short ? "H:i" : "H:i:s"), strtotime($time));
    }

    public static function getFormattedDateTimeFromDateTime($time, bool $short = false)
    {
        return date(($short ? "Y-m-d H:i:s" : "d/m/Y H:i:s"), strtotime($time));
    }

    /**
     * Retorna a data em UTC indepedente da configuracao do server
     * @param string $fmt
     * @return string
     */
    public static function getDateTimeUniversal($fmt = 'Y-m-d H:i:s'): string
    {
        $immutable = CarbonImmutable::now('UTC');
        return $immutable->format($fmt);
    }

    /**
     * Explode uma string e insere o valor default
     * @param $str
     * @param $default
     * @param bool $notEmpty
     * @return array
     */
    public static function explodeToArray($str, $default, $notEmpty = false)
    {
        $arr = explode(", ", $str);
        $arr = array_flip($arr);
        $not = [];
        foreach ($arr as $k => $v) {
            if ($notEmpty && !ValidadorController::isValidNotEmptyWithoutNumber($k)) {
                continue;
            }
            if ($notEmpty) {
                $not[$k] = $default;
            } else {
                $arr[$k] = $default;
            }
        }

        return $notEmpty ? $not : $arr;
    }

    /**
     * Format uma string convertendo para o formato de uma sigla
     * @param string $value
     * @return string
     */
    public static function formatAbbreviation(string $value): string
    {
        return strtoupper(substr(UsefulController::removeBlank($value), 0, 3));
    }

    /**
     * Pre formatando
     * @param $st_region
     * @return string
     */
//    public static function getStRegion($st_region)
//    {
//        $st = '';
//        if ($st_region == 1) {
//            $st = 'Lote 2';
//        } else if ($st_region == 2) {
//            $st = 'Premium A';
//        } else if ($st_region == 3) {
//            $st = 'Premium B';
//        } else if ($st_region == 4) {
//            $st = 'Premium C';
//        } else if ($st_region == 5) {
//            $st = 'Lote 1';
//        } else if ($st_region == 6) {
//            $st = 'Premium D';
//        } else if ($st_region == 7) {
//            $st = 'N/A';
//        }
//        return $st;
//    }


    /**
     * Pre formatada a data
     * @param array|null $post
     * @param $translate
     * @param bool $isUpdated
     * @return false[]|string[]
     * @throws \Exception
     */
    public static function getFilterScheduleDate($post, $translate, bool $isUpdated = true, bool $isMileage = false): array
    {
        $schedule_start = UsefulController::getValueInArray($post, 'schedule_start', null);
        $start_hour = UsefulController::getValueInArray($post, 'start_hour', '00:00:00');
        $schedule_end = UsefulController::getValueInArray($post, 'schedule_end', null);
        $end_hour = UsefulController::getValueInArray($post, 'end_hour', '00:00:00');
        $nof_oc_hratei1u = UsefulController::getValueInArray($post, 'nof_oc_hratei1u', null);
        $nof_oc_hratef1u = UsefulController::getValueInArray($post, 'nof_oc_hratef1u', null);
        $schedule_start_dt = UsefulController::getValueInArray($post, 'order_schedule_start_dt', null);
        $schedule_start_hr = UsefulController::getValueInArray($post, 'order_schedule_start_hr', '00:00:00');
        $schedule_end_dt = UsefulController::getValueInArray($post, 'order_schedule_end_dt', null);
        $schedule_end_hr = UsefulController::getValueInArray($post, 'order_schedule_end_hr', '00:00:00');
        // Validate
        if ((ValidadorController::isValidNotEmpty($schedule_start_dt) && ValidadorController::isValidNotEmpty($schedule_end_dt))) {
            $schedule_start = strlen($schedule_start_dt) == 19 ? $schedule_start_dt : "{$schedule_start_dt} {$schedule_start_hr}";
            $schedule_end = strlen($schedule_end_dt) == 19 ? $schedule_end_dt : "{$schedule_end_dt} {$schedule_end_hr}";
            $Q5 = "0";
            $date = date('Y-m-d H:i:s');
            $diff = UsefulController::getTimeDifferenceBetweenDates($schedule_end, $schedule_start);
            $ts = (int)$diff['time'];
            $Q5 .= $diff['hours'] > 0 ? substr('00' . $diff['hours'] . 'h ', 2) : '';
            if ($ts < 86000 && $Q5 < 1 && !$isMileage) {
                throw new \Exception($translate->translate('scheduled date must be at least one hour') . '[2112]');
            }
            if (!$isUpdated && strtotime($schedule_start) < strtotime($date)) {
                throw new \Exception($translate->translate('the scheduled date must not be retroactive'));
            }
            return [$schedule_start, $schedule_end];

        } else {
            if (!ValidadorController::isValidNotEmpty($schedule_start) || !ValidadorController::isValidNotEmpty($schedule_end)) {
                throw new \Exception($translate->translate('Schedule Date is empty'));
            } elseif (!ValidadorController::isValidNotEmpty($start_hour) || !ValidadorController::isValidNotEmpty($end_hour)) {
                throw new \Exception($translate->translate('Schedule Hour is empty'));
            }
            $start = UsefulController::getFormattedDateToDb($schedule_start);
            $end = UsefulController::getFormattedDateToDb($schedule_end);
            if (!ValidadorController::isValidDate($start)) {
                throw new \Exception($translate->translate('Schedule Start Date is invalid'));
            } elseif (!ValidadorController::isValidDate($end)) {
                throw new \Exception($translate->translate('Schedule End Date is invalid'));
            } elseif (strtotime($start) > strtotime($end)) {
                throw new \Exception($translate->translate('End date cannot be greater than start date'));
            }
            list($schedule_start, $schedule_end) = UsefulController::getFilterDate($start, $start_hour, $end, $end_hour);
            $Q4 = "0";
            $date = date('Y-m-d H:i:s');
            $diff = UsefulController::getTimeDifferenceBetweenDates($schedule_end, $schedule_start);
            $Q4 .= $diff['hours'] > 0 ? substr('00' . $diff['hours'] . 'h ', 2) : '';

            if ($Q4 < 1 && !$isMileage) {
                throw new \Exception($translate->translate('scheduled date must be at least one hour') . '[2142]');
            }
            if (!$isUpdated && strtotime($schedule_start) < strtotime($date)) {
                throw new \Exception($translate->translate('the scheduled date must not be retroactive'));
            }
            return [$schedule_start, $schedule_end];
        }
        return [false, false];
    }

    /**
     * Proximo dia comercial/semana
     * @param string $now_at
     * @return false|string
     */
    public static function getNextBusinessDay(string $now_at)
    {
        $add_day = 0;
        do {
            $add_day++;
            $new_date = date('Y-m-d H:i:s', strtotime("$now_at +$add_day Days"));
            $new_day_of_week = date('w', strtotime($new_date));
        } while ($new_day_of_week == 6 || $new_day_of_week == 0);

        return $new_date;
    }

    /**
     * Translate do internal status
     * @param $translate
     * @param int $internal
     * @return mixed|string
     */
    public static function getStatusName($translate, int $status)
    {
        $name = '';
        switch ($status) {
            case 0 :
                $name = $translate->translate('waiting');
                break;
            case 1:
                $name = $translate->translate('started');
                break;
            case 2:
                $name = $translate->translate('pending');
                break;
            case 3:
                $name = $translate->translate('finished');
                break;

        }
        return $name;
    }

    /**
     * Translate do internal status/substatus
     * @param $translate
     * @param int $internal
     * @return mixed|string
     */
    public static function getWeekName($translate, string $week)
    {
        $name = '';
        switch ($week) {
            case 'Monday' :
                $name = $translate->translate('Monday');
                break;

            case 'Tuesday' :
                $name = $translate->translate('Tuesday');
                break;

            case 'Wednesday' :
                $name = $translate->translate('Wednesday');
                break;

            case 'Thursday' :
                $name = $translate->translate('Thursday');
                break;

            case 'Friday' :
                $name = $translate->translate('Friday');
                break;

            case 'Saturday' :
                $name = $translate->translate('Saturday');
                break;

            case 'Sunday' :
                $name = $translate->translate('Sunday');
                break;

        }
        return $name;
    }

    /**
     * Convert uma timezona de uma data
     * @param $date
     * @param string $from
     * @param string $to
     * @param string $format
     * @return string
     */
    public static function convertDateTimeTimezone($date, string $from, string $to, string $format = 'Y-m-d H:i:s'): string
    {
        return Carbon::createFromFormat($format, $date, $from)->setTimezone($to)->format($format);
    }

    /**
     * Se string comeca com
     * @param $string
     * @param $startString
     * @return bool
     */
    public static function startsWith($string, $startString): bool
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    /**
     * Se string termina com
     * @param $string
     * @param $endString
     * @return bool
     */
    public static function endsWith($string, $endString): bool
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

    /**
     * @return bool
     */
    public static function isSubdomainGlory(): bool
    {
        list($subdomain,$host) = explode('.', $_SERVER["SERVER_NAME"]);
        // Adicionando glory-teste para teste local do layout -- Gabriel 11/22/2022
        return in_array($subdomain, ['glory', 'glory-dev', 'glory-teste']);
    }


    public static function isSubdomainProsegur(): bool
    {
        list($subdomain,$host) = explode('.', $_SERVER["SERVER_NAME"]);
        return in_array($subdomain, ['prosegur', 'prosegur-dev','web-73']);
    }

    /**
     * Pre formata o genero
     * @param $gender
     * @return int
     */
    public function getGender($gender): int
    {
        if ($gender == 'male') {
            return 1;
        } elseif ($gender == 'female') {
            return 2;
        } else {
            return 0;
        }
    }

    /**
     * Gera uma senha de acordo com a pre formatacao
     *
     * @return string
     */
    public static function createPassword(): string
    {
        return \Laminas\Math\Rand::getString(8, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }

    /**
     * Gerar uma sequencia de numero
     * @param int $length
     * @return string
     */
    public static function createPin($length = 6): string
    {
        return \Laminas\Math\Rand::getString($length, '0123456789');
    }

    /**
     * Remove qualquer caractere que nao esteja na lista de permitidos
     *
     * @param $string
     * @return null|string|string[]
     */
    public static function removeAllCharacters($string): string
    {
        $string = preg_replace("/[\\n\\r\\t\\s]+/", "", rtrim(trim($string)));
        $string = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($string, ENT_NOQUOTES));
        $string = preg_replace("/[^a-zA-Z0-9]/", "", $string);
        return $string;
    }

    /**
     * Remove caracteres em brancos e espacos
     * @param $string
     * @param string $replace
     * @return mixed
     */
    public static function removeBlank($string, $replace = ' '): string
    {
        return trim(str_replace(array(
            ' ',
            '\s',
            '\t'
        ), $replace, $string));
    }

    /**
     * Remove todos os caracteres, exceto os numeros
     * @param $string
     * @return mixed|null|string|string[]
     */
    public static function removeAllCharactersExceptNumber($string): string
    {
        $string = self::removeAllCharacters($string);
        $string = preg_replace("/[^0-9]/", "", $string);
        return $string;
    }

    /**
     * Remove caracteres não permitidos
     *
     * @param $string
     * @return null|string|string[]
     */
    public static function removeAllCharactersExceptLettersNumbersSpacesUnderscores($string): string
    {
        $string = preg_replace("/[^ \w]+/", "", $string);
        return $string;
    }

    /**
     * Remove todos os caracteres, exceto os letras
     *
     * @param $string
     * @return null|string|string[]
     */
    public static function removeAllCharactersExceptLetters($string): string
    {
        $string = preg_replace("/[^a-zA-Z\s]/", '', $string);
        return $string;
    }

    /**
     * Pre formata uma string
     *
     * @param $string
     * @return mixed|null|string|string[]
     */
    public static function removeCharactersToUrl($string): string
    {
        $string = self::removeBlank(strtolower($string), '_');
        $string = self::removeAllCharactersExceptLettersNumbersSpacesUnderscores($string);
        return $string;
    }

    /**
     * Remove os digitros entre os caracteres T e Z
     * @param $string
     * @return string|string[]|null
     */
    public static function removeRRuleTz($string)
    {
        $string = preg_replace("/(T[\d]+[Z]?)/", "", $string);
        return $string;
    }

    /**
     * Converte um nome, para nome de pasta permitido
     *
     * @param $string
     * @return string
     */
    public static function stringToFolderName($string): string
    {
        $string = \Useful\Controller\TexturizeController::remove_accents($string);
        $string = self::removeCharactersToUrl($string);

        return strtoupper($string);
    }

    /**
     * Requiscao e XML como Resposta
     *
     * @param $url
     * @param array $parameters
     * @param string $method
     * @param null $logger
     * @return \SimpleXMLElement
     */
    public static function requestXml($url, $parameters = array(), $method = 'GET', $logger = null)
    {
        $request = new \Laminas\Http\Request();
        $request->setUri($url);
        $request->setMethod($method);

        $client = new \Laminas\Http\Client();
        $client->setOptions([
            'sslverifypeer' => false
        ]);

        $response = $client->dispatch($request);
        $body = $response->getBody();
        // Debug
        if (!is_null($logger) && is_object($logger)) {
            $logger->info($url . PHP_EOL);
            //$logger->info($RawBody . PHP_EOL);
            $logger->info($client->getResponse()
                ->getReasonPhrase());
            foreach ($client->getResponse()->getHeaders() as $header) {
                $logger->info($header->getFieldName() . ' with value ' . $header->getFieldValue());
            }
            $logger->info($body . PHP_EOL);
        }
        return simplexml_load_string($body);
    }

    /**
     * Chamando processo em background
     * @param $url
     * @param $payload
     * @param bool $debug
     * @param null $authorization
     * @param string $type
     * @return bool
     */
    public static function requestFork($url, $payload, $debug = false, $authorization = null, $type = 'Basic'): bool
    {
        $cmd = "curl  ";
        if ($debug) {
            $cmd .= " -v ";
        }
        $cmd .= " --insecure -X POST -H 'Content-Type: application/json' -H 'accept: application/json' -H 'cache-control: no-cache' ";
        if (ValidadorController::isValidNotEmpty($authorization)) {
            $cmd .= " -H 'Authorization: {$type} {$authorization}' ";
        }
        $cmd .= " -d '" . \Laminas\Json\Json::encode($payload) . "' " . "'" . $url . "'";
        if ($debug) {
            $today = date('Y-m-d');
            $cmd .= " --trace-ascii ./data/logs/CURL-" . $today . " >> ./data/logs/CURL-" . $today . " 2>&1 &";
        }
        exec($cmd, $output, $exit);
        return $exit == 0;
    }

    /**
     * Request adicionando JWT Token
     * @param string $iss
     * @param array $payload
     * @param bool $debug
     */
    public static function requestJwt(string $iss, array $payload, bool $debug = false)
    {
        $payload['passkey'] = UsefulController::getConfig('Project', 'passkey'); //Adicionando chave adicional para validacao extra
        $idekey = UsefulController::getConfig('OAuth', 'ide');
        $pKey = UsefulController::getConfig('OAuth', 'privKey');
        $key = self::formattedPkey($pKey);
        $Jwt = CryptController::generateJwt($iss, $payload, $key);
        if ($Jwt instanceof \Lcobucci\JWT\Token) {
            //Execute
//            echo ($Jwt->toString());
//            exit();
            UsefulController::requestFork($iss, [], $debug ?? false, $Jwt->__toString(), $idekey);
        }
    }

    /**
     * Obter os dados de um post com json
     *
     * @param bool $is_json
     * @param null $encodedValue
     * @param null $logger
     * @param null $request
     * @return bool|mixed
     */
    public static function getPost(bool $is_json = true, $encodedValue = null, $logger = null, $request = null)
    {
        try {
            if ($is_json) {
                $result = \Laminas\Json\Json::decode($encodedValue);
                // BEGIN DEBUG
                if (!is_null($logger) && is_object($logger)) {
                    foreach ($request->getHeaders() as $header) {
                        $logger->info($header->getFieldName() . ' with value ' . $header->getFieldValue());
                    }
                    $logger->info($encodedValue . PHP_EOL);
                    $logger->info(json_encode($result) . PHP_EOL);
                }
                // END DEBUG
                return $result;
            } else {
                if (!is_null($logger) && is_object($logger)) {
                    foreach ($request->getHeaders() as $header) {
                        $logger->info($header->getFieldName() . ' with value ' . $header->getFieldValue());
                    }
                    $logger->info(json_encode($_POST) . PHP_EOL);
                }

                return $_POST;
            }
        } catch (\Exception $e) {
        }
        return false;
    }

    /**
     * Retorna Padrao
     *
     * @param $status
     * @param null $message
     * @param array $data
     * @param bool $outcome
     * @param null $logger
     * @param null $request
     * @param null $render
     * @return \Laminas\View\Model\JsonModel
     */
    public static function createResponse($status, $message = null, $data = [], $outcome = false, $logger = null, $request = null, $render = null)
    {
        header('Content-Type: application/json');
        $result = new \Laminas\View\Model\JsonModel(array(
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'outcome' => $outcome,
            'render' => $render
        ));
        // BEGIN DEBUG
        if (!is_null($logger) && is_object($logger)) {
            foreach ($request->getHeaders() as $header) {
                $logger->info($header->getFieldName() . ' with value ' . $header->getFieldValue());
            }
            $logger->info($result->serialize() . PHP_EOL);
        }
        // END DEBUG
        return $result;
    }

    /**
     * Retorna uma configuracao
     *
     * @param $key
     * @param $subkey
     * @return bool|mixed
     */
    public static function getConfig($key, $subkey = null)
    {
        $config = \Laminas\Config\Factory::fromFile(realpath(__DIR__ . '/../../../../config/autoload/local.php'));
        if (strlen($key) > 0 && isset($config[$key])) {
            if (!is_null($subkey) && strlen($subkey) > 0) {
                return isset($config[$key][$subkey]) ? $config[$key][$subkey] : false;
            }
            return isset($config[$key]) ? $config[$key] : false;
        }
        return false;
    }

    /**
     * Carrega as configuracoes globais
     * @param $key
     * @param null $subkey
     * @return false|mixed
     */
    public static function getConfigGlobal($key, $subkey = null)
    {
        $config = \Laminas\Config\Factory::fromFile(realpath(__DIR__ . '/../../../../config/autoload/global.php'));
        if (strlen($key) > 0 && isset($config[$key])) {
            if (!is_null($subkey) && strlen($subkey) > 0) {
                return isset($config[$key][$subkey]) ? $config[$key][$subkey] : false;
            }
            return isset($config[$key]) ? $config[$key] : false;
        }
        return false;
    }

    /**
     * Retorna a diferenca entre duas datas
     * @param $start
     * @param $end
     * @return array
     */
    public static function getTimeDifferenceBetweenDates($start, $end)
    {
        $time = strtotime($start) - strtotime($end);

        $days = floor($time / 86400);
        $hours = floor(($time - ($days * 86400)) / 3600);
        $minutes = floor(($time - ($days * 86400) - ($hours * 3600)) / 60);
        $seconds = floor(($time - ($days * 86400) - ($hours * 3600) - ($minutes * 60)));

        return array(
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'time' => $time
        );
    }

    /**
     * Retorna o tempo de duracao entre duas datas
     * @param $Q1
     * @param $Q2
     * @return string
     */
    public static function getTimeDuration($Q1, $Q2)
    {

        // Duration
        $Q3 = "N/A";
        if (ValidadorController::isValidRegexp($Q1, 'date_time') && ValidadorController::isValidRegexp($Q2, 'date_time')) {
            if (strtotime($Q1) == strtotime($Q2)) {
                $Q3 = "0";
            } elseif (strtotime($Q1) < strtotime($Q2)) {
                $diff = UsefulController::getTimeDifferenceBetweenDates($Q2, $Q1);
                $Q3 = $diff['days'] > 0 ? $diff['days'] . 'd ' : '';
                $Q3 .= $diff['hours'] > 0 ? substr('00' . $diff['hours'] . 'h ', 2) : '';
                $Q3 .= $diff['minutes'] > 0 ? substr('00' . $diff['minutes'] . 'm ', 2) : '';
                $Q3 .= $diff['seconds'] > 0 ? substr('00' . $diff['seconds'] . 's ', 2) : '';
            }
        }
        return $Q3;
    }

    /**
     * Converte uma imagem url para base64
     * @param $url
     * @return string
     */
    public static function getBaseFromImageUrl($url)
    {
        $pattern = '~(http.*\.)(jpe?g|png|[tg]iff?|svg)~i';
        if (preg_match($pattern, $url)) {
            $type = pathinfo($url, PATHINFO_EXTENSION);
            $data = file_get_contents($url);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            return $base64;
        }
        return $url;
    }

    /**
     * Calculo do SLA Glory
     * @param $date
     * @param string $sla_minute
     * @param string $format
     * @return string
     */
    public static function SlaCalculatorGlory($date,$sla_minute,$res_holiday ,string $format = 'Y-m-d H:i:s')
    {
        $hr_start = '08:00';
        $hr_end = '18:00';
        $date_hour = date('H:i:00', strtotime($date));
        $date_at = date('Y-m-d', strtotime($date));
        $new_date = self::isWeekend($date,$res_holiday);
        while($sla_minute != 0){
            if($date_at == $new_date){
                $sla_date_calculator = new \DateTime($new_date . '' . $date_hour);
                $sla_date_calculator = $sla_date_calculator->format('Y-m-d H:i:s');
            }else{
                $sla_date_calculator = new \DateTime($new_date . '' . $hr_start);
                $sla_date_calculator = $sla_date_calculator->format('Y-m-d H:i:s');
            }
//            var_dump('DATA 1 ', $sla_date_calculator);
//            var_dump('DATA 2 ', $new_date);

            $date_start = new \DateTime($new_date . '' . $hr_start);
            $start = $date_start->format('Y-m-d H:i:s');

            $date_end = new \DateTime($new_date . '' . $hr_end);
            $end = $date_end->format('Y-m-d H:i:s');

            if($sla_date_calculator >=$start and $sla_date_calculator<$end){
                $time = strtotime($end) - strtotime($sla_date_calculator);
                $minutos = $time/60;
//                $diff=self::getTimeDuration($sla_date_calculator,$end);
//                $diff = str_replace('s',':',(str_replace('m ',':',(str_replace('h ',':',$diff)))));
//                $partes = explode(":", $diff);
//                $minutos = $partes[0]*60+$partes[1];

                $sla_minute = $sla_minute - $minutos;

                if($sla_minute<0 || $sla_minute ==0){
                    $sla_date_calculator = new \DateTime($new_date . '' . $hr_end);
                    $sla_date_calculator->modify("+{$sla_minute} minutes");
                    $sla_date_calculator = $sla_date_calculator->format('Y-m-d H:i:s');
                    $sla_minute = 0;
                }else{
                    $sla_date_calculator = date('Y-m-d', strtotime($sla_date_calculator . "+1 day"));
                    $new_date = self::isWeekend($sla_date_calculator,$res_holiday);
                }

//                var_dump('SLA  - ',$sla_date_calculator,' - horas - ',$diff,' - $sla_minute - ',$sla_minute);exit();
            }else if($sla_date_calculator>=$end){
                $sla_date_calculator = date('Y-m-d', strtotime($sla_date_calculator . "+1 day"));
                $new_date = self::isWeekend($sla_date_calculator,$res_holiday);
            }else{
                $sla_date_calculator = new \DateTime($new_date . '' . $hr_start);
                $sla_date_calculator = $sla_date_calculator->format('Y-m-d H:i:s');
                $time = strtotime($end) - strtotime($sla_date_calculator);
                $minutos = $time/60;
//                $diff=self::getTimeDuration($sla_date_calculator,$end);
//                $diff = str_replace('s',':',(str_replace('m ',':',(str_replace('h ',':',$diff)))));
//                $partes = explode(":", $diff);
//                $minutos = $partes[0]*60+$partes[1];
                $sla_minute = $sla_minute - $minutos;
                if($sla_minute<0 || $sla_minute ==0){
                    $sla_date_calculator = new \DateTime($new_date . '' . $hr_end);
                    $sla_date_calculator->modify("+{$sla_minute} minutes");
                    $sla_date_calculator = $sla_date_calculator->format('Y-m-d H:i:s');
                    $sla_minute = 0;
                }else{
                    $sla_date_calculator = date('Y-m-d', strtotime($sla_date_calculator . "+1 day"));
                    $new_date = self::isWeekend($sla_date_calculator,$res_holiday);
                }
            }
        }
        Return $sla_date_calculator;
    }
    /**
     * Próximo dia útil
     * @param $date
     * @param string $sla_minute
     * @param string $format
     * @return string
     */
    public static function isWeekend($date, $res_holiday)
    {

        $date = date('Y-m-d', strtotime($date));
        $dutil=FALSE;
        while (!$dutil) {
            while (date('w', strtotime($date)) == 6 or date('w', strtotime($date)) == 0) {
                $date = date('Y-m-d', strtotime($date . "+1 day"));
            }
            if (array_search($date, $res_holiday))
            {
                $date = date('Y-m-d', strtotime($date. '+ 1 days'));
            }else{
                $dutil=TRUE;
            }
        }
        return $date;


//        $dutil=true;
//        while($dutil) {
//
//            while (array_search($date, $res_holiday)){
//                $date = date('Y-m-d', strtotime($date . "+1 day"));
//            }
//
//
//            while (date('w', strtotime($date)) == 6 or date('w', strtotime($date)) == 0) {
//                $date = date('Y-m-d', strtotime($date . "+1 day"));
//            }
//        }
//        return date($date);

    }
}