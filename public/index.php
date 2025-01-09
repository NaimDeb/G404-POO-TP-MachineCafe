<?php
include_once("../utils/autoloader.php");

$maCafetiere = new MachineACafe("MohandArabica");

echo $maCafetiere->allumage();
echo "<br>";
echo $maCafetiere->faireDuCafe();
echo "<br>";
echo $maCafetiere->mettreUneDosette();
echo "<br>";
echo $maCafetiere->getAll();
echo "<br>";
echo $maCafetiere->ajoutSucre(12);
echo "<br>";
echo $maCafetiere->faireDuCafe(true);
echo "<br>";
echo $maCafetiere->getAll();