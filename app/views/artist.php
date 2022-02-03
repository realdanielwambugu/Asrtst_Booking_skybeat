<?php foreach ($artists as $artist): ?>
  <tr class="pointer bgColor-sec border-8 b-solid b-color-3-100">
    <td class="px-4 py-1 color-2-600 border-0  ">
      <div class="rounded-full bgColor-pri h-8 w-8">
        <img class="w-full h-full rounded-full cover" src='<?=url($artist->photo, 'images.artist');?>' alt="artist image">
      </div>
    </td>
    <td class="px-4 py-1 color-2-600 border-0"> <?=$artist->name;?> </td>
    <td class="px-4 py-1 color-2-600 border-0"> <?=$artist->country;?> </td>
    <td class="px-4 py-1 color-2-600 border-0">

       <?=date_format(date_create($artist->birth_day),"F j, Y");?>

    <td class="px-4 py-1color-2-600 border-0 "> <?=$artist->cost_per_hr;?> </td>
    <td class="px-4 py-1 color-pri">

      <button  class="w-auto bgColor-2-600 color-danger fs-sm
       py-1 px-3 border-0 rounded fw-bold ff-pri pointer outline-0"
       onclick="route.set('deleteArtist+ id= <?=$artist->id;?>')">Remove</button>
    </td>
  </tr>
<?php endforeach; ?>
