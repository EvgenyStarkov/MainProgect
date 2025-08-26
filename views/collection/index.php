<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/model/Model.php';


if(isset($_POST['collection'])){
    $collectionId = $_POST['collection'];


    /*  @var $m */

    $collection = $m->getCollection($collectionId);
    $pageTitle = $collection['title'];

    require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';



?>


    <section class="collections">
            <div class="collections__item">
                <h2 class="collections__item-title h1"> <?= $collection['title'] ?></h2>
                <div class="collections__item-body grid">

                    <?php $films = $m->getFilmOnCollection($collectionId);
                    foreach ($films as $f) {
                        ?>

                        <form class="collections__item-link" action="../films/video.php" method="post" >
                            <button style="background-color: transparent; width: fit-content; height: fit-content; border: none;" value="<?= $f['id'] ?>" name="filmFakeLink">
                                <img src="<?= './' . $f['cover'] ?>" class="collections__item-link-img">
                            </button>
                        </form>

                        <?php } ?>

                </div>
            </div>
    </section>

<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';

} else{
    echo "Ошибка перехода((((";
}
?>