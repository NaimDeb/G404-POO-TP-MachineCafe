<?php

class MachineACafe
{

    private string $marque;
    private int $cafe;
    private bool $enFonction;
    private int $sugar;
    private float $prixCafe;
    private float $prixSucre;

    private bool $hasAskedSugar;
    private bool $hasAskedForCoffee;

    private float $finalPrice;


    public function __construct(string $marque)
    {

        $this->marque = $marque;
        $this->cafe = 0;
        $this->enFonction = false;
        $this->sugar = 0;
        $this->prixCafe = 7.50;
        $this->prixSucre = 0.50;

        $this->hasAskedForCoffee = false;
    }


    public function getMarque():string {
        return $this->marque;
    }

    public function getEnFonction():bool {
        return $this->enFonction;
    }

    public function getSugar():int {
        return $this->sugar;
    }
    public function getCoffee():int {
        return $this->cafe;
    }
    public function getPrixCafe():float {
        return $this->prixCafe;
    }
    public function getPrixSucre():float {
        return $this->prixSucre;
    }


    public function allumage(): string
    {

        if ($this->enFonction) {
            return "{$this->marque} est déja allumée";
        }
        $this->enFonction = !$this->enFonction;
        return "{$this->marque} est en fonction";
    }

    public function eteignage(): string
    {

        if (!$this->enFonction) {
            return "{$this->marque} est déja éteinte";
        }
        $this->enFonction = !$this->enFonction;
        return "{$this->marque} est maintenant éteinte";
    }


    // Todo : enlever isWantSucre car demandé dans DemanderCafé
    /**
     * Fait du café, en demandant si l'utilisateut veut du sucre
     */
    private function faireDuCafe(string $texteRenduMonnaie): string
    {

        if ($this->hasAskedSugar) {
            $this->sugar--;
            $message = "Votre café sucré au sucre est prêt";
        } else {
            $message = "Votre café est prêt";
        }
        $this->cafe--;
        $this->hasAskedForCoffee = false;

        $message = $message . "<br>" . $texteRenduMonnaie;

        return $message;
    }

    /**
     * Ajoute un nombre $nbDosettes dans la cafetière
     */
    public function mettreUneDosette(int $nbDosettes = 1): string
    {
        $this->cafe += $nbDosettes;
        return "Je mets une dosette";
    }


    /**
     * Fonction montrant la marque, le nombre de dosettes de café et de sucre
     */
    // todo : faire plusieures fonctions et tt mettre dans getAll
    public function getAll(): string
    {
        return "<br>Votre machine est de marque : <strong>{$this->marque}</strong> <br> Vous avez actuellement <strong>{$this->cafe}</strong> dosettes a l'intérieur,<br> et vous avez <strong>{$this->sugar}</strong> sucres aussi le sang";
    }

    /**
     * ajoute un nombre nbSucres a la machine
     */
    public function ajoutSucre(int $nbSucres = 1): string
    {
        $this->sugar += $nbSucres;
        return "Vous ajoutez $nbSucres sucres";
    }




    // ! On a besoin du return $this ici ?
    /**
     * demande un nombre de café et demande si tu veux du sucre ,stocke les choix, appelle calculerPrixCafe() et affiche le prix
     */
    public function demanderCafe(bool $avecSucre = false, int $nbCafes = 1):string {

        // Si la machine est pas allumée
        if (!$this->enFonction) {
            return "Tu demande un café a la machine éteinte, bizarrement, la machine ne répond pas.";
        }
        // Si il n'y a pas de dosettes
        if ($this->cafe <= 0) {
            return "Il n'y a plus de café dans la machine !";
        }

        if ($avecSucre && $this->sugar <= 0) {
            return "Il n'y a plus de sucre dans la machine !";
        }


        // Pas besoin de checker si l'user a déja demandé un café pck il peut juste redemander si il s'est trompé.
        $this->hasAskedForCoffee = true;
        // Stocke si l'utilisateur veut du sucre
        $this->hasAskedSugar = $avecSucre ? true : false; 

        // Todo : Faire différents cafés ? 

        $this->calculerPrixCafe();

        $hasSugarText =  $this->hasAskedSugar ? "avec sucre" : "sans sucre";
        
        return "Vous avez choisi un café " . $hasSugarText . ", vous devez payer : <strong>" . $this->finalPrice . " € </strong> ";



    }


    // ! Je pense qu'on a besoin du return $this ici car elle est appelée dans demandeCafe ?
    /**
     * Calcule et stocke le prix que l'user doit payer et l'attribue a finalPrice
     */
    private function calculerPrixCafe():self {

        $prix = 0;

        // Todo : Checker le type de café, et avoir le prix approprié
        $prix += $this->prixCafe;

        // J'aime bien faire chier avec mes opérations ternaires
        $prix += $this->hasAskedSugar ? $this->prixSucre : 0;

        $this->finalPrice = $prix;

        return $this;



    }


    


    /**
     * Fonction pour payer un café, on donne l'argent nécessaire, fais les checks nécessaires, puis appelle le monnayeur
     */
    public function payerCafe(float $argentDonne) {

        // Check avant si l'utilisateur a demandé un café
        // todo: check if machine on
        if (!$this->hasAskedForCoffee) {
            return "Veuillez d'abord demander un café";
        }

        // Regarde si l'argent donné peut payer le café
        if ($argentDonne < $this->finalPrice) {
            return "T'essaye de m'arnaquer ? t'as même pas assez pour du café allez barre toi";
        }

        // Regarde si la macihne est allumée
        if (!$this->enFonction) {
            return "Vous insérez {$argentDonne} € dans la machine a café. Vous attendez un moment, les minutes passent, et il n'y a toujours pas de café, peut-être est-ce du au fait que vous aviez éteint la machine juste avant de dépenser votre argent, personne ne le saura.";
        }


        $texteRendu = $this->calculerDifferenceCafe($argentDonne, $this->finalPrice);

        return $this->faireDuCafe($texteRendu);


    }


    /**
     * Calcule ce que la machine doit te rendre et rend un string 
     */
    private  function calculerDifferenceCafe(float $argentDonne, float $finalPrice): string {

        $argentARendre = $argentDonne - $finalPrice;

        // On transforme 
        $argentARendre = floor($argentARendre * 100);

        // Premier check si il n'y a pas d'argent a rendre
        if ($argentARendre <= 0) {
            return "";
        }
        
        $texteRendu = "Argent redonné : <br>";

        $validArgent = [
            "2 euro" => 200,
            "1 euro" => 100,
            "50 centimes" => 50,
            "20 centimes" => 20,
            "10 centimes" => 10,
            "5 centimes" => 5,
            "2 centimes" => 2,
            "1 centime" => 1,
        ];
        
        
        foreach ($validArgent as $texteArgent => $argent) {
        
        
            if ($argentARendre <= 0) {
                break;
            }
    

            $nbPieces = floor($argentARendre / $argent);
            // Pour éviter de marquer "0 pièces de X"
            if($nbPieces == 0) {
                continue;
            } 

            // Correct syntax
            $textePiecesDe = $nbPieces == 1 ? " pièce de " : " pièces de ";


            $texteRendu .= $nbPieces . $textePiecesDe . $texteArgent . "<br>";
            $argentARendre = $argentARendre % $argent;
        }


        return $texteRendu;

 }





}
