
  <option value="">Category</option>

  <?php foreach ($categories as $category): ?>

      <option value="<?=$category->id;?>">

         <?=$category->category;?>

      </option>

  <?php endforeach; ?>
