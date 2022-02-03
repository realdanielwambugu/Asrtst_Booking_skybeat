<div  class="w-48 h-64 bgColor-sec  pt-5">
  <div class="w-32 h-32 bgColor-sec shadow m-0-auto">
   <img class="w-full h-full cover" src="<?=url($artist->photo, 'images.artist');?>" alt="artist image">
  </div>

  <div class="txt-h-c pt-4 color-2-800 fs">
    <h4> <?=$artist->name;?> </h4>
    <h5 class="fs-xs color-pri-600"> <?=$artist->category->category;?> </h5>
    <h5 class="mt-2">Born: <span class="fw-500"> <?=$artist->birth_day;?> </span></h5>
    <h5 class="mt-2">Country: <span class="fw-500"> <?=$artist->country;?> </span></h5>
  </div>
</div>

<div  class="w-8/12 h-auto bgColor-sec pt-2 color-2-700">
<h3 class="py-4">Biography.</h3>
<div class="fs-sm lh-relaxed ls-wide"> <?=$artist->biography;?> </div>

<div class="mt-2 pb-10 py-5">
<h3 class="mb-2">Top Songs.</h3>
<table class="txt-h-l table-auto">
  <tr class="">
   <th class="px-4 py-2">Name</th>
   <th class="px-4 py-2">Album</th>
   <th class="px-4 py-2">Year</th>
  </tr>

  <?php foreach ($artist->song as $song) : ?>

    <tr>
      <td class="border px-4 py-2"> <?=$song->title;?> </td>
      <td class="border px-4 py-2"> <?=$song->album;?> </td>
      <td class="border px-4 py-2"> <?=$song->releaseYear;?> </td>
    </tr>

  <?php endforeach; ?>

</div>
</div>
