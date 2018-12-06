<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$navbar = new \Anax\Navigation\Navbar();
$navbar->setDI($di);
$items = $navbarConfig["items"] ?? [];
$html = $navbar->createMenuWithSubMenus($navbarConfig);

$classes = "rm-navbar-max rm-navbar rm-max rm-swipe-right " . ( $class ?? null);


?><!-- menu wrapper -->
<?php foreach ($items ?? [] as $item) : ?>
    <li><a href="<?= url($item["url"]) ?>" title="<?= $item["title"] ?>"><?= $item["text"] ?></a></li>
<?php endforeach; ?>
