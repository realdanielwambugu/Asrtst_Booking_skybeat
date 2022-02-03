<?php if ($artists[0]->name): ?>

  <?php foreach ($artists as $artist): ?>
    <a class="no-line" href="#templates/customer/booking/service//artistController/show/id = <?=$artist->id;?>/artist_details">
      <div class="fx fx-i-c mb-2 pointer py-1">
        <div class="rounded-full bgColor-pri h-8 w-8">
          <img class="w-full h-full rounded-full cover" src="<?=url($artist->photo, 'images.artist');?>" alt="">
        </div>
       <h5 class="ml-3 color-2-800"> <?=$artist->name;?> </h5>
      </div>
   </a>

  <?php endforeach; ?>

<?php else: ?>

  <div class="fx fx-i-c mb-2 pointer py-4">

   <h5 class="ml-3 color-2-800"> No search result found!</h5>

  </div>

<?php endif; ?>
