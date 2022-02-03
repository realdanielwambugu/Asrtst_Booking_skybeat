<?php if (isset($category) ): ?>

     <?php $artists = $category->artist; ?>

<?php endif; ?>

<?php if ($artists[0]->name): ?>

  <?php foreach ($artists as $artist) : ?>

    <div class="w-auto h-auto fx bgColor-sec shadow pointer ml-2 mb-2 py-2 px-2">
       <div class="w-24 h-auto bgColor-pri shadow">
        <img class="w-full h-full cover" src="<?=url($artist->photo, 'images.artist');?>" alt="">
       </div>
       <div class="h-full bgColor-sec ml-4 color-2-800 ">
         <h5><span class="fw-black">Name:</span>  <?=$artist->name;?> </h5>
         <h5 class="pt-1"><span class="fw-black">Genres: </span> <?=$artist->category->category;?> </h5>
         <h5 class="pt-1"><span class="fw-black">Cost: </span> <?=$artist->cost_per_hr;?> </h5>

         <a href="#templates/customer/booking/service//artistController/show/id = <?=$artist->id;?>/artist_details">
           <button class="bgColor-pri shadow py-1 px-3 mt-2 color-sec border-1 b-solid b-color-pri
           rounded pointer fw-bold hover:bgColor-pri-600 hover:b-color-pri-100 "
            type="button" name="button">book artist</button>
         </a>
       </div>
    </div>

  <?php endforeach; ?>

  <?php else: ?>

    <div class="w-full txt-h-c">

    <img class="w-4/12 mt-10" src='<?=url('empty.svg', 'images.svg')?>' alt="empty">

     <p class="color-pri mt-5 fs-pri">No artists in this category</p>

    </div>

  <?php endif; ?>
