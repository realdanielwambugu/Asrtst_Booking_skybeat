<?php foreach ($categories as $category): ?>

  <h4 class="py-3 hover:color-2-800 pointer"
   onclick="route.set(`CategoryController/show/id = <?=$category->id?>/all_artists`)"> 

    <?=$category->category;?>

  </h4>

<?php endforeach; ?>
