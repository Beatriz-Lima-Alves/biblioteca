<?php

namespace Useful\Controller;

/**
 * Class ValidadorController
 * @package Useful\Controller
 * Author Raquel
 * Date 13/09/2019
 * Time 16:00
 */
class ValidadorController
{

    /**
     * Compara dois numeros
     *
     * @param $a
     * @param $b
     * @return bool
     */
    public static function ifDigitsComparison($a, $b): bool
    {
        if ((int)$a == (int)$b) {
            return true;
        } elseif ((int)$a > (int)$b) {
            return false;
        } elseif ((int)$a < (int)$b) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se array contem somente numeros
     *
     * @param $arr
     * @return bool
     */
    public static function isValidArrayDigits($arr): bool
    {
        foreach ($arr as $item) {
            if (!self::isValidDigits($item)) {
                return false;
                break;
            }
        }
        return true;
    }

    /**
     * Checa se um arquivo foi enviado
     *
     * @param $file
     * @return bool
     */
    public static function isValidSetFile($file): bool
    {
        return (isset($file) && $file['error'] != UPLOAD_ERR_NO_FILE);
    }

    /**
     * Verifica se o valor informado esta entre dois outros valores.
     *
     * @param String $value ,
     *            valor a ser verificado
     * @param Int $min ,
     *            menor valor permitido
     * @param Int $max ,
     *            maior valor permitido
     * @return Boolean
     */
    public static function isValidBetweenDigits($value, $min = 1, $max = 2): bool
    {
        if (self::isValidDigits($value)) {
            $validator = new \Laminas\Validator\Between(array(
                'min' => $min,
                'max' => $max
            ));
            return $validator->isValid($value);
        }
        return false;
    }

    /**
     * Valida e retorna o tipo de visualizao de cardo com a extensao
     *
     * @param $media_url
     * @param $media_extesion
     * @return null|string
     */
    public static function isValidContestPreview($media_url, $media_extesion)
    {
        if (ValidadorController::isValidURL($media_url)) {
            if (in_array($media_extesion, array(
                'jpeg',
                'jpg',
                'png',
                'gif'
            ))) {
                return 'image';
            } elseif (in_array($media_extesion, array(
                'mp4',
                'ogg'
            ))) {
                return 'video';
            }
        }
        return null;
    }

    /**
     * Verifica se contem uma string no formato valido de um json
     *
     * @param $string
     * @return bool
     */
    public static function isValidJson($string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Verifica se o valor informado e vazio/null
     *
     * @param String $value , valor a ser verificado
     * @return Boolean, FALSE se esta vazio e TRUE se nao esta vazio
     */
    public static function isValidNotEmpty($value = null): bool
    {
        // Returns false on 0 or '0'
        $validator = new \Laminas\Validator\NotEmpty([
            \Laminas\Validator\NotEmpty::INTEGER,
            \Laminas\Validator\NotEmpty::ZERO,
            \Laminas\Validator\NotEmpty::NULL,
            \Laminas\Validator\NotEmpty::STRING
        ]);
        return $validator->isValid($value);
    }

    /**
     * Se uma string eh vazia sem considerar valores numericos
     * @param null $value
     * @return bool, FALSE se esta vazio e TRUE se nao esta vazio
     */
    public static function isValidNotEmptyWithoutNumber($value = null): bool
    {
        $validator = new \Laminas\Validator\NotEmpty([
            \Laminas\Validator\NotEmpty::NULL,
            \Laminas\Validator\NotEmpty::STRING
        ]);
        return $validator->isValid($value);
    }

    /**
     * Verifica se uma string esta entre um comprimento definido.
     *
     * @param String $value ,
     *            valor a ser verificado
     * @param Int $min ,
     *            Define o tamanho minimo permitido para uma string.
     * @param Int $max ,
     *            Define o tamanho maximo permitido para uma string.
     * @return Boolean
     */
    public static function isValidStringLength($value, $min = 5, $max = 15): bool
    {
        if (self::isValidNotEmpty($value)) {
            $validator = new \Laminas\Validator\StringLength(array(
                'min' => $min,
                'max' => $max
            ));
            return $validator->isValid($value);
        }
        return false;
    }

    /**
     * Verifica se o valor informado e uma data dentro do formato permitido
     *
     * @param String $value ,
     *            valor a ser verificado
     * @param String $format ,
     *            formato de data para validacao. T
     * @return Boolean
     * @see This param $format accepts format as specified in the standard PHP function date() http://php.net/manual/en/function.date.php
     */
    public static function isValidDate($value, $format = 'Y-m-d'): bool
    {
        if (self::isValidNotEmpty($value) && self::isValidStringLength($value, 10, 10)) {
            $validator = new \Laminas\Validator\Date();
            $validator->setFormat($format);
            return $validator->isValid($value);
        }
        return false;
    }

    /**
     * Validacao de hora
     *
     * @see http://www.mkyong.com/regular-expressions/how-to-validate-time-in-24-hours-format-with-regular-expression/
     * @param string $time
     * @return boolean
     */
    public static function isValidTime($time): bool
    {
        $validator = new \Laminas\Validator\Regex('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/');
        return $validator->isValid($time);
    }

    /**
     * Verifica se um valor se encontra dentro do padrao de uma expressao regular pre definida
     *
     * @param String $value ,
     *            valor a ser verificado
     * @param String $const ,
     *            tipo da expressao
     * @return Boolean
     */
    public static function isValidRegexp($value, $const = 'resultado'): bool
    {
        if (self::isValidNotEmpty($value)) {
            switch ($const) {
                case 'resultado':
                    // Expressao regular para validacao de resultado
                    $pattern = "/^([0-9])*((-)?([0-9])+)*$/";
                    return preg_match($pattern, $value);
                    break;
                case 'date_time':
                    return preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value);
                    break;
                case 'acronym_bar_name':
                    return self::isValidAcronymBarName($value);
                    break;
                case 'base64':
                    return base64_decode($value);
                    break;
                case 'color':
                    return preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value);
                    break;
                case 'phone':
                    return preg_match('/^(\+(1)?[0-9]{2,3})?([-.\s]?[0-9]{2,3})?[-.\s]?[0-9]{4,5}[-.\s]?[0-9]{4,5}$/', $value);
                    break;
                default:
                    return false;
            }
        }
        return false;
    }

