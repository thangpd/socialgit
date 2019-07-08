<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\core\RuntimeException $exception
 */
?>

<?php wp_head(); ?>
<body class="lema-single-page">
<div class="lema-content-wrapper">
    <style>
        .lema-message.error{
            color: red;
            width: 65%;
            min-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border: 1px #ddd solid;
            text-align: center;
            -webkit-box-shadow: 1px 3px 5px 0px rgba(0,0,0,0.54);
            -moz-box-shadow: 1px 3px 5px 0px rgba(0,0,0,0.54);
            box-shadow: 1px 3px 5px 0px rgba(0,0,0,0.54);
        }
    </style>
    <div class="lema-message error" style="">
        <?php echo $exception->getMessage()?>
    </div>
</div>
</body>
<?php wp_footer()?>

