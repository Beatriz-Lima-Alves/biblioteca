<?php

namespace Useful\Controller;

/**
 * Class SwearwordsController
 * @package Useful\Controller
 */
class SwearwordsController
{
    const BADWORDS = ['ANUS', 'BABA-OVO', 'BABAOVO', 'BABACA', 'BACURA', 'BAGOS', 'BAITOLA', 'BEBUM', 'BESTA', 'BICHA', 'BISCA', 'BIXA', 'BOAZUDA', 'BOCETA', 'BOCO', 'BOIOLA', 'BOLAGATO', 'BOQUETE', 'BOLCAT', 'BOSSETA', 'BOSTA', 'BOSTANA', 'BRECHA', 'BREXA', 'BRIOCO', 'BRONHA', 'BUCA', 'BUCETA', 'BUNDA', 'BUNDUDA', 'BURRA', 'BURRO', 'BUSSETA', 'CACHORRA', 'CACHORRO', 'CADELA', 'CAGA', 'CAGADO', 'CAGAO', 'CAGONA', 'CANALHA', 'CARALHO', 'CASSETA', 'CASSETE', 'CHECHECA', 'CHERECA', 'CHIBUMBA', 'CHIBUMBO', 'CHIFRUDA', 'CHIFRUDO', 'CHOTA', 'CHOCHOTA', 'CHUPADA', 'CHUPADO', 'CLITORIS', 'COCAINA', 'COCO', 'CORNA', 'CORNO', 'CORNUDA', 'CORNUDO', 'CORRUPTA', 'CORRUPTO', 'CRETINA', 'CRETINO', 'CRUZ-CREDO', 'CU', 'CULHAO', 'CURALHO', 'CUZAO', 'CUZUDA', 'CUZUDO', 'DEBIL', 'DEBILOIDE', 'DEFUNTO', 'DEMONIO', 'DIFUNTO', 'DOIDA', 'DOIDO', 'EGUA', 'ESCROTA', 'ESCROTO', 'ESPORRADA', 'ESPORRADO', 'ESPORRO', 'ESTUPIDA', 'ESTUPIDEZ', 'ESTUPIDO', 'FEDIDA', 'FEDIDO', 'FEDOR', 'FEDORENTA', 'FEIA', 'FEIO', 'FEIOSA', 'FEIOSO', 'FEIOZA', 'FEIOZO', 'FELACAO', 'FENDA', 'FODA', 'FODAO', 'FODE', 'FODIDAFODIDO', 'FORNICA', 'FUDENDO', 'FUDECAO', 'FUDIDA', 'FUDIDO', 'FURADA', 'FURADO', 'FURÃO', 'FURNICA', 'FURNICAR', 'FURO', 'FURONA', 'GAIATA', 'GAIATO', 'GAY', 'GONORREA', 'GONORREIA', 'GOSMA', 'GOSMENTA', 'GOSMENTO', 'GRELINHO', 'GRELO', 'HOMO-SEXUAL', 'HOMOSSEXUAL', 'HOMOSSEXUAL', 'IDIOTA', 'IDIOTICE', 'IMBECIL', 'ISCROTA', 'ISCROTO', 'JAPA', 'LADRA', 'LADRAO', 'LADROEIRA', 'LADRONA', 'LALAU', 'LEPROSA', 'LEPROSO', 'LÉSBICA', 'MACACA', 'MACACO', 'MACHONA', 'MACHORRA', 'MANGUACA', 'MANGUA¦A', 'MASTURBA', 'MELECA', 'MERDA', 'MIJA', 'MIJADA', 'MIJADO', 'MIJO', 'MOCREA', 'MOCREIA', 'MOLECA', 'MOLEQUE', 'MONDRONGA', 'MONDRONGO', 'NABA', 'NADEGA', 'NOJEIRA', 'NOJENTA', 'NOJENTO', 'NOJO', 'OLHOTA', 'OTARIA', 'OT-RIA', 'OTARIO', 'OT-RIO', 'PACA', 'PASPALHA', 'PASPALHAO', 'PASPALHO', 'PAU', 'PEIA', 'PEIDO', 'PEMBA', 'PÊNIS', 'PENTELHA', 'PENTELHO', 'PERERECA', 'PERU', 'PICA', 'PICAO', 'PILANTRA', 'PIRANHA', 'PIROCA', 'PIROCO', 'PIRU', 'PORRA', 'PREGA', 'PROSTIBULO', 'PROST-BULO', 'PROSTITUTA', 'PROSTITUTO', 'PUNHETA', 'PUNHETAO', 'PUS', 'PUSTULA', 'PUTA', 'PUTO', 'PUXA-SACO', 'PUXASACO', 'RABAO', 'RABO', 'RABUDA', 'RABUDAO', 'RABUDO', 'RABUDONA', 'RACHA', 'RACHADA', 'RACHADAO', 'RACHADINHA', 'RACHADINHO', 'RACHADO', 'RAMELA', 'REMELA', 'RETARDADA', 'RETARDADO', 'RIDÍCULA', 'ROLA', 'ROLINHA', 'ROSCA', 'SACANA', 'SAFADA', 'SAFADO', 'SAPATAO', 'SIFILIS', 'SIRIRICA', 'TARADA', 'TARADO', 'TESTUDA', 'TEZAO', 'TEZUDA', 'TEZUDO', 'TROCHA', 'TROLHA', 'TROUCHA', 'TROUXA', 'TROXA', 'VACA', 'VAGABUNDA', 'VAGABUNDO', 'VAGINA', 'VEADA', 'VEADAO', 'VEADO', 'VIADA', 'VÍADO', 'VIADAO', 'XAVASCA', 'XERERECA', 'XEXECA', 'XIBIU', 'XIBUMBA', 'XOTA', 'XOCHOTA', 'XOXOTA', 'XANA', 'XANINHA'];

    /**
     * Altera a palavra se ela existe no array de palavroes
     * @param string $str
     * @return array|string|string[]
     */
    public static function censor(string $str)
    {
        if ($str) {
            $badwords = self::BADWORDS;
            $replacewith = array();
            $index = 0;
            foreach ($badwords as $value) {
                $words = explode(" ", $str);
                foreach ($words as $word) {
                    $a = strtoupper(trim($word));
                    $b = strtoupper(trim($value));
                    if ($a == $b) {
                        $str = str_replace($word, "**", $str);
                    }
                }
            }
        }
        return $str;
    }
}