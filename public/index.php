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
    <link rel="stylesheet" href="../src/css/style.css">

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
    let hasAskedSugar = false;

// A voir ça a l'air cool
    const observer = new MutationObserver(updateTextContainer);
    observer.observe($textContainer, { childList: true, subtree: true });


    function updateTextContainer() {
        const paragraphs = $textContainer.querySelectorAll('p');
        if (paragraphs.length > 5) {
            paragraphs[0].remove();
        }
    }
    

    async function updateMachine(){

        return fetch('actions.php', {
            method: 'POST',
            body: new URLSearchParams({
            "action": 'update',
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
            console.log("Updated")
            
            document.querySelector("#createMachineForm>input").value = ""
            document.querySelector(".input-section").style.visibility = "hidden"

            document.querySelector("#sugar").innerHTML = hasAskedSugar ? "Sugar: On" : "Sugar: Off";
            document.querySelector("#cafeCount").innerHTML = "Dosettes en stock : "  + data.coffee
            document.querySelector("#sugarCount").innerHTML = "Sucre en stock : "  + data.sugar


            
            document.querySelector("#power").classList.toggle("on", data.isOn);


        })

    }
    
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
        updateMachine();
    <?php } ?>
    
</script>

</html>