<?php

class MachineACafe
{

    private string $marque;
    private int $cafe;
    private bool $enFonction;
    private int $sugar;
    private float $prixCafe;
    private float $prixSucre;


    public function __construct(string $marque)
    {

        $this->marque = $marque;
        $this->cafe = 0;
        $this->enFonction = false;
        $this->sugar = 0;
        $this->prixCafe = 7.50;
        $this->prixSucre = 0.50;
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


    public function payerCafe(int $nbCafes) {}



    /**
     * Fait du café, en demandant si l'utilisateut veut du sucre
     */
    public function faireDuCafe(bool $isWantSucre = false): string
    {
        
        // Si la machine est pas allumée
        if (!$this->enFonction) {
            return "Tu ne peux pas faire de café avec une machine éteinte";
        }
        // Si il n'y a pas de dosettes
        if ($this->cafe <= 0) {
            return "T'as pas de café mon gars ^^ faut en mettre";
        }


        $message = "Le café est prêt";

        if ($isWantSucre) {
            if ($this->sugar <= 0) {
            return "La machine n'a malheureusement pas de sucre :'(, veuillez refaire votre commande.";
            }
            $this->sugar--;
            $message = "Le café sucré au sucre est prêt";
        }

        $this->cafe--;
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
    public function getAll(): string
    {
        return "<br>Votre machine est de marque : <strong>{$this->marque}</strong> <br> Vous avez actuellement <strong>{$this->cafe}</strong> dosettes a l'intérieur,<br> et vous avez <strong>{$this->sugar}</strong> sucres aussi le sang";
    }

    /**
     * ajoute un nombre nbSucres a la machine
     */
    public function ajoutSucre(int $nbSucres): string
    {
        $this->sugar += $nbSucres;
        return "Vous ajoutez $nbSucres sucres";
    }


    // Monnayeur

    // Prix du café
    // Monnaie donnée a la machine
    // Compter différence monnaie donnée et prix du café  Rendre le reste





}
