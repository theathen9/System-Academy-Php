<?php
function Navbar($infoSchemaData, $route)
{
?>
    <nav class="navBar col-12 col-md-3 col-lg-2 p-3">
        <div class="d-flex gap-1 mb-4 align-items-center align-self-center position-sticky top-0 bg-white p-0">
            <img src="<?php echo $infoSchemaData[5]["image"] ?>" width="60" height="60" alt="logo" class="rounded-circle">
            <div class="title">
                <p class="m-auto"><?php echo $infoSchemaData[1]["name_short"] ?></p>
            </div>
        </div>
        <ul class="nav flex-column">
            <ul class="nav flex-column">
                <?php foreach ($route as $item): ?>
                    <?php if (isset($item['submenu'])): ?>
                        <li class="nav-item mb-1">
                            <a class="nav-link rounded d-flex justify-content-between align-items-center <?= !empty($item['active']) ? 'text-dark' : ' text-dark'; ?>"
                                data-bs-toggle="collapse"
                                href="#<?= $item['submenu_id']; ?>"
                                aria-expanded="<?= (!empty($item['active']) ? 'true' : 'false'); ?>">
                                <?= $item['title']; ?>
                                <span class=" bi submenu-icon <?= (!empty($item['active']) || !empty(array_filter($item['submenu'], fn($s) => !empty($s['active'])))) ? 'bi-chevron-down' : 'bi-chevron-left'; ?>"></span>
                            </a>
                            <ul id="<?= $item['submenu_id']; ?>"
                                class="nav collapse flex-column ms-3 <?= (!empty($item['active']) || !empty(array_filter($item['submenu'], fn($s) => !empty($s['active'])))) ? 'show' : ''; ?>">
                                <?php foreach ($item['submenu'] as $sub): ?>
                                    <li class="nav-item mb-1 w-100">
                                        <a href="<?= $sub['link']; ?>" class="nav-link rounded <?= !empty($sub['active']) ? 'bg-primary text-white' : 'text-dark'; ?>">
                                            <?= $sub['title']; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                        </li>

                    <?php else: ?>
                        <li class="nav-item mb-1 w-100">
                            <a href="<?= $item['link']; ?>" class="nav-link rounded
            <?= !empty($item['active']) ? 'bg-primary text-white' : 'text-dark'; ?>">
                                <?php if (!empty($item['icon'])): ?>
                                    <i class="<?= $item['icon']; ?> me-1"></i>
                                <?php endif; ?>
                                <?= $item['title']; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </ul>
    </nav>
<?php
}
?>