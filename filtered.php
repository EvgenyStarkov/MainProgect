<?php

/* @var $filtered */
/* @var $link */

?>

<section class="collections">
    <div class="collections__item">
        <h2 class="collections__item-title h1"> Ваша подборка</h2>
        <div class="collections__item-body.grid">
            <?php foreach ($filtered as $fr){?>
                <form class="collections__item-link" action="/views/<?= $link ?>/video.php?<?= $fr['title']?>" method="post">
                    <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;"
                            value="<?= $fr['id'] ?>" name="filmFakeLink">
                        <img src="<?= '/' . $fr['cover'] ?>" class="collections__item-link-img" alt="<?= $fr['title'] ?>">
                    </button>
                </form>
        <?php } ?>
        </div>
    </div>
</section>