    /**
     * Validacao de IP
     * @param $packed
     * @return bool
     */
    public static function isValidPackedIP($packed): bool
    {
        $isValid = false;
        $ipStr = @inet_ntop($packed);
        if ($ipStr !== false) {
            $isValid = filter_var($ipStr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false;
        }

        return $isValid;
    }

    /**
     * Verifica se a cor esta no padrao esperado, senao retorna o default
     *
     * @param $color
     * @param string $default
     * @return mixed|string
     */
    public static function isValidRGB($color, $default = ''): bool
    {
        $color = UsefulController::removeBlank($color);
        if (substr($color, 0, 1) != '#') {
            $color = '#' . $color;
        }
        return self::isValidRegexp($color, 'color') ? $color : $default;
    }

    /**
     * Valida de string contem um nome seguido de barra e terminando com uma sigla de dois caracteres
     *
     * @param String $string
     * @return boolean
     */
    public static function isValidAcronymBarName($string): bool
    {
        $validator = new \Laminas\Validator\Regex('/[A-Za-z\s]+\/[A-Za-z0-9]{2}(\s)?/');
        return $validator->isValid($string);
    }

    /**
     * Verifica se o valor se encontra dentro do padrao do username esperado
     *
     * @param String $username , nome do usuario
     * @return Boolean , TRUE se dentro do padrao e false fora do padrao
     */
    public static function isValidUsername($username): bool
    {
        $validator = new \Laminas\Validator\Regex('/^[A-Za-z0-9]+[._-]{0,1}[A-Za-z0-9]+[._-]{0,1}[A-Za-z0-9]+$/');
        return $validator->isValid($username);
    }

    /**
     * Valida uma senha segundo a expressao regular pre definida
     *
     * @param String $senha
     * @return Boolean , TRUE se dentro do padrao e false fora do padrao
     */
    public static function isValidSenha($senha): bool
    {
        $validator = new \Laminas\Validator\Regex('/^[A-Za-z0-9@#$%&*?!.]{8,40}$/');
        return $validator->isValid($senha);
    }

    /**
     * Valida uma data e hora segundo a expressao regular pre definida
     *
     * @param $date
     * @return bool
     */
    public static function isValidDateBrazil($date): bool
    {
        $validator = new \Laminas\Validator\Regex('/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}\s[0-9]{2}:[0-9]{2}$/');
        return $validator->isValid($date);
    }

    /**
     * Valida uma url segundo a expressao regular pre definida
     * @param $url
     * @return bool , TRUE se dentro do padrao e false fora do padrao
     */
    public static function isValidURL($url): bool
    {
        $validator = new \Laminas\Validator\Regex('/^((http|https)\:\/\/)?(www\.)?[a-zA-Z0-9-_]+\.([a-zA-Z0-9]+\.)?([a-zA-Z0-9]+)([A-za-z0-9-_.\&\/\?\=\(\)]+)?$/');
        return $validator->isValid($url);
    }

    /**
     * Valida uma conta de email segundo a expressao regular pre definida
     * @param $mail
     * @return bool , TRUE se dentro do padrao e false fora do padrao
     */
    public static function isValidEmail($mail): bool
    {
        $validator = new \Laminas\Validator\Regex('/^[a-zA-Z0-9._-]+@([a-zA-Z0-9-]+\.)?([a-zA-Z0-9-]+\.)?[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/');
        return $validator->isValid($mail);
    }

    /**
     * Validacao de coordenadas geograficas (latitude e longitude)
     * @param $coordinate
     * @return bool
     */
    public static function isValidGeoPosition($coordinate): bool
    {
        $validator = new \Laminas\Validator\Regex('/^(\-?\d+(?:\.\d+)?),?\s*(\-?\d+(?:\.\d+)?)$/');
        return $validator->isValid($coordinate);
    }

    /**
     * Validate latitude format and numbers
     * @param $value
     * @return bool
     */
    public static function isValidGeoLat($value)
    {
        $validator = new \Laminas\Validator\Regex('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/');
        return $validator->isValid($value);
    }

    /**
     * Validate longitude format and numbers
     * @param $value
     * @return bool
     */
    public static function isValidGeoLng($value)
    {
        $validator = new \Laminas\Validator\Regex('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/');
        return $validator->isValid($value);
    }

    /**
     * Valida o formato de um endereco de IPv4/IPv6
     * @param $ip
     * @return bool
     */
    public static function isValidIP($ip): bool
    {
        $validator = new \Laminas\Validator\Ip();
        return $validator->isValid($ip);
    }

    /**
     * Verifica se uma string se encontra dentro do padroa monetario de um pais.
     * Default DE (0.000,00)
     *
     * @param String $value ,
     *            valor para verificacao
     * @return Boolean
     */
    public static function isValidMoney($value, $locale = 'de'): bool
    {
        if (self::isValidNotEmpty($value)) {
            $value = self::cleanValueMoney($value);
            $validator = new \Laminas\I18n\Validator\Float(array(
                'locale' => $locale
            ));
            return $validator->isValid($value);
        }
        return false;
    }

    /**
     * Verifica se a string informada contem somente numeros
     *
     * @param String $value ,
     *            valor a ser verificado
     * @return Boolean
     */
    public static function isValidDigits($value): bool
    {
        $validator = new \Laminas\Validator\Digits();
        return $validator->isValid($value);
    }

    /**
     * Validacao Decimal
     *
     * @param $value
     * @return boolean
     */
    public static function isValidDecimal($value): bool
    {
        return (self::isValidDigits($value) || self::isValidFloat($value) || is_float($value + 0));
    }

    /**
     * Validacao de inteiro com ponto flutuante
     *
     * @param $value
     * @return bool
     */
    public static function isValidFloat($value): bool
    {
        // $validator = new \Laminas\Validator\Regex ( '/^(\-?\d+(?:\.\d+)?),?\s*(\-?\d+(?:\.\d+)?)$/' );
        // return $validator->isValid ( $value );
        return is_float($value) || is_numeric($value) && ((float)$value != (int)$value);
    }

    /**
     * Remove caracteres comum em uma variavel de moeda, porem nao permitidos
     * @param $value
     * @return mixed
     */
    public static function cleanValueMoney($value)
    {
        return str_replace(array(
            '$',
            'R$',
            'R',
            ' '
        ), '', $value);
    }

    /**
     * Verifica se contem uma string na relacao passada dentro array de palavras.
     * Retorna TRUE para a primeira palavra encontrada.
     * Caso encontrado uma palavra, nao eh verificado as palavras seguintes do array.
     *
     * @param Array $words ,
     *            contem a lista de palavras que deve ser procurada dentro do texto
     * @param String $string ,
     *            texto para busca
     * @return boolean
     */
    public static function ifStringContainsSpecificWords($words, $string): bool
    {
        foreach ($words as $a) {
            if (strpos($string, $a) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Compara duas string
     *
     * @param string $str1
     * @param string $str2
     * @param boolean $case ,
     *            Comparação de string caso-sensitivo de Binário seguro dos primeiros n caracteres
     * @return boolean, TRUE se forem iguais e FALSE se nao forem iguais.
     */
    public static function ifSafeStringComparison(string $str1, string $str2, $case = false): bool
    {
        if (!$case) {
            $cmp = strcmp($str1, $str2);
        } else {
            $cmp = strncasecmp($str1, $str2, strlen($str1));
        }
        if ($cmp == 0) { // successful match
            return true;
        }
        return false;
    }

    /**
     * Tenta obter a moeda de um array, caso contrario, retorna default
     * @param $data
     * @return string
     */
    public static function whichMyCurrency($data)
    {
        // Verificacoes adicionais da moeda, defaul eh BRL
        $currency = isset($data['currency']) ? $data['currency'] : 'BRL';
        $currency = ($currency == 'BRL' || $currency == 'USD') ? $currency : 'BRL';

        return $currency;
    }

    /**
     * Se timezone esta no formato esperado
     * @param string $timezone
     * @return bool
     */
    public static function isValidTimezone(string $timezone): bool
    {
        $validator = new \Laminas\Validator\Timezone();
        $validator->setType(\Laminas\Validator\Timezone::LOCATION);
        return $validator->isValid($timezone); // returns true
    }


    /**
     * Validando datetime
     * @param $value
     * @param string $pattern
     * @return bool
     */
    public static function isValidDatetime($value, $pattern = 'Y-m-d H:i:s')
    {
        $validator = new \Laminas\I18n\Validator\DateTime();
        $validator->setPattern($pattern);
        return $validator->isValid($value);
    }


    /**
     * @param string $dt
     * @return bool
     */
    public static function isValidDateWithParse(string $dt): bool
    {
        try {
            $carbon = \Carbon\Carbon::parse($dt);
            return $carbon->isValid();
        } catch (\Exception $e) {
            // Se cair aqui, nem precisa exibir mensagem de error
        }
        return false;
    }

    /**
     * @param $value
     * @return string
     */
    public static function isValidDocument($value)
    {
        $value = UsefulController::removeAllCharactersExceptNumber($value);
        $res = 'O';
        if (self::isValidNotEmpty($value)) {
            if (self::isValidateCpf($value)) {
                $res = 'F';
            } else if (self::isValidateCnpj($value)) {
                $res = 'J';
            }
        }
        return $res;
    }


    /**
     * Valida CPF
     * @param string $value
     * @return boolean
     */

    public static function isValidateCpf($value)
    {
        $c = preg_replace('/\D/', '', $value);

        if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
            return false;
        }

        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--) ;

        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--) ;

        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;

    }

    /**
     * Valida CNPJ
     * @param string $value
     * @return boolean
     */
    public static function isValidateCnpj($value)
    {
        $c = preg_replace('/\D/', '', $value);

        if (strlen($c) != 14 || preg_match("/^{$c[0]}{14}$/", $c)) {
            return false;
        }

        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]) ;

