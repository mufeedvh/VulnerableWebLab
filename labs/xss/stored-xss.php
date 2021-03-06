<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stored XSS Lab</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
    <style>
        form {
        padding: 20px;
        margin: 0 auto;
        max-width: 600px;
        }
        form h1 {
        padding-bottom: 10px;
        margin-bottom: 25px;
        font-family: 'Open Sans';
        font-weight: 100;
        text-align: center;
        color: #0288D1;
        border-bottom: 1px solid #ccc;
        }
        .titles {
        padding-bottom: 10px;
        margin-bottom: 25px;
        font-family: 'Open Sans';
        font-weight: 100;
        text-align: center;
        color: #0288D1;
        border-bottom: 1px solid #ccc;
        }        
        form .row {
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        }
        form input[type="text"], form input[type="password"], form textarea {
        width: 100%;
        height: 40px;
        padding: 10px 10px 10px 90px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        color: #333;
        border-radius: 3px;
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9);
        }
        form textarea {
        height: auto;
        min-height: 200px;
        padding: 50px 10px 10px 10px;
        }
        form input[type="text"] + label, form input[type="password"] + label, form textarea + label {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        height: 40px;
        line-height: 40px;
        font-size: 12px;
        font-weight: bold;
        width: 80px;
        text-shadow: 0 0 2px rgba(0, 0, 0, 0.1);
        text-align: center;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: white;
        background: #0288D1;
        border-radius: 3px 0 0 3px;
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9);
        -webkit-transform: translateZ(0) translateX(0);
                transform: translateZ(0) translateX(0);
        }
        form textarea + label {
        width: 100%;
        border-radius: 3px 3px 0 0;
        }
        form input[type="text"]:focus, form input[type="password"]:focus {
        padding-left: 10px;
        }
        form textarea:focus {
        padding-top: 10px;
        }
        form input[type="text"]:focus + label, form input[type="password"]:focus + label {
        -webkit-transform: translateZ(0) translateX(-100%);
                transform: translateZ(0) translateX(-100%);
        }
        form textarea:focus + label {
        -webkit-transform: translateZ(0) translateY(-100%);
                transform: translateZ(0) translateY(-100%);
        }
        form input[type="checkbox"], form input[type="radio"] {
        position: absolute;
        overflow: hidden;
        clip: rect(0 0 0 0);
        height: 1px;
        width: 1px;
        margin: -1px;
        padding: 0;
        }
        form input[type="radio"] + label {
        position: relative;
        display: inline-block;
        overflow: hidden;
        text-indent: -9999px;
        background: #ccc;
        width: 30px;
        height: 30px;
        border-radius: 100%;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9);
        }
        form input[type="radio"] + label:before {
        content: '';
        position: absolute;
        display: block;
        height: 10px;
        width: 10px;
        top: 50%;
        left: 50%;
        background: white;
        border-radius: 100%;
        box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9) 0.1s;
        -webkit-transform: translateZ(0) translate(-50%, -50%) scale(0);
                transform: translateZ(0) translate(-50%, -50%) scale(0);
        }
        form input[type="radio"]:checked + label {
        background: #0288D1;
        }
        form input[type="radio"]:checked + label:before {
        -webkit-transform: translateZ(0) translate(-50%, -50%) scale(1);
                transform: translateZ(0) translate(-50%, -50%) scale(1);
        }
        form input[type="checkbox"] + label {
        position: relative;
        display: inline-block;
        overflow: hidden;
        text-indent: -9999px;
        background: #ccc;
        width: 60px;
        height: 30px;
        border-radius: 100px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9);
        }
        form input[type="checkbox"] + label:before {
        content: '';
        position: absolute;
        display: block;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: #0288D1;
        border-radius: 100px;
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9) 0.1s;
        -webkit-transform: translateZ(0) scale(0);
                transform: translateZ(0) scale(0);
        }
        form input[type="checkbox"] + label:after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        display: block;
        height: 26px;
        width: 26px;
        background: white;
        border-radius: 100%;
        box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.2);
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9);
        -webkit-transform: translateZ(0) translateX(0);
                transform: translateZ(0) translateX(0);
        }
        form input[type="checkbox"]:checked + label {
        background: #0288D1;
        }
        form input[type="checkbox"]:checked + label:after {
        left: calc(100% - 28px);
        -webkit-transform: translateZ(0);
                transform: translateZ(0);
        }
        form button {
        position: relative;
        overflow: hidden;
        height: 40px;
        line-height: 40px;
        padding: 0 20px;
        font-size: 12px;
        font-weight: bold;
        text-shadow: 0 0 2px rgba(0, 0, 0, 0.1);
        text-align: center;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: white;
        border: none;
        background: #0288D1;
        border-radius: 3px;
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9);
        -webkit-transform: translateZ(0) translateX(0);
                transform: translateZ(0) translateX(0);
        z-index: 2;
        }
        form button:before {
        content: '';
        display: block;
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        background: #01579B;
        height: 100%;
        width: 100%;
        border-radius: 3px;
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9);
        -webkit-transform: translateZ(0) scale(0);
                transform: translateZ(0) scale(0);
        z-index: -1;
        }
        form button:hover:before, form button:focus:before {
        -webkit-transform: scale(1);
                transform: scale(1);
        transition: all 0.3s cubic-bezier(1, 0.1, 0, 0.9);
        }
        .comments {
                text-align: center;
        }
    </style>
    <form method="POST">
        <h1>Leave Your Comment</h1>
        
        <div class="row">
            <textarea name="comment" id="fancy-textarea"></textarea>
            <label for="fancy-textarea">Comment</label>
        </div>
        
        <button type="submit" tabindex="0">Submit</button>
    </form>
    <h1 class="titles">Comments</h1>
    <div class="comments">
        <?php

                ini_set('session.gc_maxlifetime', 3600);
                session_set_cookie_params(0.1);
                session_start();

                if (isset($_POST['comment'])) {
                        $user_comment = $_POST['comment'];
                        $_SESSION['stored_comment'] = $user_comment;
                        echo 1;
                        echo $_SESSION["stored_comment"];
                }

        ?>
    </div>
</body>
</html>