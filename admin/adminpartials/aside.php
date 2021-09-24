<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #063771;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .openButton {
            font-size: 20px;
            cursor: pointer;
            background-color: #063771;
            color: white;
            padding: 10px 15px;
            border: none;
        }

        .openButton:hover {
            background-color: #444;
        }

        #main {
            transition: margin-left .5s;
            padding: 16px;
        }

        @media screen and (max-height: 450px) {
            .sidebar {padding-top: 15px;}
            .sidebar a {font-size: 18px;}
        }
    </style>
</head>
<body>

<div id="Sidebar" class="sidebar">
    <a href="javascript:void(0)" class="closButton" onclick="closeNav()">×</a>
    <a href="adminindex.php">Aceuill</a>
    <a href="categories.php">Catégorie</a>
    <a href="productsshow.php">Vinyles</a>
    <a href="orders.php">Commandes</a>
    <a href="indexchat.php">Contact</a>
    <a href="adminpartials/logout.php">Log Out</a>
</div>

<div id="main">
    <button class="openButton" onclick="openNav()">☰</button>

<script>
    function openNav() {
        document.getElementById("Sidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("Sidebar").style.width = "0";
        document.getElementById("main").style.marginLeft= "0";
    }
</script>
