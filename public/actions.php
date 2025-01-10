<?php

// Include the autoloader to automatically load required classes
require_once("../utils/autoloader.php");
// Start the session to manage user-specific data
session_start();




if (!isset($_POST["action"])) {
    echo json_encode([
        "status" => "Failed",
        "message" => "No post action",
    ]);
    return;
}

if (isset($_SESSION["machine"])) {
    $machine = $_SESSION["machine"];
}


switch ($_POST["action"]) {
        case 'create':
            if (isset($_POST["name"]) && !empty($_POST["name"])) {
                $newMachine = new MachineACafe($_POST["name"]);
                $_SESSION["machine"] = $newMachine;
                echo json_encode([
                    "status" => "Success",
                    "message" => "La machine a bien été créée",
                ]);
            } else {
                echo json_encode([
                    "status" => "Failed",
                    "message" => "Le nom de la machine a café est requis",
                ]);
            }
            break;
    
        case 'power':
            
            if($machine->getEnFonction()){
                $message = $machine->eteignage();
            } else {
                $message = $machine->allumage();
            };
            echo json_encode([
                "status" => "Success",
                "message" => $message,
            ]);
            break;
    
        case 'sugar':
            
            break;
    
        case 'ask':

            break;
    
        case 'pay':

            break;
    
    default:
        echo json_encode([
            "status" => "Failed",
            "message" => "Rien qui marche",
        ]);
        break;
}

?>
