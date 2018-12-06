<?php

namespace Anax\View;

use Anax\StyleChooser\StyleChooserController;

/**
 * A layout rendering views in defined regions.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$htmlClass = $htmlClass ?? [];
$lang = $lang ?? "sv";
$charset = $charset ?? "utf-8";
$title = ($title ?? "No title") . ($baseTitle ?? " | No base title defined");
$bodyClass = $bodyClass ?? null;

// Set active stylesheet
$request = $di->get("request");
$session = $di->get("session");
if ($request->getGet("style")) {
    $session->set("redirect", currentUrl());
    redirect("style/update/" . rawurlencode($_GET["style"]));
}

// Get the active stylesheet, if any.
$activeStyle = $session->get(StyleChooserController::getSessionKey(), null);
if ($activeStyle) {
    $stylesheets = [];
    $stylesheets[] = $activeStyle;
}

// Get hgrid & vgrid
if ($request->hasGet("hgrid")) {
    $htmlClass[] = "hgrid";
}
if ($request->hasGet("vgrid")) {
    $htmlClass[] = "vgrid";
}

// Show regions
if ($request->hasGet("regions")) {
    $htmlClass[] = "regions";
}

// Get flash message if any and add to region flash-message
$flashMessage = $session->getOnce("flashmessage");
if ($flashMessage) {
    $di->get("view")->add(__DIR__ . "/../flashmessage/default", ["message" => $flashMessage], "flash-message");
}

// Get current route to make as body class
$route = "route-" . str_replace("/", "-", $di->get("request")->getRoute());


?><!doctype html>
<html <?= classList($htmlClass) ?> lang="<?= $lang ?>">
<head>
    <meta charset="<?= $charset ?>">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="js/jquery-2.1.4.min.js"></script>
    <?php if (isset($favicon)) : ?>
    <link rel="icon" href="<?= asset($favicon) ?>">
    <?php endif; ?>

    <?php if (isset($stylesheets)) : ?>
        <?php foreach ($stylesheets as $stylesheet) : ?>
            <link rel="stylesheet" type="text/css" href="<?= asset($stylesheet) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($style)) : ?>
    <style><?= $style ?></style>
    <?php endif; ?>

</head>

<body <?= classList($bodyClass, $route) ?>>

<!-- wrapper around all items on page -->
<div class="wrap-all">



<!-- siteheader with optional columns -->

<div class="banner_top" id="home">
    <div class="wrapper_top_w3layouts">
        <div class="header_agileits">
            <div class="overlay overlay-contentpush">
                <button type="button" class="overlay-close"><i class="fa fa-times" aria-hidden="true"></i></button>
                <nav>
                    <ul>
                        <?php renderRegion("header-col-3") ?>
                    </ul>
                </nav>
        </div>
        <div class="mobile-nav-button">
            <button id="trigger-overlay" type="button"><i class="fa fa-bars" aria-hidden="true"></i></button>
        </div>
    </div>
<?php if ($di->request->getRoute() == "") : ?>
        <div class="callbacks_container">
                    <div class="banner-top">
                        <div class="banner-info-wthree">
                            <div class="vox"><h3>Ramverk1</h3></div>
                            <div class="yala">
                                <span>Jonathan Hellberg</span>
                            </div>

                        </div>

                    </div>
        </div>
        <div class="divde-block">
      <h3 class="head">Om mig</h3>
      <p class="head">Små saker</p>
    </div>
<?php else : ?>
    <div class="callbacks_container">
        <div class="banner-top-hidesite"></div>
    </div>
<?php endif; ?>

        <div class="clearfix"></div>

<!-- navbar -->
<?php if (regionHasContent("navbar")) : ?>
<div class="outer-wrap outer-wrap-navbar">
    <div class="inner-wrap inner-wrap-navbar">
        <div class="row">
            <nav class="region-navbar" role="navigation">
                <?php renderRegion("navbar") ?>
            </nav>
        </div>
    </div>
</div>
<?php endif; ?>


<!-- breadcrumb -->
<?php if (regionHasContent("breadcrumb")) : ?>
<div class="outer-wrap outer-wrap-breadcrumb">
    <div class="inner-wrap inner-wrap-breadcrumb">
        <div class="row">
            <div class="region-breadcrumb">
                <?php renderRegion("breadcrumb") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- flash message -->
<?php if (regionHasContent("flash-message")) : ?>
<div class="outer-wrap outer-wrap-flash-message">
    <div class="inner-wrap inner-wrap-flash-message">
        <div class="row">
            <div class="region-flash-message">
                <?php renderRegion("flash-message") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- columns-above -->
<?php if (regionHasContent("columns-above")) : ?>
<div class="outer-wrap outer-wrap-columns-above">
    <div class="inner-wrap inner-wrap-columns-above">
        <div class="row">
            <div class="region-columns-above">
                <?php renderRegion("columns-above") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- main -->
<div class="outer-wrap outer-wrap-main">
    <div class="inner-wrap inner-wrap-main">
        <div class="row">

<?php
$sidebarLeft  = regionHasContent("sidebar-left");
$sidebarRight = regionHasContent("sidebar-right");
$class = "";
$class .= $sidebarLeft  ? "has-sidebar-left "  : "";
$class .= $sidebarRight ? "has-sidebar-right " : "";
$class .= empty($class) ? "" : "has-sidebar";
?>

            <?php if ($sidebarLeft) : ?>
            <div class="wrap-sidebar region-sidebar-left <?= $class ?>" role="complementary">
                <?php renderRegion("sidebar-left") ?>
            </div>
            <?php endif; ?>

            <?php if (regionHasContent("main")) : ?>
            <main class="region-main <?= $class ?>" role="main">
                <?php renderRegion("main") ?>
            </main>
            <?php endif; ?>

            <?php if ($sidebarRight) : ?>
            <div class="wrap-sidebar region-sidebar-right <?= $class ?>" role="complementary">
                <?php renderRegion("sidebar-right") ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>



<!-- after-main -->
<?php if (regionHasContent("after-main")) : ?>
<div class="outer-wrap outer-wrap-after-main">
    <div class="inner-wrap inner-wrap-after-main">
        <div class="row">
            <div class="region-after-main">
                <?php renderRegion("after-main") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- columns-below -->
<?php if (regionHasContent("columns-below")) : ?>
<div class="outer-wrap outer-wrap-columns-below">
    <div class="inner-wrap inner-wrap-columns-below">
        <div class="row">
            <div class="region-columns-below">
                <?php renderRegion("columns-below") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- sitefooter -->
<?php if (regionHasContent("footer")) : ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 backone">
                <ul class="text-center lists">
                    <a href=""><li>PRIVACY POLICY</li></a>
                    <a href=""><li>TERMS & CONDITIONS</li></a>
                    <a href=""><li>ABOUT</li></a>
                </ul>
            </div>
            <div class="col-md-4 text-center">
                <h2>Hellberg</h2>
                <h4>Me-sida</h4>
                <div class="row zero">
                    <div class="col-md-4 center">
                        <div class="center">
                            <img src="img/twitter.png" alt="">
                        </div>
                    </div>
                    <div class="col-md-4 center">
                        <img src="img/instagram.png" alt="">
                    </div>
                    <div class="col-md-4 center">
                        <div class="center">
                            <img src="img/facebook.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="row zero">
                    <div class="col-md-6 streck">

                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
                <p class="zero">&copy; 2018</p>
            </div>
            <div class="col-md-4 backone">
                <ul class="text-center lists">
                    <a href=""><li>GITHUB</li></a>
                    <a href=""><li>COURSE</li></a>
                    <a href=""><li>WEBSITE</li></a>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>



</div> <!-- end of wrapper -->



<!-- render javascripts -->
<?php if (isset($javascripts)) : ?>
    <?php foreach ($javascripts as $javascript) : ?>
    <script async src="<?= asset($javascript) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>


<!-- useful for inline javascripts such as google analytics-->
<?php if (regionHasContent("body-end")) : ?>
    <?php renderRegion("body-end") ?>
<?php endif; ?>

</body>
</html>
