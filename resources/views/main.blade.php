<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Greener grass</title>
    <link href="https://fonts.googleapis.com/css?family=Squada+One&display=swap" rel="stylesheet">
    <link href="https://cdn.muicss.com/mui-0.9.42/css/mui.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous" />
    <script src="https://cdn.muicss.com/mui-0.9.42/js/mui.min.js"></script>

    <style>
        html,
        body {
            height: 100%;
            font-family: 'Squada One', cursive;
        }

        body {
            background-color: #81C784;
        }

        html,
        body,
        input,
        textarea,
        button {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.004);
        }


        /**
        * Header CSS
        */
        header {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 2;
            background-color: #4CAF50 !important;
        }

        header ul.mui-list--inline {
            margin-bottom: 0;
        }

        header a,
        header .mui--text-title {
            color: white;
            font-size: xx-large
        }

        header table {
            width: 100%;
        }


        /**
        * Content CSS
        */
        #content-wrapper {
            min-height: 100%;

            /* sticky footer */
            box-sizing: border-box;
            margin-bottom: -50px;
            padding-bottom: 50px;
        }


        /**
        * Footer CSS
        */
        footer {
            box-sizing: border-box;
            height: 50px;
            background-color: #eee;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }

        .spinner {
            margin-left: 1em;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>

    <header class="mui-appbar mui--z1">
        <div class="mui-container">
            <table>
                <tr class="mui--appbar-height">
                    <td class="mui--text-title">Greener grass</td>
                    <td class="mui--text-right">
                        <ul class="mui-list--inline mui--text-body2">
                            <!-- <li><a href="#">About</a></li>
                            <li><a href="#">Pricing</a></li>
                            <li><a href="#">Login</a></li> -->
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </header>
    <div id="content-wrapper" class="mui--text-center">
        <div class="mui--appbar-height"></div>
        <br>
        <br>
        <div class="mui--text-display3">Greener grass</div>
        <br>
        <br>
        <p class="mui--text-subhead">
            Application created by Niek van den Bos, to make sure we have a nice 'n green contributions overview on
            GitHub.
        </p>
        <br>
        <a href="/add-contribution" class="mui-btn mui-btn--raised add-contribution-button"
            style="background-color: rgba(0, 200, 83, 0.479); color: rgb(42, 42, 42);">Add contribution<span class="spinner fas fa-spinner fa-spin hidden"></span></a>
        <br>
        <br>
        <p style="font-style: italic;">You have made <span
                style="text-decoration: underline;">{{ $contributionsCount }}</span> contributions today.</p>
    </div>
    <footer>
        <div class="mui-container mui--text-center">
            Made with <span style="color: red;">♥</span> by <a href="https://github.com/OneBigOwnage">OneBigOwnage</a>
        </div>
    </footer>

    <script>
        const button  = document.querySelector('a.add-contribution-button');
        const spinner = document.querySelector('a.add-contribution-button>.spinner');

        button.addEventListener('click', () => spinner.classList.remove('hidden'));
    </script>

</body>

</html>
