<html>
    <head>
        <meta charset="UTF-8">

        <style>
            body{
                background-image: url("https://img.freepik.com/free-photo/black-friday-elements-assortment_23-2149074076.jpg?t=st=1746599379~exp=1746602979~hmac=ef9de24cc2176ff54e132517d16b6d4c698b9534b49d5c86bee2b9fc434f2efb&w=1380");
                background-size:cover;
                background-repeat:no-repeat;
                background-position:center;   
                margin:0;
                padding:0;
                height:100vh;
                display:flex;
                align-items:center;
                justify-content: flex-start;
                padding-left:10%;             
            }
            .button {
            font-family: Georgia, 'Times New Roman', Times, serif;
            height: 155px;
            width: 215px;
            background-color: rgb(165, 24, 92);
            color: white;
            font-size: 20px;
            border-radius: 10px;
            margin-right: 50px;
            border: none;
            box-shadow: 10px 11px 10px rgba(139, 9, 9, 0.48);
            }
            .button:hover {
            background-color: #ddd;
            color: black;
            }
            .back{
            position:absolute;
            font-size:16px;
            top: 10px;
            left:10px;
            color:rgb(165, 24, 92);
            }
            .back:hover{
                color:black;
            }
            .logout{
                position:absolute;
                font-size:20px;
                top:10px;
                right:10px;
                color:black;
                text-decoration:none;
            }
            .logout:hover{
                color:white;
            }
            .buttons {
            font-family: Georgia, 'Times New Roman', Times, serif;
            height: 155px;
            width: 215px;
            background-color: rgb(165, 24, 92);
            color: white;
            font-size: 20px;
            border-radius: 10px;
            margin-left: 140px;
            margin-top:50px;
            border: none;
            box-shadow: 10px 11px 10px rgba(139, 9, 9, 0.48);
            }
            .buttons:hover {
            background-color: #ddd;
            color: black;
            }
        </style>
    </head>
    <body>
    <a href="/php/auth/logout.php" class="logout" title="Logout">&#x23FB;</a>
    </body>
    

<form method = "post" action = "">
    <button type="button" class="button" onclick = "window.location.href='/php/admin/add-category.php'">Add a category</button>
    <button type="button" class="button" onclick = "window.location.href='http://localhost/php/admin/add-product.php'">Add a product</button><br>
    <button type="button" class="buttons" onclick="window.location.href='http://localhost/php/reports/index.php'">Reports Overview</button>
</form>