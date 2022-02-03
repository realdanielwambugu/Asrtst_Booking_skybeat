<?php if ($bookings[0]->status) : ?>

  <?php foreach ($bookings as $booking): ?>

  <tr class="pointer bgColor-sec border-8 b-solid b-color-3-100">
    <td class="px-4 py-1 color-2-600 border-0  ">
      <div class="rounded-full bgColor-pri h-8 w-8">
        <img class="w-full h-full rounded-full cover" src="<?=url($booking->user->photo, 'images.user');?>" alt="user pic">
      </div>
    </td>

    <td class="px-4 py-1 color-2-600 border-0 truncate"> <?=$booking->user->fullName;?> </td>
    <td class="px-4 py-1 color-2-600 border-0"> <?=$booking->mpesa_code?> </td>
    <td class="px-4 py-1 color-2-600 border-0"> <?=$booking->cost?> </td>
    <td class="px-4 color-2-600 border-0  ">
      <div class="rounded-full bgColor-pri h-8 w-8">
        <img class="w-full h-full rounded-full cover" src="<?=url($booking->artist->photo, 'images.artist');?>" alt="artist pic">
      </div>
    </td>
    <td class="px-2 py-1 color-2-600 border-0 truncate"> <?=$booking->artist->name;?> </td>
    <td class="px-4 py-1 color-2-600 border-0 truncate"> <?=$booking->book_date;?> </td>
   <td class="px-4 py-1 color-2-600 border-0"> <?= date('h:i A', strtotime($booking->from_time));?>  </td>
    <td class="px-4 py-1 color-2-600 border-0"> <?= date('h:i A', strtotime($booking->to_time));?>  </td>

    <?php if ($booking->status === 'confirmed') : ?>

      <td class="px-4 py-1 color-pri border-0"> <?=$booking->status;?> </td>

    <?php elseif ($booking->status === 'pending') : ?>

      <td class="px-4 py-1 color-3-600 border-0"> <?=$booking->status;?> </td>

      <td class="px-2 py-1 color-pri">
        <button class="w-auto bgColor-pri color-sec fs-sm  py-1 px-3 border-0 rounded
        fw-bold ff-pri pointer outline-0" type="submit"
        onclick="route.set('BookingController/update/id = <?=$booking->id;?>, status = confirmed/all_booking')">Accept</button>
      </td>

      <td class="px-2 py-1 color-pri">
        <button class="w-auto bgColor-3-400 color-danger fs-sm  py-1 px-3 border-0 rounded
        fw-bold ff-pri pointer outline-0" type="submit"
        onclick="route.set('BookingController/update/id = <?=$booking->id;?>, status = rejected/all_booking')">Reject</button>
      </td>

    <?php else :?>

      <td class="px-4 py-1 color-danger border-0"> <?=$booking->status;?> </td>

    <?php endif; ?>

  </tr>

  <?php endforeach; ?>

<?php else: ?>

  <div class="w-full txt-h-c">

  <img class="w-4/12 mt-20 mr-10" src='<?=url('empty.svg', 'images.svg')?>' alt="empty">

   <p class="color-pri mt-5 fs-pri">No booking history found</p>

  </div>

<?php endif; ?>
