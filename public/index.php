<?php
include_once("../utils/autoloader.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine à Café</title>

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
            background:rgb(177, 2, 2);
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
        <div class="text-container"></div>
        <div class="machine-container"></div>
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
            <button class="switch-btn" id="power">I/O</button>
            <div class="flex flex-col">
                <button class="button" id="sugar">Sugar: Off</button>
                <p class="button" id="sugarCount">En stock :</p>
                <button class="button" id="addSugar">Ajouter Sucre</button>
                <p class="button" id="cafeCount">Dosettes En stock :</p>
                <button class="button" id="addCoffee">Ajouter Café</button>
            </div>
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
        document.querySelector("#addSugar").addEventListener("click", addSugar);
        document.querySelector("#addCoffee").addEventListener("click", addCoffee);

    }


    let hasAskedSugar = false;

    function toggleSugar(event){
        event.preventDefault();
        
        hasAskedSugar = !hasAskedSugar;
        document.querySelector("#sugar").innerHTML = hasAskedSugar ? "Sugar: On" : "Sugar: Off";
    }


    function demanderCafe(event){
        event.preventDefault();

        return fetch('actions.php', {
            method: 'POST',
            body: new URLSearchParams({
            "action": 'ask',
            "avecSucre": hasAskedSugar
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
        })
        

    }



    function addCoffee(event){
        event.preventDefault();

        return fetch('actions.php', {
            method: 'POST',
            body: new URLSearchParams({
            "action": 'addcoffee',
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
            document.querySelector("#cafeCount").innerHTML = "Dosettes en stock : "  + data.coffee
        })
        

    }

    function addSugar(event){
        event.preventDefault();

        return fetch('actions.php', {
            method: 'POST',
            body: new URLSearchParams({
            "action": 'addsugar',
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
            document.querySelector("#sugarCount").innerHTML = "Sucre en stock : "  + data.sugar
        })
        

    }


    function payerCafe(event){
        event.preventDefault();


        event.preventDefault();
        
        let amountGiven = document.querySelector("#pay>input").value;

        return fetch('actions.php', {
            method: 'POST',
            body: new URLSearchParams({
            "action": 'pay',
            "amountgiven": amountGiven
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
            
            document.querySelector("#pay>input").value = ""
            document.querySelector("#cafeCount").innerHTML = "Dosettes en stock : "  + data.coffee
            document.querySelector("#sugarCount").innerHTML = "Sucre en stock : "  + data.sugar



        })

        

    }

    <?php if (isset($_SESSION["machine"])){ ?>
        drawMachine("<?php echo $_SESSION["machine"]->getMarque(); ?>");
    <?php } ?>
    
</script>

</html>