<!-- AFFICHAGE D'UNE REALISATION -->
<div class="item mb-5">
    <div class="media">
        <!-- Lien vers l'article sélectionné -->
        <a href="<?=  $router->url('achievement', ['slug' => $post->getSlug(), 'id' => $post->getId()]) ?>">
            <!-- IMAGE -->
            <img src="<?= '../../assets/upload/img/'.$post->getPicture() ?>" class="mr-3 img-fluid post-thumb d-none d-md-flex" alt="Image">
        </a>
        <div class="media-body">
            
            <h3 class="title mb-1">
                <a href="<?= $router->url('achievement', ['slug' => $post->getSlug(), 'id' => $post->getId()]) ?>">
                    <!-- NOM -->
                    <?= $post->getTitle() ?>
                </a>
            </h3>

            <div class="meta mb-1">
                <!-- DATE DE CREATION -->
                <span class="date">
                    <?= $post->getCreatedAt_fr() ?>
                </span>
                <span class="time">
                    <?php foreach($post->getCategories() as $category): ?>
                            <!-- Lien vers les articles qui ont la même category -->
                            <a href="<?= $router->url('achievements-category', ['slug' => $category->getSlug(), 'id' => $category->getId()]) ?>">
                                <?= $category->getName() ?>
                            </a>
                    <?php endforeach ?>
                </span>
                <!--
                <span class="comment">
                    <a href="#">8 comments</a>
                </span>
                -->
            </div>
            <div class="intro">
                <!-- CONTENU -->
                <?= $post->getContent_excerpt() ?>
            </div>
            <a class="more-link" href="<?= $router->url('achievement', ['slug' => $post->getSlug(), 'id' => $post->getId()]) ?>">En savoir plus &rarr;</a>
        </div>
    </div>
</div>