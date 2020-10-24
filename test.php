<?php

//Complétez le code de la fonction findMiddle(). Ouvrez le fichier dont le chemin d'accès est passé en paramètre. Trouvez le milieu de ce fichier (c'est à dire le point où il y a autant d'octets avant et après dans le fichier) et la ligne du fichier qui contient le milieu du fichier. Renvoyez un tableau contenant deux éléments :
//- Index 0 : la position du début de la ligne contenant le milieu du fichier (utilisable avec fseek()).
//- Index 1 : le texte de la ligne contenant le milieu du fichier.
//
//Pour cet exercice, vous pouvez supposer que chaque caractère du fichier n'est codé que sur un seul octet.

$path = "path/to/file.txt";
function findMiddle($file)
{

    // $ret[0] : la position du début de la ligne contenant le milieu du fichier (utilisable avec [b]fseek()[/b]).
    // $ret[1] : le texte de la ligne contenant le milieu du fichier.

    //----------NE MODIFIEZ PAS LE CODE AU DESSUS DE CETTE LIGNE, IL SERA REINITIALISE LORS DE l'EXECUTION----------
    $ret = array();
    $middle = filesize($file) / 2;
    $thefile = fopen($file, "r");
    $read = 0;
    while (($buffer = fgets($thefile)) !== false) {
        $read += strlen($buffer);
        if ($read > $middle) {
            return array($pos, $buffer);
        }
        $pos = ftell($thefile);
    }
    //----------NE MODIFIEZ PAS LE CODE EN DESSOUS DE CETTE LIGNE, IL SERA REINITIALISE LORS DE l'EXECUTION----------
    return $ret;
}

print_r($middle = findMiddle($path));


//Ecrivez le code la fonction is_anagram() qui détermine si deux mots sont des anagrammes . La fonction doit renvoyer true
//si les deux mots sont des anagrammes et false sinon .
//Les mots passés en paramètres peuvent contenir des majuscules et des minuscules, mais ceci ne doit pas être pris en compte
//pour déterminer si deux mots sont des anagrammes . Les mots peuvent aussi contenir des caractères accentués codés en UTF - 8.

function is_anagram($wrd_1, $wrd_2) {
    //----------NE MODIFIEZ PAS LE CODE AU DESSUS DE CETTE LIGNE, IL SERA REINITIALISE LORS DE l'EXECUTION----------

    $wrd_1 = strtolower($wrd_1);
    $wrd_2 = strtolower($wrd_2);
    for($iter=0; $iter<strlen($wrd_1); $iter++) {
        $char = substr($wrd_1, $iter, 1);
        if (substr_count($wrd_1,$char)!=substr_count($wrd_2,$char)) {
            return false;
        }
    }
    for($iter=0; $iter<strlen($wrd_2); $iter++) {
        $char = substr($wrd_2, $iter, 1);
        if (substr_count($wrd_1, $char) != substr_count($wrd_2, $char)) {
            return false;
        }
    }
    return true;

    //----------NE MODIFIEZ PAS LE CODE EN DESSOUS DE CETTE LIGNE, IL SERA REINITIALISE LORS DE l'EXECUTION----------
}