        if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]) ;

        if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;

    }

    /**
     * Se token esta no tamanho esperado
     * @param string $token
     * @param int $len
     * @return bool
     */
    public static function isValidTokenAccess(string $token, int $len): bool
    {
        return ValidadorController::isValidNotEmpty($token) && is_string($token) && strlen($token) == $len;
    }

    /**
     * Check if date is real by carbon lib
     * @param string|null $date
     * @return bool
     * @throws \Exception
     */
    public static function isValidDateForCarbon(?string $date)
    {
        return new \Carbon\Carbon($date) instanceof \Carbon\Carbon;
    }

    /**
     * Se uma string esta no formato valido da RFC 5545
     * @param string $rfc5545
     * @return bool
     */
    public static function isValidRfc5545(string $rfc5545)
    {
        $re = '/^(DTSTART|RRULE|EXDATE)(?:;VALUE=([^:;]+))?(?:;TZID=([^:;]+))?:(.*)$/m';
        $validator = new \Laminas\Validator\Regex($re);
        return $validator->isValid($rfc5545);
    }

    /**
     * Valida CNPJ ou CPF
     * @param string $value
     * @return boolean
     */
    protected function isValidCpfCnpj(?string $value)
    {
        return ($this->validateCpf($value) || $this->validateCnpj($value));
    }

}