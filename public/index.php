<?php
include_once("../utils/autoloader.php");
session_start();

var_dump($_SESSION)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .flex{
            display: flex;
        }
        .flex-col{
            flex-direction: column;
            justify-content: space-between;
        }
        body {
            background-color: skyblue;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            gap: 40px;
            margin: 20px;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }

        .input-section {
            padding: 5px;
            background: white;
            width:100vw;
            display: flex;
            justify-content: center;
        }
        .input-section > form{
            display: flex;
            justify-content: center;
        }

        .machine-container {
            flex: 2;
            display: flex;
            justify-content: center;
        }
        .text-container {
            display: flex;
            justify-content: center;
            text-align: center;
            transition: all 0.5s;
            background-color:rgba(245, 245, 245, 0.31);
            padding: 40px 0;
            box-shadow:rgba(26, 26, 26, 0.22) 0px 0px 3px;
            border-radius: 4px;

            flex-direction: column;


            min-width: 40vw;
            min-height: 60vh;
        }

        .coffee-machine {
            width: 300px;
            background: linear-gradient(145deg, #3a3a3a, #1a1a1a);
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .coffee-machine::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 20px;
            background: #2c2c2c;
            border-radius: 10px 10px 0 0;
        }

        .coffee-machine::after {
            content: '';
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 10px;
            background: #4a4a4a;
            border-radius: 5px;
            box-shadow: 0 15px 0 #333;
        }

        .switch-btn{
            margin: 8px;
            padding: 12px 20px;
            border-radius: 20px;
            border: none;
            background:rgb(105, 7, 0);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 5px rgba(255,255,255,0.2);
        }
        .switch-btn.on{
            background:rgb(32, 252, 24);
        }

        .button {
            margin: 8px;
            padding: 12px 20px;
            border-radius: 20px;
            border: none;
            background: #4CAF50;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 5px rgba(255,255,255,0.2);
        }

        .button:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .status {
            color: #00ff00;
            font-size: 18px;
            margin-bottom: 20px;
            padding: 10px;
            background: #000;
            border-radius: 8px;
            text-align: center;
            font-family: 'Courier New', monospace;
            box-shadow: inset 0 0 10px rgba(0,255,0,0.3);
        }

        select.button {
            width: 100%;
            background: #2196F3;
        }

        input[type="number"], 
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: #f5f5f5;
        }

        form {
            margin: 15px 0;
        }
    </style>
</head>
<body class="flex flex-col">
    
    <div class="input-section">
        <form id="createMachineForm" onsubmit="createMachine(event)">
            <input type="text" name="machineName" placeholder="Machine Name">
            <button type="submit" class="button" >Create Machine</button>
        </form>
    </div>

    <div class="container">



        <div class="text-container">

        </div>


        <div class="machine-container">
            

        </div>


    </div>
</body>



<script>
    
    let $machineContainer = document.querySelector(".machine-container");
    let $textContainer = document.querySelector(".text-container");
    
    
    // Créer la machine a café
    async function createMachine(event) {
        event.preventDefault();
        
        let machineName = document.querySelector("#createMachineForm>input").value;

        return fetch('actions.php', {
            method: 'POST',
            body: new URLSearchParams({
            "action": 'create',
            "name": machineName
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // error_log(print_r(data))
            $textContainer.innerHTML += `<p> ${data.message} </p>`
            
            document.querySelector("#createMachineForm>input").value = ""
            document.querySelector(".input-section").style.visibility = "hidden"
            drawMachine(machineName)
        })


    }




    async function powerMachine(event) {

        event.preventDefault();
        console.log("On power la machine");
        

        return fetch('actions.php', {
            method: 'POST',
            body: new URLSearchParams({
            "action": 'power',
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            $textContainer.innerHTML += `<p> ${data.message} </p>`
            
            document.querySelector("#power").classList.toggle("on")
            
        })

    }



    // Mettre la machine a café en html
    function drawMachine(machineName) {

        $container = document.querySelector(".machine-container")

        $container.innerHTML += `
            <div class="coffee-machine" id="machine-${machineName}">
                <div class="status">${machineName}</div>
                <button class="switch-btn" id="power">Power On/Off</button>
                <button class="button" id="sugar">Sugar: Off</button>
                <button class="button" id="demanderCafe">Demander</button>

                <form id="pay">
                    <input type="number" step="0.01" placeholder="Insert Money €">
                    <button type="submit" class="button">Pay</button>
                </form>
            </div>
        `

        // Initialise la machine todo: mettre dans une nouvelle fonction
        document.querySelector("#power").addEventListener("click", powerMachine);
        document.querySelector("#sugar").addEventListener("click", toggleSugar);
        document.querySelector("#demanderCafe").addEventListener("click", demanderCafe);
        document.querySelector("#pay").addEventListener("submit", payerCafe);

    }



    function toggleSugar(event){
        event.preventDefault();
        

    }

    function demanderCafe(event){
        event.preventDefault();
        

    }

    function payerCafe(event){
        event.preventDefault();
        

    }



    // async function fetchData(bodyContent) {
    //     return fetch('actions.php', {
    //         method: 'POST',
    //         body: new URLSearchParams(bodyContent),
    //         headers: {
    //             'Content-Type': 'application/json'
    //         }
    //     })
    //     .then(response => {
    //         if (!response.ok) {
    //             throw new Error('Network response was not ok');
    //         }
    //         return response.json();
    //     })
    //     .then(data => {
    //         // error_log(print_r(data))
    //         return data;
    //     })
    //     .catch(error => {
    //         console.error('Erreur ici :', error);
    //         return { status: "Failed", message: error.message };
    //     });
    // }
    
    
    
</script>

</html>