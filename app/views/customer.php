<?php foreach ($users as $user): ?>

  <?php if (!$user->isAdmin()) : ?>

    <tr class="pointer bgColor-sec border-8 b-solid b-color-3-100">
      <td class="px-4 py-1 color-2-600 border-0  ">
        <div class="rounded-full bgColor-pri h-8 w-8">
          <img class="w-full h-full rounded-full cover" src='<?=url($user->photo, 'images.user');?>' alt="customer">
        </div>
      </td>
      <td class="px-4 py-1 color-2-600 border-0"> <?=$user->fullName;?> </td>
      <td class="px-4 py-1 color-2-600 border-0"> <?=$user->email;?>    </td>
      <td class="px-4 py-1 color-2-600 border-0"> <?=$user->phone;?>    </td>
     <td class="px-4 py-1color-2-600 border-0">

         <?=date_format(date_create($user->created_at),"F j, Y");?>

     </td>
     <td class="px-4 py-1 color-pri"> <?=$user->status;?> </td>
     <td class="px-4 py-1 color-pri">

         <?php if ($user->status != 'blocked'): ?>

           <button class="w-auto bgColor-3-600 color-danger fs-sm  py-1 px-5 border-0 rounded
           fw-bold ff-pri pointer outline-0" type="button"
            onclick="route.set('updateCustomer+ id= <?=$user->id;?>,status = blocked')">
            Block
           </button>

         <?php else: ?>

           <button  class="w-auto bgColor-3-600 color-danger fs-sm  py-1 px-5 border-0 rounded
            fw-bold ff-pri pointer outline-0" type="button"
            onclick="route.set('updateCustomer+ id= <?=$user->id;?>,status = active')">
            Unblock
           </button>

         <?php endif; ?>

     </td>
     <td class="px-4 py-1 color-pri">
       <button  class="w-auto bgColor-2-600 color-danger fs-sm  py-1 px-3 border-0 rounded
       fw-bold ff-pri pointer outline-0" type="button"
       onclick="route.set('deleteCustomer+ id= <?=$user->id;?>')">
       Remove</button>
     </td>
    </tr>


    <?php endif; ?>


<?php endforeach; ?>
