<!DOCTYPE html>
<html lang="id-ID" dir="ltr">
    <head>
        <!-- Metadata -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Website Title -->
        <title><?php echo $title; ?></title>

        <link rel="shortcut icon" href="<?php echo $collections['static_link']('app/algoritmaru/favicon.png'); ?>">

        <!-- Core Style -->
        <!-- Latest compiled and minified CSS -->
        <?php
        echo $collections['static']('app/algoritmaru/app.min.css');
        echo $collections['static']('fontawesome@5.1.0/css/all.css');
        ?>

        <style>
            body {
                padding-top: 70px;
            }

            a span, .btn i, h3 i {
                margin-right: 5px;
            }

            .question {
                margin-top: 20px;
            }

            /* .navbar-nav > li > a, .navbar-brand {
                padding-top:10px !important; padding-bottom:0 !important;
                height: 40px;
            }
            .navbar {min-height:40px !important;}
            .navbar-toggle {
                padding: 5px 5px;
                margin-top: 7px;
                margin-bottom: 0;
            } */

            /*
            * Callouts
            *
            * Not quite alerts, but custom and helpful notes for folks reading the docs.
            * Requires a base and modifier class.
            */

            /* Common styles for all types */
            .bs-callout {
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #eee;
            border-left-width: 5px;
            border-radius: 3px;
            }
            .bs-callout h4 {
            margin-top: 0;
            margin-bottom: 5px;
            }
            .bs-callout p:last-child {
            margin-bottom: 0;
            }
            .bs-callout code {
            border-radius: 3px;
            }

            /* Tighten up space between multiple callouts */
            .bs-callout + .bs-callout {
            margin-top: -5px;
            }

            /* Variations */
            .bs-callout-danger {
            border-left-color: #ce4844;
            }
            .bs-callout-danger h4 {
            color: #ce4844;
            }
            .bs-callout-warning {
            border-left-color: #aa6708;
            }
            .bs-callout-warning h4 {
            color: #aa6708;
            }
            .bs-callout-info {
            border-left-color: #1b809e;
            }
            .bs-callout-info h4 {
            color: #1b809e;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url('/'); ?>">ALGORITMARU</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav pull-right">
                        <?php echo $collections['navigation'](); ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container content-container">
            <?php $this->load->view($view, array('data' => $data, 'collections' => $collections)); ?>
        </div>
    </body>
    <?php
    echo $collections['static']('jquery@3.3.1/jquery.min.js', 'text/javascript');
    ?>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <?php
    echo $collections['static']('app/algoritmaru/app.min.js', 'text/javascript');
    ?>
</html>
