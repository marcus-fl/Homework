<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php

    /*
    ZDE SI VYTVÁŘÍM KLÍČOVÉ PROMĚNNÉ PRO MŮJ PROGRAM!
    */
    //první oktet
    $octet1 = 190;
    //druhý oktet
    $octet2 = 160;
    //třetí oktet
    $octet3 = 60;
    //čtvrtý oktet
    $octet4 = 0;
    //prefix
    $prefix = 26;
    /*
    zde jsem si převedl všechny oktety na desítkovou soustavu a poté jsem je rozšířil v funkci str_pad() na velikost binárního oktetu v ip adrese (8 bitů)
    */
    $binarni_octet1 = str_pad(decbin($octet1),8,"0", STR_PAD_LEFT); 
    $binarni_octet2 = str_pad(decbin($octet2),8,"0", STR_PAD_LEFT);
    $binarni_octet3 = str_pad(decbin($octet3),8,"0", STR_PAD_LEFT);
    $binarni_octet4 = str_pad(decbin($octet4),8,"0", STR_PAD_LEFT);
    //$binarni_octety_bez_tecek je proménná, která v sobě má naplácané všechny oktety v binární pdoobě např: 10100000100000001111000000000000
    $binarni_octety_bez_tecek = $binarni_octet1.$binarni_octet2.$binarni_octet3.$binarni_octet4;
    /*
    * celý program je umístěn v podmínce, která se stará o to, aby program přijmul validní adresu
    - v podmínce je převedená čáast ip adresdy, která se nachází za prefixem a hlavní část programu se vykoná pouze kokud tato část bude mít nulovou hodnotu
    */
    if(bindec(substr($binarni_octety_bez_tecek, $prefix)) == 0){
        /*
        * zde vypisuji zadanou IP adresu
        */
        $ip = $octet1.'.'.$octet2.'.'.$octet3.'.'.$octet4.' / '.$prefix;
        echo '<p class="nadpis">IP adresa:</p>'.'<p>'.$ip.'</p>';
        /*
        * zde vypisuji zadanou ip adresu v binární podobě
        */
        $ip_dvojkova_soustava = $binarni_octet1.".".$binarni_octet2.".".$binarni_octet3.".".$binarni_octet4;
        echo '<p class="nadpis">IP adresa v binární podobě:</p>'.'<p>'.$ip_dvojkova_soustava.'</p>';
        /*
        * zde vygeneruji a následně vypíši masku sítě v dvojkové podobě
        */
        $maska_binarni = "";
        for($i = 0; $i < 32;$i++){
            if($i == 8 || $i == 16 || $i == 24){
                $maska_binarni = $maska_binarni.".";
            }
            if($i < $prefix){
                $maska_binarni = $maska_binarni."1";  
            }else{
                $maska_binarni = $maska_binarni."0";
            }
        }
        echo '<p class="nadpis">Maska v binární podobě:</p>'.'<p>'.$maska_binarni.'</p>';
        /*
        * zde vygeneruji a následně vypíši masku sítě v desítkové soustavě
        */
            for($i = 0; $i < 4;$i++){
                if($i == 0){
                    $maska_binarni_pole = explode(".",$maska_binarni);
                    $maska_desitkova = "";
                }
                $maska_binarni_pole[$i] = bindec($maska_binarni_pole[$i]);
                if($i == 0){
                    $maska_desitkova = $maska_desitkova.$maska_binarni_pole[$i];
                }else{
                $maska_desitkova = $maska_desitkova.".".$maska_binarni_pole[$i];
                }
            }
        echo '<p class="nadpis">Maska v desitkove podobě:</p>'.'<p>'.$maska_desitkova.'</p>';
        /*
        * zde vygeneruji a následně vypíši broadcast sítě v dvojkové soustavě
        */
        $broadcast_binarni = "";
        for($i = 0; $i < 32;$i++){
            if($i == 8 || $i == 16 || $i == 24){
                $broadcast_binarni = $broadcast_binarni.".";
            }
            if($i < $prefix){
                $broadcast_binarni = $broadcast_binarni.$binarni_octety_bez_tecek[$i];
            }else{
                $broadcast_binarni = $broadcast_binarni."1";  
            }
        }
        echo '<p class="nadpis">Broadcast v binární podobě:</p>'.'<p>'.$broadcast_binarni.'</p>';
        /*
        * zde vygeneruji a následně vypíši broadcast sítě v desítkové soustavě
        */
        for($i = 0; $i < 4;$i++){
            if($i == 0){
                $broadcast_binarni_pole = explode(".",$broadcast_binarni);
                $broadcast_desitkovy = "";
            }
            $broadcast_binarni_pole[$i] = bindec($broadcast_binarni_pole[$i]);
            if($i == 0){
                $broadcast_desitkovy = $broadcast_desitkovy.$broadcast_binarni_pole[$i];
            }else{
            $broadcast_desitkovy = $broadcast_desitkovy.".".$broadcast_binarni_pole[$i];
            }
        }
        echo '<p class="nadpis">Broadcast v desitkové soustavě:</p>'.'<p>'.$broadcast_desitkovy.'</p>';
        //název porměnné mluví sám za sebe
        $prvni_tri_octety_v_desitkove_soustave = $octet1.".".$octet2.".".$octet3.".";
        /*
        * tento kousek programu se postará o výpočet prvního hosta, posledního hosta a počtu hostů
        */
        $posledni_octet = "";
        for($i = 24; $i < 32; $i++){
            $posledni_octet = $posledni_octet.$binarni_octety_bez_tecek[$i];
            if($i == 31){
                $prvni_host = bindec($posledni_octet) + 1;
                $posledni_host = 254 - bindec(substr($maska_binarni, 27, 35));
                $pocet_hostu = 254 - $prvni_host;
            }
        }
        /*
        * tyto echa vypisují
            - ip adresu prvního hosta
            - ip adresu posledního hosta
            - počet hostů
        */
        echo '<p class="nadpis">První host:</p>'.'<p>'.$prvni_tri_octety_v_desitkove_soustave.$prvni_host.'</p>';
        echo '<p class="nadpis">Poslední host:</p>'.'<p>'.$prvni_tri_octety_v_desitkove_soustave.$posledni_host.'</p>';
        echo '<p class="nadpis">Počet hostů:</p>'.'<p>'.$pocet_hostu.'</p>';
        //zde můžete vidět else stav celé této podmínky, který obsahuje text, který se vypíše pokud je adresa sítě nevalidní
    }else{
        echo '<b>ŠPATNĚ ZADANÁ ADRESA!</b>';
    }

    ?>


</body>